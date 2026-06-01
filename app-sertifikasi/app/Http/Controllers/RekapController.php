<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapController extends Controller
{
    public function index()
    {
        $availableColumns = $this->availableColumns();
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();

        return view('rekap.index', compact('availableColumns', 'kategoris'));
    }

    public function export(Request $request)
    {
        $selectedColumns = $request->input('columns', []);

        if (empty($selectedColumns)) {
            return back()->with('error', 'Pilih minimal satu kolom untuk diekspor.');
        }

        $startYear = $request->input('start_year');
        $endYear = $request->input('end_year');
        $selectedKategori = $request->input('id_kategori');

        // Map display column names -> actual table.column (karena data perusahaan sudah di tabel terpisah)
        $columnMap = [
            'no_referensi' => 'sertifikasi.no_referensi',
            'kategori' => 'kategori.nama_kategori',
            'nama_perusahaan' => 'perusahaan.nama_perusahaan',
            'alamat_kantor' => 'perusahaan.alamat_kantor',
            'telp_kantor' => 'perusahaan.telp_kantor',
            'fax_kantor' => 'perusahaan.fax_kantor',
            'email' => 'perusahaan.email',
            'nama_importir' => 'perusahaan.nama_importir',
            'alamat_importir' => 'perusahaan.alamat_importir',
            'telp_importir' => 'perusahaan.telp_importir',
            'fax_importir' => 'perusahaan.fax_importir',
            'kontak_person' => 'perusahaan.contact_person',
            'komoditi_produk' => 'perusahaan.komoditi',
            'merk' => 'perusahaan.merek',
            'type_jenis_produk' => 'perusahaan.tipe_produk',
            'alamat_pabrik' => 'perusahaan.alamat_pabrik',
            'telp_pabrik' => 'perusahaan.telp_pabrik',
            'fax_pabrik' => 'perusahaan.fax_pabrik',
            'tgl_permohonan' => 'sertifikasi.tgl_permohonan',
            'no_sni' => 'sertifikasi.no_sni',
            'tgl_kontrak' => 'sertifikasi.tgl_kontrak',
            'tgl_audit_kecukupan' => 'sertifikasi.tgl_audit_kecukupan',
            'auditor' => "(SELECT GROUP_CONCAT(auditor.nama_auditor SEPARATOR ', ') FROM sertifikasi_auditor JOIN auditor ON sertifikasi_auditor.id_auditor = auditor.id_auditor WHERE sertifikasi_auditor.id_sertifikasi = sertifikasi.id_sertifikasi)",
            'tgl_mulai_audit_lapangan' => 'sertifikasi.tgl_mulai_audit_lapangan',
            'tgl_selesai_audit_lapangan' => 'sertifikasi.tgl_selesai_audit_lapangan',
            'tgl_rapat_teknis' => 'sertifikasi.tgl_rapat_teknis',
            'tgl_sertifikasi' => 'sertifikasi.tgl_sertifikasi',
            'lama_sertifikasi' => 'sertifikasi.lama_sertifikasi',
            'status_permohonan' => 'sertifikasi.status_permohonan',
            'keterangan' => 'sertifikasi.keterangan',
        ];

        // Buat daftar select dengan alias agar nama kolom tetap sama di hasil query
        $selectList = [];
        foreach ($selectedColumns as $col) {
            if (isset($columnMap[$col])) {
                $selectList[] = \DB::raw($columnMap[$col].' as '.$col);
            }
        }

        $query = \DB::table('sertifikasi')
            ->leftJoin('perusahaan', 'sertifikasi.id_perusahaan', '=', 'perusahaan.id_perusahaan')
            ->leftJoin('kategori', 'sertifikasi.id_kategori', '=', 'kategori.id_kategori')
            ->select($selectList);

        if (! empty($startYear) && ! empty($endYear)) {
            $query->whereYear('sertifikasi.tgl_permohonan', '>=', $startYear)
                ->whereYear('sertifikasi.tgl_permohonan', '<=', $endYear);
        } elseif (! empty($startYear)) {
            $query->whereYear('sertifikasi.tgl_permohonan', '>=', $startYear);
        } elseif (! empty($endYear)) {
            $query->whereYear('sertifikasi.tgl_permohonan', '<=', $endYear);
        }

        if (! empty($selectedKategori)) {
            $query->where('sertifikasi.id_kategori', $selectedKategori);
        }

        $data = $query->get();

        // Header label
        $availableColumns = $this->availableColumns();
        $headers = array_map(fn ($col) => $availableColumns[$col] ?? $col, $selectedColumns);

        $excelData = [$headers];
        foreach ($data as $row) {
            $rowArray = (array) $row;
            $rowData = [];
            foreach ($selectedColumns as $col) {
                $rowData[] = $rowArray[$col] ?? '';
            }
            $excelData[] = $rowData;
        }

        $fileName = 'rekap_sertifikasi_'.date('Ymd_His').'.xlsx';

        $response = new StreamedResponse(function () use ($excelData) {
            $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($excelData);
            $xlsx->saveAs('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    private function availableColumns(): array
    {
        return [
            'no_referensi' => 'No. Referensi',
            'kategori' => 'Kategori',
            'nama_perusahaan' => 'Nama Perusahaan',
            'alamat_kantor' => 'Alamat Kantor',
            'telp_kantor' => 'Telp Kantor',
            'fax_kantor' => 'Fax Kantor',
            'email' => 'Email',
            'nama_importir' => 'Nama Importir',
            'alamat_importir' => 'Alamat Importir',
            'telp_importir' => 'Telp Importir',
            'fax_importir' => 'Fax Importir',
            'kontak_person' => 'Kontak Person',
            'komoditi_produk' => 'Komoditi Produk',
            'merk' => 'Merk',
            'type_jenis_produk' => 'Tipe/Jenis Produk',
            'alamat_pabrik' => 'Alamat Pabrik',
            'telp_pabrik' => 'Telp Pabrik',
            'fax_pabrik' => 'Fax Pabrik',
            'tgl_permohonan' => 'Tgl Permohonan',
            'no_sni' => 'No. SNI',
            'tgl_kontrak' => 'Tgl Kontrak',
            'tgl_audit_kecukupan' => 'Tgl Audit Kecukupan',
            'auditor' => 'Auditor',
            'tgl_mulai_audit_lapangan' => 'Tgl Mulai Audit Lapangan',
            'tgl_selesai_audit_lapangan' => 'Tgl Selesai Audit Lapangan',
            'tgl_rapat_teknis' => 'Tgl Rapat Teknis',
            'tgl_sertifikasi' => 'Tgl Sertifikasi',
            'lama_sertifikasi' => 'Lama Sertifikasi',
            'status_permohonan' => 'Status Permohonan',
            'keterangan' => 'Keterangan',
        ];
    }
}
