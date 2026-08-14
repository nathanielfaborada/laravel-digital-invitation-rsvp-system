@props([
    'message' => session('success') ?? session('error'),
    'type' => session('error') ? 'error' : 'success',
    'duration' => 4000
])

<div 
    x-data="{
        show: false,
        message: @js($message ?? ''),
        type: @js($type ?? 'success'),
        timeout: null,
        progress: 100,
        progressInterval: null,
        init() {
            if (this.message) {
                this.triggerToast(this.message, this.type);
            }
        },
        triggerToast(msg, toastType = 'success') {
            this.message = msg;
            this.type = toastType;
            this.show = true;
            this.progress = 100;
            
            if (this.timeout) clearTimeout(this.timeout);
            if (this.progressInterval) clearInterval(this.progressInterval);

            const intervalTime = 40;
            const decrement = (intervalTime / @js($duration)) * 100;

            this.progressInterval = setInterval(() => {
                this.progress = Math.max(0, this.progress - decrement);
            }, intervalTime);

            this.timeout = setTimeout(() => {
                this.dismiss();
            }, @js($duration));
        },
        dismiss() {
            this.show = false;
            if (this.timeout) clearTimeout(this.timeout);
            if (this.progressInterval) clearInterval(this.progressInterval);
        }
    }"
    @notify.window="triggerToast($event.detail?.message || $event.detail, $event.detail?.type || 'success')"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave-end="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
    class="fixed top-4 inset-x-4 sm:left-auto sm:right-5 sm:top-5 z-[9999] max-w-md mx-auto sm:mx-0 w-auto shadow-lg rounded-xl transition-all duration-300 pointer-events-auto"
>
    <div 
        :class="type === 'error' ? 'border-rose-500' : 'border-emerald-500'"
        class="flex items-center gap-3 p-3.5 sm:p-4 bg-white border border-gray-100 border-l-4 rounded-xl shadow-xl relative overflow-hidden"
    >
        <!-- Icon -->
        <div 
            :class="type === 'error' ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600'"
            class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
        >
            <template x-if="type === 'error'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </template>
            <template x-if="type !== 'error'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </template>
        </div>

        <!-- Message -->
        <p class="text-xs sm:text-sm font-medium text-gray-800 break-words flex-1 leading-snug" x-text="message"></p>

        <!-- Manual Dismiss Button -->
        <button 
            type="button" 
            @click="dismiss()" 
            class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors shrink-0 cursor-pointer focus:outline-none"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Progress Bar -->
        <div 
            :class="type === 'error' ? 'bg-rose-100' : 'bg-emerald-100'"
            class="absolute bottom-0 left-0 right-0 h-1"
        >
            <div 
                :class="type === 'error' ? 'bg-rose-500' : 'bg-emerald-500'"
                class="h-full transition-all ease-linear" 
                :style="'width: ' + progress + '%'"
            ></div>
        </div>
    </div>
</div>
