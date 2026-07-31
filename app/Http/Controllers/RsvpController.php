<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    /**
     * Show the RSVP form for a guest.
     */
    public function create(Guest $guest)
    {
        // Kung naka-RSVP na, dumiretso na sa confirmation view
        if ($guest->rsvp) {
            return view('invite.confirmation', compact('guest'));
        }

        $event = $guest->event;
        return view('invite.rsvp', compact('guest', 'event'));
    }

    /**
     * Store the RSVP response.
     */
    public function store(Request $request, Guest $guest)
    {
        // Prevent duplicate RSVP
        if ($guest->rsvp) {
            return redirect()->route('rsvp.create', $guest);
        }

        $validated = $request->validate([
            'status' => 'required|in:attending,not_attending',
            'companions_count' => 'nullable|integer|min:0|max:' . $guest->max_companions,
            'message' => 'nullable|string|max:500',
        ]);

        $validated['guest_id'] = $guest->id;
        $validated['companions_count'] = $validated['companions_count'] ?? 0;
        $validated['responded_at'] = now();

        Rsvp::create($validated);

        $guest->load('rsvp');

        return view('invite.confirmation', compact('guest'));
    }
}