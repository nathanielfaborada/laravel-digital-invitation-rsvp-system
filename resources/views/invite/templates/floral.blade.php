<div class="max-w-lg w-full bg-gradient-to-b from-pink-50 to-white rounded-3xl shadow-xl overflow-hidden border-4 border-pink-100">

    @if ($event->cover_image)
        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
    @endif

    <div class="p-8 text-center">
        <div class="text-3xl mb-2">🌸 ✿ 🌸</div>
        <p class="text-sm text-rose-400 uppercase tracking-wide mb-2 font-medium">You're Cordially Invited</p>
        <h1 class="text-3xl font-serif text-rose-900 mb-4">{{ $event->title }}</h1>

        @if ($event->description)
            <p class="text-rose-700 mb-6 italic">{{ $event->description }}</p>
        @endif

        <div class="bg-white/60 rounded-2xl border border-pink-200 py-5 px-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-rose-400 uppercase">Date</p>
                    <p class="font-semibold text-rose-900">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-rose-400 uppercase">Time</p>
                    <p class="font-semibold text-rose-900">{{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-pink-100">
                <p class="text-xs text-rose-400 uppercase">Venue</p>
                <p class="font-semibold text-rose-900">{{ $event->venue }}</p>
            </div>
        </div>

        @include('invite.partials.countdown', compact('event'))

        <p class="text-lg text-rose-800 mb-6 font-serif italic">
            Dearest {{ $guest->name }}, we would be honored by your presence.
        </p>

        <a href="{{ route('rsvp.create', $guest) }}" class="inline-block bg-rose-400 text-white px-8 py-3 rounded-full font-semibold hover:bg-rose-500 shadow-md">
            RSVP with Love 💌
        </a>
    </div>
</div>