<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;


    public $mailshot;

    /**
     * Create a new message instance.
     */
    public function __construct($mailshot)
    {
        //
        $this->mailshot = $mailshot;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('monster@trialmonster.uk', 'TrialMonster'),
            replyTo: [
                new Address($this->mailshot->reply_to_address,
                $this->mailshot->reply_to_name,),
            ],
            subject: $this->mailshot->subject
//        replyTo: $this->mailshot->reply_to_address
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            'mails.dev',
            with: [
                'mailshot' => $this->mailshot,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path('attachments/'.$this->mailshot->fileName))
                ->as($this->mailshot->originalName)
                ->withMime($this->mailshot->mimeType),
        ];
    }
}
