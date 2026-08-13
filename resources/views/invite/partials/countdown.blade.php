<div x-data="{
    days: 0, hours: 0, minutes: 0, seconds: 0, isPast: false,
    init() {
        const rawDate = @js($event->event_date);
        const rawTime = @js($event->event_time ?? '00:00:00');
        
        let datePart = rawDate;
        if (typeof datePart === 'string' && datePart.includes('T')) {
            datePart = datePart.split('T')[0];
        } else if (typeof datePart === 'string' && datePart.includes(' ')) {
            const parts = datePart.split(' ');
            if (parts[0].match(/^\d{4}-\d{2}-\d{2}$/)) {
                datePart = parts[0];
            }
        }

        let timePart = rawTime || '00:00';
        if (timePart.length === 5) {
            timePart += ':00';
        }

        let target = new Date(`${datePart}T${timePart}`).getTime();
        if (isNaN(target)) {
            target = new Date(`${rawDate} ${rawTime}`.trim()).getTime();
        }
        if (isNaN(target)) {
            target = new Date(rawDate).getTime();
        }

        const updateTimer = () => {
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
        };

        updateTimer();
        setInterval(updateTimer, 1000);
    }
}" class="mb-4">

    <template x-if="isPast">
        <div class="bg-amber-50 border border-amber-200 rounded-xl py-2 px-3 text-center mb-2">
            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider">🎉 Event Has Started</span>
        </div>
    </template>

    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
        <div class="bg-gray-50 rounded-lg py-2 text-center border border-gray-100">
            <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="days"></p>
            <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Days</p>
        </div>
        <div class="bg-gray-50 rounded-lg py-2 text-center border border-gray-100">
            <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="hours"></p>
            <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Hours</p>
        </div>
        <div class="bg-gray-50 rounded-lg py-2 text-center border border-gray-100">
            <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="minutes"></p>
            <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Mins</p>
        </div>
        <div class="bg-gray-50 rounded-lg py-2 text-center border border-gray-100">
            <p class="text-sm sm:text-xl font-bold text-gray-800" x-text="seconds"></p>
            <p class="text-[8px] sm:text-[10px] text-gray-500 uppercase font-medium">Secs</p>
        </div>
    </div>
</div>