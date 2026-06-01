@extends('layouts.app')

@section('content')
<div class="p-4 md:p-8">
    <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center space-y-4 md:space-y-0 mb-8">
        <div>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <a href="{{ route('surveilans.index') }}" class="hover:text-blue-600">Data Konsumen</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-gray-800">Form Survailen</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Form Survailen</h1>
            <p class="text-gray-500">Masukkan data survailen konsumen untuk pengawasan berkala.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Kembali button moved to bottom -->
        </div>
    </div>

    <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <form action="{{ route('surveilans.store') }}" method="POST" class="p-4 md:p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Data Sertifikasi Awal -->
                <div class="md:col-span-2 mb-2 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Data Survailen
                    </h2>
                </div>

                <div class="hidden">
                    <input type="hidden" id="no_referensi_input" name="no_referensi" value="{{ request('no_referensi') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perushaan</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Komoditi</label>
                    <input type="text" id="komoditi" name="komoditi" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No Sertifikat SNI</label>
                    <input type="text" id="no_sertifikat_sni" name="no_sertifikat_sni" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Sertifikat SNI</label>
                    <input type="text" id="tanggal_sertifikat_sni" name="tanggal_sertifikat_sni" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Auditor Sertifikasi</label>
                    <input type="text" id="auditor_sertifikasi" name="auditor_sertifikasi" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">PPC Sertifikasi</label>
                    <input type="text" id="ppc_sertifikasi" name="ppc_sertifikasi" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Sertifikasi</label>
                    <textarea id="status_sertifikasi" name="status_sertifikasi" rows="2" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Laboratorium Pengujian</label>
                    <input type="text" id="laboratorium_pengujian" name="laboratorium_pengujian" readonly class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-600 focus:outline-none transition text-sm" placeholder="Otomatis terisi">
                </div>
            </div>

            <!-- Dropdown Pemilihan Pengawasan Berkala -->
            <div class="mt-8 pt-8 border-t border-gray-100">
                <div class="md:w-1/2">
                    <label class="block text-xl font-bold text-gray-800 mb-2">Data Pengawasan Berkala</label>
                    <p class="text-sm text-gray-500 mb-4">Silakan pilih tahapan pengawasan berkala yang ingin Anda isi/lihat.</p>
                    <div class="relative mb-4">
                        <select id="pengawasan_selector" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition cursor-pointer appearance-none bg-white">
                            <option value="1">Pengawasan Berkala 1</option>
                            <option value="2">Pengawasan Berkala 2</option>
                            <option value="3">Pengawasan Berkala 3</option>
                            <option value="4" class="hidden" id="opt_pengawasan_4">Pengawasan Berkala 4</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($pengawasans as $i => $style)
            <!-- Pengawasan Berkala {{ $i }} -->
            <div id="pengawasan_{{ $i }}" class="pengawasan-section {{ $i != 1 ? 'hidden' : '' }} mt-6 mb-6 {{ $style['bg'] }} p-6 rounded-2xl border {{ $style['border'] }} transition-all duration-300">
                <h3 class="text-lg font-bold {{ $style['text'] }} mb-6 flex items-center border-b {{ $style['border_b'] }} pb-3">
                    <span class="w-7 h-7 rounded-full {{ $style['badge_bg'] }} text-white flex items-center justify-center text-sm mr-3 shadow-sm">{{ $i }}</span>
                    Detail Pengawasan Berkala {{ $i }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Pemberitahuan Pengawasan Berkala {{ $i }}</label>
                        <input type="date" name="tgl_surat_pengawasan_{{ $i }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 {{ $style['ring'] }} {{ $style['focus_border'] }} transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pelaksanaan Pengawasan Berkala {{ $i }}</label>
                        <input type="date" name="tgl_pelaksanaan_pengawasan_{{ $i }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 {{ $style['ring'] }} {{ $style['focus_border'] }} transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Teguran 1</label>
                        <input type="date" name="tgl_surat_teguran_1_{{ $i }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 {{ $style['ring'] }} {{ $style['focus_border'] }} transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Teguran 2</label>
                        <input type="date" name="tgl_surat_teguran_2_{{ $i }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 {{ $style['ring'] }} {{ $style['focus_border'] }} transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Pembekuan 1</label>
                        <input type="date" name="tgl_pembekuan_1_{{ $i }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 {{ $style['ring'] }} {{ $style['focus_border'] }} transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Surat Pembekuan 2</label>
                        <input type="date" name="tgl_pembekuan_2_{{ $i }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 {{ $style['ring'] }} {{ $style['focus_border'] }} transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Auditor Untuk Pengawasan Berkala {{ $i }}</label>
                        <div class="react-auditor-select-container"
                            data-name="auditor_pelaksanaan_{{ $i }}"
                            data-options="{{ json_encode($auditors) }}"
                            data-placeholder="Pilih atau ketik Auditor..."
                            data-is-multi="true">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PPC Untuk Pengawasan Berkala {{ $i }}</label>
                        <div class="react-auditor-select-container"
                            data-name="ppc_pelaksanaan_{{ $i }}"
                            data-options="{{ json_encode($petugas) }}"
                            data-placeholder="Pilih atau ketik PPC..."
                            data-is-multi="true">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Laboratorium Pengujian</label>
                        <div class="react-auditor-select-container"
                            data-name="laboratorium_pengujian_{{ $i }}"
                            data-options="{{ json_encode($labs) }}"
                            data-placeholder="Pilih atau ketik Laboratorium..."
                            data-is-multi="false">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <div class="relative dropdown-container">
                        <button type="button" class="dropdown-trigger w-full sm:w-auto px-6 py-2.5 bg-green-50 text-green-700 font-semibold rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm border border-transparent shadow-green-600/10 flex items-center justify-center focus:outline-none">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Surat
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="dropdown-menu absolute bottom-full right-0 mb-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl hidden z-50 flex flex-col overflow-hidden origin-bottom-right">
                            <a href="{{ route('persuratan.create') }}?jenis=pemberitahuan" onclick="submitCetakSurat(event, this, {{ $i }})" class="px-4 py-3 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition">Surat Pemberitahuan</a>
                            <a href="{{ route('persuratan.create') }}?jenis=teguran" onclick="submitCetakSurat(event, this, {{ $i }})" class="px-4 py-3 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition border-t border-gray-50">Surat Teguran</a>
                            <a href="{{ route('persuratan.create') }}?jenis=pembekuan" onclick="submitCetakSurat(event, this, {{ $i }})" class="px-4 py-3 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition border-t border-gray-50">Surat Pembekuan</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Keterangan & Catatan -->
            <div class="mt-8 mb-6 border-t border-gray-100 pt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Catatan</label>
                <textarea name="keterangan" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm"></textarea>
                <p class="text-sm text-gray-500 mt-2 italic">(* ) Data bisa di update dari menu Sertifikasi</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end items-center gap-3 sm:gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('surveilans.index') }}" class="w-full sm:w-auto px-8 py-2.5 bg-white text-gray-700 border border-gray-300 font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm text-center">
                    Kembali
                </a>
                <!-- <button type="reset" class="w-full sm:w-auto px-8 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition shadow-sm">
                    Reset
                </button> -->
                <button type="button" onclick="submitSelesai(event)" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm border border-transparent shadow-blue-600/20 text-center flex items-center justify-center">
                    Selesai
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/surveilans.js') }}?v={{ time() }}"></script>
<script>
    function submitSelesai(e) {
        e.preventDefault();
        
        const form = e.target.closest('form');
        if (!form) return;
        
        const selector = document.getElementById('pengawasan_selector');
        const sectionId = selector ? selector.value : '1';
        
        // Bersihkan input dinamis sebelumnya (jika ada)
        let oldAction = form.querySelector('input[name="action"]');
        if (oldAction) oldAction.remove();
        let oldRedirect = form.querySelector('input[name="redirect_to"]');
        if (oldRedirect) oldRedirect.remove();
        
        let actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'simpan_' + sectionId;
        
        form.appendChild(actionInput);
        form.submit();
    }

    function submitCetakSurat(e, link, sectionId) {
        e.preventDefault();
        
        const form = link.closest('form');
        if (!form) return;
        
        // Bersihkan input dinamis sebelumnya (jika ada)
        let oldAction = form.querySelector('input[name="action"]');
        if (oldAction) oldAction.remove();
        let oldRedirect = form.querySelector('input[name="redirect_to"]');
        if (oldRedirect) oldRedirect.remove();
        
        // Add hidden inputs for action
        let actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'simpan_' + sectionId;
        
        let redirectUrl = new URL(link.href);
        let jenis = redirectUrl.searchParams.get('jenis');
        
        let dateInputName = '';
        if (jenis === 'pemberitahuan') {
            dateInputName = 'tgl_surat_pengawasan_' + sectionId;
        } else if (jenis === 'teguran') {
            let t2 = form.querySelector(`[name="tgl_surat_teguran_2_${sectionId}"]`);
            if (t2 && t2.value) {
                dateInputName = 'tgl_surat_teguran_2_' + sectionId;
                redirectUrl.searchParams.set('jenis', 'teguran2');
            } else {
                dateInputName = 'tgl_surat_teguran_1_' + sectionId;
                redirectUrl.searchParams.set('jenis', 'teguran1');
            }
            
            // Ambil tanggal surat pemberitahuan (pengawasan)
            let tglPengawasan = form.querySelector(`[name="tgl_surat_pengawasan_${sectionId}"]`);
            if (tglPengawasan && tglPengawasan.value) {
                redirectUrl.searchParams.set('tgl_survailen', tglPengawasan.value);
            }
        } else if (jenis === 'pembekuan') {
            let p2 = form.querySelector(`[name="tgl_pembekuan_2_${sectionId}"]`);
            if (p2 && p2.value) {
                dateInputName = 'tgl_pembekuan_2_' + sectionId;
                redirectUrl.searchParams.set('jenis', 'pembekuan2');
                
                // Pembekuan 2 merujuk ke tanggal Pembekuan 1
                let tglPembekuan1 = form.querySelector(`[name="tgl_pembekuan_1_${sectionId}"]`);
                if (tglPembekuan1 && tglPembekuan1.value) {
                    redirectUrl.searchParams.set('tgl_survailen', tglPembekuan1.value);
                }
            } else {
                dateInputName = 'tgl_pembekuan_1_' + sectionId;
                redirectUrl.searchParams.set('jenis', 'pembekuan1');
                
                // Pembekuan 1 merujuk ke tanggal Teguran 2
                let tglTeguran2 = form.querySelector(`[name="tgl_surat_teguran_2_${sectionId}"]`);
                if (tglTeguran2 && tglTeguran2.value) {
                    redirectUrl.searchParams.set('tgl_survailen', tglTeguran2.value);
                }
            }
        }
        
        if (dateInputName) {
            let dateInput = form.querySelector(`[name="${dateInputName}"]`);
            if (dateInput && dateInput.value) {
                redirectUrl.searchParams.set('tanggal_surat', dateInput.value);
            }
        }

        let pengawasanSelector = document.getElementById('pengawasan_selector');
        if (pengawasanSelector) {
            redirectUrl.searchParams.set('survailen', pengawasanSelector.value);
        }

        let redirectInput = document.createElement('input');
        redirectInput.type = 'hidden';
        redirectInput.name = 'redirect_to';
        redirectInput.value = redirectUrl.pathname + redirectUrl.search;

        form.appendChild(actionInput);
        form.appendChild(redirectInput);
        
        form.submit();
    }
</script>
@endsection
