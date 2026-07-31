<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function show(Guest $guest)
    {
        $event = $guest->event;
        return view('invite.show', compact('guest', 'event'));
    }
}