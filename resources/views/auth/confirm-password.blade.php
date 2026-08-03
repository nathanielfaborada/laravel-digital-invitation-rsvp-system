<x-guest-layout>
    <div class="text-center mb-6">
        <div class="text-4xl mb-3">🔒</div>
        <h1 class="text-xl font-bold text-gray-900">Confirm your password</h1>
        <p class="text-sm text-gray-500 mt-1">This is a secure area. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700 transition">
            {{ __('Confirm') }}
        </button>
    </form>
</x-guest-layout>