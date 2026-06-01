@extends('layouts.app')

@section('content')
<div class="p-8" x-data="liveSearch()">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Konsumen</h1>
            <p class="text-gray-500">Daftar semua perusahaan yang terdaftar sebagai konsumen aktif.</p>
        </div>
        <!-- <a href="{{ route('surveilans.create') }}" class="px-5 py-2.5 bg-[#0093ff] text-white font-semibold rounded-xl hover:bg-blue-600 transition shadow-sm shadow-blue-200 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Survailen
        </a> -->
    </div>

    {{-- Info Banner --}}
    <div class="mb-6 flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-2xl px-5 py-4">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-blue-700">
            <span class="font-semibold">Data perusahaan terintegrasi secara otomatis</span> dari Sertifikasi.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center gap-4">
            <form action="{{ route('surveilans.index') }}" method="GET" class="w-full max-w-md relative" @submit.prevent="performSearch">
                <input type="text" x-model="searchQuery" @input.debounce.200ms="performSearch" placeholder="Cari Perusahaan, No Referensi..." class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
            
            <button type="button" @click="sort = (sort === 'newest' ? 'oldest' : 'newest'); performSearch()" class="p-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl transition text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" :title="sort === 'newest' ? 'Terbaru (klik untuk Terlama)' : 'Terlama (klik untuk Terbaru)'">
                <!-- Icon Sort Descending (Newest) -->
                <svg x-show="sort === 'newest'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h10M4 12h8M4 18h6M18 6v12M18 18l-3-3M18 18l3-3"></path>
                </svg>
                <!-- Icon Sort Ascending (Oldest) -->
                <svg x-show="sort === 'oldest'" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h6M4 12h8M4 18h10M18 18V6M18 6l-3 3M18 6l3 3"></path>
                </svg>
            </button>
        </div>

        <div id="table-and-pagination">
            <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No Referensi</th>
                        <th class="px-6 py-4 font-semibold">Nama Perusahaan</th>
                        <th class="px-6 py-4 font-semibold">Komoditi</th>
                        <th class="px-6 py-4 font-semibold text-center">Survailen</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($surveilans as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold">{{ $item->no_referensi ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $item->perusahaan->nama_perusahaan ?? '-' }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $item->perusahaan->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <span class="inline-block px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-medium">{{ $item->perusahaan->komoditi ?? '-' }}</span>
                            <div class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ $item->perusahaan->merek ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->surveilans_count == 0)
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold">Belum Ada</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">Ke-{{ $item->surveilans_count }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $latestDate = null;
                                $latestStatus = '-';
                                
                                if ($item->surveilans) {
                                    foreach ($item->surveilans as $surv) {
                                        // 1. Cek Pelaksanaan Pengawasan Berkala
                                        if ($surv->tgl_pelaksanaan) {
                                            if (!$latestDate || $surv->tgl_pelaksanaan > $latestDate) {
                                                $latestDate = $surv->tgl_pelaksanaan;
                                                $latestStatus = 'Pengawasan Berkala';
                                            }
                                        }
                                        
                                        // 2. Cek semua tipe Surat
                                        if ($surv->surats) {
                                            foreach ($surv->surats as $surat) {
                                                if ($surat->tgl_terbit) {
                                                    if (!$latestDate || $surat->tgl_terbit > $latestDate) {
                                                        $latestDate = $surat->tgl_terbit;
                                                        
                                                        if ($surat->jenis_surat == 'Pengawasan Berkala') {
                                                            $latestStatus = 'Pemberitahuan Pengawasan Berkala';
                                                        } else {
                                                            $latestStatus = $surat->jenis_surat;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp
                            
                            @if($latestStatus != '-')
                                @php
                                    $statusColorClass = 'bg-green-100 text-green-700';
                                    if (str_contains(strtolower($latestStatus), 'teguran')) {
                                        $statusColorClass = 'bg-yellow-100 text-yellow-700';
                                    } elseif (str_contains(strtolower($latestStatus), 'pembekuan')) {
                                        $statusColorClass = 'bg-red-100 text-red-700';
                                    }
                                @endphp
                                <span class="inline-block px-3 py-1 {{ $statusColorClass }} rounded-lg text-xs font-bold">{{ $latestStatus }}</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('surveilans.create', ['no_referensi' => $item->no_referensi]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-sm font-semibold hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Survailen
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <p>Tidak ada data survailen yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-5 border-t border-gray-50 flex flex-col items-center justify-center gap-6">
            @if ($surveilans->hasPages())
                <div class="flex items-center justify-center gap-6 text-sm">
                    @if (!$surveilans->onFirstPage())
                        <a href="{{ $surveilans->appends(['per_page' => $perPage, 'search' => $search])->previousPageUrl() }}" class="text-blue-500 hover:text-blue-700 font-medium transition">
                            Sebelumnya
                        </a>
                    @endif

                    <div class="flex items-center gap-3">
                        @php
                            $start = max(1, $surveilans->currentPage() - 2);
                            $end = min($surveilans->lastPage(), $surveilans->currentPage() + 2);
                        @endphp
                        
                        @if($start > 1)
                            <a href="{{ $surveilans->appends(['per_page' => $perPage, 'search' => $search])->url(1) }}" class="text-blue-500 hover:text-blue-700 font-medium">1</a>
                            @if($start > 2) <span class="text-gray-400">...</span> @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $surveilans->currentPage())
                                <span class="text-gray-800 font-bold">{{ $i }}</span>
                            @else
                                <a href="{{ $surveilans->appends(['per_page' => $perPage, 'search' => $search])->url($i) }}" class="text-blue-500 hover:text-blue-700 font-medium transition">{{ $i }}</a>
                            @endif
                        @endfor

                        @if($end < $surveilans->lastPage())
                            @if($end < $surveilans->lastPage() - 1) <span class="text-gray-400">...</span> @endif
                            <a href="{{ $surveilans->appends(['per_page' => $perPage, 'search' => $search])->url($surveilans->lastPage()) }}" class="text-blue-500 hover:text-blue-700 font-medium">{{ $surveilans->lastPage() }}</a>
                        @endif
                    </div>

                    @if ($surveilans->hasMorePages())
                        <a href="{{ $surveilans->appends(['per_page' => $perPage, 'search' => $search])->nextPageUrl() }}" class="text-blue-500 hover:text-blue-700 font-medium transition">
                            Berikutnya
                        </a>
                    @endif
                </div>
            @endif

            <div>
                <form action="{{ route('surveilans.index') }}" method="GET" class="flex items-center justify-center gap-2" @submit.prevent="performSearch">
                    <label for="per_page_bottom" class="text-sm text-gray-600 whitespace-nowrap">Tampilkan:</label>
                    <select x-model="perPage" @change="performSearch" id="per_page_bottom" class="border border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 pl-3 pr-8 py-1.5">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-600 whitespace-nowrap">data per halaman</span>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function liveSearch() {
        return {
            searchQuery: '{{ $search ?? '' }}',
            perPage: '{{ $perPage ?? 10 }}',
            sort: '{{ request('sort', 'newest') }}',
            isLoading: false,
            abortController: null,
            performSearch() {
                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();
                
                this.isLoading = true;
                let url = new URL(window.location.href);
                if (this.searchQuery.trim() === '') {
                    url.searchParams.delete('search');
                } else {
                    url.searchParams.set('search', this.searchQuery);
                }
                url.searchParams.set('per_page', this.perPage);
                url.searchParams.set('sort', this.sort);
                // Reset page to 1 on new search
                url.searchParams.delete('page');
                
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: this.abortController.signal
                })
                .then(res => res.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    document.getElementById('table-and-pagination').innerHTML = doc.getElementById('table-and-pagination').innerHTML;
                    window.history.pushState({}, '', url);
                    this.isLoading = false;
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        console.error('Search failed:', err);
                        this.isLoading = false;
                    }
                });
            }
        }
    }
</script>
@endpush
@endsection
