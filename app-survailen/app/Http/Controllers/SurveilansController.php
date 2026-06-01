<?php

namespace App\Http\Controllers;

use App\Models\Sertifikasi;
use App\Models\Surveilans;
use Illuminate\Http\Request;

class SurveilansController extends Controller
{
    /**
     * Menampilkan daftar surveilans
     */
    public function index(Request $request)
    {
        $search = trim($request->get('search'));
        $perPage = $request->get('per_page', 10); // default to 10
        $sort = $request->get('sort', 'newest');

        $query = Sertifikasi::with(['perusahaan', 'surveilans.surats'])->withCount('surveilans')
            ->when($search, function ($query) use ($search) {
                $keywords = explode(' ', $search); // support multi-kata

                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $like = "%{$keyword}%";

                        $q->where(function ($inner) use ($like) {
                            $inner->where('no_referensi', 'LIKE', $like)
                                ->orWhereHas('perusahaan', function ($p) use ($like) {
                                    $p->where('nama_perusahaan', 'LIKE', $like)
                                        ->orWhere('komoditi', 'LIKE', $like)
                                        ->orWhere('email', 'LIKE', $like);
                                });
                        });
                    }
                });
            });

        if ($sort === 'oldest') {
            $query->orderBy('tgl_permohonan', 'asc')->orderBy('id_sertifikasi', 'asc');
        } else {
            $query->orderBy('tgl_permohonan', 'desc')->orderBy('id_sertifikasi', 'desc');
        }

        $surveilans = $query->paginate($perPage)
            ->appends($request->query());

        return view('Surveilance.index', compact('surveilans', 'search', 'perPage'));
    }

    /**
     * Menampilkan form tambah surveilans
     */
    public function create()
    {
        $sertifikasis = Sertifikasi::with('perusahaan')->whereNotNull('no_sni')->where('no_sni', '!=', '')->get();

        $pengawasans = [
            1 => [
                'bg' => 'bg-blue-50/50', 'border' => 'border-blue-100', 'text' => 'text-blue-800',
                'badge_bg' => 'bg-blue-600', 'border_b' => 'border-blue-200/50',
                'ring' => 'focus:ring-blue-500/20', 'focus_border' => 'focus:border-blue-500',
            ],
            2 => [
                'bg' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'text' => 'text-amber-800',
                'badge_bg' => 'bg-amber-500', 'border_b' => 'border-amber-200/50',
                'ring' => 'focus:ring-amber-500/20', 'focus_border' => 'focus:border-amber-500',
            ],
            3 => [
                'bg' => 'bg-purple-50/50', 'border' => 'border-purple-100', 'text' => 'text-purple-800',
                'badge_bg' => 'bg-purple-600', 'border_b' => 'border-purple-200/50',
                'ring' => 'focus:ring-purple-500/20', 'focus_border' => 'focus:border-purple-500',
            ],
            4 => [
                'bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-100', 'text' => 'text-emerald-800',
                'badge_bg' => 'bg-emerald-600', 'border_b' => 'border-emerald-200/50',
                'ring' => 'focus:ring-emerald-500/20', 'focus_border' => 'focus:border-emerald-500',
            ],
        ];

        $auditors = \App\Models\Auditor::orderBy('nama_auditor')->pluck('nama_auditor');
        $labs = \App\Models\Lab::orderBy('nama_lab')->pluck('nama_lab');
        $petugas = \App\Models\PetugasPengambilContoh::orderBy('nama_ppc')->pluck('nama_ppc');

        return view('Surveilance.create', compact('sertifikasis', 'pengawasans', 'auditors', 'labs', 'petugas'));
    }

    /**
     * Menyimpan data surveilans baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_referensi' => 'required|string',
            'action' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Tentukan periode dari action (simpan_1, simpan_2, simpan_3, simpan_4)
        $periode = 1;
        if ($validated['action'] == 'simpan_2') {
            $periode = 2;
        }
        if ($validated['action'] == 'simpan_3') {
            $periode = 3;
        }
        if ($validated['action'] == 'simpan_4') {
            $periode = 4;
        }

        // Cari sertifikasi berdasarkan no referensi
        $sertifikasi = Sertifikasi::where('no_referensi', $validated['no_referensi'])->firstOrFail();

        // 1. Simpan ke tabel Surveilans
        $surveilans = \App\Models\Surveilans::updateOrCreate(
            [
                'id_sertifikasi' => $sertifikasi->id_sertifikasi,
                'periode' => $periode,
            ],
            [
                'id_user' => auth()->id(),
                'tgl_pelaksanaan' => $request->input("tgl_pelaksanaan_pengawasan_{$periode}"),
                'keterangan' => $validated['keterangan'] ?? null,
            ]
        );

        $inputLab = $request->input("laboratorium_pengujian_{$periode}");
        if (! empty($inputLab)) {
            $lab = \App\Models\Lab::firstOrCreate(['nama_lab' => trim($inputLab)]);
            $surveilans->update(['id_lab' => $lab->id_lab]);
        } else {
            $surveilans->update(['id_lab' => null]);
        }

        $inputAuditor = $request->input("auditor_pelaksanaan_{$periode}");
        if ($inputAuditor !== null) {
            $auditorNames = array_filter(array_map('trim', explode(',', $inputAuditor)));
            $auditorIds = [];
            foreach ($auditorNames as $name) {
                $auditorIds[] = \App\Models\Auditor::firstOrCreate(['nama_auditor' => $name])->id_auditor;
            }
            $surveilans->auditors()->sync($auditorIds);
        } else {
            $surveilans->auditors()->sync([]);
        }

        $inputPPC = $request->input("ppc_pelaksanaan_{$periode}");
        if ($inputPPC !== null) {
            $ppcNames = array_filter(array_map('trim', explode(',', $inputPPC)));
            $ppcIds = [];
            foreach ($ppcNames as $name) {
                $ppcIds[] = \App\Models\PetugasPengambilContoh::firstOrCreate(['nama_ppc' => $name])->id_ppc;
            }
            $surveilans->petugasPengambilContoh()->sync($ppcIds);
        } else {
            $surveilans->petugasPengambilContoh()->sync([]);
        }

        // 2. Simpan ke tabel Surat
        $jenisSurat = [
            'Pengawasan Berkala' => "tgl_surat_pengawasan_{$periode}",
            'Teguran 1' => "tgl_surat_teguran_1_{$periode}",
            'Teguran 2' => "tgl_surat_teguran_2_{$periode}",
            'Pembekuan 1' => "tgl_pembekuan_1_{$periode}",
            'Pembekuan 2' => "tgl_pembekuan_2_{$periode}",
        ];

        foreach ($jenisSurat as $jenis => $inputName) {
            if ($request->filled($inputName)) {
                \App\Models\Surat::updateOrCreate(
                    [
                        'id_surveillance' => $surveilans->id_surveillance,
                        'jenis_surat' => $jenis,
                    ],
                    [
                        'id_user' => auth()->id(),
                        'tgl_terbit' => $request->input($inputName),
                    ]
                );
            }
        }

        if ($request->has('redirect_to') && ! empty($request->redirect_to)) {
            $redirectUrl = $request->input('redirect_to');

            // Security check: ensure redirect_to is a local/relative URL and not an absolute external domain
            if (! str_starts_with($redirectUrl, '/') || str_starts_with($redirectUrl, '//')) {
                $redirectUrl = route('surveilans.index');
            }

            $separator = parse_url($redirectUrl, PHP_URL_QUERY) ? '&' : '?';
            $redirectUrl .= $separator.'sertifikasi_id='.$sertifikasi->id_sertifikasi;

            return redirect($redirectUrl)->with('success', 'Data survailen berhasil disimpan.');
        }

        return redirect()->route('surveilans.index')->with('success', 'Data survailen ke- '.$periode.' berhasil disimpan!');
    }

    /**
     * Mengambil data perusahaan berdasarkan no referensi sertifikasi (API)
     */
    public function getDataBySertifikasi($no_referensi)
    {
        $sertifikasi = Sertifikasi::with(['perusahaan', 'auditors', 'petugasPengambilContoh', 'lab', 'surveilans.surats', 'kategori'])->where('no_referensi', $no_referensi)->first();

        if ($sertifikasi) {
            $kategori_name = strtolower($sertifikasi->kategori->nama_kategori ?? '');
            $max_surveilans = 3; // Default 3

            if (str_contains($kategori_name, 'luar negeri')) {
                $max_surveilans = 4;
            } elseif (str_contains($kategori_name, 'siinas')) {
                if (str_contains($kategori_name, 'non siinas') || str_contains($kategori_name, 'non-siinas')) {
                    $max_surveilans = 3;
                } else {
                    $max_surveilans = 4;
                }
            }

            $data = [
                'id_sertifikasi' => $sertifikasi->id_sertifikasi,
                'nama_perusahaan' => $sertifikasi->perusahaan->nama_perusahaan ?? null,
                'komoditi' => $sertifikasi->perusahaan->komoditi ?? null,
                'no_sni' => $sertifikasi->no_sni,
                'tgl_sertifikasi' => $sertifikasi->tgl_sertifikasi,
                'auditor_sertifikasi' => $sertifikasi->nama_auditor,
                'ppc_sertifikasi' => $sertifikasi->nama_petugas,
                'laboratorium_pengujian' => $sertifikasi->nama_lab,
                'status_sertifikasi' => $sertifikasi->status_permohonan,
                'max_surveilans' => $max_surveilans,
            ];

            // Tambahkan data surveilans dan surat jika ada
            foreach ($sertifikasi->surveilans as $surv) {
                $p = $surv->periode;
                $data["tgl_pelaksanaan_pengawasan_{$p}"] = $surv->tgl_pelaksanaan;
                $data["auditor_pelaksanaan_{$p}"] = $surv->auditors->pluck('nama_auditor')->implode(', ');
                $data["ppc_pelaksanaan_{$p}"] = $surv->petugasPengambilContoh->pluck('nama_ppc')->implode(', ');
                $data["laboratorium_pengujian_{$p}"] = $surv->lab->nama_lab ?? '';

                foreach ($surv->surats as $surat) {
                    if ($surat->jenis_surat == 'Pengawasan Berkala') {
                        $data["tgl_surat_pengawasan_{$p}"] = $surat->tgl_terbit;
                    } elseif ($surat->jenis_surat == 'Teguran 1') {
                        $data["tgl_surat_teguran_1_{$p}"] = $surat->tgl_terbit;
                    } elseif ($surat->jenis_surat == 'Teguran 2') {
                        $data["tgl_surat_teguran_2_{$p}"] = $surat->tgl_terbit;
                    } elseif ($surat->jenis_surat == 'Pembekuan 1') {
                        $data["tgl_pembekuan_1_{$p}"] = $surat->tgl_terbit;
                    } elseif ($surat->jenis_surat == 'Pembekuan 2') {
                        $data["tgl_pembekuan_2_{$p}"] = $surat->tgl_terbit;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan',
        ], 404);
    }
}
