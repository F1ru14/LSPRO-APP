<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Utama - LSPRO BSPJI Surabaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden bg-slate-900">
    
    <!-- Background Image -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/bg-portal.png') }}" class="w-full h-full object-cover opacity-20" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/50 to-slate-900/80 mix-blend-multiply"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[30rem] h-[30rem] bg-cyan-400/20 rounded-full blur-3xl"></div>

    <div class="relative z-10 w-full max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 tracking-tight drop-shadow-md">
                Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}
            </h1>
            <p class="text-lg text-blue-100 font-medium tracking-wide">
                Silakan pilih sistem aplikasi yang ingin Anda akses hari ini
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 px-4">
            <!-- Card Sertifikasi -->
            <a href="/dashboard" class="glass-card group rounded-3xl p-8 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer">
                <div class="w-24 h-24 mb-6 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">Sistem Sertifikasi</h2>
                <p class="text-gray-500 font-medium leading-relaxed mb-8">
                    Akses modul pendaftaran, pengaturan jadwal audit, penerbitan sertifikat SNI, dan rekapitulasi data.
                </p>
                <div class="mt-auto inline-flex items-center space-x-2 text-blue-600 font-semibold group-hover:tracking-wider transition-all">
                    <span>Masuk Aplikasi</span>
                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Card Survailen -->
            <a href="http://survailen.lspro.test:8001/dashboard" class="glass-card group rounded-3xl p-8 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer">
                <div class="w-24 h-24 mb-6 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-3 group-hover:text-emerald-600 transition-colors">Sistem Survailen</h2>
                <p class="text-gray-500 font-medium leading-relaxed mb-8">
                    Pantau masa berlaku sertifikat, jadwal kunjungan survailen, hingga penerbitan surat pemberitahuan.
                </p>
                <div class="mt-auto inline-flex items-center space-x-2 text-emerald-600 font-semibold group-hover:tracking-wider transition-all">
                    <span>Masuk Aplikasi</span>
                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>
        </div>

        <div class="mt-16 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center space-x-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-full font-semibold transition-all border border-white/20 backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>Keluar / Log Out</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
