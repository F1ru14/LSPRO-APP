<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LSPRO APP Surabaya</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}?v=2">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
        }
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-100 h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/50 z-40 md:hidden" @click="sidebarOpen = false" style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="bg-[#0093ff] w-72 flex-shrink-0 flex flex-col text-white shadow-xl fixed inset-y-0 left-0 z-50 md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-8 flex flex-col items-center border-b border-blue-400/30">
            <div class="bg-white p-2 rounded-full w-32 h-32 flex items-center justify-center mb-4 overflow-hidden shadow-inner">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-24 object-contain">
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <p class="px-4 text-xs font-semibold uppercase tracking-widest text-blue-100 mb-4 opacity-70">Menu</p>

            <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <div x-data="{ open: {{ request()->routeIs('sertifikasi.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-white/10 transition">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="font-medium">Sertifikasi</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open" x-cloak class="mt-1 space-y-1 ml-9 border-l border-white/20">
                    <a href="{{ route('sertifikasi.create') }}" class="block px-4 py-2 text-sm text-blue-50 hover:text-white transition">Tambah Sertifikasi</a>
                    <a href="{{ route('sertifikasi.index') }}" class="block px-4 py-2 text-sm text-blue-50 hover:text-white transition">Data Sertifikasi</a>
                </div>
            </div>


            <a href="{{ route('rekap.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="font-medium">Rekap Data</span>
            </a>
        </nav>

        <div class="p-4 border-t border-blue-400/30">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-start space-x-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white hover:text-red-200 transition border-none cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="font-bold uppercase tracking-wider text-sm">Log out</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50">
        
        <header class="bg-white h-20 shadow-sm flex items-center justify-between px-4 md:px-10">
            <div class="flex items-center space-x-4 md:space-x-6">
                <!-- Hamburger Menu Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <!-- Navigation Portal -->
                <div class="hidden md:flex items-center space-x-3 ml-2">
                    <a href="/dashboard" class="px-4 py-1.5 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition text-sm"> Dashboard Sertifikasi</a>
                    <a href="http://survailen.localhost/dashboard" class="px-4 py-1.5 bg-emerald-100 text-emerald-700 font-semibold rounded-lg hover:bg-emerald-200 transition text-sm flex items-center space-x-1" target="_blank">
                        <span>Ke Sistem Survailen</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
                <div x-data="clock()" x-init="startClock()" class="hidden md:flex items-center space-x-2 text-sm font-medium text-gray-500 bg-gray-50 px-4 py-1.5 rounded-full border border-gray-100">
                    <svg class="w-4 h-4 text-[#0093ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span x-text="date" class="tracking-wide"></span>
                    <span class="text-gray-300 px-1">|</span>
                    <svg class="w-4 h-4 text-[#0093ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="time" class="tracking-widest font-semibold text-gray-600"></span>
                </div>
            </div>
            <div x-data="{ openProfile: false }" class="relative flex items-center space-x-4 cursor-pointer" @click.away="openProfile = false">
                <div @click="openProfile = !openProfile" class="flex items-center space-x-4">
                    <span class="font-bold text-gray-800">{{ Auth::check() ? Auth::user()->name : 'NAMA AKUN' }}</span>
                    <div class="w-12 h-12 bg-[#0093ff] rounded-full flex items-center justify-center overflow-hidden shadow-md hover:ring-2 ring-blue-300 transition">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                </div>
                
                <!-- Dropdown -->
                <div x-show="openProfile" x-cloak x-transition.opacity.duration.200ms
                     class="absolute right-0 top-14 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-medium">Setting</span>
                    </a>
                    <button onclick="toggleTheme()" class="w-full flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition text-left">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <span class="font-medium">Tema Gelap / Terang</span>
                    </button>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full m-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-left">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto">
            @yield('content')
        </div>
    </main>

    <script>
        function clock() {
            return {
                time: '',
                date: '',
                startClock() {
                    const updateClock = () => {
                        const now = new Date();
                        this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                        this.date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    };
                    updateClock();
                    setInterval(updateClock, 1000);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
