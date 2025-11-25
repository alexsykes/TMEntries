<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class WelcomeNewMember extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public object $club_member)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address('ammnewhouse@gmail.com', 'Amanda Newhouse'),
            ],
            subject: 'Welcome New Member',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.new_member_welcome',
            with: ['member' => $this->club_member],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $link1 = public_path('pdf/Trials_Rule_Book_2025.pdf');
//        $link2 = public_path('pdf/YCMCC_dummy_rules.pdf');
        return [
            Attachment::fromPath($link1)
                ->as('AMCA Trials Rule Book.pdf')
                ->withMime('application/pdf'),
//            Attachment::fromPath($link2)
//                ->as('Placeholder Rules.pdf')
//                ->withMime('application/pdf'),
        ];
    }
}
