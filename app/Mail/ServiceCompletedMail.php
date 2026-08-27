<?php

namespace App\Mail;

use App\Models\ServiceOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceCompletedMail extends Mailable
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
            subject: 'Perbaikan Servis Selesai & Kartu Garansi - ' . $this->order->service_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.service-completed',
            with: [
                'order' => $this->order,
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
        try {
            $pdf = Pdf::loadView('pdf.warranty', ['serviceOrder' => $this->order]);
            return [
                Attachment::fromData(fn () => $pdf->output(), 'Kartu-Garansi-' . $this->order->service_code . '.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
