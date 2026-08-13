<div class="max-w-lg w-full bg-gradient-to-b from-pink-50 via-rose-50/40 to-white rounded-3xl shadow-xl overflow-hidden border-4 border-pink-100">

    @if ($event->cover_image)
        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-56 object-cover opacity-80">
    @endif

    <div class="pt-8 pb-8 px-6 sm:px-10 text-center relative z-10">
        <!-- FLORAL HEADER SVG ORNAMENT -->
        <div class="flex justify-center mb-2">
            <svg class="w-36 h-9" viewBox="0 0 200 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 25 C50 12, 70 35, 90 25" stroke="#E11D48" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.35"/>
                <path d="M180 25 C150 12, 130 35, 110 25" stroke="#E11D48" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.35"/>
                <circle cx="100" cy="25" r="7" fill="#FDA4AF" opacity="0.8"/>
                <circle cx="94" cy="21" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="106" cy="21" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="94" cy="29" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="106" cy="29" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="100" cy="25" r="3.5" fill="#FFF1F2"/>
            </svg>
        </div>

        <p class="text-xs text-rose-500 uppercase tracking-widest mb-2 font-bold">YOU'RE CORDIALLY INVITED</p>
        <h1 class="text-2xl sm:text-3xl font-serif text-rose-950 mb-3 leading-tight">{{ $event->title }}</h1>

        @if ($event->description)
            <p class="text-sm text-rose-800/80 mb-5 italic font-serif leading-relaxed">{{ $event->description }}</p>
        @endif

        <div class="bg-white/85 backdrop-blur-sm rounded-2xl border border-pink-200/90 py-4 px-4 mb-5 shadow-2xs">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-rose-400 uppercase font-semibold tracking-wider">Date</p>
                    <p class="font-semibold text-rose-900 text-sm">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-rose-400 uppercase font-semibold tracking-wider">Time</p>
                    <p class="font-semibold text-rose-900 text-sm">{{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-pink-100">
                <p class="text-xs text-rose-400 uppercase font-semibold tracking-wider">Venue</p>
                <p class="font-semibold text-rose-900 text-sm">{{ $event->venue }}</p>
            </div>
        </div>

        @include('invite.partials.map', compact('event'))
        @include('invite.partials.countdown', compact('event'))

        <p class="text-base text-rose-900 mb-5 font-serif italic">
            Dearest {{ $guest->name }}, we would be honored by your presence.
        </p>

        <a href="{{ route('rsvp.create', $guest) }}" @click.prevent="showRsvpModal = true" class="inline-block bg-rose-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-rose-700 shadow-md transition-colors lg:hidden cursor-pointer">
            RSVP with Love 💌
        </a>
    </div>
</div>