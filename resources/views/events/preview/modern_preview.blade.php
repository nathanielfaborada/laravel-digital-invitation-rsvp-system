@if ($event->cover_image)
    <div class="relative">
        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-72 object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    </div>
@endif

<div class="p-10">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-3">Invitation</p>
    <h1 class="text-4xl font-light text-gray-900 mb-6 leading-tight" x-text="title || 'Event Title'"></h1>

    @if ($event->description)
        <p class="text-gray-500 mb-8 leading-relaxed"x-text="description || 'Description'"></p>
    @endif

    <div class="space-y-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-xs font-bold">
                {{ \Carbon\Carbon::parse($event->event_date)->format('d') }}
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase"
                    x-text="event_date
                        ? new Date(event_date).toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        })
                        : 'Select Date'">
                </p>
                <p class="font-medium text-gray-800"
                    x-text="event_time
                    ? new Date(`1970-01-01T${event_time}`).toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    })
                    : 'Select Time'">
                </p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-xs">
                📍
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Venue</p>
                <p class="font-medium text-gray-800" x-text="venue || 'Venue Name'"></p>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-100 pt-6">

        @include('invite.partials.map', compact('event'))
        @include('invite.partials.countdown', compact('event'))

        <p class="text-gray-600 mb-6">
            , you are cordially invited to join us.
        </p>

        <a class="block text-center bg-gray-900 text-white px-8 py-4 rounded-xl font-medium hover:bg-gray-800 transition">
            RSVP →
        </a>
    </div>
</div>