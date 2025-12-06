<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PopularUsersAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The popular users collection.
     */
    public Collection $popularUsers;

    /**
     * Create a new message instance.
     */
    public function __construct(Collection $popularUsers)
    {
        $this->popularUsers = $popularUsers;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔥 Popular Users Alert - ' . $this->popularUsers->count() . ' user(s) have 50+ likes!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.popular-users-alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}