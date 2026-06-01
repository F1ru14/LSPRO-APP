@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8">
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center space-y-4 md:space-y-0 mb-8">
        <div>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="http://survailen.localhost:8001/surveilans" class="hover:text-blue-600">Survailen</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-800">Form Persuratan</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800" id="form-title">Form {{ get_nama_surat(request('jenis')) }}</h1>
            <p class="text-gray-500">Isi detail surat untuk tahap pengawasan berkala.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Kembali button moved to bottom -->
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 mb-6">
        <!-- Form Container (Blue Box) -->
        <div class="w-full lg:w-2/5 bg-white rounded-2xl shadow-sm border-2 border-blue-500 overflow-hidden flex flex-col">
            <form action="{{ route('persuratan.generate') }}" method="POST" class="p-4 md:p-6 flex-1 flex flex-col" id="persuratan-form" target="_blank" onsubmit="setTimeout(() => { window.history.back(); }, 1000);">
                @csrf
                <input type="hidden" name="sertifikasi_id" id="sertifikasi_id" value="{{ request('sertifikasi_id') }}">
                <input type="hidden" name="jenis_surat" id="jenis_surat" value="{{ request('jenis', 'pemberitahuan') }}">
                <input type="hidden" name="survailen" id="survailen" value="{{ request('survailen', '1') }}">
                
                <div class="mb-4 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Detail <span id="jenis-surat-label" class="ml-1 text-blue-600">
                            {{ get_nama_surat(request('jenis')) }}
                        </span>
                    </h2>
                </div>

                <div class="space-y-5 flex-1">
                    <div class="{{ request('sertifikasi_id') ? 'hidden' : '' }}">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Data Sertifikasi</label>
                        <select id="select_sertifikasi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                            <option value="">-- Ketik Data Secara Manual --</option>
                            @foreach($sertifikasis as $sert)
                                <option value="{{ $sert->id_sertifikasi }}" 
                                    data-nama="{{ $sert->perusahaan->nama_perusahaan ?? '' }}"
                                    data-alamat="{{ $sert->perusahaan->alamat_kantor ?? $sert->perusahaan->alamat_pabrik ?? '' }}"
                                    data-kota="{{ $sert->perusahaan->kota->provinsi->provinsi ?? '' }}"
                                    data-komoditi="{{ !empty($sert->perusahaan->komoditi) ? $sert->perusahaan->komoditi : ($sert->perusahaan->tipe_produk ?? '') }}"
                                    data-tanggal="{{ $sert->tgl_sertifikasi ?? '' }}"
                                    data-merek="{{ $sert->merk ?? '' }}"
                                    data-kategori="{{ $sert->kategori->nama_kategori ?? '' }}"
                                >
                                    {{ $sert->perusahaan->nama_perusahaan ?? 'Tanpa Nama' }} ({{ $sert->no_sni ?? 'Tanpa SNI' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 {{ str_contains(request('jenis', 'pemberitahuan'), 'teguran') || str_contains(request('jenis', 'pemberitahuan'), 'pembekuan') ? 'md:grid-cols-2' : '' }} gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Surat</label>
                            <input type="date" id="tanggal_surat" name="tanggal_surat" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                            <input type="hidden" name="tgl_survailen" value="{{ request('tgl_survailen') }}">
                        </div>
                        @if(str_contains(request('jenis', 'pemberitahuan'), 'teguran') || str_contains(request('jenis', 'pemberitahuan'), 'pembekuan'))
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                @if(str_contains(request('jenis', 'pemberitahuan'), 'teguran'))
                                    Nomor Surat Survailen
                                @else
                                    Nomor Surat Sebelumnya
                                @endif
                            </label>
                            <input type="text" id="nomor_surat_rujukan" name="nomor_surat_rujukan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Masukkan Nomor Surat">
                        </div>
                        @endif
                    </div>

                    @if(str_contains(request('jenis', 'pemberitahuan'), 'pembekuan'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor SPPT SNI</label>
                            <input type="text" id="nomor_sppt_sni" name="nomor_sppt_sni" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Masukkan Nomor SPPT SNI" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Merek</label>
                            <input type="text" id="merek" name="merek" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Otomatis terisi" required readonly>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan</label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Otomatis terisi atau ketik manual" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Perusahaan</label>
                        <textarea id="alamat_perusahaan" name="alamat_perusahaan" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Otomatis terisi atau ketik manual" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi</label>
                        <input type="text" id="kota_provinsi" name="kota_provinsi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none transition text-sm" placeholder="Otomatis terisi" required readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Terbit Sertifikat</label>
                        <input type="date" id="tanggal_sertifikat" name="tanggal_sertifikat" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" required>
                    </div>
                    
                    <div class="ln-only hidden space-y-4 border-2 border-dashed border-gray-300 p-4 rounded-xl bg-gray-50">
                        <h3 class="font-bold text-gray-700">Data Khusus Luar Negeri</h3>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan Induk</label>
                            <input type="text" id="nama_perusahaan_induk" name="nama_perusahaan_induk" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Masukkan Nama Perusahaan Induk">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Terbit Sertifikat Induk</label>
                            <input type="date" id="tanggal_terbit_sertifikat_induk" name="tanggal_terbit_sertifikat_induk" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mulai Pelaksanaan</label>
                            <input type="date" id="mulai_pelaksanaan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Selesai Pelaksanaan</label>
                            <input type="date" id="selesai_pelaksanaan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hari/Tanggal Pelaksanaan</label>
                        <input type="text" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 cursor-not-allowed focus:outline-none transition text-sm" placeholder="Akan terisi otomatis dari kalender di atas" required readonly>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Jam Mulai</label>
                            <input type="time" id="jam_mulai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" value="09:00">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Pelaksanaan</label>
                            <input type="text" id="waktu_pelaksanaan" name="waktu_pelaksanaan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 cursor-not-allowed focus:outline-none transition text-sm" value="09.00 s/d selesai" required readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Pelaksanaan</label>
                        <input type="text" id="biaya" name="biaya" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Contoh: Rp. 11.250.000" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Komoditi</label>
                        <input type="text" id="komoditi" name="komoditi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Otomatis terisi atau ketik manual" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Laboratorium</label>
                        <input type="text" id="laboratorium" name="laboratorium" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" value="Laboratorium BSPJI Surabaya" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ketua Tim</label>
                        <input type="text" id="ketua_tim" name="ketua_tim" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" value="Indra Wahyu Diantoro" required>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-6 mt-6">
                    <a href="javascript:history.back()" class="px-6 py-2.5 bg-white text-gray-700 border border-gray-300 font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm text-sm">
                        Kembali
                    </a>
                    <button type="reset" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition shadow-sm text-sm">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm border border-transparent shadow-green-600/20 text-sm">
                        Simpan & Lanjut Cetak
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Preview Container (Blue Box) -->
        <div class="w-full lg:w-3/5 bg-white rounded-2xl shadow-sm border-2 border-blue-500 overflow-hidden flex flex-col p-4 md:p-6">
            <div class="mb-4 border-b border-gray-100 pb-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Live Preview Surat
                </h2>
                <!-- Zoom Controls -->
                <div class="flex items-center space-x-1 bg-gray-50 rounded-lg p-1 border border-gray-200">
                    <button type="button" id="zoom-out" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition" title="Zoom Out">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path></svg>
                    </button>
                    <input type="range" id="zoom-slider" min="0.5" max="2.5" step="0.1" value="1" class="w-16 md:w-24 mx-1 accent-blue-500 cursor-pointer">
                    <span id="zoom-level" class="text-xs font-semibold text-gray-600 w-10 text-center">100%</span>
                    <button type="button" id="zoom-in" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition" title="Zoom In">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    </button>
                </div>
            </div>
            
            <!-- Document Canvas Wrapper -->
            <div id="preview-container" class="flex-1 bg-gray-200 rounded-lg p-4 md:p-8 overflow-auto shadow-inner flex items-start cursor-grab select-none">
                <!-- A4 Paper Surface -->
                <div id="a4-paper" class="bg-white shadow-lg mx-auto text-gray-800 flex flex-col relative shrink-0 transition-all duration-200" style="width: 210mm; aspect-ratio: 210 / 297; container-type: inline-size; padding: 2.4% 11.9% 11.9% 11.9%;">
                
                <!-- Header/Kop Surat Placeholder -->
                <div class="flex items-center justify-between" style="border-bottom: 4px double #000; padding-bottom: 1.5%; margin-bottom: 4%;">
                    <div style="width: 20.2%;" class="flex-shrink-0 flex justify-center">
                        <img src="{{ asset('Kemenperin.png') }}" alt="Logo" class="w-[75%] h-auto object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span class="hidden font-bold text-gray-400">LOGO</span>
                    </div>
                    <div style="width: 79.8%;" class="text-center">
                        <h3 class="text-[#595959]" style="font-family: Arial, sans-serif; font-size: 2.35cqi; line-height: 1.15; margin-bottom: 0;">BADAN STANDARDISASI DAN KEBIJAKAN JASA INDUSTRI</h3>
                        <h2 class="text-[#595959] uppercase font-black" style="font-family: 'Arial Black', sans-serif; font-size: 1.93cqi; line-height: 1.15; margin-bottom: 0;">BALAI STANDARDISASI DAN PELAYANAN JASA INDUSTRI SURABAYA</h2>
                        <p class="text-[#595959]" style="font-family: Calibri, sans-serif; font-size: 1.51cqi; line-height: 1.0; margin-top: 0.5cqi; margin-bottom: 0;">Jl. Jagir Wonokromo No. 360 Surabaya 60244, Telp. (031) 99843670, Fax. (031) 8410480</p>
                        <p class="text-[#595959]" style="font-family: Calibri, sans-serif; font-size: 1.51cqi; line-height: 1.0; margin-top: 0;">E-mail: <a href="mailto:bspjisurabaya@kemenperin.go.id" class="text-[#0563C1] underline">bspjisurabaya@kemenperin.go.id</a>  Web: <a href="https://bspjisurabaya.kemenperin.go.id" class="text-[#0563C1] underline">bspjisurabaya.kemenperin.go.id</a></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between mb-[3cqi] gap-[3cqi]" style="font-size: 1.8cqi; line-height: 1.5;">
                    <div>
                        <table>
                            <tr>
                                <td class="pr-[1cqi] align-top">Nomor</td>
                                <td class="px-[1cqi] align-top">:</td>
                                <td class="align-top font-medium" id="preview-nomor">-</td>
                            </tr>
                            <tr>
                                <td class="pr-[1cqi] align-top">Lampiran</td>
                                <td class="px-[1cqi] align-top">:</td>
                                <td class="align-top font-medium">-</td>
                            </tr>
                            <tr>
                                <td class="pr-[1cqi] align-top">Hal</td>
                                <td class="px-[1cqi] align-top">:</td>
                                <td class="align-top font-medium" id="preview-perihal">{{ get_perihal_surat(request('jenis'), request('survailen', '1')) }}</td>
                            </tr>

                        </table>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="font-medium">Surabaya, <span id="preview-tanggal">-</span></p>
                    </div>
                </div>

                <div class="mb-[3cqi]" style="font-size: 1.8cqi; line-height: 1.5;">
                    <p>Yth. Pimpinan Perusahaan</p>
                    <p class="font-bold" id="preview-nama-perusahaan">[Nama Perusahaan]</p>
                    <p id="preview-alamat-perusahaan">[Alamat Perusahaan]</p>
                    <p>Di</p>
                    <p id="preview-kota-provinsi">[Kota / Provinsi]</p>
                </div>

                <div class="mb-[3cqi] leading-relaxed text-justify" style="font-size: 1.8cqi; line-height: 1.5;">
                    @php $jenis = request('jenis', 'pemberitahuan'); @endphp
                    
                    @if(str_contains($jenis, 'teguran'))
                    @php
                        $tgl_survailen_raw = request('tgl_survailen');
                        $tgl_survailen_fmt = $tgl_survailen_raw ? tanggal_indo($tgl_survailen_raw) : '[Tanggal Survailen]';
                    @endphp
                    <p class="indent-[4cqi]">Menindaklanjuti surat Pemberitahuan Surveilen tanggal {{ $tgl_survailen_fmt }} Nomor: <span id="preview-nomor-surat-rujukan">[Nomor Surat Survailen]</span> mengenai Survailen Sistem Sertifikasi Produk (SNI) <span class="ln-only hidden">milik <span id="preview-nama-perusahaan-ln-1" class="font-bold">[Nama Perusahaan Induk]</span> </span>yang hingga saat ini belum terlaksana, dengan ini kami beritahukan bahwa kegiatan survailen ke-{{ request('survailen', '1') }} di perusahaan saudara akan dilaksanakan pada:</p>
                    <table class="ml-[5cqi] mt-[1cqi] mb-[1cqi]">
                        <tr>
                            <td class="pr-[2cqi] align-top">Hari/Tanggal</td>
                            <td class="px-[1cqi] align-top">:</td>
                            <td class="align-top font-medium" id="preview-tanggal-pelaksanaan">[Tanggal Pelaksanaan]</td>
                        </tr>
                        <tr>
                            <td class="pr-[2cqi] align-top">Waktu</td>
                            <td class="px-[1cqi] align-top">:</td>
                            <td class="align-top font-medium" id="preview-waktu">09.00 s/d selesai</td>
                        </tr>
                    </table>
                    <p>Selanjutnya kami informasikan hal-hal sebagai berikut :</p>
                    <ol class="list-decimal pl-[5cqi] space-y-[0.5cqi]">
                        <li>Biaya yang diperlukan untuk pelaksanaan kegiatan tersebut sebesar Rp. <span id="preview-biaya">[Biaya]</span>, (<span id="preview-terbilang-biaya">Sebelas Juta Dua Ratus Lima Puluh Ribu Rupiah</span>). Pembayaran akan dilakukan melalui virtual account dan invoice akan kami terbitkan setelah kami menerima konfirmasi jadwal dari perusahaan.</li>
                        <li>Biaya tersebut belum termasuk biaya pengujian, transportasi dan akomodasi Auditor serta Petugas Pengambil Contoh.</li>
                        <li>Kegiatan Pengambilan sampel <span id="preview-komoditi">[Komoditi]</span> yang dilengkapi dengan Berita Acara Pengambilan Contoh dan Label Contoh Uji, selanjutnya untuk diuji di <span id="preview-laboratorium">Laboratorium BSPJI Surabaya</span>.</li>
                        <li>Biaya transportasi lokal dari rumah ke bandara/stasiun/terminal pulang pergi, mohon diganti sesuai yang dikeluarkan (adcost) dan langsung diserahkan kepada tim audit.</li>
                        <li>Untuk kelancaran pelaksanaan kegiatan tersebut kami mohon konfirmasi tertulis selambat-lambatnya 5 (lima) hari setelah menerima pemberitahuan ini.</li>
                    </ol>

                    @elseif(str_contains($jenis, 'pembekuan'))
                    @php
                        $tgl_survailen_raw = request('tgl_survailen');
                        $tgl_survailen_fmt = $tgl_survailen_raw ? tanggal_indo($tgl_survailen_raw) : '[Tanggal Surat Sebelumnya]';
                        $isPembekuan2 = $jenis === 'pembekuan2';
                    @endphp
                    <p class="indent-[4cqi]">Sehubungan dengan surat tanggal {{ $tgl_survailen_fmt }} No. <span id="preview-nomor-surat-rujukan-{{ $isPembekuan2 ? '3' : '2' }}">[Nomor Surat Sebelumnya]</span> terkait {{ $isPembekuan2 ? 'Pembekuan I' : 'teguran II' }} di perusahaan saudara dan hingga saat ini belum ditindaklanjuti. Dengan ini kami sampaikan hal-hal sebagai berikut:</p>
                    <ol class="list-decimal pl-[5cqi] space-y-[0.5cqi]">
                        <li>SPPT SNI milik <strong><span id="preview-nama-perusahaan-{{ $isPembekuan2 ? '3' : '2' }}">[Nama Perusahaan]</span></strong> dengan Nomor <strong><span id="preview-nomor-sppt-sni-{{ $isPembekuan2 ? '3' : '2' }}">[Nomor SPPT SNI]</span></strong> dengan komoditi <strong><span id="preview-komoditi-{{ $isPembekuan2 ? '3' : '2' }}">[Komoditi]</span></strong> merek <strong><span id="preview-merek-{{ $isPembekuan2 ? '3' : '2' }}">[Merek]</span></strong> untuk sementara dibekukan.</li>
                        <li>Selama proses pembekuan SPPT SNI, perusahaan dilarang menggunakan tanda/logo SNI di produk.</li>
                        <li>Apabila dalam jangka waktu 6 (enam) bulan sejak surat ini diterbitkan tidak ada tindak lanjut dari perusahaan saudara, maka akan kami terbitkan surat {{ $isPembekuan2 ? 'Pencabutan' : 'Pembekuan 2' }}.</li>
                    </ol>

                    @else
                    <p class="indent-[4cqi]">Berdasarkan Sertifikat Produk Penggunaan Tanda SNI <span class="ln-only hidden">milik <span id="preview-nama-perusahaan-ln-2" class="font-bold">[Nama Perusahaan Induk]</span> </span>yang diterbitkan LSPro BSPJI Surabaya tanggal <span id="preview-tanggal-sertifikat" class="dn-only">[Tanggal Sertifikat]</span><span id="preview-tanggal-sertifikat-ln-2" class="ln-only hidden font-bold">[Tanggal Sertifikat Induk]</span>, dengan ini kami beritahukan bahwa kegiatan survailen ke-{{ request('survailen', '1') }} di perusahaan saudara akan dilaksanakan pada:</p>
                    <table class="ml-[5cqi] mt-[1cqi] mb-[1cqi]">
                        <tr>
                            <td class="pr-[2cqi] align-top">Hari/Tanggal</td>
                            <td class="px-[1cqi] align-top">:</td>
                            <td class="align-top font-medium" id="preview-tanggal-pelaksanaan">[Tanggal Pelaksanaan]</td>
                        </tr>
                        <tr>
                            <td class="pr-[2cqi] align-top">Waktu</td>
                            <td class="px-[1cqi] align-top">:</td>
                            <td class="align-top font-medium" id="preview-waktu">09.00 s/d selesai</td>
                        </tr>
                    </table>
                    <p>Selanjutnya kami informasikan hal-hal sebagai berikut :</p>
                    <ol class="list-decimal pl-[5cqi] space-y-[0.5cqi]">
                        <li>Biaya yang diperlukan untuk pelaksanaan kegiatan tersebut sebesar Rp. <span id="preview-biaya">[Biaya]</span>, (<span id="preview-terbilang-biaya">Sebelas Juta Dua Ratus Lima Puluh Ribu Rupiah</span>). Invoice akan kami terbitkan setelah kami menerima konfirmasi jadwal dari perusahaan.</li>
                        <li>Biaya tersebut belum termasuk biaya pengujian, transportasi dan akomodasi Auditor serta Petugas Pengambil Contoh.</li>
                        <li>Kegiatan Pengambilan sampel <span id="preview-komoditi-3">[Komoditi]</span> yang dilengkapi dengan Berita Acara Pengambilan Contoh dan Label Contoh Uji, selanjutnya untuk diuji di <span id="preview-laboratorium-3">Laboratorium BSPJI Surabaya</span>.</li>
                        <li>Biaya transportasi lokal dari rumah ke bandara/stasiun/terminal pulang pergi, mohon diganti sesuai yang dikeluarkan (adcost) dan langsung diserahkan kepada tim audit.</li>
                        <li>Untuk kelancaran pelaksanaan kegiatan tersebut kami mohon konfirmasi tertulis selambat-lambatnya 5 (lima) hari setelah menerima pemberitahuan ini.</li>
                    </ol>
                    @endif

                    <p class="mt-[1cqi]">Demikian, atas perhatian dan kerjasama yang baik kami sampaikan terima kasih.</p>
                </div>

                <div class="mt-[4cqi] flex justify-end" style="font-size: 1.8cqi; line-height: 1.5;">
                    <div class="text-center w-[40cqi]">
                        <p>Ketua Tim Standardisasi dan Sertifikasi</p>
                        <p class="mt-[10cqi] font-bold" id="preview-ketua-tim">Indra Wahyu Diantoro</p>
                    </div>
                </div>
                
                <div class="mt-[4cqi]" style="font-size: 1.6cqi; line-height: 1.5;">
                    <p>Tembusan:</p>
                    <p>Kepala BSPJI Surabaya</p>
                </div>
                </div> <!-- End A4 Paper Surface -->
            </div> <!-- End Document Canvas Wrapper -->
        </div>
    </div>
</div>

<script src="{{ asset('js/persuratan.js') }}?v={{ filemtime(public_path('js/persuratan.js')) }}"></script>
@endsection
