@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8">
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center space-y-4 md:space-y-0 mb-8">
        <div>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="#" class="hover:text-blue-600">Menu</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-800">Rekap Data Sertifikasi</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Rekap Data Sertifikasi</h1>
            <p class="text-gray-500">Pilih kolom-kolom yang ingin Anda sertakan dalam laporan ekspor (Excel).</p>
        </div>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <strong class="font-semibold">Oops!</strong> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <form action="{{ route('rekap.export') }}" method="POST" class="p-4 md:p-8">
            @csrf
            
            <!-- Parameter Tahun & Kategori -->
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-800 flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Laporan (Opsional)
                </h2>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-full md:w-1/2 flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-full sm:w-1/2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Awal</label>
                            <input type="number" name="start_year" min="2000" max="2100" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Contoh: 2024">
                        </div>
                        <div class="hidden sm:block text-gray-400 mt-6 font-bold">-</div>
                        <div class="w-full sm:w-1/2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Akhir</label>
                            <input type="number" name="end_year" min="2000" max="2100" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm" placeholder="Contoh: 2026">
                        </div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Sertifikasi</label>
                        <select name="id_kategori" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm bg-white">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">*Biarkan pilihan di atas kosong/default jika ingin mengekspor seluruh data.</p>
            </div>

            <div class="mb-6 pb-4 border-t border-gray-100 pt-8 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Daftar Kolom (Field) Sertifikasi
                </h2>
                <div class="flex items-center space-x-4">
                    <button type="button" onclick="selectAll()" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">Pilih Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" onclick="deselectAll()" class="text-sm font-semibold text-gray-600 hover:text-gray-800 transition">Batal Semua</button>
                </div>
            </div>

            <!-- Checkbox Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-8 mb-8">
                @foreach ($availableColumns as $key => $label)
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="columns[]" value="{{ $key }}" class="peer w-5 h-5 rounded border-2 border-gray-300 text-blue-600 focus:ring-blue-500 transition-all checked:border-blue-600 cursor-pointer" checked>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 border-t border-gray-100 pt-6">
                <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm border border-transparent shadow-green-600/20 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Ekspor ke Excel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function selectAll() {
        document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deselectAll() {
        document.querySelectorAll('input[name="columns[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
</script>
@endsection
