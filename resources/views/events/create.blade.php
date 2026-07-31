<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ template: 'classic', title: '', venue: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- LEFT: FORM -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Event Title</label>
                            <input type="text" name="title" x-model="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Event Date</label>
                                <input type="date" name="event_date" value="{{ old('event_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Event Time</label>
                                <input type="time" name="event_time" value="{{ old('event_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Venue</label>
                            <input type="text" name="venue" x-model="venue" value="{{ old('venue') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invitation Template</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="border-2 rounded-lg p-3 cursor-pointer text-center hover:border-indigo-400"
                                       :class="template === 'classic' ? 'border-indigo-600 bg-indigo-50' : ''">
                                    <input type="radio" name="template" value="classic" x-model="template" checked class="sr-only">
                                    <div class="text-2xl mb-1">🎩</div>
                                    <p class="text-sm font-medium">Classic</p>
                                </label>
                                <label class="border-2 rounded-lg p-3 cursor-pointer text-center hover:border-indigo-400"
                                       :class="template === 'modern' ? 'border-indigo-600 bg-indigo-50' : ''">
                                    <input type="radio" name="template" value="modern" x-model="template" class="sr-only">
                                    <div class="text-2xl mb-1">✨</div>
                                    <p class="text-sm font-medium">Modern</p>
                                </label>
                                <label class="border-2 rounded-lg p-3 cursor-pointer text-center hover:border-indigo-400"
                                       :class="template === 'floral' ? 'border-indigo-600 bg-indigo-50' : ''">
                                    <input type="radio" name="template" value="floral" x-model="template" class="sr-only">
                                    <div class="text-2xl mb-1">🌸</div>
                                    <p class="text-sm font-medium">Floral</p>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Cover Image</label>
                            <input type="file" name="cover_image" class="mt-1 block w-full">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Create Event
                            </button>
                            <a href="{{ route('events.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <!-- RIGHT: LIVE PREVIEW -->
                <div class="lg:sticky lg:top-6 self-start">
                    <p class="text-sm text-gray-500 mb-2 text-center">Live Preview</p>

                    <!-- Classic Preview -->
                    <div x-show="template === 'classic'" class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">You're Invited</p>
                            <h1 class="text-2xl font-bold text-gray-800 mb-3" x-text="title || 'Event Title'"></h1>
                            <div class="border-t border-b border-gray-200 py-3 mb-4 text-sm">
                                <p class="text-gray-500 uppercase text-xs">Venue</p>
                                <p class="font-semibold" x-text="venue || 'Venue Name'"></p>
                            </div>
                            <span class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-semibold">RSVP Now</span>
                        </div>
                    </div>

                    <!-- Modern Preview -->
                    <div x-show="template === 'modern'" class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <div class="p-8">
                            <p class="text-xs text-gray-400 uppercase tracking-widest mb-2">Invitation</p>
                            <h1 class="text-2xl font-light text-gray-900 mb-4" x-text="title || 'Event Title'"></h1>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center text-white text-xs">📍</div>
                                <p class="font-medium text-gray-800 text-sm" x-text="venue || 'Venue Name'"></p>
                            </div>
                            <span class="block text-center bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-medium">RSVP →</span>
                        </div>
                    </div>

                    <!-- Floral Preview -->
                    <div x-show="template === 'floral'" class="bg-gradient-to-b from-pink-50 to-white rounded-3xl shadow-xl overflow-hidden border-4 border-pink-100">
                        <div class="p-8 text-center">
                            <div class="text-2xl mb-2">🌸 ✿ 🌸</div>
                            <p class="text-sm text-rose-400 uppercase tracking-wide mb-2 font-medium">You're Cordially Invited</p>
                            <h1 class="text-xl font-serif text-rose-900 mb-3" x-text="title || 'Event Title'"></h1>
                            <div class="bg-white/60 rounded-2xl border border-pink-200 py-3 px-4 mb-4 text-sm">
                                <p class="text-xs text-rose-400 uppercase">Venue</p>
                                <p class="font-semibold text-rose-900" x-text="venue || 'Venue Name'"></p>
                            </div>
                            <span class="inline-block bg-rose-400 text-white px-6 py-2 rounded-full text-sm font-semibold">RSVP with Love 💌</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>