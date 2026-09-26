<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User   $user,
        public readonly string $otp,
    ) {}

    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address', 'support@prokarelektronik.com');
        $fromName = config('mail.from.name', 'Prokar Elektronik');

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromAddress, $fromName),
            replyTo: [new \Illuminate\Mail\Mailables\Address($fromAddress, $fromName)],
            subject: 'Kode Verifikasi OTP — ' . $fromName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            text: 'emails.otp-text',
            with: [
                'verifyUrl' => route('auth.otp.auto', ['id' => $this->user->id, 'code' => $this->otp]),
            ],
        );
    }
}
