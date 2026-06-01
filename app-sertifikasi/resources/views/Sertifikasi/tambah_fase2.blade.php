<!-- @extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 max-w-6xl mx-auto">
    <div class="mb-4 sm:mb-6 flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
        <a href="{{ route('sertifikasi.index') }}" class="hover:text-blue-600">Sertifikasi</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Tahap 2 - Lengkapi Data</span>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-blue-600 p-4 sm:p-6 text-white flex justify-between items-center">
            <div>
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold">Form Input Data Sertifikasi - Tahap 2</h2>
                <p class="text-blue-100 text-xs sm:text-sm mt-1">Lengkapi data sertifikasi untuk No. Referensi: <span class="font-bold">{{ $sertifikasi->no_referensi }}</span> - {{ $sertifikasi->nama_perusahaan }}</p>
            </div>
            <div class="hidden sm:block text-right">
                <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold backdrop-blur-sm shadow-sm">{{ $sertifikasi->nama_perusahaan }}</span>
            </div>
        </div>

        <form action="{{ route('sertifikasi.storeFase2', $sertifikasi->id_sertifikasi) }}" method="POST" class="p-4 sm:p-6 md:p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
                
                {{-- Bagian Kiri --}}
                <div class="space-y-4 sm:space-y-5">
                    <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mb-4">Informasi Dokumen & Kontrak</h3>
                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nomor SNI</label>
                        <input type="text" name="no_sni" value="{{ old('no_sni', $sertifikasi->no_sni) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tanggal Kontrak</label>
                        <input type="date" name="tgl_kontrak" value="{{ old('tgl_kontrak', $sertifikasi->tgl_kontrak) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tanggal Sertifikasi</label>
                        <input type="date" name="tgl_sertifikasi" value="{{ old('tgl_sertifikasi', $sertifikasi->tgl_sertifikasi ? \Carbon\Carbon::parse($sertifikasi->tgl_sertifikasi)->format('Y-m-d') : '') }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Lama Sertifikasi</label>
                        <input type="text" name="lama_sertifikasi" value="{{ old('lama_sertifikasi', $sertifikasi->lama_sertifikasi) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base" placeholder="Contoh: 4 Tahun">
                    </div>
                </div>

                {{-- Bagian Kanan --}}
                <div class="space-y-4 sm:space-y-5">
                    <h3 class="text-sm font-bold text-gray-800 border-b pb-2 mb-4">Informasi Audit & Penanggung Jawab</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Tgl Audit Kecukupan</label>
                            <input type="date" name="tgl_audit_kecukupan" value="{{ old('tgl_audit_kecukupan', $sertifikasi->tgl_audit_kecukupan) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                        </div>
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
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Staff Auditor Kecukupan</label>
                            <div class="react-auditor-select-container"
                                data-name="auditor_kecukupan"
                                data-value="{{ old('auditor_kecukupan', $sertifikasi->auditor_kecukupan) }}"
                                data-options="{{ json_encode($auditors) }}"
                                data-store-url="/api/auditor/store"
                                data-placeholder="Pilih atau ketik nama auditor...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Nama Lead Auditor</label>
                            <div class="react-auditor-select-container"
                                data-name="nama_auditor"
                                data-value="{{ old('nama_auditor', $sertifikasi->nama_auditor) }}"
                                data-options="{{ json_encode($auditors) }}"
                                data-store-url="/api/auditor/store"
                                data-placeholder="Pilih atau ketik nama auditor...">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Petugas Pengambil Contoh</label>
                            <input type="text" name="nama_petugas" value="{{ old('nama_petugas', $sertifikasi->nama_petugas) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Lab Penguji</label>
                            <input type="text" name="nama_lab" value="{{ old('nama_lab', $sertifikasi->nama_lab) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1 sm:mb-2">Komite Teknis</label>
                        <input type="text" name="nama_teknis" value="{{ old('nama_teknis', $sertifikasi->nama_teknis) }}" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50 transition text-xs sm:text-base">
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
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

            <div class="mt-8 sm:mt-10 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-4 border-t pt-6 sm:pt-8 border-gray-100">
                <a href="{{ route('sertifikasi.index') }}" class="px-4 sm:px-8 py-2 sm:py-3 text-xs sm:text-base text-center text-gray-500 font-semibold hover:text-gray-700 transition rounded-lg sm:rounded-xl">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white px-4 sm:px-10 py-2 sm:py-3 rounded-lg sm:rounded-xl font-bold text-xs sm:text-base shadow-lg shadow-blue-200 hover:bg-blue-700 transition transform active:scale-95 text-center">
                    Simpan Data Tahap 2
                </button>
            </div>
        </form>
    </div>
</div>
@endsection -->
