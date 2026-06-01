<?php

namespace App\Http\Controllers;

use App\Models\Sertifikasi;
use App\Models\Surveilans;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Kartu Ringkasan Statistik
        $totalSertifikatAktif = Sertifikasi::whereNotNull('no_sni')->where('no_sni', '!=', '')->count();
        $surveilansTahunIni = Surveilans::whereYear('tgl_pelaksanaan', now()->year)->count();
        $suratBreakdown = \App\Models\Surat::selectRaw('jenis_surat, count(*) as total')
            ->groupBy('jenis_surat')
            ->get();
        $totalSurat = $suratBreakdown->sum('total');
        $sertifikasiProses = Sertifikasi::where(function ($q) {
            $q->whereNull('no_sni')->orWhere('no_sni', '');
        })->count();

        // 2. Jadwal Surveilans Terdekat (Mendatang)
        $surveilansMendatang = Surveilans::with(['sertifikasi.perusahaan'])
            ->whereNotNull('tgl_pelaksanaan')
            ->whereDate('tgl_pelaksanaan', '>=', now())
            ->orderBy('tgl_pelaksanaan', 'asc')
            ->take(5)
            ->get();

        // 3. Sertifikasi Tertunda (Sedang Proses)
        $sertifikasiTertunda = Sertifikasi::with(['perusahaan'])
            ->where(function ($q) {
                $q->whereNull('no_sni')->orWhere('no_sni', '');
            })
            ->latest()
            ->take(5)
            ->get();

        // 4. Pengingat Menyurati Surveilans & Masa Berlaku
        $sertifikasis = Sertifikasi::with(['perusahaan', 'kategori', 'surveilans'])
            ->where('status_permohonan', 'Terbit')
            ->whereNotNull('tgl_sertifikasi')
            ->get();

        $reminders = [];
        $now = \Carbon\Carbon::now();

        foreach ($sertifikasis as $sertifikat) {
            $tglSertifikat = \Carbon\Carbon::parse($sertifikat->tgl_sertifikasi);

            // Kategori & Periode Pengingat
            $kategoriObj = $sertifikat->kategori;
            $kategoriName = $kategoriObj->nama_kategori ?? 'Non SIINAS';
            $kategoriUpper = strtoupper($kategoriName);

            // Tentukan masa berlaku, jumlah surveilans maks, dan pengingat (bulan sebelum)
            if ($kategoriUpper === 'SIINAS' || $kategoriUpper === 'LUAR NEGERI') {
                $yearsValid = 5;
                $maxSurveilans = 4;
                $reminderMonths = ($kategoriUpper === 'SIINAS') ? 9 : 6;
            } else {
                // Non SIINAS
                $yearsValid = 4;
                $maxSurveilans = 3;
                $reminderMonths = 11;
            }

            $masaBerlaku = (clone $tglSertifikat)->addYears($yearsValid);

            // Cari periode berikutnya yang belum dijadwalkan
            $completedPeriods = $sertifikat->surveilans->pluck('periode')->toArray();
            $nextPeriod = null;
            for ($p = 1; $p <= $maxSurveilans; $p++) {
                if (! in_array($p, $completedPeriods)) {
                    $nextPeriod = $p;
                    break;
                }
            }

            if ($nextPeriod !== null) {
                $dueDate = (clone $tglSertifikat)->addYears($nextPeriod);
                $label = 'Survailen '.$nextPeriod;

                // Munculkan pengingat jika tanggal sekarang sudah melewati/masuk masa pengingat
                $reminderStartDate = (clone $dueDate)->subMonths($reminderMonths);

                if ($now->greaterThanOrEqualTo($reminderStartDate)) {
                    $daysRemaining = (int) $now->diffInDays($dueDate, false);
                    $reminders[] = [
                        'sertifikasi' => $sertifikat,
                        'kategori' => $kategoriName,
                        'label' => $label,
                        'tgl_sertifikasi' => $tglSertifikat,
                        'masa_berlaku' => $masaBerlaku,
                        'tgl_jatuh_tempo' => $dueDate,
                        'hari_sisa' => $daysRemaining,
                        'periode' => $nextPeriod,
                    ];
                }
            }
        }

        // Urutkan pengingat berdasarkan jatuh tempo terdekat
        usort($reminders, function ($a, $b) {
            return $a['tgl_jatuh_tempo']->timestamp <=> $b['tgl_jatuh_tempo']->timestamp;
        });

        return view('Dashboard.index', compact(
            'totalSertifikatAktif',
            'surveilansTahunIni',
            'totalSurat',
            'suratBreakdown',
            'sertifikasiProses',
            'surveilansMendatang',
            'sertifikasiTertunda',
            'reminders'
        ));
    }
}
