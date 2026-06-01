<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LSPRO APP Surabaya</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}?v=2">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('images/background_apik.png') }}');">
    
    <!-- Overlay Transparan -->
    <div class="absolute inset-0 bg-black/5"></div>

    <!-- Login Box -->
    <div class="relative z-10 bg-white/95 backdrop-blur-sm rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-xs sm:max-w-sm md:max-w-md p-6 sm:p-8 md:p-12">
        
        <div class="flex flex-col items-center mb-6 sm:mb-8 md:mb-10">
            <img src="{{ asset('logo.png') }}" alt="Logo BSPJI Surabaya" class="h-24 sm:h-28 md:h-32 mb-3 sm:mb-4">
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800 text-center tracking-tight">LSPRO APP Surabaya</h1>
        </div>

        @if (session('error') || $errors->any())
            <!-- We will handle the error display using SweetAlert2 below -->
        @endif
    
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <!-- Input Username -->
            <div class="mb-4 sm:mb-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input type="text" name="username" placeholder="username" 
                    class="block w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-3 sm:py-4 text-sm sm:text-base bg-[#dee7ee] border border-gray-400 rounded-lg sm:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition placeholder-gray-500 text-gray-700">
            </div>

            <!-- Input Password -->
            <div class="mb-4 sm:mb-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password" id="password" name="password" placeholder="password" 
                    class="block w-full pl-10 sm:pl-12 pr-10 sm:pr-12 py-3 sm:py-4 text-sm sm:text-base bg-[#dee7ee] border border-gray-400 rounded-lg sm:rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition placeholder-gray-500 text-gray-700">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg id="eyeSlashIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    <svg id="eyeIcon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>


            <!-- Remember Me -->
            <div class="flex items-center mb-6 sm:mb-8 md:mb-10">
                <input id="remember" type="checkbox" class="h-4 w-4 border-gray-400 rounded text-blue-600 cursor-pointer">
                <label for="remember" class="ml-2 text-xs sm:text-sm text-gray-600 font-medium cursor-pointer">Remember me</label>
            </div>

            <!-- Tombol Login -->
            <div class="flex justify-center">
                <button type="submit" 
                    class="bg-[#4285f4] w-4/5 sm:w-3/5 text-white font-bold py-3 sm:py-3.5 text-sm sm:text-base rounded-full shadow-lg hover:brightness-110 active:scale-95 transition-all duration-200 uppercase tracking-widest">
                    LOGIN
                </button>
            </div>
        </form>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                } else {
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                }
            });

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#3085d6',
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#3085d6',
                });
            @endif
        });
    </script>
</body>
</html>