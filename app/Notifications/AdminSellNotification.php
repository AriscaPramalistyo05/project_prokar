<?php

namespace App\Notifications;

use App\Models\SellSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminSellNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SellSubmission $submission
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $device = trim(($this->submission->brand ?? '') . ' ' . ($this->submission->device_type ?? 'Barang Bekas'));

        return [
            'type'          => 'sell',
            'submission_id' => $this->submission->id,
            'code'          => $this->submission->submission_code,
            'title'         => 'Pengajuan Jual Baru',
            'message'       => "Penawaran {$this->submission->submission_code} ({$device}) dari {$this->submission->customer_name}",
            'url'           => route('admin.sell-submissions.index'),
            'icon'          => 'arrow-down-tray',
            'color'         => 'amber',
            'created_at'    => now()->toISOString(),
        ];
    }
}
