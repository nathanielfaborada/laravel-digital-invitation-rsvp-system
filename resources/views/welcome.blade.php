<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Invitr') }} — Digital Invitations Made Simple</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Alpine.js Cloak Protection -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ showCoffeeModal: false }" 
    class="bg-gradient-to-b from-indigo-50 via-white to-white min-h-screen overflow-x-hidden flex flex-col"
>

    <!-- NAVBAR -->
    <x-public-nav />

    <!-- MAIN CONTENT (grows to fill available space) -->
    <div class="flex-1 flex flex-col justify-center">

        <!-- HERO -->
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 pt-4 sm:pt-16 pb-6 sm:pb-20">
            <p class="text-indigo-600 font-semibold text-xs sm:text-sm uppercase tracking-wide mb-2 sm:mb-3">Digital Invitations & RSVP</p>
            <h1 class="text-2xl sm:text-5xl font-bold text-gray-900 mb-3 sm:mb-6 leading-tight">
                Send beautiful invites.<br>Track every RSVP.
            </h1>
            <p class="text-sm sm:text-lg text-gray-500 mb-5 sm:mb-8 max-w-xl mx-auto">
                Create event invitations, invite guests with unique links, and track attendance in real-time.
            </p>
            <div class="flex justify-center">
                @auth
                    <a href="{{ route('events.index') }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 sm:px-8 sm:py-3.5 rounded-xl text-sm sm:text-base shadow-sm hover:shadow-md transition">
                        Go to Dashboard →
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 sm:px-8 sm:py-3.5 rounded-xl text-sm sm:text-base shadow-sm hover:shadow-md transition">
                        Create Your First Event — It's Free
                    </a>
                @endauth
            </div>
        </div>

        <!-- FEATURES -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 pb-8 sm:pb-16 w-full">
            <div class="grid grid-cols-3 gap-2.5 sm:gap-6">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-2.5 sm:p-6 text-center">
                    <div class="text-xl sm:text-3xl mb-1 sm:mb-3">✨</div>
                    <h3 class="font-semibold text-gray-800 mb-0.5 sm:mb-2 text-[11px] sm:text-base leading-tight">Beautiful Templates</h3>
                    <p class="hidden sm:block text-sm text-gray-500">Pick a style that matches your event — Classic, Floral, Dark Elegant, and more.</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-2.5 sm:p-6 text-center">
                    <div class="text-xl sm:text-3xl mb-1 sm:mb-3">📊</div>
                    <h3 class="font-semibold text-gray-800 mb-0.5 sm:mb-2 text-[11px] sm:text-base leading-tight">Real-Time Tracking</h3>
                    <p class="hidden sm:block text-sm text-gray-500">See who's attending, who's not, and your total headcount instantly.</p>
                </div>
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-2.5 sm:p-6 text-center">
                    <div class="text-xl sm:text-3xl mb-1 sm:mb-3">📧</div>
                    <h3 class="font-semibold text-gray-800 mb-0.5 sm:mb-2 text-[11px] sm:text-base leading-tight">Automatic Invites</h3>
                    <p class="hidden sm:block text-sm text-gray-500">Add a guest and their personalized invite is sent instantly.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER (always at bottom) -->
    <x-public-footer />

    <!-- Coffee Modal Component -->
    <x-coffee-modal />

    <!-- Universal Toast Handler -->
    @if (session('alert.config') || session('success') || session('error') || session('warning') || session('info') || session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const alertConfig = @json(session('alert.config'));
                const successMsg = @json(session('success'));
                const errorMsg = @json(session('error'));
                const warningMsg = @json(session('warning'));
                const infoMsg = @json(session('info'));
                const statusMsg = @json(session('status'));

                if (alertConfig) {
                    try {
                        let parsedConfig = typeof alertConfig === 'string' ? JSON.parse(alertConfig) : alertConfig;
                        if (typeof parsedConfig === 'object' && parsedConfig !== null) {
                            parsedConfig.customClass = Object.assign({}, parsedConfig.customClass, {
                                container: 'z-[99999]'
                            });
                            Swal.fire(parsedConfig);
                            return;
                        }
                    } catch (e) {
                        console.error('Failed to parse alert.config:', e);
                    }
                }

                let title = successMsg || errorMsg || warningMsg || infoMsg || statusMsg;
                let icon = 'info';

                if (successMsg) {
                    icon = 'success';
                } else if (errorMsg) {
                    icon = 'error';
                } else if (warningMsg) {
                    icon = 'warning';
                } else if (infoMsg) {
                    icon = 'info';
                } else if (statusMsg) {
                    icon = 'info';
                    if (title === 'password-updated') {
                        title = 'Password updated successfully!';
                        icon = 'success';
                    } else if (title === 'verification-link-sent') {
                        title = 'A new verification link has been sent to your email address.';
                        icon = 'success';
                    } else if (title === 'profile-updated') {
                        title = 'Profile updated successfully!';
                        icon = 'success';
                    }
                }

                if (title) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: title,
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        customClass: {
                            container: 'z-[99999]'
                        }
                    });
                }
            });
        </script>
    @endif

</body>
</html>