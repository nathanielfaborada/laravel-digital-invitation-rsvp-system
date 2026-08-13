<x-app-layout>

    @php
        $isPastEvent = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'))->isPast();
    @endphp

    <div 
        @open-preview-modal.window="showPreviewModal = true"
        @open-guest-modal.window="activeGuest = $event.detail; showGuestDetailsModal = true"
        x-data="{
            template: @js(old('template', $event->template)),
            title: @js(old('title', $event->title)),
            description: @js(old('description', $event->description)),
            event_date: @js(old('event_date', $event->event_date)),
            event_time: @js(old('event_time', $event->event_time)),
            venue: @js(old('venue', $event->venue)),
            showAddGuestModal: @js(old('name') !== null || $errors->any()),
            showPreviewModal: false,
            showGuestDetailsModal: false,
            activeGuest: null,
            copied: false,
            coverImagePreview: @js($event->cover_image)
        }"
    >

        <!-- Custom Header Bar -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 sm:gap-3">
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                            {{ __('Event Details') }}
                        </h2>
                        @if ($isPastEvent)
                            <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-0.5 rounded-full font-semibold border border-gray-200 shrink-0">
                                Completed
                            </span>
                        @endif
                    </div>

                    <!-- Clean Responsive Header Toolbar -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Main Primary Action: Add Guest -->
                        @if ($isPastEvent)
                            <button type="button" 
                                     disabled 
                                     title="Cannot add guests to past events"
                                     class="h-9 sm:h-10 text-gray-400 bg-gray-100 px-3.5 sm:px-4 rounded-lg text-xs sm:text-sm font-semibold opacity-60 cursor-not-allowed inline-flex items-center justify-center gap-1.5 whitespace-nowrap shrink-0 border border-gray-200">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span class="whitespace-nowrap">Add Guest Locked</span>
                            </button>
                        @else
                            <button @click="showAddGuestModal = true" 
                                class="h-9 sm:h-10 bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 sm:px-4 rounded-lg text-xs sm:text-sm font-semibold shadow-xs transition-colors inline-flex items-center justify-center gap-1.5 whitespace-nowrap shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span class="whitespace-nowrap">Add Guest</span>
                            </button>
                        @endif

                        <!-- Edit Event Button (Disabled if past event) -->
                        @if ($isPastEvent)
                            <button type="button" 
                                     disabled 
                                     title="Past events cannot be edited"
                                     class="h-9 sm:h-10 inline-flex items-center justify-center gap-1.5 bg-gray-100 text-gray-400 px-3 sm:px-4 rounded-lg text-xs sm:text-sm font-medium opacity-60 cursor-not-allowed whitespace-nowrap shrink-0 border border-gray-200">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span class="hidden sm:inline whitespace-nowrap">Edit Locked</span>
                            </button>
                        @else
                            <a href="{{ route('events.edit', $event) }}" 
                               title="Edit Event"
                               class="h-9 sm:h-10 inline-flex items-center justify-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 sm:px-4 rounded-lg text-xs sm:text-sm font-medium transition-colors whitespace-nowrap shrink-0 border border-gray-200/60">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span class="hidden sm:inline whitespace-nowrap">Edit</span>
                            </a>
                        @endif

                        <!-- Export CSV Icon / Pill Button -->
                        <a href="{{ route('events.guests.export', $event) }}" 
                           title="Export CSV"
                           class="h-9 sm:h-10 inline-flex items-center justify-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 sm:px-4 rounded-lg text-xs sm:text-sm font-medium transition-colors whitespace-nowrap shrink-0 border border-emerald-200/60">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span class="hidden sm:inline whitespace-nowrap">Export</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-3 lg:px-8">

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs sm:text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-xs sm:text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-3 sm:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

                        <!-- LEFT: INVITATION PREVIEW (Hidden on mobile < lg, visible in grid on desktop lg:) -->
                        <div class="hidden lg:block">
                            <!-- Classic Preview -->
                            <div x-show="template === 'classic'">
                                @include('events.preview.classic_preview', compact('event'))
                            </div>

                            <!-- Modern Preview -->
                            <div x-show="template === 'modern'" x-cloak>
                                @include('events.preview.modern_preview', compact('event'))
                            </div>

                            <!-- Floral Preview -->
                            <div x-show="template === 'floral'" x-cloak>
                                @include('events.preview.floral_preview', compact('event'))
                            </div>
                        </div>

                        <!-- RIGHT: GUEST LIST & STATS (Full width on mobile < lg, 2nd column on desktop lg:) -->
                        <div class="min-w-0 w-full">
                            @include('guests.index', compact('event', 'stats'))
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <!-- ADD GUEST MODAL DIALOG POPUP -->
        <div 
            x-show="showAddGuestModal" 
            x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-black/50 backdrop-blur-xs fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
            @keydown.escape.window="showAddGuestModal = false"
        >
            <div 
                @click.away="showAddGuestModal = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-lg"
            >
                @include('guests.create', compact('event'))
            </div>
        </div>

        <!-- INVITATION CARD PREVIEW MODAL DIALOG (Mobile / Popup) -->
        <div 
            x-show="showPreviewModal" 
            x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-black/50 backdrop-blur-xs fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
            @keydown.escape.window="showPreviewModal = false"
        >
            <!-- Modal Window -->
            <div 
                @click.away="showPreviewModal = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white w-full max-w-md sm:max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col my-auto max-h-[90vh]"
            >
                <!-- Modal Header -->
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-base">👁️</span>
                        <h3 class="text-base font-bold text-gray-800">Invitation Preview</h3>
                    </div>
                    
                    <button 
                        @click="showPreviewModal = false" 
                        type="button"
                        class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable preview content) -->
                <div class="p-3 sm:p-5 overflow-y-auto flex-1 bg-gray-50">
                    <!-- Classic Preview -->
                    <div x-show="template === 'classic'">
                        @include('events.preview.classic_preview', compact('event'))
                    </div>

                    <!-- Modern Preview -->
                    <div x-show="template === 'modern'" x-cloak>
                        @include('events.preview.modern_preview', compact('event'))
                    </div>

                    <!-- Floral Preview -->
                    <div x-show="template === 'floral'" x-cloak>
                        @include('events.preview.floral_preview', compact('event'))
                    </div>
                </div>
            </div>
        </div>

        <!-- GUEST DETAILS INLINE POPUP MODAL -->
        <div 
            x-show="showGuestDetailsModal" 
            x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="bg-black/50 backdrop-blur-xs fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
            @keydown.escape.window="showGuestDetailsModal = false; isEditing = false"
            x-data="{ 
                isEditing: false,
                editForm: {
                    name: '',
                    email: '',
                    phone: '',
                    max_companions: 0,
                    status: 'pending'
                },
                startEditing() {
                    if (!activeGuest) return;
                    const rsvpStatus = activeGuest.rsvp ? activeGuest.rsvp.status : (activeGuest.status || 'pending');
                    this.editForm = {
                        name: activeGuest.name || '',
                        email: activeGuest.email || '',
                        phone: activeGuest.phone || '',
                        max_companions: activeGuest.max_companions ?? 0,
                        status: ['attending', 'not_attending', 'pending'].includes(rsvpStatus) ? rsvpStatus : 'pending'
                    };
                    this.isEditing = true;
                },
                cancelEditing() {
                    this.isEditing = false;
                }
            }"
            @open-guest-modal.window="isEditing = false"
        >
            <div 
                @click.away="showGuestDetailsModal = false; isEditing = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white w-full max-w-md sm:max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col my-auto max-h-[90vh]"
            >
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-base" x-text="isEditing ? '✏️' : '👤'"></span>
                        <h3 class="text-base font-bold text-gray-800" x-text="isEditing ? 'Edit Guest' : 'Guest Details'"></h3>
                    </div>
                    
                    <button 
                        @click="showGuestDetailsModal = false; isEditing = false" 
                        type="button"
                        class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- EDIT MODE FORM -->
                <form 
                    x-show="isEditing" 
                    x-cloak 
                    :action="'/guests/' + activeGuest?.id" 
                    method="POST" 
                    class="flex flex-col flex-1 min-h-0"
                >
                    @csrf
                    @method('PUT')

                    <!-- Modal Body (Edit Mode) -->
                    <div class="p-5 overflow-y-auto flex-1 bg-white space-y-4 text-xs sm:text-sm">
                        <!-- Name -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Guest Name <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="editForm.name" 
                                required 
                                placeholder="Full Name"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Email Address <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                x-model="editForm.email" 
                                placeholder="guest@example.com"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors"
                            >
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Phone Number <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input 
                                type="text" 
                                name="phone" 
                                x-model="editForm.phone" 
                                placeholder="+1 234 567 890"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors"
                            >
                        </div>

                        <!-- Max Companions & Status (Grid) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Max Companions -->
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Max Companions</label>
                                <input 
                                    type="number" 
                                    name="max_companions" 
                                    x-model="editForm.max_companions" 
                                    min="0" 
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs sm:text-sm text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-colors"
                                >
                            </div>

                            <!-- Read-only RSVP Status -->
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">RSVP Status <span class="text-gray-400 font-normal">(Read-only)</span></label>
                                <div class="py-2">
                                    <template x-if="activeGuest && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) === 'attending'">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 whitespace-nowrap">
                                            Attending
                                        </span>
                                    </template>
                                    <template x-if="activeGuest && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) === 'not_attending'">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 whitespace-nowrap">
                                            Not Attending
                                        </span>
                                    </template>
                                    <template x-if="activeGuest && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) !== 'attending' && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) !== 'not_attending'">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 whitespace-nowrap">
                                            Pending
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer Actions (Edit Mode: Save & Cancel) -->
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                        <button 
                            @click="cancelEditing()" 
                            type="button" 
                            class="h-9 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="h-9 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors cursor-pointer inline-flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save
                        </button>
                    </div>
                </form>

                <!-- VIEW MODE CONTAINER -->
                <div x-show="!isEditing" class="flex flex-col flex-1 min-h-0">
                    <!-- Modal Body (View Mode) -->
                    <div class="p-5 overflow-y-auto flex-1 bg-white space-y-4">
                        <!-- Guest Name & RSVP Status Badge -->
                        <div class="flex justify-between items-center gap-3 border-b border-gray-100 pb-3">
                            <h2 class="text-lg font-bold text-gray-900 truncate" x-text="activeGuest?.name"></h2>
                            <div>
                                <template x-if="activeGuest && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) === 'attending'">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200 whitespace-nowrap">
                                        Attending
                                    </span>
                                </template>
                                <template x-if="activeGuest && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) === 'not_attending'">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 whitespace-nowrap">
                                        Not Attending
                                    </span>
                                </template>
                                <template x-if="activeGuest && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) !== 'attending' && (activeGuest.rsvp ? activeGuest.rsvp.status : activeGuest.status) !== 'not_attending'">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 whitespace-nowrap">
                                        Pending
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- 2-Column Metadata Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs bg-gray-50/80 p-3.5 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Email</p>
                                <p class="font-medium text-gray-800 truncate break-all" x-text="activeGuest?.email || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Phone</p>
                                <p class="font-medium text-gray-800 truncate" x-text="activeGuest?.phone || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Max Companions</p>
                                <p class="font-medium text-gray-800" x-text="activeGuest?.max_companions ?? 0"></p>
                            </div>
                            <template x-if="activeGuest?.rsvp">
                                <div>
                                    <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Bringing</p>
                                    <p class="font-medium text-gray-800" x-text="activeGuest?.rsvp?.companions_count ?? 0"></p>
                                </div>
                            </template>
                            <template x-if="activeGuest?.rsvp?.message">
                                <div class="col-span-2 pt-1 border-t border-gray-200/60">
                                    <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">RSVP Message</p>
                                    <p class="font-normal text-gray-700 italic mt-0.5" x-text="activeGuest?.rsvp?.message"></p>
                                </div>
                            </template>
                        </div>

                        <!-- QR Code & Invite Link Box -->
                        <div class="bg-gray-50/80 rounded-xl border border-gray-100 p-3.5 flex items-start gap-3.5">
                            <template x-if="activeGuest?.unique_code">
                                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=' + encodeURIComponent(window.location.origin + '/invite/' + activeGuest.unique_code)" alt="QR Code" class="w-20 h-20 rounded-lg border border-gray-200 p-1 shrink-0 bg-white shadow-2xs">
                            </template>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider mb-1">Invite Link</p>
                                <div class="flex items-center gap-2">
                                    <code class="text-xs bg-white text-gray-800 p-2 rounded-md truncate font-mono border border-gray-200 select-all flex-1 min-w-0" x-text="window.location.origin + '/invite/' + (activeGuest?.unique_code || '')"></code>
                                    <button 
                                        @click="if (activeGuest?.unique_code) { navigator.clipboard.writeText(window.location.origin + '/invite/' + activeGuest.unique_code); copied = true; setTimeout(() => copied = false, 2000); }" 
                                        type="button" 
                                        class="shrink-0 h-8 px-2.5 inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-md border border-indigo-100 transition-colors cursor-pointer"
                                    >
                                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z"/>
                                        </svg>
                                        <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer Actions (View Mode) -->
                    <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex flex-col gap-2.5 shrink-0">
                        <!-- Row 1: Primary "Send Invite" button (Full Width) -->
                        <template x-if="activeGuest?.email">
                            @if ($isPastEvent)
                                <button type="button" disabled title="Past events cannot send invites" class="w-full h-9 bg-gray-200 text-gray-400 rounded-lg text-xs font-semibold opacity-60 cursor-not-allowed inline-flex items-center justify-center gap-1.5 border border-gray-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Send Invite
                                </button>
                            @else
                                <form :action="'/guests/' + activeGuest?.id + '/send-invite'" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full h-9 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors inline-flex items-center justify-center gap-1.5 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Send Invite
                                    </button>
                                </form>
                            @endif
                        </template>
                        <template x-if="!activeGuest?.email">
                            <button type="button" disabled title="Guest has no email address" class="w-full h-9 bg-gray-100 text-gray-400 rounded-lg text-xs font-semibold opacity-60 cursor-not-allowed inline-flex items-center justify-center gap-1.5 border border-gray-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Send Invite (No Email)
                            </button>
                        </template>

                        <!-- Row 2: Secondary Action Row (Equal width 2-column grid) -->
                        <div class="grid grid-cols-2 gap-2 w-full">
                            <!-- Edit (Amber) -->
                            @if ($isPastEvent)
                                <span title="Past events cannot be edited" class="h-9 flex items-center justify-center text-xs text-gray-400 opacity-60 bg-gray-200 rounded-lg font-semibold cursor-not-allowed border border-gray-200">Edit Locked</span>
                            @else
                                <button type="button" @click="startEditing()" class="h-9 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center justify-center gap-1 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                            @endif

                            <!-- Delete (Red) -->
                            @if ($isPastEvent)
                                <span title="Past events cannot be deleted" class="h-9 flex items-center justify-center text-xs text-gray-400 opacity-60 bg-gray-200 rounded-lg font-semibold cursor-not-allowed border border-gray-200">Delete Locked</span>
                            @else
                                <button 
                                    type="button" 
                                    @click="showGuestDetailsModal = false; $dispatch('open-delete-modal', {
                                        title: 'Delete Guest',
                                        targetName: activeGuest?.name || '',
                                        actionUrl: '/guests/' + activeGuest?.id
                                    })" 
                                    class="w-full h-9 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition-colors inline-flex items-center justify-center gap-1 cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- REUSABLE DELETE CONFIRMATION MODAL -->
    <x-delete-confirm-modal />
</x-app-layout>