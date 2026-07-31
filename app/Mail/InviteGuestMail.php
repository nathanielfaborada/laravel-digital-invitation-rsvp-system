<?php

namespace App\Mail;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteGuestMail extends Mailable
{
    use Queueable, SerializesModels;

    public Guest $guest;

    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You're Invited: " . $this->guest->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite-guest',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}