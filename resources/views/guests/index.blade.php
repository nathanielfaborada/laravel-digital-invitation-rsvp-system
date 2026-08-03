<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Guests for') }}: {{ $event->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('events.guests.export', $event) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                    Export CSV
                </a>
                <a href="{{ route('events.guests.create', $event) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                    + Add Guest
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:underline text-sm">← Back to Event</a>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 sm:gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-4 text-center">
                    <p class="text-lg sm:text-2xl font-bold text-gray-800">{{ $stats['total_invited'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500 uppercase">Total Invited</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-4 text-center">
                    <p class="text-lg sm:text-2xl font-bold text-green-600">{{ $stats['attending'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500 uppercase">Attending</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-4 text-center">
                    <p class="text-lg sm:text-2xl font-bold text-red-600">{{ $stats['not_attending'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500 uppercase">Not Attending</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-4 text-center">
                    <p class="text-lg sm:text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500 uppercase">Pending</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-2 sm:p-4 text-center">
                    <p class="text-lg sm:text-2xl font-bold text-indigo-600">{{ $stats['total_headcount'] }}</p>
                    <p class="text-[10px] sm:text-xs text-gray-500 uppercase">Total Headcount</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($guests->isEmpty())
                    <p class="text-gray-500">No guests added yet.</p>
                @else

                    <!-- MOBILE VIEW: Simple list -->
                    <div class="sm:hidden space-y-2">
                        @foreach ($guests as $guest)
                            <a href="{{ route('guests.show', $guest) }}" class="flex justify-between items-center border rounded-lg p-3 hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $guest->name }}</p>
                                    @if ($guest->rsvp?->status === 'attending')
                                        <span class="text-xs text-green-600">Attending</span>
                                    @elseif ($guest->rsvp?->status === 'not_attending')
                                        <span class="text-xs text-red-600">Not Attending</span>
                                    @else
                                        <span class="text-xs text-yellow-600">Pending</span>
                                    @endif
                                </div>
                                <span class="text-indigo-600 text-sm">View Details →</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- DESKTOP VIEW: Full table -->
                    <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Name</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Phone</th>
                                <th class="py-2">Companions</th>
                                <th class="py-2">RSVP Status</th>
                                <th class="py-2">Invite Link</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($guests as $guest)
                                <tr class="border-b">
                                    <td class="py-2">{{ $guest->name }}</td>
                                    <td class="py-2">{{ $guest->email ?? '-' }}</td>
                                    <td class="py-2">{{ $guest->phone ?? '-' }}</td>
                                    <td class="py-2">{{ $guest->max_companions }}</td>
                                    <td class="py-2">
                                        @if ($guest->rsvp?->status === 'attending')
                                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Attending</span>
                                        @elseif ($guest->rsvp?->status === 'not_attending')
                                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Not Attending</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Pending</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-2">
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ url('/invite/' . $guest->unique_code) }}</code>
                                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('/invite/' . $guest->unique_code)) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">
                                                View QR
                                            </a>
                                        </div>
                                    </td>
                                    <td class="py-2">
                                        <div class="flex gap-2">
                                            <a href="{{ route('guests.edit', $guest) }}" class="text-yellow-600 hover:underline text-sm">Edit</a>
                                            <form action="{{ route('guests.destroy', $guest) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>

                @endif
            </div>
        </div>
    </div>
</x-app-layout>