<div 
    x-show="showCoffeeModal"
    x-cloak
    @keydown.escape.window="showCoffeeModal = false"
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
>
    <div 
        @click.away="showCoffeeModal = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="max-w-sm w-full bg-white rounded-2xl p-6 shadow-xl border border-gray-100 relative my-auto text-center"
    >
        <!-- Close Button (Top Right) -->
        <button 
            type="button" 
            @click="showCoffeeModal = false"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Header Icon & Title -->
        <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-xs border border-amber-100">
            ☕
        </div>
        <h3 class="text-lg font-bold text-gray-900">Support Invitr ☕</h3>
        <p class="text-xs text-gray-500 mt-1.5 leading-relaxed px-1">
            Invitr is free to use! If it helped make your event special, consider buying the developer a coffee to help keep the servers running. Any amount is greatly appreciated!
        </p>

        <!-- QR Code Image -->
        <img 
            src="https://res.cloudinary.com/wyofiygs/image/upload/v1786680470/photo_6339320443151520448_x_ecob0p.jpg" 
            alt="GCash QR Code" 
            class="w-64 h-auto mx-auto rounded-xl shadow-md border border-gray-100 my-4"
        >

        <!-- Footer Note -->
        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-100 mb-4">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Scan via GCash App
        </div>

        <!-- Close Button -->
        <button 
            type="button" 
            @click="showCoffeeModal = false" 
            class="w-full h-9 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
        >
            Close
        </button>
    </div>
</div>
