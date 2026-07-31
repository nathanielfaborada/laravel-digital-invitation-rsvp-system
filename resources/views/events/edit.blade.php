<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($event->cover_image)
                    <div class="mb-4">
                        <span class="text-sm text-gray-500">Current Cover Image</span>
                        <img src="{{ Storage::url($event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-40 object-cover rounded-md mt-1">
                    </div>
                @endif

                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Event Title</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Date</label>
                            <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Time</label>
                            <input type="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Venue</label>
                        <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Invitation Template</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="border-2 rounded-lg p-3 cursor-pointer text-center hover:border-indigo-400 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="template" value="classic" {{ old('template', $event->template) === 'classic' ? 'checked' : '' }} class="sr-only">
                                <div class="text-2xl mb-1">🎩</div>
                                <p class="text-sm font-medium">Classic</p>
                            </label>
                            <label class="border-2 rounded-lg p-3 cursor-pointer text-center hover:border-indigo-400 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="template" value="modern" {{ old('template', $event->template) === 'modern' ? 'checked' : '' }} class="sr-only">
                                <div class="text-2xl mb-1">✨</div>
                                <p class="text-sm font-medium">Modern</p>
                            </label>
                            <label class="border-2 rounded-lg p-3 cursor-pointer text-center hover:border-indigo-400 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="template" value="floral" {{ old('template', $event->template) === 'floral' ? 'checked' : '' }} class="sr-only">
                                <div class="text-2xl mb-1">🌸</div>
                                <p class="text-sm font-medium">Floral</p>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cover Image (leave blank to keep current)</label>
                        <input type="file" name="cover_image" class="mt-1 block w-full">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Update Event
                        </button>
                        <a href="{{ route('events.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>