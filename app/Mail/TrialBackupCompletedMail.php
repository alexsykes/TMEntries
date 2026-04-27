<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialBackupCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($trialID)
    {
        $this->trialID = $trialID;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trial Backup Completed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.trial_backup_completed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {

        $attachments = [];
        $entryFilename = public_path("/backups/$this->trialID/Entries.json");
        $scoreFilename = public_path("/backups/$this->trialID/Scores.json");
        $trialFilename = public_path("/backups/$this->trialID/Trial.json");
        $entryCSVFilename = public_path("/backups/$this->trialID/entries.csv");
        $scoreCSVFilename = public_path("/backups/$this->trialID/scores.csv");
        $trialCSVFilename = public_path("/backups/$this->trialID/trials.csv");

        if (file_exists($entryFilename)) {
            array_push($attachments, Attachment::fromPath($entryFilename));
        }

        if (file_exists($scoreFilename)) {
            array_push($attachments, Attachment::fromPath($scoreFilename));
        }

        if (file_exists($trialFilename)) {
            array_push($attachments, Attachment::fromPath($trialFilename));
        }

        if (file_exists($entryCSVFilename)) {
            array_push($attachments, Attachment::fromPath($entryCSVFilename));
        }

        if (file_exists($scoreCSVFilename)) {
            array_push($attachments, Attachment::fromPath($scoreCSVFilename));
        }

        if (file_exists($trialCSVFilename)) {
            array_push($attachments, Attachment::fromPath($trialCSVFilename));
        }

        return $attachments;
    }
}
