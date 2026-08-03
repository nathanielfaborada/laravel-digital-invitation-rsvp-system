<div class="max-w-lg w-full bg-white rounded-xl shadow-lg overflow-hidden">

    @if ($event->cover_image)
        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
    @endif

    <div class="p-8 text-center">
        <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">You're Invited</p>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>

        @if ($event->description)
            <p class="text-gray-600 mb-6">{{ $event->description }}</p>
        @endif

        <div class="border-t border-b border-gray-200 py-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Date</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Time</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 uppercase">Venue</p>
                <p class="font-semibold">{{ $event->venue }}</p>
            </div>
        </div>

        @include('invite.partials.map', compact('event'))

        @include('invite.partials.countdown', compact('event'))

        <p class="text-lg mb-6">
            Dear <span class="font-semibold">{{ $guest->name }}</span>, we'd love for you to join us!
        </p>

        <a href="{{ route('rsvp.create', $guest) }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700">
            RSVP Now
        </a>
    </div>
</div>