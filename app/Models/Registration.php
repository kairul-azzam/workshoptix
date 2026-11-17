<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'user_id', 'workshop_id', 'kelas', 'whatsapp',
        'order_id', 'status_bayar', 'snap_token', 'kode_tiket',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function isLunas(): bool
    {
        return $this->status_bayar === 'lunas';
    }
}
