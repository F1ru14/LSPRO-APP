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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sertifikat Aktif</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalSertifikatAktif }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-blue-600 font-semibold">Telah Terbit SNI</p>
        </div>


        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sertifikasi Proses</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $sertifikasiProses }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-amber-600 font-semibold">Menunggu Terbit SNI</p>
        </div>

        <!-- Card 4 -->
        <!-- <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Surat</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalSurat }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-purple-600 font-semibold">Telah Diterbitkan</p>
        </div> -->
    </div>

    <div class="grid grid-cols-1 gap-8 mb-8">
        <!-- Tabel Sertifikasi Proses -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h2 class="font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sertifikasi Sedang Proses
                </h2>
                <a href="{{ route('sertifikasi.index') }}" class="text-xs text-amber-600 font-semibold hover:underline bg-amber-50 px-3 py-1.5 rounded-lg transition hover:bg-amber-100">Cek Semua</a>
            </div>
            <div class="overflow-x-auto flex-1">
                @if(count($sertifikasiTertunda) > 0)
                <table class="w-full text-left">
                    <thead class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Perusahaan</th>
                            <th class="px-6 py-3 font-semibold">Komoditi</th>
                            <th class="px-6 py-3 font-semibold">Tgl Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach($sertifikasiTertunda as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $item->perusahaan->nama_perusahaan ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600 truncate max-w-[150px]">{{ $item->perusahaan->komoditi ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($item->tgl_permohonan ?? $item->created_at)->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-8 text-center flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm">Semua sertifikasi telah memiliki No SNI.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
