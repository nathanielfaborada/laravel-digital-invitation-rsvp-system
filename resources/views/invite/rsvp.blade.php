<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP: {{ $event->title }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-lg w-full bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <p class="text-sm text-gray-500 uppercase tracking-wide mb-1 text-center">RSVP for</p>
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">{{ $event->title }}</h1>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rsvp.store', $guest) }}" method="POST">
                @csrf

                <p class="mb-4">Hi <span class="font-semibold">{{ $guest->name }}</span>, will you be attending?</p>

                <div class="mb-4 space-y-2">
                    <label class="flex items-center gap-2 border rounded-md p-3 cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="status" value="attending" required>
                        <span>Yes, I'll be there!</span>
                    </label>
                    <label class="flex items-center gap-2 border rounded-md p-3 cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="status" value="not_attending" required>
                        <span>Sorry, I can't make it</span>
                    </label>
                </div>

                @if ($guest->max_companions > 0)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            How many companions will you bring? (Max: {{ $guest->max_companions }})
                        </label>
                        <input type="number" name="companions_count" min="0" max="{{ $guest->max_companions }}" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                @endif

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Message (optional)</label>
                    <textarea name="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Leave a message for the host..."></textarea>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-indigo-700">
                    Submit RSVP
                </button>
            </form>
        </div>
    </div>

</body>
</html>