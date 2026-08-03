<nav class="max-w-7xl mx-auto w-full px-4 sm:px-6 py-4 sm:py-6 flex justify-between items-center">
    <a href="{{ url('/') }}" class="text-lg sm:text-2xl font-bold text-indigo-600">Invitr</a>

    <div class="flex gap-2 sm:gap-3">
        @auth
            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium hover:bg-indigo-700">
                Dashboard
            </a>
        @else
            @unless (request()->routeIs('login'))
                <a href="{{ route('login') }}" class="text-gray-600 px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium hover:text-indigo-600">
                    Log in
                </a>
            @endunless

            @unless (request()->routeIs('register'))
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium hover:bg-indigo-700">
                    Get Started
                </a>
            @endunless
        @endauth
    </div>
</nav>