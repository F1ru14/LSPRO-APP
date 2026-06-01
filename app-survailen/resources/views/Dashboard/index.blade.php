@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Operasional</h1>
            <p class="text-gray-500">Selamat datang di Sistem LSPRO BSPJI Surabaya</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-600">{{ now()->format('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 max-w-4xl">
        <!-- Card Survailen Aktif -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Survailen Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $surveilansTahunIni }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-green-600 font-semibold">Jadwal Tahun Ini</p>
        </div>

        <!-- Card Total Surat -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Surat Diterbitkan</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalSurat }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            @if(count($suratBreakdown) > 0)
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($suratBreakdown as $b)
                        <span class="px-2 py-1 bg-gray-50 border border-gray-100 rounded-md text-[11px] font-medium text-gray-600">
                            {{ $b->jenis_surat }}: <span class="font-bold text-purple-600">{{ $b->total }}</span>
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-purple-600 font-semibold">Total Dokumen Persuratan</p>
            @endif
        </div>
    </div>

    <!-- Section Pengingat Tindakan (Jadwal Survailen & Masa Berlaku) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h2 class="font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Pemberitahuan & Pengingat Survailen
            </h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-red-50 text-red-600 rounded-lg border border-red-100">
                {{ count($reminders) }} Tindakan Diperlukan
            </span>
        </div>
        <div class="p-6">
            @if(count($reminders) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Perusahaan / No. SNI</th>
                                <th class="px-6 py-3 font-semibold">Kategori</th>
                                <th class="px-6 py-3 font-semibold">Tindakan</th>
                                <th class="px-6 py-3 font-semibold">Tgl Terbit Sertifikat</th>
                                <th class="px-6 py-3 font-semibold">Status / Sisa Waktu</th>
                                <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($reminders as $rem)
                                @php
                                    $isOverdue = $rem['hari_sisa'] < 0;
                                    $remDays = (int) $rem['hari_sisa'];
                                    $absDays = abs($remDays);
                                    
                                    // Urgency badges
                                    if ($remDays === 0) {
                                        $statusClass = 'bg-red-50 text-red-700 border-red-100';
                                        $timeText = 'Batas waktu hari ini';
                                    } elseif ($isOverdue) {
                                        $statusClass = 'bg-red-50 text-red-700 border-red-100';
                                        $timeText = "Terlambat {$absDays} hari";
                                    } elseif ($remDays <= 30) {
                                        $statusClass = 'bg-orange-50 text-orange-700 border-orange-100';
                                        $timeText = "H-{$remDays} hari";
                                    } else {
                                        $statusClass = 'bg-gray-50 text-gray-600 border-gray-100';
                                        $timeText = "Sisa {$remDays} hari";
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $rem['sertifikasi']->perusahaan->nama_perusahaan ?? 'Perusahaan Tanpa Nama' }}</div>
                                        <div class="text-xs text-gray-400 font-normal mt-0.5">SNI: {{ $rem['sertifikasi']->no_sni ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold border bg-gray-50 text-gray-600 border-gray-200">
                                            {{ $rem['kategori'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        {{ $rem['label'] }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $rem['tgl_sertifikasi']->format('d M Y') }}
                                        <div class="text-[11px] text-gray-400">Batas Waktu: {{ $rem['tgl_jatuh_tempo']->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                            {{ $timeText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('persuratan.create') }}?jenis=pemberitahuan&sertifikasi_id={{ $rem['sertifikasi']->id_sertifikasi }}&survailen={{ $rem['periode'] }}" class="inline-flex items-center px-3.5 py-1.5 bg-[#0093ff] hover:bg-blue-600 text-white font-semibold rounded-lg text-xs transition">
                                            Buat Surat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6.5 h-6.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base mb-1">Semua Jadwal Aman</h3>
                    <p class="text-gray-500 text-sm">Tidak ada perusahaan yang memerlukan tindakan survailen saat ini.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 mb-8">
        <!-- Tabel Jadwal Survailen Terdekat -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h2 class="font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Survailen Terdekat
                </h2>
                <a href="{{ route('surveilans.index') }}" class="text-xs text-blue-600 font-semibold hover:underline bg-blue-50 px-3 py-1.5 rounded-lg transition hover:bg-blue-100">Semua Jadwal</a>
            </div>
            <div class="overflow-x-auto flex-1">
                @if(count($surveilansMendatang) > 0)
                <table class="w-full text-left">
                    <thead class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Perusahaan</th>
                            <th class="px-6 py-3 font-semibold text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($surveilansMendatang as $surv)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800">{{ $surv->sertifikasi->perusahaan->nama_perusahaan ?? 'Tanpa Nama' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Pengawasan ke-{{ $surv->periode }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 font-medium rounded-lg text-xs border border-amber-100">
                                    {{ \Carbon\Carbon::parse($surv->tgl_pelaksanaan)->format('d M Y') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm">Tidak ada jadwal survailen terdekat.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
