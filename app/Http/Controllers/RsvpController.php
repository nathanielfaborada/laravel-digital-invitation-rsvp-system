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
        $event = $guest->event;
        if ($guest->rsvp) {
            return view('invite.confirmation', compact('guest', 'event'));
        }

        return view('invite.show', compact('guest', 'event'))->with('openRsvp', true);
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

        $maxAllowed = max($guest->max_companions ?? 1, 1);

        if ($request->has('attending') && !$request->has('status')) {
            $request->merge([
                'status' => $request->input('attending') === 'yes' ? 'attending' : 'not_attending',
            ]);
        }

        $validated = $request->validate([
            'status' => 'required|in:attending,not_attending',
            'attending' => 'nullable|in:yes,no',
            'has_companion' => 'nullable',
            'companion_name' => ['nullable', 'string', 'max:255', 'regex:/^(?![=\+\-@]).*/i'],
            'companions_count' => "nullable|integer|min:0|max:{$maxAllowed}",
            'message' => ['nullable', 'string', 'max:1000', 'regex:/^(?![=\+\-@]).*/i'],
        ], [
            'companion_name.regex' => 'Field inputs cannot start with special formula characters like =, +, -, or @.',
            'message.regex' => 'Field inputs cannot start with special formula characters like =, +, -, or @.',
        ]);

        $isAttending = ($validated['status'] === 'attending') || ($request->input('attending') === 'yes');
        $hasCompanion = $isAttending && ($request->boolean('has_companion') || ($request->input('has_companion') == '1') || (($validated['companions_count'] ?? 0) > 0));

        $validated['status'] = $isAttending ? 'attending' : 'not_attending';
        $validated['guest_id'] = $guest->id;
        $validated['companions_count'] = $hasCompanion ? 1 : 0;
        $validated['companion_name'] = $hasCompanion ? ($validated['companion_name'] ?? null) : null;
        $validated['responded_at'] = now();

        Rsvp::create($validated);

        $guest->load('rsvp');
        $event = $guest->event;

        return view('invite.confirmation', compact('guest', 'event'));
    }
}