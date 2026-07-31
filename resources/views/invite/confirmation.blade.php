<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Confirmed</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8 text-center">

            @if ($guest->rsvp->status === 'attending')
                <div class="text-5xl mb-4">🎉</div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Thank You, {{ $guest->name }}!</h1>
                <p class="text-gray-600 mb-4">
                    We've received your RSVP. We're excited to see you there!
                </p>
            @else
                <div class="text-5xl mb-4">💌</div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Thanks for Letting Us Know</h1>
                <p class="text-gray-600 mb-4">
                    We're sorry you can't make it, {{ $guest->name }}. You'll be missed!
                </p>
            @endif

            <div class="border-t border-gray-200 pt-4 mt-4 text-left">
                <p class="text-sm text-gray-500 mb-1">Your Response</p>
                <p class="font-semibold mb-2">
                    {{ $guest->rsvp->status === 'attending' ? '✅ Attending' : '❌ Not Attending' }}
                </p>

                @if ($guest->rsvp->status === 'attending' && $guest->rsvp->companions_count > 0)
                    <p class="text-sm text-gray-500 mb-1">Companions</p>
                    <p class="font-semibold mb-2">{{ $guest->rsvp->companions_count }}</p>
                @endif

                @if ($guest->rsvp->message)
                    <p class="text-sm text-gray-500 mb-1">Your Message</p>
                    <p class="font-semibold">{{ $guest->rsvp->message }}</p>
                @endif
            </div>

        </div>
    </div>

</body>
</html>