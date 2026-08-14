<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png">

        <!-- Open Graph / Facebook / Messenger -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta property="og:title" content="Invitr - Digital Invitations & RSVP System">
        <meta property="og:description" content="Create beautiful event invitations, invite guests with unique links, and track attendance in real-time.">
        <meta property="og:image" content="https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png">

        <!-- Twitter / X -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ config('app.url') }}">
        <meta name="twitter:title" content="Invitr - Digital Invitations & RSVP System">
        <meta name="twitter:description" content="Create beautiful event invitations, invite guests with unique links, and track attendance in real-time.">
        <meta name="twitter:image" content="https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Alpine.js Cloak Protection -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->    
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body 
        x-data="{ showCoffeeModal: false }"
        class="font-sans antialiased"
    >
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col justify-between">
            <div>
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>

            <!-- Footer -->
            <x-public-footer />
        </div>

        <!-- Universal Alert Handler (For Custom SweetAlert Dialogs) -->
        @if (session('alert.config'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const alertConfig = @json(session('alert.config'));

                    if (alertConfig) {
                        try {
                            let parsedConfig = typeof alertConfig === 'string' ? JSON.parse(alertConfig) : alertConfig;
                            if (typeof parsedConfig === 'object' && parsedConfig !== null) {
                                parsedConfig.customClass = Object.assign({}, parsedConfig.customClass, {
                                    container: 'z-[99999]'
                                });
                                Swal.fire(parsedConfig);
                            }
                        } catch (e) {
                            console.error('Failed to parse alert.config:', e);
                        }
                    }
                });
            </script>
        @endif

        <!-- Toast Notification Component -->
        <x-toast-notification />

        <!-- Coffee Modal Component -->
        <x-coffee-modal />
    </body>
</html>