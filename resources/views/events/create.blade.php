<x-app-layout>
    <!-- Prevent Alpine elements from flickering on page reloads/submit errors -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Root Alpine Wrapper -->
    <div x-data="{
        template: @js(old('template', 'classic')),
        title: @js(old('title', '')),
        description: @js(old('description', '')),
        event_date: @js(old('event_date', '')),
        event_time: @js(old('event_time', '')),
        venue: @js(old('venue', '')),
        coverImagePreview: null,
        coverFileName: '',
        showMobilePreview: false,

        previewCoverImage(event) {
            const file = event.target.files[0];
            if (!file) { 
                this.coverImagePreview = null; 
                this.coverFileName = '';
                return; 
            }
            
            this.coverFileName = file.name;

            const reader = new FileReader();
            reader.onload = (e) => { 
                this.coverImagePreview = e.target.result; 
            };
            reader.readAsDataURL(file);
        }
    }">

        <!-- HEADER -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                        {{ __('Create New Event') }}
                    </h2>

                    <!-- Mobile Preview Button -->
                    <button type="button" 
                        @click="showMobilePreview = true"
                        class="lg:hidden inline-flex items-center gap-1.5 bg-indigo-600 text-white px-2.5 py-1.5 rounded-md hover:bg-indigo-700 transition-colors text-xs font-medium">
                        <img src="https://res.cloudinary.com/wyofiygs/image/upload/v1786287350/view_jhdunn.png" alt="Preview" class="w-3.5 h-3.5">
                        <span>Preview</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT AREA -->
        <div class="py-2 sm:py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- MAIN CARD -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 items-start">

                        <!-- LEFT: FORM -->
                        <div>
                            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col justify-between min-h-[520px] sm:min-h-0">
                                @csrf

                                @if ($errors->any())
                                    <div class="mb-3 bg-rose-50 border border-rose-200 text-rose-700 px-3.5 py-2.5 rounded-xl text-xs">
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Top Form Fields Wrapper -->
                                <div class="space-y-2 sm:space-y-4">
                                    <!-- Cover Image -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Cover Image</label>
                                        <div class="relative">
                                            <input type="file" name="cover_image" id="cover_image" @change="previewCoverImage($event)" class="sr-only">
                                            <label for="cover_image" class="flex items-center justify-between border-2 border-dashed border-gray-300 rounded-lg p-2 cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/50 transition-all">
                                                <div class="flex items-center gap-2 overflow-hidden">
                                                    <div class="p-1.5 bg-indigo-100 rounded-md text-indigo-600 shrink-0">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-xs text-gray-600 truncate max-w-[180px] sm:max-w-[220px]" 
                                                        x-text="coverFileName || 'Choose cover photo...'"></span>
                                                </div>
                                                <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded shrink-0">Browse</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Invitation Template -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Invitation Template</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <label class="border-2 rounded-lg p-1.5 sm:p-3 cursor-pointer text-center hover:border-indigo-400 transition-colors"
                                                :class="template === 'classic' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
                                                <input type="radio" name="template" value="classic" x-model="template" class="sr-only">
                                                <div class="text-lg sm:text-2xl mb-0.5">🎩</div>
                                                <p class="text-xs font-medium">Classic</p>
                                            </label>
                                            <label class="border-2 rounded-lg p-1.5 sm:p-3 cursor-pointer text-center hover:border-indigo-400 transition-colors"
                                                :class="template === 'modern' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
                                                <input type="radio" name="template" value="modern" x-model="template" class="sr-only">
                                                <div class="text-lg sm:text-2xl mb-0.5">✨</div>
                                                <p class="text-xs font-medium">Modern</p>
                                            </label>
                                            <label class="border-2 rounded-lg p-1.5 sm:p-3 cursor-pointer text-center hover:border-indigo-400 transition-colors"
                                                :class="template === 'floral' ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200'">
                                                <input type="radio" name="template" value="floral" x-model="template" class="sr-only">
                                                <div class="text-lg sm:text-2xl mb-0.5">🌸</div>
                                                <p class="text-xs font-medium">Floral</p>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Event Title -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label class="block text-xs font-medium text-gray-700">Event Title</label>
                                            <span 
                                                class="text-[11px] font-medium transition-colors"
                                                :class="(50 - (title || '').length) === 0 ? 'text-red-500 font-semibold' : ((50 - (title || '').length) <= 5 ? 'text-amber-500 font-semibold' : 'text-gray-400')"
                                                x-text="(title || '').length + '/50 characters'"
                                            ></span>
                                        </div>
                                        <input type="text" name="title" x-model="title" maxlength="50" class="block w-full text-xs sm:text-sm rounded-md border-gray-300 shadow-sm py-1">
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <div class="flex justify-between items-center mb-1">
                                            <label class="block text-xs font-medium text-gray-700">Description</label>
                                            <span 
                                                class="text-[11px] font-medium transition-colors"
                                                :class="(300 - (description || '').length) === 0 ? 'text-red-500 font-semibold' : ((300 - (description || '').length) <= 30 ? 'text-amber-500 font-semibold' : 'text-gray-400')"
                                                x-text="(description || '').length + '/300 characters'"
                                            ></span>
                                        </div>
                                        <textarea name="description" x-model="description" maxlength="300" rows="2" class="block w-full text-xs sm:text-sm rounded-md border-gray-300 shadow-sm py-1"></textarea>
                                    </div>

                                    <!-- Date & Time -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Event Date</label>
                                            <input type="date" name="event_date" x-model="event_date" min="{{ now()->format('Y-m-d') }}" class="mt-0.5 block w-full text-xs sm:text-sm rounded-md border-gray-300 shadow-sm py-1 px-1.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Event Time</label>
                                            <input type="time" name="event_time" x-model="event_time" class="mt-0.5 block w-full text-xs sm:text-sm rounded-md border-gray-300 shadow-sm py-1 px-1.5">
                                        </div>
                                    </div>

                                    <!-- Venue -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700">Venue</label>
                                        <input type="text" name="venue" x-model="venue" class="mt-0.5 block w-full text-xs sm:text-sm rounded-md border-gray-300 shadow-sm py-1">
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 pt-4 mt-6">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-xs sm:text-sm font-medium transition-colors shadow-sm">
                                        Create Event
                                    </button>
                                    <a href="{{ route('events.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-xs sm:text-sm font-medium transition-colors">
                                        Cancel
                                    </a>
                                </div>

                            </form>
                        </div>

                        <!-- RIGHT: DESKTOP LIVE PREVIEW -->
                        <div class="hidden lg:block lg:sticky lg:top-6 self-start">
                            <p class="text-sm font-medium text-gray-500 mb-2 text-center">Live Preview</p>

                            <div x-show="template === 'classic'" class="bg-white rounded-xl shadow-lg overflow-hidden">
                                @include('events.preview.classic_preview')
                            </div>

                            <div x-show="template === 'modern'" x-cloak class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                                @include('events.preview.modern_preview')
                            </div>

                            <div x-show="template === 'floral'" x-cloak class="bg-gradient-to-b from-pink-50 to-white rounded-3xl shadow-xl overflow-hidden border-4 border-pink-100">
                                @include('events.preview.floral_preview')
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- MOBILE POP-UP MODAL -->
        <div 
            x-show="showMobilePreview" 
            x-cloak
            class="lg:hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
            @click.self="showMobilePreview = false"
        >
            <div class="relative w-full max-w-lg max-h-[85vh] overflow-y-auto bg-white rounded-2xl p-4 shadow-2xl">
                <!-- Mobile Close Bar -->
                <div class="flex justify-between items-center mb-3 border-b pb-2">
                    <span class="font-semibold text-gray-700">Live Preview</span>
                    <button @click="showMobilePreview = false" type="button" class="text-gray-500 hover:text-gray-800 text-lg font-bold px-2 py-1">
                        ✕
                    </button>
                </div>

                <div x-show="template === 'classic'" class="bg-white rounded-xl overflow-hidden">
                    @include('events.preview.classic_preview')
                </div>

                <div x-show="template === 'modern'" class="bg-white rounded-2xl overflow-hidden">
                    @include('events.preview.modern_preview')
                </div>

                <div x-show="template === 'floral'" class="bg-gradient-to-b from-pink-50 to-white rounded-3xl overflow-hidden border-4 border-pink-100">
                    @include('events.preview.floral_preview')
                </div>
            </div>
        </div>

    </div>
</x-app-layout>