<footer class="text-center text-xs sm:text-sm text-gray-400 py-4 sm:pb-8">
    <p>© {{ date('Y') }} Invitr. Built with Laravel.</p>
    <p class="mt-1">
        <a href="{{ route('terms') }}" class="hover:text-indigo-600">Terms</a>
        ·
        <a href="{{ route('privacy') }}" class="hover:text-indigo-600">Privacy</a>
    </p>
    <div class="mt-2 flex justify-center items-center">
        <button @click="showCoffeeModal = true" class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 rounded-full transition-all shadow-sm cursor-pointer">
            <span>☕</span> Buy the Developer a Coffee
        </button>
    </div>
</footer>