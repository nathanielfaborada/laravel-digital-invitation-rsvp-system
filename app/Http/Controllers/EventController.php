<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = auth()->user()->events()->latest()->get();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:300',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'venue' => 'required|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'template' => 'nullable|in:classic,modern,floral',
        ], [
            'event_date.after_or_equal' => 'Event date cannot be in the past.',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $uploadedFile = Cloudinary::uploadApi()->upload($request->file('cover_image')->getRealPath(), [
                'folder' => 'invitr/event-covers',
            ]);
            $validated['cover_image'] = $uploadedFile['secure_url'];
        }

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $guests = $event->guests()->with('rsvp')->latest()->get();

        $stats = [
            'total_invited' => $guests->count(),
            'attending' => $guests->filter(fn($g) => $g->rsvp?->status === 'attending')->count(),
            'not_attending' => $guests->filter(fn($g) => $g->rsvp?->status === 'not_attending')->count(),
            'pending' => $guests->filter(fn($g) => $g->rsvp === null)->count(),
            'total_headcount' => $guests
                ->filter(fn($g) => $g->rsvp?->status === 'attending')
                ->sum(fn($g) => 1 + $g->rsvp->companions_count),
        ];

        return view('events.show', compact(
            'event',
            'guests',
            'stats'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Past events cannot be edited.');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Past events cannot be edited.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:300',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'venue' => 'required|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'template' => 'nullable|in:classic,modern,floral',
        ], [
            'event_date.after_or_equal' => 'Event date cannot be in the past.',
        ]);

        if ($request->hasFile('cover_image')) {
    $uploadedFile = Cloudinary::uploadApi()->upload($request->file('cover_image')->getRealPath(), [
        'folder' => 'invitr/event-covers',
    ]);
    $validated['cover_image'] = $uploadedFile['secure_url'];
}

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}