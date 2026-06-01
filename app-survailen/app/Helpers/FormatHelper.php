<?php

if (! function_exists('tanggal_indo')) {
    /**
     * Format tanggal ke dalam format Bahasa Indonesia (contoh: 20 Mei 2026)
     *
     * @param  string|null  $tanggal
     * @return string
     */
    function tanggal_indo($tanggal)
    {
        if (! $tanggal) {
            return '-';
        }

        return \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y');
    }
}

if (! function_exists('terbilang')) {
    /**
     * Mengubah angka menjadi teks terbilang Bahasa Indonesia
     *
     * @param  numeric  $angka
     * @return string
     */
    function terbilang($angka)
    {
        $angka = abs((float) $angka);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $terbilang = '';
        if ($angka < 12) {
            $terbilang = ' '.$baca[(int) $angka];
        } elseif ($angka < 20) {
            $terbilang = terbilang($angka - 10).' Belas';
        } elseif ($angka < 100) {
            $terbilang = terbilang($angka / 10).' Puluh'.terbilang(fmod($angka, 10));
        } elseif ($angka < 200) {
            $terbilang = ' Seratus'.terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = terbilang($angka / 100).' Ratus'.terbilang(fmod($angka, 100));
        } elseif ($angka < 2000) {
            $terbilang = ' Seribu'.terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = terbilang($angka / 1000).' Ribu'.terbilang(fmod($angka, 1000));
        } elseif ($angka < 1000000000) {
            $terbilang = terbilang($angka / 1000000).' Juta'.terbilang(fmod($angka, 1000000));
        } elseif ($angka < 1000000000000) {
            $terbilang = terbilang($angka / 1000000000).' Miliar'.terbilang(fmod($angka, 1000000000));
        }

        return $terbilang;
    }
}

if (! function_exists('bulan_romawi')) {
    /**
     * Mengubah angka bulan menjadi Romawi
     */
    function bulan_romawi($bulan)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        return $romans[(int) $bulan] ?? 'I';
    }
}

if (! function_exists('get_perihal_surat')) {
    /**
     * Menentukan teks perihal berdasarkan jenis surat
     */
    function get_perihal_surat($jenis, $survailenKe = '1')
    {
        $jenis = strtolower(trim($jenis ?? 'pemberitahuan'));
        if ($jenis === 'teguran1') {
            return 'Teguran 1';
        }
        if ($jenis === 'teguran2') {
            return 'Teguran 2';
        }
        if ($jenis === 'pembekuan1' || $jenis === 'pembekuan') {
            return 'Pembekuan ke-1 SPPT SNI';
        }
        if ($jenis === 'pembekuan2') {
            return 'Pembekuan ke-2 SPPT SNI';
        }
        if ($jenis === 'pelaksanaan') {
            return 'Pelaksanaan Survailen ke-'.$survailenKe;
        }

        return 'Pemberitahuan Survailen ke-'.$survailenKe;
    }
}

if (! function_exists('get_nama_surat')) {
    /**
     * Menentukan nama surat untuk keperluan judul/form
     */
    function get_nama_surat($jenis)
    {
        $jenis = strtolower(trim($jenis ?? 'pemberitahuan'));
        if ($jenis === 'teguran1') {
            return 'Surat Teguran 1';
        }
        if ($jenis === 'teguran2') {
            return 'Surat Teguran 2';
        }
        if ($jenis === 'pembekuan1' || $jenis === 'pembekuan') {
            return 'Surat Pembekuan 1';
        }
        if ($jenis === 'pembekuan2') {
            return 'Surat Pembekuan 2';
        }
        if ($jenis === 'pelaksanaan') {
            return 'Surat Pelaksanaan';
        }

        return 'Surat Pemberitahuan';
    }
}
