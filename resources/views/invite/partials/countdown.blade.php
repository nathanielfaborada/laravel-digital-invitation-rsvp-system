<div x-data="{
    days: 0, hours: 0, minutes: 0, seconds: 0,
    init() {
        const target = new Date('{{ \Carbon\Carbon::parse($event->event_date . ' ' . $event->event_time)->toIso8601String() }}').getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const distance = target - now;
            if (distance < 0) {
                this.days = this.hours = this.minutes = this.seconds = 0;
                return;
            }
            this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
            this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }, 1000);
    }
}" class="grid grid-cols-4 gap-2 mb-6">
    <div class="bg-gray-50 rounded-lg py-3 text-center">
        <p class="text-2xl font-bold" x-text="days"></p>
        <p class="text-xs text-gray-500 uppercase">Days</p>
    </div>
    <div class="bg-gray-50 rounded-lg py-3 text-center">
        <p class="text-2xl font-bold" x-text="hours"></p>
        <p class="text-xs text-gray-500 uppercase">Hours</p>
    </div>
    <div class="bg-gray-50 rounded-lg py-3 text-center">
        <p class="text-2xl font-bold" x-text="minutes"></p>
        <p class="text-xs text-gray-500 uppercase">Mins</p>
    </div>
    <div class="bg-gray-50 rounded-lg py-3 text-center">
        <p class="text-2xl font-bold" x-text="seconds"></p>
        <p class="text-xs text-gray-500 uppercase">Secs</p>
    </div>
</div>