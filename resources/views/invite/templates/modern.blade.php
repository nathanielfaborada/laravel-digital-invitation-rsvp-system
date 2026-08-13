<div class="max-w-lg w-full bg-white rounded-2xl shadow-2xl overflow-hidden relative max-h-screen sm:max-h-none overflow-y-auto sm:overflow-y-visible border border-slate-100">

    @if ($event->cover_image)
        <img src="{{ $event->cover_image }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover opacity-20">
    @endif

    <!-- Subtle Minimalist Geometric Top Pattern Accent -->
    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-slate-900 via-indigo-600 to-slate-900 z-10"></div>

    <div class="pt-8 pb-8 px-6 sm:px-10 relative z-10">
        <div class="mb-3">
            <span class="inline-block px-3 py-1 bg-slate-900 text-white text-[10px] sm:text-xs font-bold uppercase tracking-widest rounded-full shadow-xs">
                INVITATION
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2 leading-tight tracking-tight">{{ $event->title }}</h1>

        @if ($event->description)
            <p class="text-xs sm:text-sm text-slate-600 mb-5 leading-relaxed font-normal">{{ $event->description }}</p>
        @endif

        <div class="space-y-3 mb-5 bg-slate-50/90 backdrop-blur-sm rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-semibold tracking-wider">Date & Time</p>
                    <p class="text-xs sm:text-sm font-bold text-slate-800">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} · {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2.5 border-t border-slate-200/60">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white text-xs shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-semibold tracking-wider">Location</p>
                    <p class="text-xs sm:text-sm font-bold text-slate-800">{{ $event->venue }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200/80 pt-4">

            @include('invite.partials.map', compact('event'))

            @include('invite.partials.countdown', compact('event'))
            
            <p class="text-xs sm:text-sm text-slate-600 mb-4 font-medium text-center">
                {{ $guest->name }}, you are cordially invited to join us.
            </p>

            <a href="{{ route('rsvp.create', $guest) }}" @click.prevent="showRsvpModal = true" class="block text-center bg-slate-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-sm lg:hidden cursor-pointer">
                RSVP →
            </a>
        </div>
    </div>
</div>