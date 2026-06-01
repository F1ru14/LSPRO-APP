<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class PersuratanController extends Controller
{
    public function generate(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_surat' => 'required|date',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string|max:1000',
            'kota_provinsi' => 'required|string|max:255',
            'tanggal_sertifikat' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|string|max:255',
            'biaya' => 'required|string|max:255',
            'komoditi' => 'required|string|max:255',
            'laboratorium' => 'required|string|max:255',
            'ketua_tim' => 'required|string|max:255',
            'nomor_surat_rujukan' => 'nullable|string|max:255',
            'nomor_sppt_sni' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'nama_perusahaan_induk' => 'nullable|string|max:255',
            'tanggal_terbit_sertifikat_induk' => 'nullable|string|max:255',
        ]);

        // Menentukan template berdasarkan jenis surat
        $jenis_surat = $request->input('jenis_surat', 'pemberitahuan');
        $templateName = 'surat_pemberitahuan_survailen.docx';

        if ($jenis_surat === 'teguran1') {
            $templateName = 'surat_teguran_1.docx';
        } elseif ($jenis_surat === 'teguran2') {
            $templateName = 'surat_teguran_2.docx';
        } elseif ($jenis_surat === 'pembekuan1') {
            $templateName = 'surat_pembekuan_1.docx';
        } elseif ($jenis_surat === 'pembekuan2') {
            $templateName = 'surat_pembekuan_2.docx';
        }

        // Cek kategori luar negeri dari sertifikasi_id
        $isLuarNegeri = false;
        if ($request->filled('sertifikasi_id')) {
            $sertifikasi = \App\Models\Sertifikasi::with('kategori')->find($request->sertifikasi_id);
            if ($sertifikasi && $sertifikasi->kategori && strtolower($sertifikasi->kategori->nama_kategori) === 'luar negeri') {
                $isLuarNegeri = true;
            }
        }

        // Untuk surat pembekuan, formatnya disamakan dengan Dalam Negeri
        if (str_contains($jenis_surat, 'pembekuan')) {
            $isLuarNegeri = false;
        }

        // Mengarahkan ke file template DOCX di folder DN atau LN
        $folder = $isLuarNegeri ? 'LN' : 'DN';
        $templatePath = resource_path("template_surat/{$folder}/".$templateName);

        if (! file_exists($templatePath)) {
            return redirect()->back()->with('error', 'File template surat tidak ditemukan.');
        }

        // Format tanggal menggunakan Helper (DRY)
        $dateCarbon = \Carbon\Carbon::parse($request->tanggal_surat);
        $tanggal = tanggal_indo($request->tanggal_surat);
        $tglSertifikatFormatted = tanggal_indo($request->tanggal_sertifikat);
        $tglSurvailenFormatted = tanggal_indo($request->tgl_survailen);
        $tglSertifikatIndukFormatted = tanggal_indo($request->tanggal_terbit_sertifikat_induk);

        // Auto-generate Nomor Surat seperti template dengan Bulan Romawi & Tahun menggunakan Helper (DRY)
        $romanMonth = bulan_romawi($dateCarbon->month);
        $year = $dateCarbon->year;

        // Format identik dengan template (prefix sudah ada di docx: /BSPJI-Surabaya/MS/${nomor_surat})
        $nomorSurat = $romanMonth.'/'.$year;

        // Load the template with TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Hitung Terbilang Biaya menggunakan Helper
        $angkaBiaya = preg_replace('/[^0-9]/', '', $request->biaya);
        $terbilangBiaya = 'Nol Rupiah';
        if (! empty($angkaBiaya)) {
            $terbilangBiaya = trim(terbilang($angkaBiaya)).' Rupiah';
            $terbilangBiaya = ucwords(strtolower($terbilangBiaya));
        }

        // Mengganti placeholder di template DOCX
        // Menambahkan \n untuk newline antara nama dan alamat karena di docx mereka di satu baris yang sama
        $namaAlamat = $request->nama_perusahaan."\n".$request->alamat_perusahaan;

        $survailenKe = $request->input('survailen', '1');

        // Menentukan perihal untuk template docx menggunakan Helper (DRY)
        $perihal_text = get_perihal_surat($jenis_surat, $survailenKe);

        $templateProcessor->setValues([
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => $tanggal,
            'perihal' => $perihal_text,
            'nama_perusahaan' => $namaAlamat,
            'alamat_perusahaan' => '', // Dikosongkan karena digabung dengan nama
            'kota_provinsi' => $request->kota_provinsi,
            'tanggal_sertifikat' => $tglSertifikatFormatted,
            'survailen' => $survailenKe,
            'tgl_survailen' => $tglSurvailenFormatted,
            'tanggal_surat_teguran2' => $tglSurvailenFormatted,
            'tgl_surat_pembekuan1' => $tglSurvailenFormatted,
            'nomor_surat_survailen' => $request->nomor_surat_rujukan ?? '-',
            'nomor_surat_teguran2' => $request->nomor_surat_rujukan ?? '-',
            'nomor_surat_pembekuan 1' => $request->nomor_surat_rujukan ?? '-',
            'tgl_pemberitahuan_survailen' => $tglSurvailenFormatted,
            'nomor_survailen_LN' => $request->nomor_surat_rujukan ?? '-',
            'nomor_SPPT_SNI' => $request->nomor_sppt_sni ?? '-',
            'merek' => $request->merek ?? '-',
            'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
            'tanggal_pelaksanan' => $request->tanggal_pelaksanaan, // Typo fallback
            'waktu_pelaksanaan' => str_replace(' s/d selesai', '', $request->waktu_pelaksanaan), // Di template sudah ada s/d selesai
            'biaya' => $request->biaya,
            'terbilang_biaya' => $terbilangBiaya,
            'jenis_produk' => $request->komoditi,
            'nama_perusahaan_induk' => $request->nama_perusahaan_induk ?? '-',
            'tgl_terbit_sertifikat_induk' => $tglSertifikatIndukFormatted,
            'laboratorium' => $request->laboratorium,
            'ketua_tim' => $request->ketua_tim,
        ]);

        // Menyimpan ke file temporary
        // Nama file mengikuti perihal dan nama perusahaan, dengan karakter tidak valid diganti underscore
        $jenisName = ucwords(str_replace(['1', '2'], [' 1', ' 2'], $jenis_surat));
        if (str_contains($jenis_surat, 'pemberitahuan')) {
            $jenisName = 'Pemberitahuan';
        }
        $safePerihal = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $jenisName);
        $safePerusahaan = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $request->nama_perusahaan);
        $fileName = 'Surat_'.$safePerihal.'_'.$safePerusahaan.'.docx';

        // --- SIMPAN KE DATABASE SECARA OTOMATIS ---
        $sertifikasi_id = $request->input('sertifikasi_id');
        if ($sertifikasi_id) {
            // 1. Buat record Surveilans awal (jika belum ada) agar hilang dari Pengingat
            $surveilans = \App\Models\Surveilans::firstOrCreate(
                [
                    'id_sertifikasi' => $sertifikasi_id,
                    'periode' => $survailenKe,
                ],
                [
                    'id_user' => auth()->id() ?? 1,
                ]
            );

            // 2. Petakan jenis surat untuk database
            $jenisSuratDb = 'Pengawasan Berkala';
            if ($jenis_surat === 'teguran1') {
                $jenisSuratDb = 'Teguran 1';
            } elseif ($jenis_surat === 'teguran2') {
                $jenisSuratDb = 'Teguran 2';
            } elseif ($jenis_surat === 'pembekuan1' || $jenis_surat === 'pembekuan') {
                $jenisSuratDb = 'Pembekuan 1';
            } elseif ($jenis_surat === 'pembekuan2') {
                $jenisSuratDb = 'Pembekuan 2';
            }

            // 3. Simpan rekam jejak Surat ke Database
            \App\Models\Surat::updateOrCreate(
                [
                    'id_surveillance' => $surveilans->id_surveillance,
                    'jenis_surat' => $jenisSuratDb,
                ],
                [
                    'id_user' => auth()->id() ?? 1,
                    'tgl_terbit' => $request->tanggal_surat,
                ]
            );
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $templateProcessor->saveAs($tempFile);

        // Mendownload file
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
