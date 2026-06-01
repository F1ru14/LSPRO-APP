@extends('layouts.app')

@section('content')
<div class="p-8" x-data="liveSearch()">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Sertifikasi</h1>
            <p class="text-gray-500">Kelola dan pantau proses sertifikasi konsumen.</p>
        </div>
        <div>
            <!-- Button was removed to enforce using the sidebar menu -->
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center gap-4 flex-wrap">
            <form action="{{ route('sertifikasi.index') }}" method="GET" class="w-full max-w-md relative" @submit.prevent="performSearch">
                <input type="text" x-model="searchQuery" @input.debounce.200ms="performSearch" placeholder="Cari No. Referensi, Perusahaan, Email..." class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm">
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
                        <th class="px-6 py-4 font-semibold">No. Referensi</th>
                        <th class="px-6 py-4 font-semibold">Perusahaan</th>
                        <th class="px-6 py-4 font-semibold">Komoditi</th>
                        <th class="px-6 py-4 font-semibold text-center">Tgl Permohonan</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($datas as $item)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $item->no_referensi }}
                            <div class="text-xs text-gray-400 font-normal mt-0.5">{{ $item->kategori->nama_kategori ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $item->perusahaan->nama_perusahaan ?? '-' }}
                            <div class="text-xs text-gray-400 font-normal mt-0.5">{{ $item->perusahaan->email ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->perusahaan->komoditi ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 text-center">{{ \Carbon\Carbon::parse($item->tgl_permohonan)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $status = empty($item->status_permohonan) ? 'Belum Terbit' : ucwords($item->status_permohonan);
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                {{ strtolower($status) == 'terbit' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}
                            ">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">

                                <a href="{{ route('sertifikasi.edit', $item->id_sertifikasi) }}" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('sertifikasi.destroy', $item->id_sertifikasi) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p>Tidak ada data sertifikasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-5 border-t border-gray-50 flex flex-col items-center justify-center gap-6">
            @if ($datas->hasPages())
                <div class="flex items-center justify-center gap-6 text-sm">
                    @if (!$datas->onFirstPage())
                        <a href="{{ $datas->appends(['per_page' => $perPage, 'search' => $search])->previousPageUrl() }}" class="text-blue-500 hover:text-blue-700 font-medium transition">
                            Sebelumnya
                        </a>
                    @endif

                    <div class="flex items-center gap-3">
                        @php
                            $start = max(1, $datas->currentPage() - 2);
                            $end = min($datas->lastPage(), $datas->currentPage() + 2);
                        @endphp
                        
                        @if($start > 1)
                            <a href="{{ $datas->appends(['per_page' => $perPage, 'search' => $search])->url(1) }}" class="text-blue-500 hover:text-blue-700 font-medium">1</a>
                            @if($start > 2) <span class="text-gray-400">...</span> @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $datas->currentPage())
                                <span class="text-gray-800 font-bold">{{ $i }}</span>
                            @else
                                <a href="{{ $datas->appends(['per_page' => $perPage, 'search' => $search])->url($i) }}" class="text-blue-500 hover:text-blue-700 font-medium transition">{{ $i }}</a>
                            @endif
                        @endfor

                        @if($end < $datas->lastPage())
                            @if($end < $datas->lastPage() - 1) <span class="text-gray-400">...</span> @endif
                            <a href="{{ $datas->appends(['per_page' => $perPage, 'search' => $search])->url($datas->lastPage()) }}" class="text-blue-500 hover:text-blue-700 font-medium">{{ $datas->lastPage() }}</a>
                        @endif
                    </div>

                    @if ($datas->hasMorePages())
                        <a href="{{ $datas->appends(['per_page' => $perPage, 'search' => $search])->nextPageUrl() }}" class="text-blue-500 hover:text-blue-700 font-medium transition">
                            Berikutnya
                        </a>
                    @endif
                </div>
            @endif

            <div>
                <form action="{{ route('sertifikasi.index') }}" method="GET" class="flex items-center justify-center gap-2" @submit.prevent="performSearch">
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
            searchQuery: '{{ $search }}',
            perPage: '{{ $perPage }}',
            sort: '{{ $sort ?? "newest" }}',
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
