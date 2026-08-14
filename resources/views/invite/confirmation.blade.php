@php
    $isEmbedded = $isEmbedded ?? false;
    $status = $guest->rsvp->status ?? 'pending';
    $isAttending = $status === 'attending';
    $totalGuests = 1 + ($guest->rsvp->companions_count ?? 0);
@endphp

@if(!$isEmbedded)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Confirmed: {{ $guest->event->title ?? 'Invitation' }}</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png">

    <!-- Open Graph / Facebook / Messenger -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="RSVP Confirmed: {{ $guest->event->title ?? 'Invitation' }}">
    <meta property="og:description" content="RSVP has been confirmed for {{ $guest->name }} - {{ $guest->event->title ?? 'Event' }}.">
    <meta property="og:image" content="{{ $guest->event->cover_image ?? 'https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png' }}">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="RSVP Confirmed: {{ $guest->event->title ?? 'Invitation' }}">
    <meta name="twitter:description" content="RSVP has been confirmed for {{ $guest->name }} - {{ $guest->event->title ?? 'Event' }}.">
    <meta name="twitter:image" content="{{ $guest->event->cover_image ?? 'https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png' }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 text-slate-800 font-sans">
@endif

    <div class="{{ $isEmbedded ? 'w-full' : 'max-w-lg w-full' }} bg-white rounded-2xl p-6 sm:p-8 shadow-xl border border-gray-100 text-center">
        <!-- Status Icon Header -->
        @if ($isAttending)
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-xs">
                🎉
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Thank You, {{ $guest->name }}!</h1>
            <p class="text-xs sm:text-sm text-gray-600 mb-6">
                We've received your RSVP. We're excited to celebrate with you!
            </p>
        @else
            <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl shadow-xs">
                💌
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Thanks for Letting Us Know</h1>
            <p class="text-xs sm:text-sm text-gray-600 mb-6">
                We're sorry you can't make it, {{ $guest->name }}. You will be missed!
            </p>
        @endif

        <!-- Response Summary Card -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-left mb-6 space-y-3 text-xs sm:text-sm">
            <div class="flex items-center justify-between border-b border-gray-200 pb-2.5">
                <span class="text-gray-500 font-medium">RSVP Status</span>
                <span class="font-bold {{ $isAttending ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $isAttending ? 'Status: Attending (' . $totalGuests . ' ' . ($totalGuests > 1 ? 'Guests' : 'Guest') . ')' : 'Status: Declined' }}
                </span>
            </div>

            @if ($isAttending && ($guest->rsvp->companions_count ?? 0) > 0)
                <div class="flex items-center justify-between border-b border-gray-200 pb-2.5">
                    <span class="text-gray-500 font-medium">Companion</span>
                    <span class="font-bold text-gray-800">
                        {{ $guest->rsvp->companion_name ? $guest->rsvp->companion_name . ' (+1)' : '+1 Companion Guest' }}
                    </span>
                </div>
            @endif

            @if ($guest->rsvp->message ?? false)
                <div>
                    <span class="text-gray-500 font-medium block mb-1">Your Message</span>
                    <p class="text-gray-800 italic bg-white p-2.5 rounded-lg border border-gray-200 text-xs">
                        "{{ $guest->rsvp->message }}"
                    </p>
                </div>
            @endif
        </div>

        <!-- Check-in QR Code (If Attending) -->
        @if ($isAttending)
            <div class="border-t border-gray-100 pt-5 mb-6 text-center">
                <p class="text-xs text-gray-500 font-medium mb-3">Show this QR code at the event entrance for check-in</p>
                <img 
                    src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode(url('/invite/' . $guest->unique_code)) }}" 
                    alt="Check-in QR Code" 
                    class="mx-auto rounded-xl border border-gray-200 p-2 shadow-xs bg-white"
                >
            </div>
        @endif

        <!-- Action Button: View Invitation Details -->
        <a 
            href="{{ route('invite.show', $guest->unique_code) }}" 
            class="inline-flex items-center justify-center gap-2 w-full py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-md transition-all cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>View Invitation Details</span>
        </a>
    </div>

@if(!$isEmbedded)
</body>
</html>
@endif