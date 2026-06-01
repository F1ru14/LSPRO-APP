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
<body class="min-h-screen flex flex-col p-6 relative overflow-hidden bg-slate-900">
    
    <!-- Background Image -->
    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/background_apik.png') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-slate-900/50"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[30rem] h-[30rem] bg-cyan-400/20 rounded-full blur-3xl"></div>

    <div class="relative z-10 w-full max-w-4xl mx-auto flex flex-col flex-1">
        <div class="text-center mt-12 md:mt-24 mb-auto">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight drop-shadow-md">
                Selamat Datang, {{ Auth::user()->name ?? 'Administrator' }}
            </h1>
            <p class="text-lg text-blue-100 font-medium tracking-wide">
                Silakan pilih sistem aplikasi yang ingin Anda akses hari ini
            </p>
        </div>

        <div class="mb-8 md:mb-12">
            <div class="grid md:grid-cols-2 gap-6 px-4">
            <!-- Card Sertifikasi -->
            <a href="/dashboard" class="glass-card group rounded-2xl p-6 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                <div class="w-16 h-16 mb-4 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-blue-600 transition-colors">Sistem Sertifikasi</h2>
                
                <div class="mt-auto inline-flex items-center space-x-2 text-blue-600 font-semibold group-hover:tracking-wider transition-all">
                    <span>Masuk Aplikasi</span>
                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>

            <!-- Card Survailen -->
            <a href="http://survailen.lspro.test:8001/dashboard" class="glass-card group rounded-2xl p-6 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                <div class="w-16 h-16 mb-4 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-emerald-600 transition-colors">Sistem Survailen</h2>
                
                <div class="mt-auto inline-flex items-center space-x-2 text-emerald-600 font-semibold group-hover:tracking-wider transition-all">
                    <span>Masuk Aplikasi</span>
                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </a>
        </div>
        </div>
        </div>
    </div>
</body>
</html>
