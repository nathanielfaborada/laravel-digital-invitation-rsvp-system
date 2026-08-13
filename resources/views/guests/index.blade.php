<div class="w-full" x-data="{
    search: '',
    statusFilter: 'all',
    currentPage: 1,
    perPage: 10,
    guests: @js($guests),
    selected: [],

    get isAllSelected() {
        if (this.paginatedGuests.length === 0) return false;
        return this.paginatedGuests.every(g => this.selected.includes(g.id));
    },

    toggleSelectAll() {
        const pageIds = this.paginatedGuests.map(g => g.id);
        if (this.isAllSelected) {
            this.selected = this.selected.filter(id => !pageIds.includes(id));
        } else {
            this.selected = Array.from(new Set([...this.selected, ...pageIds]));
        }
    },

    clearSelection() {
        this.selected = [];
    },

    getStatus(guest) {
        if (guest.rsvp && guest.rsvp.status) {
            return guest.rsvp.status;
        }
        return guest.status || 'pending';
    },

    get filteredGuests() {
        return this.guests.filter(guest => {
            const q = this.search.toLowerCase().trim();
            const matchesSearch = q === '' || 
                (guest.name && guest.name.toLowerCase().includes(q)) || 
                (guest.email && guest.email.toLowerCase().includes(q)) || 
                (guest.phone && guest.phone.toLowerCase().includes(q));

            const status = this.getStatus(guest);
            const matchesStatus = this.statusFilter === 'all' || status === this.statusFilter;

            return matchesSearch && matchesStatus;
        });
    },

    get totalPages() {
        return Math.ceil(this.filteredGuests.length / this.perPage) || 1;
    },

    get paginatedGuests() {
        if (this.currentPage > this.totalPages) {
            this.currentPage = this.totalPages;
        }
        if (this.currentPage < 1) {
            this.currentPage = 1;
        }
        const start = (this.currentPage - 1) * this.perPage;
        return this.filteredGuests.slice(start, start + this.perPage);
    },

    goToPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
        }
    },

    resetFilters() {
        this.search = '';
        this.statusFilter = 'all';
        this.currentPage = 1;
        this.selected = [];
    }
}">
    <!-- STAT CARDS (3 Columns on Mobile, 5 Columns on Desktop) -->
    <div class="grid grid-cols-3 lg:grid-cols-5 gap-2.5 mb-5">
        <!-- Row 1 / Slot 1: Total Invited -->
        <div class="bg-white rounded-xl shadow-2xs py-2 px-2.5 text-center border border-gray-100 flex flex-col justify-center">
            <p class="text-base sm:text-lg font-bold text-gray-800">{{ $stats['total_invited'] }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold truncate" title="Total Invited">Total Invited</p>
        </div>

        <!-- Row 1 / Slot 2: Attending -->
        <div class="bg-white rounded-xl shadow-2xs py-2 px-2.5 text-center border border-gray-100 flex flex-col justify-center">
            <p class="text-base sm:text-lg font-bold text-emerald-600">{{ $stats['attending'] }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold truncate" title="Attending">Attending</p>
        </div>

        <!-- Row 1 / Slot 3: Not Attending -->
        <div class="bg-white rounded-xl shadow-2xs py-2 px-2.5 text-center border border-gray-100 flex flex-col justify-center">
            <p class="text-base sm:text-lg font-bold text-rose-600">{{ $stats['not_attending'] }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold truncate" title="Not Attending">Not Attending</p>
        </div>

        <!-- Row 2 / Slot 4: Pending -->
        <div class="bg-white rounded-xl shadow-2xs py-2 px-2.5 text-center border border-gray-100 flex flex-col justify-center">
            <p class="text-base sm:text-lg font-bold text-amber-600">{{ $stats['pending'] }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold truncate" title="Pending">Pending</p>
        </div>

        <!-- Row 2 / Slot 5: Total Headcount -->
        <div class="bg-white rounded-xl shadow-2xs py-2 px-2.5 text-center border border-gray-100 flex flex-col justify-center">
            <p class="text-base sm:text-lg font-bold text-indigo-600">{{ $stats['total_headcount'] }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold truncate" title="Total Headcount">Total Headcount</p>
        </div>

        <!-- Row 2 / Slot 6: View Preview Action Card (Mobile/Tablet Only: Hidden on Desktop lg:) -->
        <button 
            @click.stop="showPreviewModal = true; $dispatch('open-preview-modal')" 
            type="button"
            title="View Invitation Preview"
            class="bg-indigo-50/60 hover:bg-indigo-100/80 border border-indigo-100 rounded-xl shadow-2xs py-2 px-2.5 text-center flex flex-col items-center justify-center cursor-pointer transition group focus:outline-none focus:ring-2 focus:ring-indigo-500 lg:hidden"
        >
            <div class="flex items-center justify-center gap-1 mb-0.5">
                <svg class="w-4 h-4 text-indigo-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-indigo-700 tracking-wider uppercase truncate">VIEW PREVIEW</span>
        </button>
    </div>

    <!-- MAIN GUEST LIST CONTAINER -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6 border border-gray-100">
        @if ($guests->isEmpty())
            <div class="text-center py-8">
                <div class="text-3xl mb-2">👥</div>
                <p class="text-sm text-gray-500">No guests added yet. Click "+ Add Guest" above to get started.</p>
            </div>
        @else

            <!-- BULK ACTION BAR -->
            <div 
                x-show="selected.length > 0" 
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="bg-red-50 border border-red-200 rounded-xl p-3 sm:p-4 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs"
            >
                <div class="flex items-center justify-between sm:justify-start gap-3 w-full sm:w-auto">
                    <div class="flex items-center gap-2 text-red-800 font-semibold text-xs sm:text-sm">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span x-text="selected.length + ' guest(s) selected'"></span>
                    </div>

                    <button 
                        type="button" 
                        @click="clearSelection()" 
                        class="text-xs text-gray-500 hover:text-gray-700 font-medium px-2 py-1 transition-colors cursor-pointer"
                    >
                        Deselect All
                    </button>
                </div>

                <form action="{{ route('guests.bulk-destroy') }}" method="POST" id="bulk-delete-form" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="'bulk-id-' + id">
                        <input type="hidden" name="guest_ids[]" :value="id">
                    </template>
                    <button 
                        type="button"
                        @click="$dispatch('open-delete-modal', {
                            title: 'Delete Selected Guests',
                            description: 'Are you sure you want to permanently delete these ' + selected.length + ' guest(s)? This action cannot be undone.',
                            submitFormId: 'bulk-delete-form',
                            confirmButtonText: 'Yes, Delete Guests'
                        })"
                        class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 shadow-sm transition cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Delete Selected (<span x-text="selected.length"></span>)</span>
                    </button>
                </form>
            </div>

            <!-- SEARCH & RSVP FILTER TOOLBAR -->
            <div class="mb-4 flex flex-col sm:flex-row gap-3 items-center justify-between">
                <!-- Search Bar -->
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Search guest name or email..." 
                        class="w-full pl-9 pr-8 py-1.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors"
                    >
                    <button 
                        x-show="search.length > 0" 
                        x-cloak 
                        @click="search = ''; currentPage = 1" 
                        type="button" 
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- RSVP Status Filter -->
                <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
                    <span class="text-xs text-gray-500 font-medium hidden sm:inline">Status:</span>
                    <select 
                        x-model="statusFilter" 
                        @change="currentPage = 1"
                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"
                        class="appearance-none w-full sm:w-auto pl-3 pr-8 py-1.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-colors cursor-pointer"
                    >
                        <option value="all">All Statuses</option>
                        <option value="attending">Attending</option>
                        <option value="not_attending">Not Attending</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <!-- GUEST TABLE / LIST -->
            <!-- DESKTOP VIEW (md: and above) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 text-gray-500 uppercase text-[10px] sm:text-xs tracking-wider">
                            <th class="py-2.5 px-2 text-center w-10">
                                <input 
                                    type="checkbox" 
                                    :checked="isAllSelected" 
                                    @change="toggleSelectAll()" 
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                    title="Select all on current page"
                                >
                            </th>
                            <th class="py-2.5 px-2 text-left">Guest</th>
                            <th class="py-2.5 px-2 text-center">Status</th>
                            <th class="py-2.5 px-2 hidden sm:table-cell text-center">Companions</th>
                            <th class="py-2.5 px-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="guest in paginatedGuests" :key="guest.id">
                            <tr class="hover:bg-gray-50/80 transition-colors" :class="selected.includes(guest.id) ? 'bg-indigo-50/40' : ''">
                                <td class="py-3 px-2 text-center w-10">
                                    <input 
                                        type="checkbox" 
                                        :value="guest.id" 
                                        x-model.number="selected" 
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                    >
                                </td>
                                <td class="py-3 px-2 text-left">
                                    <div class="font-semibold text-gray-900" x-text="guest.name"></div>
                                    <div class="text-xs text-gray-500 font-normal" x-text="guest.email || guest.phone || 'No contact info'"></div>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <template x-if="getStatus(guest) === 'attending'">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            Attending
                                        </span>
                                    </template>
                                    <template x-if="getStatus(guest) === 'not_attending'">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            Not Attending
                                        </span>
                                    </template>
                                    <template x-if="getStatus(guest) === 'pending' || !['attending', 'not_attending'].includes(getStatus(guest))">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            Pending
                                        </span>
                                    </template>
                                </td>
                                <td class="py-3 px-2 hidden sm:table-cell text-center text-gray-600 font-medium">
                                    <span x-text="(guest.rsvp ? guest.rsvp.companions_count : guest.companions_count) || 0"></span>
                                    <span class="text-gray-400 text-xs" x-text="'/ ' + (guest.max_companions || 0)"></span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    <button @click.prevent.stop="$dispatch('open-guest-modal', guest)" type="button" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs cursor-pointer">View</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- MOBILE CARD VIEW (< md screens) -->
            <div class="block md:hidden">
                <!-- Lightweight Header Control Bar for Mobile Card View -->
                <div class="flex items-center justify-between py-2 px-1 border-b border-gray-100 mb-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            :checked="isAllSelected" 
                            @change="toggleSelectAll()" 
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                        >
                        <span>Select All</span>
                    </label>
                    <span>Action</span>
                </div>

                <div class="divide-y divide-gray-100">
                    <template x-for="guest in paginatedGuests" :key="'mobile-' + guest.id">
                        <div class="py-3 flex items-start justify-between gap-3 border-b border-gray-100 last:border-b-0" :class="selected.includes(guest.id) ? 'bg-indigo-50/40 -mx-2 px-2 rounded-lg' : ''">
                            <div class="pt-0.5 shrink-0">
                                <input 
                                    type="checkbox" 
                                    :value="guest.id" 
                                    x-model.number="selected" 
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                >
                            </div>
                        <!-- Left Side: Guest Info & Details -->
                        <div class="min-w-0 flex-1 flex flex-col gap-1">
                            <!-- 1. Name -->
                            <div class="font-bold text-gray-900 text-sm truncate" x-text="guest.name"></div>
                            
                            <!-- 2. Email / Contact -->
                            <div class="text-xs text-gray-500 font-normal truncate" x-text="guest.email || guest.phone || 'No contact info'"></div>
                            
                            <!-- 3. Status Badge & 4. Companions Info -->
                            <div class="flex items-center flex-wrap gap-2 mt-1">
                                <template x-if="getStatus(guest) === 'attending'">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-100 text-green-800 border border-green-200">
                                        Attending
                                    </span>
                                </template>
                                <template x-if="getStatus(guest) === 'not_attending'">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-800 border border-red-200">
                                        Not Attending
                                    </span>
                                </template>
                                <template x-if="getStatus(guest) === 'pending' || !['attending', 'not_attending'].includes(getStatus(guest))">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        Pending
                                    </span>
                                </template>

                                <span class="text-xs text-gray-500 font-medium">
                                    Companions: <span class="font-semibold text-gray-700" x-text="(guest.rsvp ? guest.rsvp.companions_count : guest.companions_count) || 0"></span> / <span class="text-gray-400" x-text="guest.max_companions || 0"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Right Side: View Action Button -->
                        <div class="shrink-0 pt-0.5">
                            <button @click.prevent.stop="$dispatch('open-guest-modal', guest)" type="button" class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-md border border-indigo-100 transition-colors cursor-pointer">
                                View
                            </button>
                        </div>
                    </div>
                </template>
                </div>
            </div>

            <!-- PAGINATION FOOTER -->
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium">
                <div>
                    Showing <span class="font-semibold text-gray-700" x-text="filteredGuests.length > 0 ? ((currentPage - 1) * perPage + 1) : 0"></span>
                    to <span class="font-semibold text-gray-700" x-text="Math.min(currentPage * perPage, filteredGuests.length)"></span>
                    of <span class="font-semibold text-gray-700" x-text="filteredGuests.length"></span> guests
                </div>

                <div class="flex items-center gap-1" x-show="totalPages > 1">
                    <button 
                        @click="goToPage(currentPage - 1)" 
                        :disabled="currentPage === 1"
                        :class="currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100 text-gray-700'"
                        class="px-2.5 py-1 border border-gray-200 rounded-md transition-colors"
                    >
                        Prev
                    </button>
                    <span class="px-2 font-semibold text-gray-700" x-text="currentPage + ' / ' + totalPages"></span>
                    <button 
                        @click="goToPage(currentPage + 1)" 
                        :disabled="currentPage === totalPages"
                        :class="currentPage === totalPages ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100 text-gray-700'"
                        class="px-2.5 py-1 border border-gray-200 rounded-md transition-colors"
                    >
                        Next
                    </button>
                </div>
            </div>

        @endif
    </div>
</div>
