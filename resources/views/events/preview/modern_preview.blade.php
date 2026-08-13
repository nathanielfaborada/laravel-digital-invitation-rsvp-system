<div class="relative w-full overflow-hidden rounded-2xl bg-white shadow-lg border border-slate-100">
    <!-- 1. LIVE UPLOAD PREVIEW -->
    <img x-show="coverImagePreview" 
         :src="coverImagePreview" 
         alt="Cover Preview" 
         class="absolute inset-0 w-full h-full object-cover opacity-15 z-0">

    <!-- 2. EXISTING DB IMAGE -->
    @if (isset($event) && $event->cover_image)
        <img x-show="!coverImagePreview" 
             src="{{ asset('storage/' . $event->cover_image) }}" 
             alt="Cover Image" 
             class="absolute inset-0 w-full h-full object-cover opacity-15 z-0">
    @endif

    <!-- 3. DEFAULT FALLBACK IMAGE -->
    <img x-show="!coverImagePreview @if(isset($event) && $event->cover_image) && false @endif" 
         src="https://res.cloudinary.com/wyofiygs/image/upload/v1786343694/Untitled_design_6_it913f.png" 
         alt="Default Cover" 
         class="absolute inset-0 w-full h-full object-cover object-[center_25%] opacity-20 z-0">

    <!-- Subtle Minimalist Geometric Top Pattern Accent -->
    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-slate-900 via-indigo-600 to-slate-900 z-10"></div>

    <!-- TEMPLATE CONTENT -->
    <div class="pt-6 pb-6 px-4 sm:pt-8 sm:pb-8 sm:px-6 relative z-10">
        
        <!-- Header Badge -->
        <div class="mb-2">
            <span class="inline-block px-3 py-1 bg-slate-900 text-white text-[10px] sm:text-xs font-bold uppercase tracking-widest rounded-full shadow-xs">
                INVITATION
            </span>
        </div>

        <!-- Title -->
        <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2 leading-tight break-words" x-text="title || 'Event Title'"></h1>

        <!-- Description -->
        <p class="text-xs sm:text-sm text-slate-600 mb-5 leading-relaxed break-words font-normal" x-text="description || 'Description'"></p>

        <!-- Details List (Date & Venue) -->
        <div class="space-y-3 mb-5 bg-slate-50/90 backdrop-blur-sm p-3.5 rounded-xl border border-slate-200/80 shadow-2xs">
            <!-- Date & Time -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white text-xs shrink-0 shadow-xs">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider"
                        x-text="event_date
                            ? new Date(event_date + 'T00:00:00').toLocaleDateString('en-US', {
                                month: 'long', day: 'numeric', year: 'numeric'
                            })
                            : 'Select Date'">
                    </p>
                    <p class="text-xs sm:text-sm font-bold text-slate-800"
                        x-text="event_time
                            ? new Date(`1970-01-01T${event_time}`).toLocaleTimeString('en-US', {
                                hour: 'numeric', minute: '2-digit', hour12: true
                            })
                            : 'Select Time'">
                    </p>
                </div>
            </div>

            <!-- Venue -->
            <div class="flex items-center gap-3 pt-2.5 border-t border-slate-200/60">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white text-xs shrink-0 shadow-xs">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] sm:text-xs text-slate-400 font-semibold uppercase tracking-wider">Location</p>
                    <p class="text-xs sm:text-sm font-bold text-slate-800 break-words" x-text="venue || 'Venue Name'"></p>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200/80 pt-4">
            <!-- Google Maps Embed -->
            <div x-show="venue" class="mb-4 rounded-xl overflow-hidden border border-slate-200 shadow-2xs">
                <iframe
                    width="100%"
                    height="130"
                    class="sm:h-[150px]"
                    style="border:0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    :src="'https://www.google.com/maps?q=' + encodeURIComponent(venue) + '&output=embed'">
                </iframe>
            </div>

            <!-- Countdown Timer Component -->
            <div x-data="{
                days: 0, hours: 0, minutes: 0, seconds: 0, isPast: false,
                getDateVal() {
                    if (typeof event_date !== 'undefined' && event_date) return event_date;
                    @if (isset($event) && $event->event_date)
                        return @js($event->event_date);
                    @endif
                    return '';
                },
                getTimeVal() {
                    if (typeof event_time !== 'undefined' && event_time) return event_time;
                    @if (isset($event) && $event->event_time)
                        return @js($event->event_time);
                    @endif
                    return '';
                },
                tick() {
                    const rawDate = this.getDateVal();
                    const rawTime = this.getTimeVal();

                    if (!rawDate) {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                        this.isPast = false;
                        return;
                    }

                    let dateStr = String(rawDate).trim();
                    let timeStr = String(rawTime || '00:00').trim();

                    let cleanDate = dateStr;
                    if (cleanDate.includes('T')) {
                        cleanDate = cleanDate.split('T')[0];
                    } else if (cleanDate.includes(' ')) {
                        const parts = cleanDate.split(' ');
                        if (parts[0].match(/^\d{4}-\d{2}-\d{2}$/)) {
                            cleanDate = parts[0];
                        }
                    }

                    if (timeStr.length === 5) {
                        timeStr += ':00';
                    }

                    let target = new Date(`${cleanDate}T${timeStr}`).getTime();

                    if (isNaN(target)) {
                        target = new Date(`${dateStr} ${timeStr}`).getTime();
                    }
                    if (isNaN(target)) {
                        target = new Date(dateStr).getTime();
                    }

                    if (isNaN(target)) {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                        this.isPast = false;
                        return;
                    }

                    const now = new Date().getTime();
                    const distance = target - now;

                    if (distance <= 0) {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                        this.isPast = true;
                        return;
                    }

                    this.isPast = false;
                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                },
                init() {
                    this.tick();
                    setInterval(() => this.tick(), 1000);
                    if (typeof event_date !== 'undefined') {
                        this.$watch('event_date', () => this.tick());
                    }
                    if (typeof event_time !== 'undefined') {
                        this.$watch('event_time', () => this.tick());
                    }
                    if (typeof template !== 'undefined') {
                        this.$watch('template', () => this.tick());
                    }
                }
            }" class="mb-4">
                <template x-if="isPast">
                    <div class="bg-indigo-900/90 text-white rounded-xl py-2 px-3 text-center mb-2 shadow-xs">
                        <span class="text-xs font-bold uppercase tracking-wider">🎉 Event Has Started</span>
                    </div>
                </template>

                <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                    <div class="bg-slate-900 text-white rounded-xl py-2 text-center shadow-xs">
                        <p class="text-sm sm:text-xl font-bold" x-text="days"></p>
                        <p class="text-[8px] sm:text-xs text-slate-300 font-medium uppercase tracking-wider">Days</p>
                    </div>
                    <div class="bg-slate-900 text-white rounded-xl py-2 text-center shadow-xs">
                        <p class="text-sm sm:text-xl font-bold" x-text="hours"></p>
                        <p class="text-[8px] sm:text-xs text-slate-300 font-medium uppercase tracking-wider">Hours</p>
                    </div>
                    <div class="bg-slate-900 text-white rounded-xl py-2 text-center shadow-xs">
                        <p class="text-sm sm:text-xl font-bold" x-text="minutes"></p>
                        <p class="text-[8px] sm:text-xs text-slate-300 font-medium uppercase tracking-wider">Mins</p>
                    </div>
                    <div class="bg-slate-900 text-white rounded-xl py-2 text-center shadow-xs">
                        <p class="text-sm sm:text-xl font-bold" x-text="seconds"></p>
                        <p class="text-[8px] sm:text-xs text-slate-300 font-medium uppercase tracking-wider">Secs</p>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <p class="text-xs sm:text-sm text-slate-600 text-center leading-tight">
                Dear <span class="font-bold text-slate-900">{{ Auth::user()->name }}</span>, you are cordially invited.
            </p>
        </div>
    </div>
</div>