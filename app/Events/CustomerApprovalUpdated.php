<?php

namespace App\Events;

use App\Models\ServiceOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerApprovalUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ServiceOrder $serviceOrder,
        public readonly string $approval // 'approved' | 'rejected'
    ) {}
}
