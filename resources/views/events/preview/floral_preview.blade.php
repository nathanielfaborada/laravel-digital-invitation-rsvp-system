<div class="relative w-full overflow-hidden rounded-2xl bg-gradient-to-b from-pink-50 via-rose-50/40 to-white border-2 border-pink-100 shadow-lg">
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

    <!-- TEMPLATE CONTENT -->
    <div class="pt-6 pb-6 px-4 sm:pt-8 sm:pb-8 sm:px-6 text-center relative z-10">
        
        <!-- FLORAL HEADER SVG ORNAMENT -->
        <div class="flex justify-center mb-1">
            <svg class="w-36 h-9" viewBox="0 0 200 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Stems -->
                <path d="M20 25 C50 12, 70 35, 90 25" stroke="#E11D48" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.35"/>
                <path d="M180 25 C150 12, 130 35, 110 25" stroke="#E11D48" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.35"/>
                <!-- Petals & Leaves -->
                <circle cx="100" cy="25" r="7" fill="#FDA4AF" opacity="0.8"/>
                <circle cx="94" cy="21" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="106" cy="21" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="94" cy="29" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="106" cy="29" r="5" fill="#F43F5E" opacity="0.5"/>
                <circle cx="100" cy="25" r="3.5" fill="#FFF1F2"/>
            </svg>
        </div>

        <!-- Header Label -->
        <p class="text-[10px] sm:text-xs text-rose-500 uppercase tracking-widest mb-1.5 font-bold">
            YOU'RE CORDIALLY INVITED
        </p>
        
        <!-- Title -->
        <h1 class="text-xl sm:text-3xl font-serif text-rose-950 mb-2 leading-tight break-words font-normal" x-text="title || 'Event Title'"></h1>
        
        <!-- Description -->
        <p class="text-xs sm:text-sm text-rose-800/80 mb-5 italic break-words font-serif" x-text="description || 'Description'"></p>

        <!-- Event Details Box -->
        <div class="bg-white/85 backdrop-blur-sm rounded-2xl border border-pink-200/90 py-3 px-3.5 mb-5 shadow-2xs">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <p class="text-[9px] sm:text-xs text-rose-400 uppercase font-semibold tracking-wider">Date</p>
                    <p class="text-xs sm:text-sm font-semibold text-rose-900" 
                        x-text="event_date
                            ? new Date(event_date + 'T00:00:00').toLocaleDateString('en-US', {
                                month: 'short', day: 'numeric', year: 'numeric'
                            })
                            : 'Select Date'">
                    </p>
                </div>
                <div>
                    <p class="text-[9px] sm:text-xs text-rose-400 uppercase font-semibold tracking-wider">Time</p>
                    <p class="text-xs sm:text-sm font-semibold text-rose-900" 
                        x-text="event_time
                            ? new Date(`1970-01-01T${event_time}`).toLocaleTimeString('en-US', {
                                hour: 'numeric', minute: '2-digit', hour12: true
                            })
                            : 'Select Time'">
                    </p>
                </div>
            </div>
            <div class="mt-2.5 pt-2.5 border-t border-pink-100">
                <p class="text-[9px] sm:text-xs text-rose-400 uppercase font-semibold tracking-wider">Venue</p>
                <p class="text-xs sm:text-sm font-semibold text-rose-900 break-words" x-text="venue || 'Venue Name'"></p>
            </div>
        </div>

        <!-- Google Maps Embed -->
        <div x-show="venue" class="mb-5 rounded-xl overflow-hidden border border-pink-200 shadow-2xs">
            <iframe
                width="100%"
                height="130"
                class="sm:h-[140px]"
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
                <div class="bg-rose-100 border border-rose-200 rounded-xl py-2 px-3 text-center mb-2 shadow-2xs">
                    <span class="text-xs font-bold text-rose-900 uppercase tracking-wider">🎉 Event Has Started</span>
                </div>
            </template>

            <div class="grid grid-cols-4 gap-1.5">
                <div class="bg-rose-900 text-white rounded-xl py-2 text-center shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold" x-text="days"></p>
                    <p class="text-[8px] sm:text-[10px] text-pink-200 uppercase font-medium">Days</p>
                </div>
                <div class="bg-rose-900 text-white rounded-xl py-2 text-center shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold" x-text="hours"></p>
                    <p class="text-[8px] sm:text-[10px] text-pink-200 uppercase font-medium">Hours</p>
                </div>
                <div class="bg-rose-900 text-white rounded-xl py-2 text-center shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold" x-text="minutes"></p>
                    <p class="text-[8px] sm:text-[10px] text-pink-200 uppercase font-medium">Mins</p>
                </div>
                <div class="bg-rose-900 text-white rounded-xl py-2 text-center shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold" x-text="seconds"></p>
                    <p class="text-[8px] sm:text-[10px] text-pink-200 uppercase font-medium">Secs</p>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <p class="text-xs sm:text-sm text-rose-900 font-serif italic leading-tight">
            Dearest <span class="font-semibold text-rose-950">{{ Auth::user()->name }}</span>, we would be honored by your presence.
        </p>
    </div>
</div>