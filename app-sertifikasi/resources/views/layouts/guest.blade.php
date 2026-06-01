<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}?v=2">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="guest-layout-wrapper">
            <div class="guest-layout-card">
                <div class="flex flex-col items-center mb-6">
                    <a href="/">
                        <x-application-logo class="h-20 w-auto object-contain drop-shadow-sm" />
                    </a>
                    <h2 class="mt-3 text-[19px] font-bold text-[#333333]">LSPRO APP Surabaya</h2>
                </div>
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
