<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">
            {{ __('Guest Details') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">

            <div class="mb-2">
                <a href="{{ route('events.guests.index', $guest->event) }}" class="text-indigo-600 hover:underline text-xs">← Back to Guest List</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100" x-data="{ copied: false }">

                <div class="flex justify-between items-start mb-4 border-b border-gray-100 pb-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $guest->name }}</h1>
                        <p class="text-xs text-gray-500 font-normal">{{ $guest->email ?? $guest->phone ?? 'No contact info' }}</p>
                    </div>
                    @if ($guest->rsvp?->status === 'attending')
                        <span class="bg-green-100 text-green-800 border border-green-200 text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">Attending</span>
                    @elseif ($guest->rsvp?->status === 'not_attending')
                        <span class="bg-red-100 text-red-800 border border-red-200 text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">Not Attending</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">Pending</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4 text-xs bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Email</p>
                        <p class="font-medium text-gray-800 truncate">{{ $guest->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Phone</p>
                        <p class="font-medium text-gray-800 truncate">{{ $guest->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Max Companions</p>
                        <p class="font-medium text-gray-800">{{ $guest->max_companions }}</p>
                    </div>
                    @if ($guest->rsvp)
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">Bringing</p>
                            <p class="font-medium text-gray-800">{{ $guest->rsvp->companions_count }}</p>
                        </div>
                        @if ($guest->rsvp->message)
                            <div class="col-span-2 pt-1 border-t border-gray-200/60">
                                <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider">RSVP Message</p>
                                <p class="font-normal text-gray-700 italic mt-0.5">{{ $guest->rsvp->message }}</p>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="bg-gray-50/80 rounded-xl border border-gray-100 p-4 mb-4 flex items-start gap-3.5">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/invite/' . $guest->unique_code)) }}" alt="QR Code" class="w-20 h-20 rounded-lg border border-gray-200 p-1 shrink-0 bg-white shadow-2xs">
                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] text-gray-500 uppercase font-semibold tracking-wider mb-1">Invite Link</p>
                        <code class="text-xs bg-white text-gray-800 p-2 rounded-md break-all block font-mono border border-gray-200 select-all">{{ url('/invite/' . $guest->unique_code) }}</code>
                        
                        <!-- Copy Link Button -->
                        <button 
                            @click="navigator.clipboard.writeText('{{ url('/invite/' . $guest->unique_code) }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                            type="button" 
                            class="mt-2 inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors cursor-pointer"
                        >
                            <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 012-2v-8a2 2 0 01-2-2h-8a2 2 0 01-2 2v8a2 2 0 012 2z"/>
                            </svg>
                            <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span x-text="copied ? 'Copied to clipboard!' : 'Copy Invite Link'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 pt-2">
                    @if ($guest->email)
                        <form action="{{ route('guests.send-invite', $guest) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-xs transition-colors inline-flex items-center gap-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Send Invite
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('guests.edit', $guest) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <button 
                        type="button" 
                        @click="$dispatch('open-delete-modal', {
                            title: 'Delete Guest',
                            targetName: @js($guest->name),
                            actionUrl: @js(route('guests.destroy', $guest))
                        })" 
                        class="bg-rose-600 hover:bg-rose-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors inline-flex items-center gap-1 cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- REUSABLE DELETE CONFIRMATION MODAL -->
    <x-delete-confirm-modal />
</x-app-layout>