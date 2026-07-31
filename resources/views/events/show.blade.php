<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($event->cover_image)
                    <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover rounded-md mb-4">
                @endif

                <h1 class="text-2xl font-bold mb-2">{{ $event->title }}</h1>

                <p class="text-gray-600 mb-4">{{ $event->description }}</p>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <span class="text-sm text-gray-500">Date</span>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Time</span>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="text-sm text-gray-500">Venue</span>
                    <p class="font-medium">{{ $event->venue }}</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('events.guests.index', $event) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Manage Guests
                    </a>
                    <a href="{{ route('events.edit', $event) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                        Edit Event
                    </a>
                    <a href="{{ route('events.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>