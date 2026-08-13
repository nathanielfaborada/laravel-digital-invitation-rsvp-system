@if ($event->cover_image)
    <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
@endif

@if ($event->cover_image)
    <div class="relative">
        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-72 object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
    </div>
@endif
 

<a href="{{ route('events.guests.index', $event) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Manage Guests
                    </a>
                    <a href="{{ route('events.edit', $event) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                        Edit Event
                    </a>
                    <a href="{{ route('events.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                        Back to List
                    </a>