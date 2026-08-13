<div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col my-auto">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Add New Guest</h3>
        </div>
        
        <button 
            @click="showAddGuestModal = false" 
            type="button"
            class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Body & Form -->
    <form action="{{ route('events.guests.store', $event) }}" method="POST" class="p-6">
        @csrf

        @if ($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-3.5 py-2.5 rounded-xl text-xs">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Guest Name -->
        <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                Guest Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="name" 
                required
                value="{{ old('name') }}" 
                placeholder="e.g. John Doe"
                class="border {{ ($errors->has('name') || session('error')) ? 'border-rose-400 ring-2 ring-rose-100' : 'border-gray-300' }} rounded-lg px-3.5 py-2 text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full transition-all"
            >
        </div>

        <!-- Email (Optional) -->
        <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                Email Address <span class="text-gray-400 font-normal lowercase">(optional)</span>
            </label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="john@example.com"
                class="border {{ ($errors->has('email') || session('error')) ? 'border-rose-400 ring-2 ring-rose-100' : 'border-gray-300' }} rounded-lg px-3.5 py-2 text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full transition-all"
            >
        </div>

        <!-- Phone (Optional) -->
        <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                Phone Number <span class="text-gray-400 font-normal lowercase">(optional)</span>
            </label>
            <input 
                type="text" 
                name="phone" 
                value="{{ old('phone') }}" 
                placeholder="+1 (555) 000-0000"
                class="border {{ $errors->has('phone') ? 'border-rose-400 ring-2 ring-rose-100' : 'border-gray-300' }} rounded-lg px-3.5 py-2 text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:outline-none w-full transition-all"
            >
        </div>

        <!-- Max Companions (Read-only fixed to 1) -->
        <div class="mb-3">
            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                Max Companions
            </label>
            <div class="relative">
                <input 
                    type="number" 
                    name="max_companions" 
                    value="1" 
                    readonly
                    class="border border-gray-200 rounded-lg px-3.5 py-2 text-sm bg-gray-100 text-gray-500 cursor-not-allowed w-full focus:outline-none select-none font-medium"
                >
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                    +1 Companion Limit
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">Each guest invitation is fixed with a maximum 1 (+1) companion limit.</p>
        </div>

        <!-- Actions -->
        <div class="pt-4 mt-2 border-t border-gray-100 flex items-center justify-end gap-3">
            <button 
                @click="showAddGuestModal = false" 
                type="button"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors"
            >
                Cancel
            </button>
            <button 
                type="submit" 
                class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors shadow-xs"
            >
                Add Guest
            </button>
        </div>
    </form>
</div>