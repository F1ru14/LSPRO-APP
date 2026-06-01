@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 max-w-6xl mx-auto">
    <div class="mb-4 sm:mb-6 flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Tambah Sertifikasi</span>
    </div>

    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#0093ff] p-4 sm:p-6 text-white">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold">Form Input Data Sertifikasi - Tahap 1</h2>
            <p class="text-blue-100 text-xs sm:text-sm mt-1">Lengkapi semua field awal untuk menyimpan data perusahaan</p>
            <div class="mt-3 inline-flex items-center gap-2 bg-white/15 rounded-xl px-3 py-1.5 text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Data konsumen akan otomatis terdaftar saat form ini disimpan
            </div>
        </div>

        <form action="{{ route('sertifikasi.store') }}" method="POST" class="p-4 sm:p-6 md:p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                
                <div class="space-y-4 sm:space-y-5">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nomor Referensi <span class="text-red-500">*</span></label>
                        <input type="text" name="no_referensi" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" placeholder="Contoh: REF-2026-001" required>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" placeholder="PT. Sukses Bersama" required>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Kantor <span class="text-red-500">*</span></label>
                        <textarea name="alamat_kantor" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required></textarea>
                    </div>

                    <div class="react-wilayah-select-container"
                        data-provinces="{{ json_encode($provinsis) }}"
                        data-prov-id="{{ old('id_provinsi', '') }}"
                        data-prov-name="{{ old('id_provinsi', '') }}"
                        data-kota-id="{{ old('id_kota', '') }}"
                        data-kota-name="{{ old('id_kota', '') }}">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">No. Telp Kantor</label>
                        <input type="text" name="telp_kantor" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Fax Kantor</label>
                        <input type="text" name="fax_kantor" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Kontak Person</label>
                        <input type="text" name="kontak_person" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Telp. Kontak Person</label>
                        <input type="text" name="telp_cp" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="id_kategori" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-5">
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nama Importir</label>
                        <input type="text" name="nama_importir" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Importir</label>
                        <textarea name="alamat_importir" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">No. Telp Importir</label>
                        <input type="text" name="telp_importir" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Fax Importir</label>
                        <input type="text" name="fax_importir" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Komoditi / Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="komoditi_produk" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Merk</label>
                        <input type="text" name="merk" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Type / Jenis Produk</label>
                        <input type="text" name="type_jenis_produk" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Pabrik</label>
                        <textarea name="alamat_pabrik" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">No. Telp Pabrik</label>
                        <input type="text" name="telp_pabrik" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Fax Pabrik</label>
                        <input type="text" name="fax_pabrik" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                    </div>

                    <div class="p-3 sm:p-4 bg-blue-50 rounded-lg sm:rounded-2xl border border-blue-100">
                        <label class="block text-xs sm:text-sm font-bold text-blue-700 mb-1 sm:mb-2">Tanggal Permohonan <span class="text-red-500">*</span></label>
                        <input type="date" name="tgl_permohonan" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-400 outline-none bg-white transition text-xs sm:text-base" required>
                    </div>
                </div>
            </div>

            <div class="mt-8 sm:mt-10 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-4 border-t pt-6 sm:pt-8">
                <a href="{{ route('dashboard') }}" class="px-4 sm:px-8 py-2 sm:py-3 text-xs sm:text-base text-center text-gray-500 font-semibold hover:text-gray-700 transition rounded-lg sm:rounded-xl">Kembali</a>
                <button type="submit" class="bg-[#0093ff] text-white px-4 sm:px-10 py-2 sm:py-3 rounded-lg sm:rounded-xl font-bold text-xs sm:text-base shadow-lg shadow-blue-200 hover:bg-blue-600 transition transform active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
