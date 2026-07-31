<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Events') }}
            </h2>
            <a href="{{ route('events.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                + Create Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($events->isEmpty())
                    <p class="text-gray-500">You haven't created any events yet.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($events as $event)
                            <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                @if ($event->cover_image)
                                    <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-40 object-cover rounded-md mb-3">
                                @endif
                                <h3 class="text-lg font-semibold">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}</p>
                                <p class="text-sm text-gray-500 mb-3">{{ $event->venue }}</p>

                                <div class="flex gap-2">
                                    <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                                    <a href="{{ route('events.edit', $event) }}" class="text-yellow-600 hover:underline text-sm">Edit</a>
                                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>