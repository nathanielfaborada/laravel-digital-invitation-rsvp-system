<x-guest-layout>
    <div class="text-center mb-6">
        <div class="text-4xl mb-3">📧</div>
        <h1 class="text-xl font-bold text-gray-900">Verify your email</h1>
        <p class="text-sm text-gray-500 mt-2">
            Thanks for signing up! Before getting started, could you verify your email address by clicking the link we just emailed you? If you didn't receive it, we'll gladly send another.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700 transition">
            {{ __('Resend Verification Email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full text-center text-sm text-gray-500 hover:text-indigo-600 hover:underline">
            {{ __('Log Out') }}
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pollInterval = 3000;
            const statusUrl = "{{ route('verification.status') }}";

            const timer = setInterval(async function () {
                try {
                    const res = await fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (res.ok) {
                        const data = await res.json();
                        if (data && data.verified === true) {
                            clearInterval(timer);
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    }
                } catch (e) {
                    // Silently ignore network interruptions during polling
                }
            }, pollInterval);

            window.addEventListener('beforeunload', function () {
                clearInterval(timer);
            });
        });
    </script>
</x-guest-layout>