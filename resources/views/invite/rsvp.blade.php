<div x-data="{ 
    attending: @js(old('status') === 'not_attending' ? 'no' : 'yes'), 
    hasCompanion: @js(old('has_companion') == '1' || old('companions_count', 0) > 0), 
    loading: false 
}" class="bg-white rounded-2xl p-6 sm:p-8 shadow-xl border border-gray-100 w-full">
    <div class="text-center mb-6">
        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-widest mb-1">RSVP Confirmation</p>
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Are you attending?</h2>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Please let us know if you will join us for <strong>{{ $event->title }}</strong></p>
    </div>

    @if ($errors->any())
        <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 p-3.5 rounded-xl text-xs sm:text-sm">
            <div class="font-bold mb-1">Please fix the following issues:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rsvp.store', $guest->unique_code) }}" method="POST" @submit="loading = true">
        @csrf
        <input type="hidden" name="status" :value="attending === 'yes' ? 'attending' : 'not_attending'">
        <input type="hidden" name="companions_count" :value="(attending === 'yes' && hasCompanion) ? 1 : 0">

        <!-- Greeting -->
        <p class="text-xs sm:text-sm text-gray-700 mb-3 font-medium">
            Hi <span class="font-bold text-gray-900">{{ $guest->name }}</span>, will you be attending?
        </p>

        <!-- Attendance Choice Radios -->
        <div class="space-y-3 mb-5">
            <label 
                @click="attending = 'yes'"
                class="flex items-center justify-between p-3.5 rounded-xl border-2 cursor-pointer transition-all"
                :class="attending === 'yes' ? 'border-emerald-500 bg-emerald-50/50 shadow-xs' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
            >
                <div class="flex items-center gap-3">
                    <input 
                        type="radio" 
                        name="attendance_choice" 
                        value="yes" 
                        x-model="attending" 
                        @click="attending = 'yes'"
                        required 
                        class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 cursor-pointer"
                    >
                    <div>
                        <div class="text-xs sm:text-sm font-bold" :class="attending === 'yes' ? 'text-emerald-900' : 'text-gray-800'">Yes, I'll be there! 🎉</div>
                        <div class="text-[11px] text-gray-500">Count me in for the celebration</div>
                    </div>
                </div>
                <span x-show="attending === 'yes'" x-cloak class="text-emerald-600 font-bold text-xs">Selected</span>
            </label>

            <!-- Companion UI Block (Right below the 'Yes, I'll be there!' radio card) -->
            <div x-show="attending === 'yes'" x-cloak class="mt-3 p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-3">
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        name="has_companion" 
                        value="1" 
                        x-model="hasCompanion" 
                        class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                    >
                    <span class="text-sm font-medium text-gray-700">Bringing a companion? (+1 Allowed)</span>
                </label>

                <div x-show="hasCompanion" x-cloak>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Companion Full Name</label>
                    <input 
                        type="text" 
                        name="companion_name" 
                        value="{{ old('companion_name') }}"
                        placeholder="e.g. Jane Doe" 
                        class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2 bg-white border"
                    >
                </div>
            </div>

            <label 
                @click="attending = 'no'"
                class="flex items-center justify-between p-3.5 rounded-xl border-2 cursor-pointer transition-all"
                :class="attending === 'no' ? 'border-rose-400 bg-rose-50/50 shadow-xs' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
            >
                <div class="flex items-center gap-3">
                    <input 
                        type="radio" 
                        name="attendance_choice" 
                        value="no" 
                        x-model="attending" 
                        @click="attending = 'no'"
                        required 
                        class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-gray-300 cursor-pointer"
                    >
                    <div>
                        <div class="text-xs sm:text-sm font-bold" :class="attending === 'no' ? 'text-rose-900' : 'text-gray-800'">Sorry, I can't make it 😔</div>
                        <div class="text-[11px] text-gray-500">I won't be able to attend</div>
                    </div>
                </div>
                <span x-show="attending === 'no'" x-cloak class="text-rose-600 font-bold text-xs">Selected</span>
            </label>
        </div>

        <!-- Message Field -->
        <div class="mb-6">
            <label class="block text-xs font-semibold text-gray-700 mb-1">Message for the Host (Optional)</label>
            <textarea 
                name="message" 
                rows="3" 
                placeholder="Write a warm note or wish for the hosts..." 
                class="w-full px-3.5 py-2.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors"
            >{{ old('message') }}</textarea>
        </div>

        <!-- Submit Button with Loading State -->
        <button 
            type="submit" 
            :disabled="loading || !attending"
            :class="(loading || !attending) ? 'opacity-60 cursor-not-allowed bg-indigo-400' : 'bg-indigo-600 hover:bg-indigo-700 cursor-pointer shadow-md hover:shadow-lg'"
            class="w-full py-3 px-6 text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center justify-center gap-2"
        >
            <svg x-show="loading" x-cloak class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="loading ? 'Submitting RSVP...' : 'Submit RSVP Response'"></span>
        </button>
    </form>
</div>