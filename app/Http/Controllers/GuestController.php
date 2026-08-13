<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Mail\InviteGuestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $this->authorize('view', $event);

        $guests = $event->guests()->with('rsvp')->latest()->get();

        $stats = [
            'total_invited' => $guests->count(),
            'attending' => $guests->filter(fn($g) => $g->rsvp?->status === 'attending')->count(),
            'not_attending' => $guests->filter(fn($g) => $g->rsvp?->status === 'not_attending')->count(),
            'pending' => $guests->filter(fn($g) => $g->rsvp === null)->count(),
            'total_headcount' => $guests->filter(fn($g) => $g->rsvp?->status === 'attending')
                ->sum(fn($g) => 1 + $g->rsvp->companions_count),
        ];

        return view('guests.index', compact('event', 'guests', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);

        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Cannot add or modify guests for a past event.');
        }

        return view('guests.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Cannot add or modify guests for a past event.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'max_companions' => 'nullable|integer|min:0',
        ]);

        $exists = Guest::where('event_id', $event->id)
            ->where(function ($q) use ($request) {
                if ($request->filled('email')) {
                    $q->where('email', $request->email);
                } else {
                    $q->where('name', $request->name);
                }
            })->exists();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Guest already exists in this event list!');
        }

        $validated['event_id'] = $event->id;
        $validated['max_companions'] = $validated['max_companions'] ?? 1;

        $guest = Guest::create($validated);

        if ($guest->email) {
            Mail::to($guest->email)->send(new InviteGuestMail($guest));
            return redirect()->route('events.show', $event)->with('success', 'Guest added and invite sent!');
        }

        return redirect()->route('events.show', $event)->with('success', 'Guest added successfully! (No email provided, invite not sent)');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        $this->authorize('view', $guest->event);
        return view('guests.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        $this->authorize('update', $guest->event);

        $event = $guest->event;
        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Cannot add or modify guests for a past event.');
        }

        return redirect()->route('events.show', $event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        $this->authorize('update', $guest->event);

        $event = $guest->event;
        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Cannot add or modify guests for a past event.'], 422);
            }
            return redirect()->route('events.show', $event)->with('error', 'Cannot add or modify guests for a past event.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'max_companions' => 'nullable|integer|min:0',
            'status' => 'nullable|in:pending,attending,not_attending',
        ]);

        $guest->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'max_companions' => $validated['max_companions'] ?? 0,
        ]);

        if (array_key_exists('status', $validated) && $validated['status'] !== null) {
            if ($validated['status'] === 'pending') {
                if ($guest->rsvp) {
                    $guest->rsvp->delete();
                }
            } else {
                if ($guest->rsvp) {
                    $guest->rsvp->update(['status' => $validated['status']]);
                } else {
                    $guest->rsvp()->create([
                        'status' => $validated['status'],
                        'companions_count' => 0,
                        'responded_at' => now(),
                    ]);
                }
            }
        }

        $guest->load('rsvp');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Guest updated successfully!',
                'guest' => $guest,
            ]);
        }

        return redirect()->route('events.show', $guest->event)->with('success', 'Guest updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        $this->authorize('update', $guest->event);

        $event = $guest->event;
        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->route('events.show', $event)->with('error', 'Cannot add or modify guests for a past event.');
        }

        $guest->delete();

        return redirect()->route('events.show', $event)->with('success', 'Guest deleted successfully!');
    }

    /**
     * Remove multiple specified resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'guest_ids' => 'required|array|min:1',
            'guest_ids.*' => 'required|integer|exists:guests,id',
        ]);

        $guests = Guest::whereIn('id', $request->guest_ids)
            ->whereHas('event', fn($q) => $q->where('user_id', auth()->id()))
            ->get();

        if ($guests->isEmpty()) {
            return redirect()->back()->with('error', 'No valid guests selected for deletion.');
        }

        $count = $guests->count();
        Guest::whereIn('id', $guests->pluck('id'))->delete();

        return redirect()->back()->with('success', $count . ' guests deleted successfully!');
    }

    public function export(Event $event)
    {
        $this->authorize('view', $event);

        $guests = $event->guests()->with('rsvp')->get();

        $filename = 'guests-' . str_replace(' ', '-', strtolower($event->title)) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($guests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Max Companions', 'RSVP Status', 'Companions Bringing', 'Message']);

            foreach ($guests as $guest) {
                fputcsv($file, [
                    $guest->name,
                    $guest->email,
                    $guest->phone,
                    $guest->max_companions,
                    $guest->rsvp?->status ?? 'pending',
                    $guest->rsvp?->companions_count ?? '-',
                    $guest->rsvp?->message ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function sendInvite(Guest $guest)
    {
        $this->authorize('update', $guest->event);

        $event = $guest->event;
        $eventDateTime = \Carbon\Carbon::parse($event->event_date . ' ' . ($event->event_time ?? '23:59:59'));
        if ($eventDateTime->isPast()) {
            return redirect()->back()->with('error', 'Cannot send invitations for a past event.');
        }

        if (!$guest->email) {
            return redirect()->back()->with('error', 'This guest has no email address.');
        }

        Mail::to($guest->email)->send(new InviteGuestMail($guest));

        return redirect()->back()->with('success', 'Invitation sent to ' . $guest->name . '!');
    }
}