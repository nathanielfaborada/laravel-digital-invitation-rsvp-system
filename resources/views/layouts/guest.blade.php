<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Invitr') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col bg-gradient-to-b from-indigo-50 via-white to-white">

            <x-public-nav />

            

            <!-- FORM -->
            <div class="flex-1 flex flex-col items-center justify-center px-4 py-8">
                
                <div class="w-full sm:max-w-sm">
                    <div class="bg-white px-6 py-8 sm:px-8 sm:py-10 shadow-sm rounded-2xl border border-gray-100">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <x-public-footer />

        </div>
    </body>
</html>