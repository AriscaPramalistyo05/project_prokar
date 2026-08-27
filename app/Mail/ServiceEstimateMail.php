<?php

namespace App\Mail;

use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceEstimateMail extends Mailable
{
    use Queueable, SerializesModels;

    public ServiceOrder $order;

    /**
     * Create a new message instance.
     */
    public function __construct(ServiceOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Estimasi Biaya Servis Siap - ' . $this->order->service_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.service-estimate',
            with: [
                'order' => $this->order,
            ]
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
