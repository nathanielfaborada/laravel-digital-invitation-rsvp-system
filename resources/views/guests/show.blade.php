<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">
            {{ __('Guest Details') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-3 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-3 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-2">
                <a href="{{ route('events.guests.index', $guest->event) }}" class="text-indigo-600 hover:underline text-xs">← Back to Guest List</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">

                <div class="flex justify-between items-start mb-3">
                    <h1 class="text-lg font-bold text-gray-800">{{ $guest->name }}</h1>
                    @if ($guest->rsvp?->status === 'attending')
                        <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full whitespace-nowrap">Attending</span>
                    @elseif ($guest->rsvp?->status === 'not_attending')
                        <span class="bg-red-100 text-red-700 text-[10px] px-2 py-0.5 rounded-full whitespace-nowrap">Not Attending</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-0.5 rounded-full whitespace-nowrap">Pending</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-x-3 gap-y-2 mb-3 text-sm">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase">Email</p>
                        <p class="font-medium truncate">{{ $guest->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase">Phone</p>
                        <p class="font-medium">{{ $guest->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase">Max Companions</p>
                        <p class="font-medium">{{ $guest->max_companions }}</p>
                    </div>
                    @if ($guest->rsvp)
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase">Bringing</p>
                            <p class="font-medium">{{ $guest->rsvp->companions_count }}</p>
                        </div>
                        @if ($guest->rsvp->message)
                            <div class="col-span-2">
                                <p class="text-[10px] text-gray-500 uppercase">Message</p>
                                <p class="font-medium">{{ $guest->rsvp->message }}</p>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="border-t border-gray-200 pt-3 mb-3 flex items-center gap-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/invite/' . $guest->unique_code)) }}" alt="QR Code" class="rounded-md border border-gray-200 p-1 flex-shrink-0">
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-500 uppercase mb-1">Invite Link</p>
                        <code class="text-[10px] bg-gray-100 px-2 py-1 rounded break-all block">{{ url('/invite/' . $guest->unique_code) }}</code>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($guest->email)
                        <form action="{{ route('guests.send-invite', $guest) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-md hover:bg-blue-700 text-xs">Send Invite</button>
                        </form>
                    @endif
                    <a href="{{ route('guests.edit', $guest) }}" class="bg-yellow-500 text-white px-3 py-1.5 rounded-md hover:bg-yellow-600 text-xs">Edit</a>
                    <form action="{{ route('guests.destroy', $guest) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-md hover:bg-red-700 text-xs">Delete</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>