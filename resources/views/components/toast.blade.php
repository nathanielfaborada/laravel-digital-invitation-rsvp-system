@props([
    'message' => session('success') ?? session('error'),
    'type' => session('error') ? 'error' : 'success',
    'duration' => 4000
])

<x-toast-notification :message="$message" :type="$type" :duration="$duration" {{ $attributes }} />
