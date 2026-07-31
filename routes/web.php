<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\RsvpController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invite/{guest:unique_code}', [InviteController::class, 'show'])->name('invite.show');
Route::get('/invite/{guest:unique_code}/rsvp', [RsvpController::class, 'create'])->name('rsvp.create');
Route::post('/invite/{guest:unique_code}/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('events', EventController::class);
    Route::get('/events/{event}/guests/export', [GuestController::class, 'export'])->name('events.guests.export');
    Route::post('/guests/{guest}/send-invite', [GuestController::class, 'sendInvite'])->name('guests.send-invite');
    Route::resource('events.guests', GuestController::class)->shallow();
});

require __DIR__.'/auth.php';