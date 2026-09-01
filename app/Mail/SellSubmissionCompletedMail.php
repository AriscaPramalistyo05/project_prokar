<?php

namespace App\Mail;

use App\Models\SellSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellSubmissionCompletedMail extends Mailable
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
            subject: 'Bukti Pembayaran Jual Barang Selesai - ' . $this->submission->submission_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sell-submission-completed',
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
