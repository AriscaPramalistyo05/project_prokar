<?php

namespace App\Mail;

use App\Models\SellSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellSubmissionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SellSubmission $submission
    ) {
        $this->submission->loadMissing('category');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pengajuan Jual Barang - ' . $this->submission->submission_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sell-submission-confirmation',
            with: [
                'submission' => $this->submission,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
