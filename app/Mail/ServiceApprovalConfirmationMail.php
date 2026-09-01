<?php

namespace App\Mail;

use App\Models\ServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceApprovalConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ServiceOrder $order,
        public string $action = 'approved' // 'approved' or 'rejected'
    ) {
        $this->order->loadMissing('category');
    }

    public function envelope(): Envelope
    {
        $subject = $this->action === 'approved'
            ? 'Persetujuan Estimasi Diterima (Perbaikan Dimulai) - ' . $this->order->service_code
            : 'Konfirmasi Penolakan Estimasi Servis - ' . $this->order->service_code;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-approval-confirmation',
            with: [
                'order' => $this->order,
                'action' => $this->action,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
