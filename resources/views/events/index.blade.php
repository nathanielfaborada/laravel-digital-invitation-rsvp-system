<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Events') }}
            </h2>
            <div class="flex items-center">
                <!-- Mobile View: Perfect Circle Icon Button -->
                <a href="{{ route('events.create') }}" 
                   title="Create Event" 
                   class="md:hidden w-9 h-9 flex items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </a>
                
                <!-- Desktop View: Rounded Button with Text -->
                <a href="{{ route('events.create') }}" 
                   class="hidden md:inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-medium">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Create Event</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        activeTab: 'active',
        search: '',
        typeFilter: '',
        dateFilter: 'all',
        customDate: '',
        events: @js($events),
        pollTimer: null,

        init() {
            this.startPolling();
            window.addEventListener('beforeunload', () => this.stopPolling());
        },

        startPolling() {
            this.pollTimer = setInterval(() => {
                this.fetchStats();
            }, 4000);
        },

        stopPolling() {
            if (this.pollTimer) {
                clearInterval(this.pollTimer);
                this.pollTimer = null;
            }
        },

        async fetchStats() {
            try {
                const response = await fetch('{{ route('dashboard.stats') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data && data.events) {
                        this.events = data.events;
                    }
                }
            } catch (e) {
                // Silently ignore background polling errors
            }
        },

        isEventPast(eventDate, eventTime) {
            if (!eventDate) return false;
            let timeStr = eventTime || '23:59:59';
            if (timeStr.length === 5) timeStr += ':00';
            const target = new Date(`${eventDate}T${timeStr}`).getTime();
            if (isNaN(target)) {
                return new Date(eventDate).getTime() < new Date().getTime();
            }
            return target < new Date().getTime();
        },

        matchesEvent(title, venue, description, template, eventDate, eventTime) {
            const past = this.isEventPast(eventDate, eventTime);

            if (this.activeTab === 'active' && past) return false;
            if (this.activeTab === 'archived' && !past) return false;

            if (this.search.trim() !== '') {
                const q = this.search.toLowerCase().trim();
                const matchTitle = title ? title.toLowerCase().includes(q) : false;
                const matchVenue = venue ? venue.toLowerCase().includes(q) : false;
                const matchDesc = description ? description.toLowerCase().includes(q) : false;
                if (!matchTitle && !matchVenue && !matchDesc) return false;
            }

            if (this.typeFilter !== '') {
                const t = this.typeFilter.toLowerCase();
                const matchTemplate = template ? template.toLowerCase() === t : false;
                const matchTitleCat = title ? title.toLowerCase().includes(t) : false;
                const matchDescCat = description ? description.toLowerCase().includes(t) : false;
                if (!matchTemplate && !matchTitleCat && !matchDescCat) return false;
            }

            if (this.dateFilter === 'this_week') {
                if (!eventDate) return false;
                let cleanDate = eventDate;
                if (typeof cleanDate === 'string' && cleanDate.includes('T')) {
                    cleanDate = cleanDate.split('T')[0];
                }
                const d = new Date(cleanDate + 'T00:00:00');
                const now = new Date();
                const dayOfWeek = now.getDay();
                const distanceToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
                const startOfWeek = new Date(now);
                startOfWeek.setDate(now.getDate() + distanceToMonday);
                startOfWeek.setHours(0, 0, 0, 0);

                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);
                endOfWeek.setHours(23, 59, 59, 999);

                if (d < startOfWeek || d > endOfWeek) return false;
            } else if (this.dateFilter === 'this_month') {
                if (!eventDate) return false;
                let cleanDate = eventDate;
                if (typeof cleanDate === 'string' && cleanDate.includes('T')) {
                    cleanDate = cleanDate.split('T')[0];
                }
                const d = new Date(cleanDate + 'T00:00:00');
                const now = new Date();
                if (d.getFullYear() !== now.getFullYear() || d.getMonth() !== now.getMonth()) {
                    return false;
                }
            } else if (this.dateFilter === 'custom' && this.customDate) {
                let cleanDate = eventDate;
                if (typeof cleanDate === 'string' && cleanDate.includes('T')) {
                    cleanDate = cleanDate.split('T')[0];
                }
                if (cleanDate !== this.customDate) return false;
            }

            return true;
        },

        get activeCount() {
            return this.events.filter(e => !this.isEventPast(e.event_date, e.event_time)).length;
        },

        get archivedCount() {
            return this.events.filter(e => this.isEventPast(e.event_date, e.event_time)).length;
        },

        get visibleCount() {
            return this.events.filter(e => this.matchesEvent(e.title, e.venue, e.description, e.template, e.event_date, e.event_time)).length;
        },

        get hasActiveFilters() {
            return this.search.trim() !== '' || this.typeFilter !== '' || this.dateFilter !== 'all' || this.customDate !== '';
        },

        get currentTabTotal() {
            return this.activeTab === 'active' ? this.activeCount : this.archivedCount;
        },

        get filterStatusText() {
            if (this.visibleCount === 0) {
                return 'No events match your search';
            }
            return `Showing ${this.visibleCount} of ${this.currentTabTotal} events`;
        },

        clearFilters() {
            this.search = '';
            this.typeFilter = '';
            this.dateFilter = 'all';
            this.customDate = '';
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (!$events->isEmpty())
                <!-- TAB SWITCHER HEADER (Active vs Archived) -->
                <div class="mb-4 flex items-center justify-between gap-3">
                    <div class="inline-flex p-1 bg-white rounded-xl border border-gray-200 shadow-2xs">
                        <button 
                            @click="activeTab = 'active'"
                            :class="activeTab === 'active' 
                                ? 'bg-indigo-600 text-white shadow-xs font-semibold' 
                                : 'text-gray-600 hover:text-gray-900 font-medium hover:bg-gray-50'"
                            class="px-3.5 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm transition-all duration-150 flex items-center gap-2"
                        >
                            <span>Active Events</span>
                            <span 
                                :class="activeTab === 'active' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'"
                                class="px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold transition-colors"
                                x-text="activeCount"
                            ></span>
                        </button>

                        <button 
                            @click="activeTab = 'archived'"
                            :class="activeTab === 'archived' 
                                ? 'bg-slate-800 text-white shadow-xs font-semibold' 
                                : 'text-gray-600 hover:text-gray-900 font-medium hover:bg-gray-50'"
                            class="px-3.5 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm transition-all duration-150 flex items-center gap-2"
                        >
                            <span>Archived Events</span>
                            <span 
                                :class="activeTab === 'archived' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700'"
                                class="px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold transition-colors"
                                x-text="archivedCount"
                            ></span>
                        </button>
                    </div>
                </div>

                <!-- SEARCH & FILTER TOOLBAR -->
                <div class="mb-4 sm:mb-6 bg-white p-3.5 sm:p-4 rounded-xl shadow-sm border border-gray-100 space-y-3">
                    <!-- MAIN INPUTS LAYOUT -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-3 items-center">
                        
                        <!-- 1. Search Bar (Full width on mobile) -->
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                x-model="search" 
                                placeholder="Search title or location..." 
                                class="w-full pl-8 pr-7 py-1.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors"
                            >
                            <button 
                                x-show="search.length > 0" 
                                x-cloak 
                                @click="search = ''" 
                                type="button" 
                                class="absolute inset-y-0 right-0 pr-2 flex items-center text-gray-400 hover:text-gray-600"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- MOBILE DROPDOWNS WRAPPER: 2-column grid on mobile (< sm), inline grid cells on desktop (>= sm) -->
                        <div class="grid grid-cols-2 gap-2 sm:contents">
                            
                            <!-- 2. Invitation Type Filter -->
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <select 
                                    x-model="typeFilter" 
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                                    class="appearance-none w-full pl-8 pr-9 py-1.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors cursor-pointer truncate"
                                >
                                    <option value="">All Types</option>
                                    <optgroup label="Templates">
                                        <option value="classic">Classic</option>
                                        <option value="modern">Modern</option>
                                        <option value="floral">Floral</option>
                                    </optgroup>
                                    <optgroup label="Categories">
                                        <option value="birthday">Birthday</option>
                                        <option value="gala">Gala</option>
                                        <option value="graduation">Graduation</option>
                                        <option value="wedding">Wedding</option>
                                        <option value="party">Party</option>
                                    </optgroup>
                                </select>
                                <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 flex items-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- 3. Date Filter -->
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <select 
                                    x-model="dateFilter" 
                                    style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                                    class="appearance-none w-full pl-8 pr-9 py-1.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors cursor-pointer truncate"
                                >
                                    <option value="all">All Dates</option>
                                    <option value="this_week">This Week</option>
                                    <option value="this_month">This Month</option>
                                    <option value="custom">Specific Date...</option>
                                </select>
                                <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 flex items-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- SPECIFIC DATE PICKER INPUT (when custom date selected) -->
                    <div x-show="dateFilter === 'custom'" x-cloak class="pt-1">
                        <div class="relative max-w-xs">
                            <input 
                                type="date" 
                                x-model="customDate" 
                                class="w-full px-2.5 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white"
                            >
                        </div>
                    </div>

                    <!-- DYNAMIC SEARCH/FILTER RESULT COUNTER (Hidden in default state, shown ONLY when filters active) -->
                    <div x-show="hasActiveFilters" x-cloak class="pt-2 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-700" x-text="filterStatusText"></span>
                            <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md font-medium text-[11px]">
                                Filtered
                            </span>
                        </div>

                        <button 
                            @click="clearFilters()" 
                            type="button" 
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors shrink-0"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset Filters</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- EVENTS CONTAINER -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-4 sm:p-6 border border-gray-100">
                @if ($events->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-4xl mb-3">📅</div>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">No Events Created Yet</h3>
                        <p class="text-sm text-gray-500 mb-4">Start by creating your first digital invitation.</p>
                        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-medium">
                            + Create Event
                        </a>
                    </div>
                @else
                    <!-- EVENTS GRID -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                        @foreach ($events as $event)
                            @php
                                $isPastEvent = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'))->isPast();
                            @endphp
                            <div 
                                x-show="matchesEvent(@js($event->title), @js($event->venue), @js($event->description ?? ''), @js($event->template ?? ''), @js($event->event_date), @js($event->event_time ?? ''))"
                                class="bg-white border border-gray-200 rounded-xl shadow-2xs hover:shadow-md transition-shadow overflow-hidden flex flex-col group"
                            >
                                <!-- ROW 1: TOP BADGES BAR (No Action Buttons Here = Zero Collision) -->
                                <div class="px-3.5 py-2 bg-white border-b border-gray-100 flex items-center justify-between gap-2 shrink-0">
                                    <!-- Category / Template Badge -->
                                    <div>
                                        @if ($event->template)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                {{ ucfirst($event->template) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200">
                                                General
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Status Badge -->
                                    <div>
                                        @if ($isPastEvent)
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] sm:text-[11px] font-semibold uppercase tracking-wider rounded-full bg-slate-200 text-slate-700 border border-slate-300/60 shadow-2xs">
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] sm:text-[11px] font-semibold uppercase tracking-wider rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Upcoming
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- ROW 2: COVER IMAGE FRAME -->
                                <div class="relative h-36 w-full bg-slate-100 overflow-hidden shrink-0">
                                    @if ($event->cover_image)
                                        <img src="{{ $event->cover_image }}" alt="{{ $event->title }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <img src="https://res.cloudinary.com/wyofiygs/image/upload/v1786343694/Untitled_design_6_it913f.png" alt="" class="w-full h-full object-cover object-[center_25%] group-hover:scale-105 transition-transform duration-300">
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent"></div>
                                    <div class="absolute bottom-2.5 left-3 right-3 text-white">
                                        <h3 class="text-sm sm:text-base font-bold text-white leading-tight drop-shadow-sm line-clamp-1 break-words">
                                            {{ $event->title }}
                                        </h3>
                                    </div>
                                </div>

                                <!-- ROW 3: CARD CONTENT DETAILS -->
                                <div class="p-3.5 bg-white flex flex-col justify-between flex-1 gap-2">
                                    <div class="space-y-1">
                                        <!-- Date & Time -->
                                        <div class="flex items-center gap-1.5 text-xs text-gray-700 font-medium">
                                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="truncate">
                                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                                            </span>
                                        </div>

                                        <!-- Venue -->
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600 min-w-0">
                                            <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate font-medium text-gray-700" title="{{ $event->venue }}">{{ $event->venue }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- ROW 4: DEDICATED ACTION BAR (At Bottom of Card) -->
                                <div class="px-3.5 py-2.5 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between text-xs font-semibold shrink-0">
                                    <!-- View Button -->
                                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition-colors">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>View Details</span>
                                    </a>

                                    <!-- Edit & Delete Buttons -->
                                    <div class="flex items-center gap-3">
                                        @if ($isPastEvent)
                                            <span title="Past events cannot be edited" class="inline-flex items-center gap-1 text-gray-400 cursor-not-allowed opacity-60">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                <span>Edit</span>
                                            </span>
                                        @else
                                            <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition-colors">
                                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                <span>Edit</span>
                                            </a>
                                        @endif

                                        <button 
                                            type="button" 
                                            @click="$dispatch('open-delete-modal', {
                                                title: 'Delete Event',
                                                targetName: @js($event->title),
                                                actionUrl: @js(route('events.destroy', $event))
                                            })" 
                                            class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 transition-colors cursor-pointer"
                                        >
                                            <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- NO MATCHING RESULTS EMPTY STATE -->
                    <div x-show="visibleCount === 0" x-cloak class="text-center py-12">
                        <div class="text-3xl mb-2" x-text="activeTab === 'archived' ? '📁' : '🔍'"></div>
                        <h3 class="text-sm font-semibold text-gray-800 mb-1" x-text="activeTab === 'archived' && archivedCount === 0 ? 'No archived events yet' : 'No events match your criteria'"></h3>
                        <p class="text-xs text-gray-500 mb-4" x-text="activeTab === 'archived' && archivedCount === 0 ? 'Completed events will automatically appear here.' : 'Try adjusting your search terms or clearing your selected filters.'"></p>
                        <button x-show="hasActiveFilters" @click="clearFilters()" type="button" class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-3.5 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors text-xs font-medium">
                            Reset Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- REUSABLE DELETE CONFIRMATION MODAL -->
    <x-delete-confirm-modal />
</x-app-layout>