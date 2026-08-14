<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Invited: {{ $event->title }}</title>
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png">

    <!-- Open Graph / Facebook / Messenger -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="You're Invited: {{ $event->title }}">
    <meta property="og:description" content="{{ $event->description ? \Illuminate\Support\Str::limit($event->description, 150) : 'You are invited to ' . $event->title . '. Click to view event details and RSVP.' }}">
    <meta property="og:image" content="{{ $event->cover_image ?: 'https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png' }}">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="You're Invited: {{ $event->title }}">
    <meta name="twitter:description" content="{{ $event->description ? \Illuminate\Support\Str::limit($event->description, 150) : 'You are invited to ' . $event->title . '. Click to view event details and RSVP.' }}">
    <meta name="twitter:image" content="{{ $event->cover_image ?: 'https://res.cloudinary.com/wyofiygs/image/upload/v1786740228/Untitled_design_10_jee5wc.png' }}">

    <!-- Alpine.js Cloak Protection -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ 
        showRsvpModal: {{ $errors->any() ? 'true' : (session('openRsvp', false) || isset($openRsvp) ? 'true' : 'false') }},
        attending: 'yes', 
        hasCompanion: false 
    }" 
    class="bg-slate-100 min-h-screen text-slate-800 font-sans selection:bg-indigo-500 selection:text-white flex flex-col justify-between"
>

    <!-- MAIN CONTAINER -->
    <main class="max-w-6xl mx-auto w-full px-4 py-6 sm:py-10">
        <!-- DESKTOP & MOBILE GRID LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- LEFT COLUMN: INVITATION CARD PREVIEW & MOBILE RSVP BUTTON -->
            <div class="w-full flex flex-col items-center justify-center">
                @include('invite.templates.' . ($event->template ?? 'classic'), ['event' => $event, 'guest' => $guest])

                <!-- Mobile Action Button (block lg:hidden) -->
                @if (!$guest->rsvp)
                    <div class="mt-6 w-full max-w-lg block lg:hidden">
                        <button 
                            @click="showRsvpModal = true" 
                            type="button" 
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 px-6 rounded-xl shadow-md flex items-center justify-center gap-2 transition cursor-pointer"
                        >
                            RSVP →
                        </button>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: DESKTOP SIDE-BY-SIDE RSVP FORM / CONFIRMATION (lg:block) -->
            <div class="hidden lg:block w-full sticky top-8">
                @if ($guest->rsvp)
                    @include('invite.confirmation', ['guest' => $guest, 'event' => $event, 'isEmbedded' => true])
                @else
                    @include('invite.rsvp', ['event' => $event, 'guest' => $guest])
                @endif
            </div>

        </div>
    </main>

    <!-- MOBILE BOTTOM SHEET SLIDE-UP MODAL (lg:hidden) -->
    <div 
        x-show="showRsvpModal" 
        x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-full"
        class="fixed inset-0 z-50 flex items-end justify-center bg-black/50 lg:hidden"
        @keydown.escape.window="showRsvpModal = false"
    >
        <div 
            @click.away="showRsvpModal = false"
            class="bg-white w-full max-h-[90vh] overflow-y-auto rounded-t-2xl p-6 relative"
        >
            <!-- Close Button -->
            <button 
                type="button" 
                @click="showRsvpModal = false" 
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 font-bold p-1 rounded-full text-lg cursor-pointer z-10"
            >
                ✕
            </button>

            <div class="mt-2">
                @if ($guest->rsvp)
                    @include('invite.confirmation', ['guest' => $guest, 'event' => $event, 'isEmbedded' => true])
                @else
                    @include('invite.rsvp', ['event' => $event, 'guest' => $guest])
                @endif
            </div>
        </div>
    </div>

</body>
</html>