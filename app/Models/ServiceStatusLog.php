<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStatusLog extends Model
{
    protected $fillable = [
        'service_order_id',
        'status',
        'note',
        'changed_by',
    ];

    // ── Relations ──

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
