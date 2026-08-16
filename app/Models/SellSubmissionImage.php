<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellSubmissionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_submission_id',
        'path',
        'type',
    ];

    public function sellSubmission(): BelongsTo
    {
        return $this->belongsTo(SellSubmission::class);
    }
}
