<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = [
        'service_order_id',
        'path',
        'type',
        'media_type',
        'uploaded_by',
    ];

    // ── Relations ──
    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
