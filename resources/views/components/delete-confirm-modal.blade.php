@props([
    'title' => 'Confirm Deletion',
    'targetName' => '',
    'actionUrl' => '',
])

<div 
    x-data="{
        show: false,
        title: @js($title),
        targetName: @js($targetName),
        actionUrl: @js($actionUrl),
        submitFormId: null,
        confirmButtonText: 'Delete',
        requireInput: true,
        description: '',
        inputConfirm: '',
        openModal(detail) {
            if (detail) {
                if (detail.title !== undefined) this.title = detail.title;
                if (detail.targetName !== undefined) this.targetName = detail.targetName;
                if (detail.actionUrl !== undefined) this.actionUrl = detail.actionUrl;
                this.submitFormId = detail.submitFormId || null;
                this.confirmButtonText = detail.confirmButtonText || (this.submitFormId ? 'Yes, Delete Guests' : 'Delete');
                this.requireInput = detail.requireInput !== undefined ? detail.requireInput : (this.submitFormId ? false : true);
                this.description = detail.description || '';
            } else {
                this.submitFormId = null;
                this.confirmButtonText = 'Delete';
                this.requireInput = true;
                this.description = '';
            }
            this.inputConfirm = '';
            this.show = true;
        },
        closeModal() {
            this.show = false;
            this.inputConfirm = '';
            this.submitFormId = null;
        },
        submitDelete(e) {
            if (this.submitFormId) {
                e.preventDefault();
                const form = document.getElementById(this.submitFormId);
                if (form) {
                    form.submit();
                }
            }
        }
    }"
    @open-delete-modal.window="openModal($event.detail)"
    @keydown.escape.window="closeModal()"
    x-show="show"
    x-cloak
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto"
>
    <div 
        @click.away="closeModal()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="max-w-md w-full bg-white rounded-2xl p-6 shadow-xl border border-gray-100 relative my-auto"
    >
        <!-- Header with Warning Icon -->
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900" x-text="title"></h3>
                <p class="text-xs text-gray-500">This action cannot be undone.</p>
            </div>
        </div>

        <!-- Description -->
        <template x-if="description">
            <p class="text-xs sm:text-sm text-gray-600 mb-4 leading-relaxed" x-text="description"></p>
        </template>
        <template x-if="!description">
            <p class="text-xs sm:text-sm text-gray-600 mb-3 leading-relaxed">
                To confirm deletion, please type: 
                <strong class="text-gray-900 font-semibold break-all select-all" x-text="targetName"></strong>
            </p>
        </template>

        <!-- Form -->
        <form :action="actionUrl || '#'" method="POST" @submit="submitDelete($event)">
            @csrf
            @method('DELETE')

            <div class="mb-5" x-show="requireInput">
                <input 
                    type="text" 
                    x-model="inputConfirm" 
                    placeholder="Type name here to confirm..." 
                    class="w-full px-3 py-2 text-xs sm:text-sm bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white transition-colors"
                    @keydown.enter="if (requireInput && (inputConfirm !== targetName || !targetName)) $event.preventDefault()"
                >
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-2.5">
                <button 
                    type="button" 
                    @click="closeModal()" 
                    class="h-9 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    :disabled="requireInput && (inputConfirm !== targetName || !targetName)"
                    :class="(!requireInput || (inputConfirm === targetName && targetName)) 
                        ? 'bg-rose-600 hover:bg-rose-700 text-white cursor-pointer shadow-xs' 
                        : 'bg-rose-200 text-rose-400 cursor-not-allowed opacity-70'"
                    class="h-9 px-4 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span x-text="confirmButtonText"></span>
                </button>
            </div>
        </form>
    </div>
</div>
