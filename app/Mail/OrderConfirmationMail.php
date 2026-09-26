<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load(['orderItems.product']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $isPaid = in_array($this->order->payment_status, ['paid', 'dp_paid', 'settlement', 'capture', 'success']);
        $fromAddress = config('mail.from.address', 'support@prokarelektronik.com');
        $fromName = config('mail.from.name', 'Prokar Elektronik');

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromAddress, $fromName),
            replyTo: [new \Illuminate\Mail\Mailables\Address($fromAddress, $fromName)],
            subject: ($isPaid ? 'Konfirmasi Pembayaran Pesanan - ' : 'Konfirmasi Pesanan - ') . $this->order->order_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $isPaid = in_array($this->order->payment_status, ['paid', 'dp_paid', 'settlement', 'capture', 'success']);
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'isPaid' => $isPaid,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $isPaid = in_array($this->order->payment_status, ['paid', 'dp_paid', 'settlement', 'capture', 'success']);
        
        // Jangan lampirkan invoice PDF jika pesanan belum dibayar
        if (!$isPaid) {
            return [];
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', ['order' => $this->order]);
            return [
                \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'Invoice-' . $this->order->order_code . '.pdf')
                    ->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
