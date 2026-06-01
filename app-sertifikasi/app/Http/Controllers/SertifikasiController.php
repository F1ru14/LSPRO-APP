<?php

namespace App\Http\Controllers;

use App\Models\Auditor;
use App\Models\Lab;
use App\Models\Perusahaan;
use App\Models\Sertifikasi;
use Illuminate\Http\Request;

class SertifikasiController extends Controller
{
    /**
     * Menampilkan daftar sertifikasi (Tabel)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10); // default to 10
        $sort = $request->get('sort', 'newest');

        $query = Sertifikasi::with('perusahaan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_referensi', 'LIKE', "%{$search}%")
                    ->orWhereHas('perusahaan', function ($q2) use ($search) {
                        $q2->where('nama_perusahaan', 'LIKE', "%{$search}%")
                            ->orWhere('komoditi', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($sort === 'oldest') {
            $query->orderBy('tgl_permohonan', 'asc')->orderBy('id_sertifikasi', 'asc');
        } else {
            $query->orderBy('tgl_permohonan', 'desc')->orderBy('id_sertifikasi', 'desc');
        }

        $datas = $query->paginate($perPage)->appends(request()->query());

        return view('Sertifikasi.index', compact('datas', 'search', 'perPage', 'sort'));
    }

    /**
     * Menampilkan Form Tambah Fase 1
     */
    public function create()
    {
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
        $provinsis = \App\Models\Provinsi::orderBy('provinsi')->get();

        return view('Sertifikasi.tambah', compact('kategoris', 'provinsis'));
    }

    /**
     * Menampilkan Form Tambah Fase 2
     */
    public function createFase2($id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);
        $auditors = Auditor::orderBy('nama_auditor')->pluck('nama_auditor');

        return view('Sertifikasi.tambah_fase2', compact('sertifikasi', 'auditors'));
    }

    /**
     * Menyimpan Data Sertifikasi Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_referensi' => 'required|string|unique:sertifikasi,no_referensi',
            'nama_perusahaan' => 'required|string',
            'alamat_kantor' => 'required|string',
            'telp_kantor' => 'nullable|string',
            'fax_kantor' => 'nullable|string',
            'email' => 'required|email',
            'nama_importir' => 'nullable|string',
            'alamat_importir' => 'nullable|string',
            'telp_importir' => 'nullable|string',
            'fax_importir' => 'nullable|string',
            'kontak_person' => 'nullable|string',
            'telp_cp' => 'nullable|string',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'komoditi_produk' => 'required|string',
            'merk' => 'nullable|string',
            'type_jenis_produk' => 'nullable|string',
            'alamat_pabrik' => 'nullable|string',
            'telp_pabrik' => 'nullable|string',
            'fax_pabrik' => 'nullable|string',
            'tgl_permohonan' => 'required|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, &$sertifikasi) {
            $id_provinsi = $request->id_provinsi;
            $id_kota = $request->id_kota;

            if (! is_numeric($id_provinsi) && ! empty($id_provinsi)) {
                $prov = \App\Models\Provinsi::firstOrCreate(['provinsi' => $id_provinsi]);
                $id_provinsi = $prov->id_provinsi;
            }

            if (! is_numeric($id_kota) && ! empty($id_kota)) {
                if ($id_provinsi) {
                    $kota = \App\Models\Kota::firstOrCreate([
                        'id_provinsi' => $id_provinsi,
                        'kota' => $id_kota,
                    ]);
                    $id_kota = $kota->id_kota;
                } else {
                    $id_kota = null;
                }
            }
            $perusahaan = Perusahaan::create([
                'nama_perusahaan' => $validated['nama_perusahaan'],
                'alamat_kantor' => $validated['alamat_kantor'],
                'telp_kantor' => $validated['telp_kantor'],
                'fax_kantor' => $validated['fax_kantor'],
                'email' => $validated['email'],
                'nama_importir' => $validated['nama_importir'],
                'alamat_importir' => $validated['alamat_importir'],
                'telp_importir' => $validated['telp_importir'],
                'fax_importir' => $validated['fax_importir'],
                'contact_person' => $validated['kontak_person'],
                'telp_cp' => $validated['telp_cp'],
                'komoditi' => $validated['komoditi_produk'],
                'merek' => $validated['merk'],
                'tipe_produk' => $validated['type_jenis_produk'],
                'alamat_pabrik' => $validated['alamat_pabrik'],
                'telp_pabrik' => $validated['telp_pabrik'],
                'fax_pabrik' => $validated['fax_pabrik'],
                'id_kota' => $id_kota,
            ]);

            $sertifikasi = Sertifikasi::create([
                'no_referensi' => $validated['no_referensi'],
                'id_perusahaan' => $perusahaan->id_perusahaan,
                'id_kategori' => $validated['id_kategori'],
                'tgl_permohonan' => $validated['tgl_permohonan'],
                'status_permohonan' => 'Belum Terbit',
            ]);
        });

        return redirect()->route('sertifikasi.index')->with('success', 'Data sertifikasi berhasil ditambahkan!');
    }

    /**
     * Menyimpan Data Sertifikasi Fase 2
     */
    public function storeFase2(Request $request, $id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        $validated = $request->validate([
            'no_sni' => 'nullable|string',
            'tgl_kontrak' => 'nullable|date',
            'tgl_pb_verifikasi' => 'nullable|date',
            'tgl_audit_kecukupan' => 'nullable|date',
            'auditor_kecukupan' => 'nullable|string',
            'tgl_mulai_audit_lapangan' => 'nullable|date',
            'tgl_selesai_audit_lapangan' => 'nullable|date',
            'tgl_rapat_teknis' => 'nullable|date',
            'tgl_sertifikasi' => 'nullable|date',
            'lama_sertifikasi' => 'nullable|string',
            'status_permohonan' => 'nullable|string',
            'nama_auditor' => 'nullable|string',
            'nama_petugas' => 'nullable|string',
            'nama_lab' => 'nullable|string',
            'nama_teknis' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $sertifikasi->update([
            'no_sni' => $validated['no_sni'] ?? null,
            'tgl_kontrak' => $validated['tgl_kontrak'] ?? null,
            'tgl_audit_kecukupan' => $validated['tgl_audit_kecukupan'] ?? null,
            'tgl_pemberitahuan_verifikasi' => $validated['tgl_pb_verifikasi'] ?? null,
            'tgl_mulai_audit_lapangan' => $validated['tgl_mulai_audit_lapangan'] ?? null,
            'tgl_selesai_audit_lapangan' => $validated['tgl_selesai_audit_lapangan'] ?? null,
            'tgl_rapat_teknis' => $validated['tgl_rapat_teknis'] ?? null,
            'tgl_sertifikasi' => $validated['tgl_sertifikasi'] ?? null,
            'lama_sertifikasi' => $validated['lama_sertifikasi'] ?? null,
            'status_permohonan' => $validated['status_permohonan'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $syncAuditors = [];

        if (! empty($validated['nama_auditor'])) {
            $leadNames = array_filter(array_map('trim', explode(',', $validated['nama_auditor'])));
            foreach ($leadNames as $name) {
                if (! empty($name)) {
                    $auditor = \App\Models\Auditor::firstOrCreate(['nama_auditor' => $name]);
                    $syncAuditors[$auditor->id_auditor] = ['peran' => 'lead'];
                }
            }
        }

        if (! empty($validated['auditor_kecukupan'])) {
            $kecukupanNames = array_filter(array_map('trim', explode(',', $validated['auditor_kecukupan'])));
            foreach ($kecukupanNames as $name) {
                if (! empty($name)) {
                    $auditor = \App\Models\Auditor::firstOrCreate(['nama_auditor' => $name]);
                    $syncAuditors[$auditor->id_auditor] = ['peran' => 'kecukupan'];
                }
            }
        }

        $sertifikasi->auditors()->sync($syncAuditors);

        if (isset($validated['nama_petugas'])) {
            $petugasNames = array_filter(array_map('trim', explode(',', $validated['nama_petugas'])));
            $petugasIds = [];
            foreach ($petugasNames as $name) {
                $petugasIds[] = \App\Models\PetugasPengambilContoh::firstOrCreate(['nama_ppc' => $name])->id_ppc;
            }
            $sertifikasi->petugasPengambilContoh()->sync($petugasIds);
        }

        if (isset($validated['nama_teknis'])) {
            $teknisNames = array_filter(array_map('trim', explode(',', $validated['nama_teknis'])));
            $teknisIds = [];
            foreach ($teknisNames as $name) {
                $teknisIds[] = \App\Models\TimTeknis::firstOrCreate(['nama_teknis' => $name])->id_teknis;
            }
            $sertifikasi->timTeknis()->sync($teknisIds);
        }

        if (! empty($validated['nama_lab'])) {
            $lab = \App\Models\Lab::firstOrCreate(['nama_lab' => trim($validated['nama_lab'])]);
            $sertifikasi->update(['id_lab' => $lab->id_lab]);
        }

        return redirect()->route('sertifikasi.index')->with('success', 'Data sertifikasi berhasil ditambahkan lengkap!');
    }

    /**
     * Menampilkan form edit
     */
    public function edit($id)
    {
        $sertifikasi = Sertifikasi::with(['perusahaan', 'auditors', 'petugasPengambilContoh', 'timTeknis', 'lab', 'kategori'])->findOrFail($id);
        $auditors = Auditor::orderBy('nama_auditor')->pluck('nama_auditor');
        $labs = Lab::orderBy('nama_lab')->pluck('nama_lab');
        $petugas = \App\Models\PetugasPengambilContoh::orderBy('nama_ppc')->pluck('nama_ppc');
        $teknis = \App\Models\TimTeknis::orderBy('nama_teknis')->pluck('nama_teknis');
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
        $provinsis = \App\Models\Provinsi::orderBy('provinsi')->get();

        return view('Sertifikasi.edit', compact('sertifikasi', 'auditors', 'labs', 'petugas', 'teknis', 'kategoris', 'provinsis'));
    }

    /**
     * Menyimpan perubahan data sertifikasi
     */
    public function update(Request $request, $id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);

        $validated = $request->validate([
            // Fase 1
            'no_referensi' => 'required|string|unique:sertifikasi,no_referensi,'.$id.',id_sertifikasi',
            'nama_perusahaan' => 'required|string',
            'alamat_kantor' => 'required|string',
            'telp_kantor' => 'nullable|string',
            'fax_kantor' => 'nullable|string',
            'email' => 'required|email',
            'nama_importir' => 'nullable|string',
            'alamat_importir' => 'nullable|string',
            'telp_importir' => 'nullable|string',
            'fax_importir' => 'nullable|string',
            'kontak_person' => 'nullable|string',
            'telp_cp' => 'nullable|string',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'komoditi_produk' => 'required|string',
            'merk' => 'nullable|string',
            'type_jenis_produk' => 'nullable|string',
            'alamat_pabrik' => 'nullable|string',
            'telp_pabrik' => 'nullable|string',
            'fax_pabrik' => 'nullable|string',
            'tgl_permohonan' => 'required|date',
            'status' => 'nullable|string',

            // Fase 2
            'no_sni' => 'nullable|string',
            'tgl_kontrak' => 'nullable|date',
            'tgl_pb_verifikasi' => 'nullable|date',
            'tgl_audit_kecukupan' => 'nullable|date',
            'auditor_kecukupan' => 'nullable|string',
            'tgl_mulai_audit_lapangan' => 'nullable|date',
            'tgl_selesai_audit_lapangan' => 'nullable|date',
            'tgl_rapat_teknis' => 'nullable|date',
            'tgl_sertifikasi' => 'nullable|date',
            'lama_sertifikasi' => 'nullable|string',
            'status_permohonan' => 'nullable|string',
            'nama_auditor' => 'nullable|string',
            'nama_petugas' => 'nullable|string',
            'nama_lab' => 'nullable|string',
            'nama_teknis' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($sertifikasi, $validated, $request) {
            $perusahaan = $sertifikasi->perusahaan;
            if ($perusahaan) {
                $id_provinsi = $request->id_provinsi;
                $id_kota = $request->id_kota ?: $perusahaan->id_kota;

                if (! empty($id_provinsi) && ! is_numeric($id_provinsi)) {
                    $prov = \App\Models\Provinsi::firstOrCreate(['provinsi' => $id_provinsi]);
                    $id_provinsi = $prov->id_provinsi;
                }

                if (! empty($id_kota) && ! is_numeric($id_kota)) {
                    if ($id_provinsi) {
                        $kota = \App\Models\Kota::firstOrCreate([
                            'id_provinsi' => $id_provinsi,
                            'kota' => $id_kota,
                        ]);
                        $id_kota = $kota->id_kota;
                    }
                }

                $perusahaan->update([
                    'nama_perusahaan' => $validated['nama_perusahaan'],
                    'alamat_kantor' => $validated['alamat_kantor'],
                    'telp_kantor' => $validated['telp_kantor'],
                    'fax_kantor' => $validated['fax_kantor'],
                    'email' => $validated['email'],
                    'nama_importir' => $validated['nama_importir'],
                    'alamat_importir' => $validated['alamat_importir'],
                    'telp_importir' => $validated['telp_importir'],
                    'fax_importir' => $validated['fax_importir'],
                    'contact_person' => $validated['kontak_person'],
                    'telp_cp' => $validated['telp_cp'],
                    'komoditi' => $validated['komoditi_produk'],
                    'merek' => $validated['merk'],
                    'tipe_produk' => $validated['type_jenis_produk'],
                    'alamat_pabrik' => $validated['alamat_pabrik'],
                    'telp_pabrik' => $validated['telp_pabrik'],
                    'fax_pabrik' => $validated['fax_pabrik'],
                    'id_kota' => $id_kota,
                ]);
            }

            $sertifikasi->update([
                'no_referensi' => $validated['no_referensi'],
                'id_kategori' => $validated['id_kategori'],
                'tgl_permohonan' => $validated['tgl_permohonan'],
                'status_permohonan' => $request->input('status_permohonan') ?: $request->input('status') ?: $sertifikasi->status_permohonan,
                'no_sni' => $validated['no_sni'] ?? null,
                'tgl_kontrak' => $validated['tgl_kontrak'] ?? null,
                'tgl_audit_kecukupan' => $validated['tgl_audit_kecukupan'] ?? null,
                'tgl_pemberitahuan_verifikasi' => $validated['tgl_pb_verifikasi'] ?? null,
                'tgl_mulai_audit_lapangan' => $validated['tgl_mulai_audit_lapangan'] ?? null,
                'tgl_selesai_audit_lapangan' => $validated['tgl_selesai_audit_lapangan'] ?? null,
                'tgl_rapat_teknis' => $validated['tgl_rapat_teknis'] ?? null,
                'tgl_sertifikasi' => $validated['tgl_sertifikasi'] ?? null,
                'lama_sertifikasi' => $validated['lama_sertifikasi'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $syncAuditors = [];

            if (! empty($validated['nama_auditor'])) {
                $leadNames = array_filter(array_map('trim', explode(',', $validated['nama_auditor'])));
                foreach ($leadNames as $name) {
                    if (! empty($name)) {
                        $auditor = \App\Models\Auditor::firstOrCreate(['nama_auditor' => $name]);
                        $syncAuditors[$auditor->id_auditor] = ['peran' => 'lead'];
                    }
                }
            }

            if (! empty($validated['auditor_kecukupan'])) {
                $kecukupanNames = array_filter(array_map('trim', explode(',', $validated['auditor_kecukupan'])));
                foreach ($kecukupanNames as $name) {
                    if (! empty($name)) {
                        $auditor = \App\Models\Auditor::firstOrCreate(['nama_auditor' => $name]);
                        $syncAuditors[$auditor->id_auditor] = ['peran' => 'kecukupan'];
                    }
                }
            }

            $sertifikasi->auditors()->sync($syncAuditors);

            if (isset($validated['nama_petugas'])) {
                $petugasNames = array_filter(array_map('trim', explode(',', $validated['nama_petugas'])));
                $petugasIds = [];
                foreach ($petugasNames as $name) {
                    $petugasIds[] = \App\Models\PetugasPengambilContoh::firstOrCreate(['nama_ppc' => $name])->id_ppc;
                }
                $sertifikasi->petugasPengambilContoh()->sync($petugasIds);
            } else {
                $sertifikasi->petugasPengambilContoh()->sync([]);
            }

            if (isset($validated['nama_teknis'])) {
                $teknisNames = array_filter(array_map('trim', explode(',', $validated['nama_teknis'])));
                $teknisIds = [];
                foreach ($teknisNames as $name) {
                    $teknisIds[] = \App\Models\TimTeknis::firstOrCreate(['nama_teknis' => $name])->id_teknis;
                }
                $sertifikasi->timTeknis()->sync($teknisIds);
            } else {
                $sertifikasi->timTeknis()->sync([]);
            }

            if (! empty($validated['nama_lab'])) {
                $lab = \App\Models\Lab::firstOrCreate(['nama_lab' => trim($validated['nama_lab'])]);
                $sertifikasi->update(['id_lab' => $lab->id_lab]);
            } else {
                $sertifikasi->update(['id_lab' => null]);
            }
        });

        return redirect()->route('sertifikasi.index')->with('success', 'Data sertifikasi berhasil diperbarui!');
    }

    /**
     * Menghapus data sertifikasi
     */
    public function destroy($id)
    {
        $sertifikasi = Sertifikasi::findOrFail($id);
        $sertifikasi->delete();

        return redirect()->route('sertifikasi.index')->with('success', 'Data sertifikasi berhasil dihapus!');
    }
}
