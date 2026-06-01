@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 max-w-6xl mx-auto">
    <div class="mb-4 sm:mb-6 flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
        <a href="{{ route('sertifikasi.index') }}" class="hover:text-blue-600">Sertifikasi</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Edit Sertifikasi</span>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex justify-between items-center transition-all duration-500">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#0093ff] p-4 sm:p-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold">Edit Data Sertifikasi</h2>
                <p class="text-blue-100 text-xs sm:text-sm mt-1">Perbarui informasi perusahaan (Tahap 1) dan detail sertifikasi (Tahap 2)</p>
            </div>
            <div class="hidden sm:block text-right">
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold backdrop-blur-sm shadow-sm">{{ $sertifikasi->no_referensi }}</span>
            </div>
        </div>

        <form id="editForm" action="{{ route('sertifikasi.update', $sertifikasi->id_sertifikasi) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div x-data="{ activeTab: 'fase1' }">
                <!-- Tabs Navigation -->
                <div class="flex border-b border-gray-200 px-4 sm:px-6 md:px-8 pt-4 bg-gray-50/50">
                    <button type="button" id="btn-tab-fase1" @click="activeTab = 'fase1'" :class="{ 'border-[#0093ff] text-[#0093ff] bg-white': activeTab === 'fase1', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50': activeTab !== 'fase1' }" class="pb-3 px-6 pt-3 border-b-2 font-bold text-sm sm:text-base transition rounded-t-lg">
                        Tahap 1: Data Perusahaan
                    </button>
                    <button type="button" id="btn-tab-fase2" @click="activeTab = 'fase2'" :class="{ 'border-[#0093ff] text-[#0093ff] bg-white': activeTab === 'fase2', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50': activeTab !== 'fase2' }" class="pb-3 px-6 pt-3 border-b-2 font-bold text-sm sm:text-base transition rounded-t-lg ml-2">
                        Tahap 2: Detail Audit & Sertifikasi
                    </button>
                </div>

                <div class="p-4 sm:p-6 md:p-8">
                    <!-- Fase 1 Tab Content -->
                    <div x-show="activeTab === 'fase1'" x-transition.opacity>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                            
                            <div class="space-y-4 sm:space-y-5">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nomor Referensi <span class="text-red-500">*</span></label>
                                    <input type="text" name="no_referensi" value="{{ old('no_referensi', $sertifikasi->no_referensi) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $sertifikasi->perusahaan->nama_perusahaan ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Kantor <span class="text-red-500">*</span></label>
                                    <textarea name="alamat_kantor" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>{{ old('alamat_kantor', $sertifikasi->perusahaan->alamat_kantor ?? '') }}</textarea>
                                </div>

                                <div class="react-wilayah-select-container"
                                    data-provinces="{{ json_encode($provinsis) }}"
                                    data-prov-id="{{ old('id_provinsi', $sertifikasi->perusahaan->kota->id_provinsi ?? '') }}"
                                    data-prov-name="{{ old('id_provinsi') ? '' : ($sertifikasi->perusahaan->kota->provinsi->provinsi ?? '') }}"
                                    data-kota-id="{{ old('id_kota', $sertifikasi->perusahaan->id_kota ?? '') }}"
                                    data-kota-name="{{ old('id_kota') ? '' : ($sertifikasi->perusahaan->kota->kota ?? '') }}">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">No. Telp Kantor</label>
                                    <input type="text" name="telp_kantor" value="{{ old('telp_kantor', $sertifikasi->perusahaan->telp_kantor ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Fax Kantor</label>
                                    <input type="text" name="fax_kantor" value="{{ old('fax_kantor', $sertifikasi->perusahaan->fax_kantor ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $sertifikasi->perusahaan->email ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                                </div>
                                
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Kontak Person</label>
                                    <input type="text" name="kontak_person" value="{{ old('kontak_person', $sertifikasi->perusahaan->contact_person ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Telp. Kontak Person</label>
                                    <input type="text" name="telp_cp" value="{{ old('telp_cp', $sertifikasi->perusahaan->telp_cp ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" inputmode="tel" pattern="[0-9\+\-\(\)\s]+" title="Boleh berisi angka, spasi, dan simbol (+, -, dll)">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Kategori <span class="text-red-500">*</span></label>
                                    <select name="id_kategori" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori', $sertifikasi->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="space-y-4 sm:space-y-5">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nama Importir</label>
                                    <input type="text" name="nama_importir" value="{{ old('nama_importir', $sertifikasi->perusahaan->nama_importir ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Importir</label>
                                    <textarea name="alamat_importir" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">{{ old('alamat_importir', $sertifikasi->perusahaan->alamat_importir ?? '') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">No. Telp Importir</label>
                                    <input type="text" name="telp_importir" value="{{ old('telp_importir', $sertifikasi->perusahaan->telp_importir ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Fax Importir</label>
                                    <input type="text" name="fax_importir" value="{{ old('fax_importir', $sertifikasi->perusahaan->fax_importir ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Komoditi / Produk <span class="text-red-500">*</span></label>
                                    <input type="text" name="komoditi_produk" value="{{ old('komoditi_produk', $sertifikasi->perusahaan->komoditi ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" required>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Merk</label>
                                    <input type="text" name="merk" value="{{ old('merk', $sertifikasi->perusahaan->merek ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Type / Jenis Produk</label>
                                    <input type="text" name="type_jenis_produk" value="{{ old('type_jenis_produk', $sertifikasi->perusahaan->tipe_produk ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Alamat Pabrik</label>
                                    <textarea name="alamat_pabrik" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">{{ old('alamat_pabrik', $sertifikasi->perusahaan->alamat_pabrik ?? '') }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">No. Telp Pabrik</label>
                                        <input type="text" name="telp_pabrik" value="{{ old('telp_pabrik', $sertifikasi->perusahaan->telp_pabrik ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                    </div>

                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Fax Pabrik</label>
                                        <input type="text" name="fax_pabrik" value="{{ old('fax_pabrik', $sertifikasi->perusahaan->fax_pabrik ?? '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                    </div>
                                </div>

                                <div class="p-3 sm:p-4 bg-blue-50 rounded-lg sm:rounded-2xl border border-blue-100">
                                    <label class="block text-xs sm:text-sm font-bold text-blue-700 mb-1 sm:mb-2">Tanggal Permohonan <span class="text-red-500">*</span></label>
                                    <input type="date" name="tgl_permohonan" value="{{ old('tgl_permohonan', isset($sertifikasi->tgl_permohonan) ? \Carbon\Carbon::parse($sertifikasi->tgl_permohonan)->format('Y-m-d') : '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-400 outline-none bg-white transition text-xs sm:text-base" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fase 2 Tab Content -->
                    <div x-show="activeTab === 'fase2'" x-cloak style="display: none;" x-transition.opacity>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                            
                            <div class="space-y-4 sm:space-y-5">
                                <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mb-4">Informasi Dokumen & Kontrak</h3>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nomor Sertifikat</label>
                                    <input type="text" name="no_sni" value="{{ old('no_sni', $sertifikasi->no_sni) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tanggal Kontrak</label>
                                    <input type="date" name="tgl_kontrak" value="{{ old('tgl_kontrak', $sertifikasi->tgl_kontrak ? \Carbon\Carbon::parse($sertifikasi->tgl_kontrak)->format('Y-m-d') : '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tanggal terbit Sertifikat</label>
                                    <input type="date" name="tgl_sertifikasi" value="{{ old('tgl_sertifikasi', $sertifikasi->tgl_sertifikasi ? \Carbon\Carbon::parse($sertifikasi->tgl_sertifikasi)->format('Y-m-d') : '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tanggal pemberitahuan Verifikasi</label>
                                    <input type="date" name="tgl_pb_verifikasi" value="{{ old('tgl_pb_verifikasi', $sertifikasi->tgl_pemberitahuan_verifikasi ? \Carbon\Carbon::parse($sertifikasi->tgl_pemberitahuan_verifikasi)->format('Y-m-d') : '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">
                                        Lama Proses Sertifikasi
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="lama_sertifikasi" name="lama_sertifikasi"
                                            value="{{ old('lama_sertifikasi', $sertifikasi->lama_sertifikasi) }}"
                                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-blue-200 focus:ring-2 focus:ring-blue-400 outline-none bg-blue-50 transition text-xs sm:text-base font-semibold text-blue-800 cursor-not-allowed"
                                            placeholder="Akan terisi otomatis..."
                                            readonly>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-blue-400">hari kerja</span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Dihitung dari: Tgl Kontrak → Tgl Pb. Verifikasi + Tgl Rapat Teknis → Tgl Sertifikasi</p>
                                </div>
                            </div>  

                            <div class="space-y-4 sm:space-y-5">
                                <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mb-4">Informasi Audit & Penanggung Jawab</h3>
                                
                                
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tgl Audit Kecukupan</label>
                                    <input type="date" name="tgl_audit_kecukupan" value="{{ old('tgl_audit_kecukupan', $sertifikasi->tgl_audit_kecukupan) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>
                                    
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tgl Mulai Audit Lapangan</label>
                                        <input type="date" name="tgl_mulai_audit_lapangan" value="{{ old('tgl_mulai_audit_lapangan', $sertifikasi->tgl_mulai_audit_lapangan) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tgl Selesai Audit Lapangan</label>
                                        <input type="date" name="tgl_selesai_audit_lapangan" value="{{ old('tgl_selesai_audit_lapangan', $sertifikasi->tgl_selesai_audit_lapangan) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tanggal Rapat Teknis</label>
                                    <input type="date" name="tgl_rapat_teknis" value="{{ old('tgl_rapat_teknis', $sertifikasi->tgl_rapat_teknis) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                                </div>
                                
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nama Lead Auditor</label>
                                    <div class="react-auditor-select-container"
                                        data-name="nama_auditor"
                                        data-value="{{ old('nama_auditor', $sertifikasi->nama_auditor) }}"
                                        data-options="{{ json_encode($auditors) }}"
                                        data-store-url="/api/auditor/store"
                                        data-placeholder="Pilih atau ketik nama auditor..."
                                        data-is-multi="true">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Staff Auditor Kecukupan</label>
                                    <div class="react-auditor-select-container"
                                        data-name="auditor_kecukupan"
                                        data-value="{{ old('auditor_kecukupan', $sertifikasi->auditor_kecukupan) }}"
                                        data-options="{{ json_encode($auditors) }}"
                                        data-store-url="/api/auditor/store"
                                        data-placeholder="Pilih atau ketik nama auditor..."
                                        data-is-multi="true">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Petugas Pengambil Contoh</label>
                                    <div class="react-auditor-select-container"
                                        data-name="nama_petugas"
                                        data-value="{{ old('nama_petugas', $sertifikasi->nama_petugas) }}"
                                        data-options="{{ json_encode($petugas) }}"
                                        data-store-url="/api/petugas/store"
                                        data-placeholder="Pilih atau ketik nama petugas..."
                                        data-is-multi="true">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Lab Penguji</label>
                                    <div class="react-auditor-select-container"
                                        data-name="nama_lab"
                                        data-value="{{ old('nama_lab', $sertifikasi->nama_lab) }}"
                                        data-options="{{ json_encode($labs) }}"
                                        data-store-url="/api/lab/store"
                                        data-placeholder="Pilih atau ketik nama lab..."
                                        data-is-multi="false">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Komite Teknis</label>
                                    <div class="react-auditor-select-container"
                                        data-name="nama_teknis"
                                        data-value="{{ old('nama_teknis', $sertifikasi->nama_teknis) }}"
                                        data-options="{{ json_encode($teknis) }}"
                                        data-store-url="/api/teknis/store"
                                        data-placeholder="Pilih atau ketik nama komite teknis..."
                                        data-is-multi="true">
                                    </div>
                                </div>
                            </div>

                            <!-- Baris Khusus Keterangan & Status (Lebar penuh) -->
                            <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8 border-t border-gray-100 pt-6 mt-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1 sm:mb-2">Status Permohonan</label>
                                    <select name="status_permohonan" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-white transition text-xs sm:text-base font-medium">
                                        <option value="Belum Terbit" {{ old('status_permohonan', $sertifikasi->status_permohonan) == 'Belum Terbit' ? 'selected' : '' }}>Belum Terbit</option>
                                        <option value="Terbit" {{ old('status_permohonan', $sertifikasi->status_permohonan) == 'Terbit' ? 'selected' : '' }}>Terbit</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1 sm:mb-2">Keterangan Tambahan</label>
                                    <textarea name="keterangan" rows="2" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">{{ old('keterangan', $sertifikasi->keterangan) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 sm:px-6 md:px-8 pb-4 sm:pb-6 md:pb-8 flex flex-col-reverse sm:flex-row justify-between items-center gap-4 border-t pt-6 border-gray-100 mt-4">
                    <div class="text-sm text-gray-500">
                        Pastikan data di kedua tab sudah benar sebelum Anda menyimpan.
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-4 w-full sm:w-auto">
                        <a href="{{ route('sertifikasi.index') }}" class="px-4 sm:px-8 py-2 sm:py-3 text-xs sm:text-base text-center text-gray-500 font-semibold hover:text-gray-700 transition rounded-lg sm:rounded-xl bg-gray-100 hover:bg-gray-200 w-full sm:w-auto">Kembali</a>
                        <button type="submit" class="bg-[#0093ff] text-white px-4 sm:px-10 py-2 sm:py-3 rounded-lg sm:rounded-xl font-bold text-xs sm:text-base shadow-lg shadow-blue-200 hover:bg-blue-600 transition transform active:scale-95 text-center w-full sm:w-auto">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Menghitung jumlah hari kerja (NETWORKDAYS) antara dua tanggal,
     * mengecualikan Sabtu & Minggu.
     * @param {Date} start
     * @param {Date} end
     * @returns {number}
     */
    function networkDays(start, end) {
        if (!start || !end || isNaN(start) || isNaN(end)) return 0;
        if (end < start) return 0;

        let count = 0;
        let current = new Date(start);
        // Normalisasi ke tengah hari agar tidak ada masalah DST
        current.setHours(12, 0, 0, 0);
        const endDate = new Date(end);
        endDate.setHours(12, 0, 0, 0);

        while (current <= endDate) {
            const day = current.getDay(); // 0=Minggu, 6=Sabtu
            if (day !== 0 && day !== 6) {
                count++;
            }
            current.setDate(current.getDate() + 1);
        }
        return count;
    }

    function hitungLamaSertifikasi() {
        const tglKontrak      = document.querySelector('input[name="tgl_kontrak"]');
        const tglPbVerifikasi = document.querySelector('input[name="tgl_pb_verifikasi"]');
        const tglRapatTeknis  = document.querySelector('input[name="tgl_rapat_teknis"]');
        const tglSertifikasi  = document.querySelector('input[name="tgl_sertifikasi"]');
        const lamaField       = document.getElementById('lama_sertifikasi');

        if (!lamaField) return;

        const d1 = tglKontrak      && tglKontrak.value      ? new Date(tglKontrak.value)      : null;
        const d2 = tglPbVerifikasi && tglPbVerifikasi.value ? new Date(tglPbVerifikasi.value) : null;
        const d3 = tglRapatTeknis  && tglRapatTeknis.value  ? new Date(tglRapatTeknis.value)  : null;
        const d4 = tglSertifikasi  && tglSertifikasi.value  ? new Date(tglSertifikasi.value)  : null;

        const bagian1 = (d1 && d2) ? networkDays(d1, d2) : 0;
        const bagian2 = (d3 && d4) ? networkDays(d3, d4) : 0;
        const total   = bagian1 + bagian2;

        if (total > 0) {
            lamaField.value = total + ' hari kerja';
            lamaField.classList.add('text-blue-800');
        } else {
            lamaField.value = '';
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isFormDirty = false;
        const form = document.getElementById('editForm');

        // Hitung lama sertifikasi otomatis saat tanggal berubah
        const tanggalTriggers = [
            'tgl_kontrak',
            'tgl_pb_verifikasi',
            'tgl_rapat_teknis',
            'tgl_sertifikasi'
        ];
        tanggalTriggers.forEach(function(name) {
            const el = document.querySelector('input[name="' + name + '"]');
            if (el) el.addEventListener('change', hitungLamaSertifikasi);
        });
        // Hitung sekali saat halaman dimuat (jika data sudah ada)
        hitungLamaSertifikasi();

        if (form) {
            // Deteksi perubahan input
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', () => { isFormDirty = true; });
                input.addEventListener('input', () => { isFormDirty = true; });
            });

            // Abaikan ketika tombol submit ditekan
            form.addEventListener('submit', () => {
                isFormDirty = false;
            });

            // Handle invalid fields on hidden tabs
            form.addEventListener('invalid', function(e) {
                let target = e.target;
                let fase1Tab = document.querySelector('[x-show="activeTab === \'fase1\'"]');
                let fase2Tab = document.querySelector('[x-show="activeTab === \'fase2\'"]');
                
                if (fase1Tab && fase1Tab.contains(target)) {
                    document.getElementById('btn-tab-fase1').click();
                } else if (fase2Tab && fase2Tab.contains(target)) {
                    document.getElementById('btn-tab-fase2').click();
                }
            }, true);
        }

        // Tangkap klik pada link (seperti tombol "Kembali" atau breadcrumb)
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                if (isFormDirty) {
                    const confirmLeave = confirm('Apakah Anda yakin? Data belum tersimpan. Jika Anda melanjutkan, perubahan Anda akan hilang.');
                    if (!confirmLeave) {
                        e.preventDefault();
                    }
                }
            });
        });

        // Peringatan default browser saat reload atau close tab
        window.addEventListener('beforeunload', function (e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = 'Apakah Anda yakin? Data belum tersimpan.';
            }
        });
    });
</script>



@endsection
