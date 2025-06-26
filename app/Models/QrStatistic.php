<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrStatistic extends Model
{
    protected $fillable = [
        'qr_code_id',
        'type',
        'ip_address',
        'user_agent'
    ];

    // Связь с моделью QR-кода (если она есть)
    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }
}