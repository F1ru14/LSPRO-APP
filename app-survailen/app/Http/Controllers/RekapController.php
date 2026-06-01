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

        // Map display column names -> actual table.column
        $columnMap = [
            'no_referensi' => 'sertifikasi.no_referensi',
            'kategori' => 'kategori.nama_kategori',
            'nama_perusahaan' => 'perusahaan.nama_perusahaan',
            'alamat_kantor' => 'perusahaan.alamat_kantor',
            'telp_kantor' => 'perusahaan.telp_kantor',
            'email' => 'perusahaan.email',
            'komoditi_produk' => 'perusahaan.komoditi',
            'merk' => 'perusahaan.merek',
            'no_sni' => 'sertifikasi.no_sni',
            'periode_survailen' => 'surveillance.periode',
            'tgl_pelaksanaan_survailen' => 'surveillance.tgl_pelaksanaan',
            'lab_pengujian' => 'lab.nama_lab',
            'auditor_survailen' => "(SELECT GROUP_CONCAT(auditor.nama_auditor SEPARATOR ', ') FROM surveillance_auditor JOIN auditor ON surveillance_auditor.id_auditor = auditor.id_auditor WHERE surveillance_auditor.id_surveillance = surveillance.id_surveillance)",
            'ppc_survailen' => "(SELECT GROUP_CONCAT(petugas_pengambil_contoh.nama_ppc SEPARATOR ', ') FROM surveillance_petugas_pengambil_contoh JOIN petugas_pengambil_contoh ON surveillance_petugas_pengambil_contoh.id_ppc = petugas_pengambil_contoh.id_ppc WHERE surveillance_petugas_pengambil_contoh.id_surveillance = surveillance.id_surveillance)",
            'tgl_pemberitahuan' => "(SELECT MAX(tgl_terbit) FROM surat WHERE surat.id_surveillance = surveillance.id_surveillance AND jenis_surat IN ('Pemberitahuan', 'Pengawasan Berkala'))",
            'tgl_teguran_1' => "(SELECT MAX(tgl_terbit) FROM surat WHERE surat.id_surveillance = surveillance.id_surveillance AND jenis_surat = 'Teguran 1')",
            'tgl_teguran_2' => "(SELECT MAX(tgl_terbit) FROM surat WHERE surat.id_surveillance = surveillance.id_surveillance AND jenis_surat = 'Teguran 2')",
            'tgl_pembekuan_1' => "(SELECT MAX(tgl_terbit) FROM surat WHERE surat.id_surveillance = surveillance.id_surveillance AND jenis_surat = 'Pembekuan 1')",
            'tgl_pembekuan_2' => "(SELECT MAX(tgl_terbit) FROM surat WHERE surat.id_surveillance = surveillance.id_surveillance AND jenis_surat = 'Pembekuan 2')",
            'keterangan_survailen' => 'surveillance.keterangan',
        ];

        // Buat daftar select dengan alias agar nama kolom tetap sama di hasil query
        $selectList = [];
        foreach ($selectedColumns as $col) {
            if (isset($columnMap[$col])) {
                $selectList[] = \DB::raw($columnMap[$col].' as '.$col);
            }
        }

        $query = \DB::table('surveillance')
            ->leftJoin('sertifikasi', 'surveillance.id_sertifikasi', '=', 'sertifikasi.id_sertifikasi')
            ->leftJoin('perusahaan', 'sertifikasi.id_perusahaan', '=', 'perusahaan.id_perusahaan')
            ->leftJoin('kategori', 'sertifikasi.id_kategori', '=', 'kategori.id_kategori')
            ->leftJoin('lab', 'surveillance.id_lab', '=', 'lab.id_lab')
            ->select($selectList);

        if (! empty($startYear) && ! empty($endYear)) {
            $query->whereYear('surveillance.tgl_pelaksanaan', '>=', $startYear)
                ->whereYear('surveillance.tgl_pelaksanaan', '<=', $endYear);
        } elseif (! empty($startYear)) {
            $query->whereYear('surveillance.tgl_pelaksanaan', '>=', $startYear);
        } elseif (! empty($endYear)) {
            $query->whereYear('surveillance.tgl_pelaksanaan', '<=', $endYear);
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

        $fileName = 'rekap_survailen_'.date('Ymd_His').'.xlsx';

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
            'email' => 'Email',
            'komoditi_produk' => 'Komoditi Produk',
            'merk' => 'Merk',
            'no_sni' => 'No. SNI',
            'periode_survailen' => 'Periode Survailen (Ke-)',
            'tgl_pelaksanaan_survailen' => 'Tgl Pelaksanaan Survailen',
            'lab_pengujian' => 'Laboratorium Pengujian',
            'auditor_survailen' => 'Auditor Survailen',
            'ppc_survailen' => 'PPC Survailen',
            'tgl_pemberitahuan' => 'Tgl Surat Pemberitahuan',
            'tgl_teguran_1' => 'Tgl Surat Teguran 1',
            'tgl_teguran_2' => 'Tgl Surat Teguran 2',
            'tgl_pembekuan_1' => 'Tgl Surat Pembekuan 1',
            'tgl_pembekuan_2' => 'Tgl Surat Pembekuan 2',
            'keterangan_survailen' => 'Keterangan Survailen',
        ];
    }
}
