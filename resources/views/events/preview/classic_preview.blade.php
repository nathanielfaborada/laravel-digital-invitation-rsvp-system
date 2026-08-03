

@if ($event->cover_image)
    <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
@endif

<div class="p-8 text-center">
    <p class="text-xs text-gray-400 uppercase tracking-widest mb-2">Invitation</p>
    <h1 class="text-3xl font-bold text-gray-800 mb-4" x-text="title || 'Event Title'"></h1>

    @if ($event->description)
        <p class="text-gray-600 mb-6" x-text="description || 'Description'" ></p>
    @endif

    <div class="border-t border-b border-gray-200 py-4 mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase">Date</p>
                <p  class="font-semibold"
                    x-text="event_date
                        ? new Date(event_date).toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        })
                        : 'Select Date'">
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Time</p>
                <p class="font-semibold"
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
        <div class="mt-4">
            <p class="text-xs text-gray-500 uppercase">Venue</p>
            <p class="font-semibold" x-text="venue || 'Venue Name'"></p>
        </div>
    </div>

    @include('invite.partials.map', compact('event'))

    @include('invite.partials.countdown', compact('event'))

    <p class="text-lg mb-6">
        Dear <span class="font-semibold"></span>, we'd love for you to join us!
    </p>

    <a  class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-indigo-700">
        RSVP Now
    </a>
</div>
