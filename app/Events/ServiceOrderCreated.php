<?php

namespace App\Events;

use App\Models\ServiceOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceOrderCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ServiceOrder $serviceOrder
    ) {}
}
