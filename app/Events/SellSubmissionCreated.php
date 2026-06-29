<?php

namespace App\Events;

use App\Models\SellSubmission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellSubmissionCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly SellSubmission $sellSubmission
    ) {}
}
