<div class="max-w-lg w-full bg-white rounded-2xl shadow-2xl overflow-hidden">

    @if ($event->cover_image)
        <div class="relative">
            <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-72 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
    @endif

    <div class="p-10">
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">Invitation</p>
        <h1 class="text-4xl font-light text-gray-900 mb-6 leading-tight">{{ $event->title }}</h1>

        @if ($event->description)
            <p class="text-gray-500 mb-8 leading-relaxed">{{ $event->description }}</p>
        @endif

        <div class="space-y-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase">Date & Time</p>
                    <p class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }} · {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-xs">
                    📍
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase">Venue</p>
                    <p class="font-medium text-gray-800">{{ $event->venue }}</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6">
            @include('invite.partials.countdown', compact('event'))

            <p class="text-gray-600 mb-6">
                {{ $guest->name }}, you are cordially invited to join us.
            </p>

            <a href="{{ route('rsvp.create', $guest) }}" class="block text-center bg-gray-900 text-white px-8 py-4 rounded-xl font-medium hover:bg-gray-800 transition">
                RSVP →
            </a>
        </div>
    </div>
</div>