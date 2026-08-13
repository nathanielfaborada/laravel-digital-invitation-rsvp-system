<div class="relative w-full overflow-hidden rounded-xl">
    <!-- 1. LIVE UPLOAD PREVIEW -->
    <img x-show="coverImagePreview" 
         :src="coverImagePreview" 
         alt="Cover Preview" 
         class="absolute inset-0 w-full h-full object-cover opacity-30 z-0">

    <!-- 2. EXISTING DB IMAGE -->
    @if (isset($event) && $event->cover_image)
        <img x-show="!coverImagePreview" 
             src="{{ asset('storage/' . $event->cover_image) }}" 
             alt="Cover Image" 
             class="absolute inset-0 w-full h-full object-cover opacity-30 z-0">
    @endif

    <!-- 3. DEFAULT FALLBACK IMAGE -->
    <img x-show="!coverImagePreview @if(isset($event) && $event->cover_image) && false @endif" 
         src="https://res.cloudinary.com/wyofiygs/image/upload/v1786343694/Untitled_design_6_it913f.png" 
         alt="Default Cover" 
         class="absolute inset-0 w-full h-full object-cover object-[center_25%] opacity-60 z-0">

    <!-- TEMPLATE CONTENT -->
    <div class="p-3 sm:p-6 text-center relative z-10">
        <p class="text-[9px] sm:text-xs text-gray-500 uppercase tracking-widest mb-1">Invitation</p>
        
        <h1 class="text-lg sm:text-2xl font-bold text-gray-800 mb-1 sm:mb-2 break-words" x-text="title || 'Event Title'"></h1>
        <p class="text-xs text-gray-600 mb-3 sm:mb-4 break-words" x-text="description || 'Description'"></p>

        <!-- Details Box -->
        <div class="border-t border-b border-gray-200/80 py-2 sm:py-3 mb-3 sm:mb-4 bg-white/80 backdrop-blur-xs rounded-lg shadow-xs px-2">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <p class="text-[9px] sm:text-xs text-gray-500 uppercase">Date</p>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800"
                        x-text="event_date
                            ? new Date(event_date + 'T00:00:00').toLocaleDateString('en-US', {
                                month: 'long', day: 'numeric', year: 'numeric'
                            })
                            : 'Select Date'">
                    </p>
                </div>
                <div>
                    <p class="text-[9px] sm:text-xs text-gray-500 uppercase">Time</p>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800"
                        x-text="event_time
                            ? new Date(`1970-01-01T${event_time}`).toLocaleTimeString('en-US', {
                                hour: 'numeric', minute: '2-digit', hour12: true
                            })
                            : 'Select Time'">
                    </p>
                </div>
            </div>
            <div class="mt-1.5 pt-1.5 border-t border-gray-100">
                <p class="text-[9px] sm:text-xs text-gray-500 uppercase">Venue</p>
                <p class="text-xs sm:text-sm font-semibold text-gray-800 break-words" x-text="venue || 'Venue Name'"></p>
            </div>
        </div>

        <!-- Google Maps -->
        <div x-show="venue" class="mb-3 rounded-lg overflow-hidden border border-gray-200/80 shadow-xs">
            <iframe
                width="100%"
                height="120"
                style="border:0"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                :src="'https://www.google.com/maps?q=' + encodeURIComponent(venue) + '&output=embed'">
            </iframe>
        </div>

        <!-- Countdown Timer -->
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
        }" class="mb-3">
            <template x-if="isPast">
                <div class="bg-amber-50 border border-amber-200/80 rounded-xl py-1.5 px-3 text-center mb-2">
                    <span class="text-xs font-bold text-amber-800 tracking-wider uppercase">🎉 Event Has Started</span>
                </div>
            </template>

            <div class="grid grid-cols-4 gap-1.5">
                <div class="bg-white/90 backdrop-blur-xs rounded-lg py-1.5 text-center border border-gray-100 shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="days"></p>
                    <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Days</p>
                </div>
                <div class="bg-white/90 backdrop-blur-xs rounded-lg py-1.5 text-center border border-gray-100 shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="hours"></p>
                    <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Hours</p>
                </div>
                <div class="bg-white/90 backdrop-blur-xs rounded-lg py-1.5 text-center border border-gray-100 shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="minutes"></p>
                    <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Mins</p>
                </div>
                <div class="bg-white/90 backdrop-blur-xs rounded-lg py-1.5 text-center border border-gray-100 shadow-2xs">
                    <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="seconds"></p>
                    <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Secs</p>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <p class="text-xs sm:text-sm text-gray-700 leading-tight">
            Dear <span class="font-semibold text-gray-900">{{ Auth::user()->name }}</span>, we'd love for you to join us!
        </p>
    </div>
</div>