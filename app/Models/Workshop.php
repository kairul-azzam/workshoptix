<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    protected $fillable = [
        'judul', 'deskripsi', 'pemateri', 'tanggal', 'jam', 'lokasi', 'harga', 'kuota',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
