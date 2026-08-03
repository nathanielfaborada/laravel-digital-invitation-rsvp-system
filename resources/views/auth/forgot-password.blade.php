<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold text-gray-900">Forgot your password?</h1>
        <p class="text-sm text-gray-500 mt-1">No problem. We'll email you a reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700 transition">
            {{ __('Email Password Reset Link') }}
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Remember your password?
        <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Log in</a>
    </p>
</x-guest-layout>