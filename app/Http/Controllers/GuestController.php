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
        return view('guests.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'max_companions' => 'nullable|integer|min:0',
        ]);

        $validated['event_id'] = $event->id;

        $guest = Guest::create($validated);

        if ($guest->email) {
            Mail::to($guest->email)->send(new InviteGuestMail($guest));
            return redirect()->route('events.guests.index', $event)->with('success', 'Guest added and invite sent!');
        }

        return redirect()->route('events.guests.index', $event)->with('success', 'Guest added successfully! (No email provided, invite not sent)');
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
        return view('guests.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        $this->authorize('update', $guest->event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'max_companions' => 'nullable|integer|min:0',
        ]);

        $guest->update($validated);

        return redirect()->route('events.guests.index', $guest->event)->with('success', 'Guest updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        $this->authorize('update', $guest->event);
        $event = $guest->event;
        $guest->delete();

        return redirect()->route('events.guests.index', $event)->with('success', 'Guest deleted successfully!');
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

        if (!$guest->email) {
            return redirect()->back()->with('error', 'This guest has no email address.');
        }

        Mail::to($guest->email)->send(new InviteGuestMail($guest));

        return redirect()->back()->with('success', 'Invitation sent to ' . $guest->name . '!');
    }
}