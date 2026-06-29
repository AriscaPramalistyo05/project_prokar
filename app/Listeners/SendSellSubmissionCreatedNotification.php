<?php

namespace App\Listeners;

use App\Events\SellSubmissionCreated;
use App\Services\FcmNotificationService;

class SendSellSubmissionCreatedNotification
{
    public function __construct(
        private readonly FcmNotificationService $fcm
    ) {}

    public function handle(SellSubmissionCreated $event): void
    {
        $submission = $event->sellSubmission;

        $this->fcm->sendToAdmins(
            title: '📦 Pengajuan Jual Baru',
            body:  "Pengajuan {$submission->submission_code} dari {$submission->customer_name}",
            data:  ['type' => 'sell', 'id' => (string) $submission->id]
        );
    }
}
