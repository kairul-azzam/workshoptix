<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Tambahkan kolom terisi_count: jumlah kursi yang sudah dipakai.
     * Pendaftaran yang gagal tidak menahan kursi.
     * Pakai scope ini saat mengambil banyak workshop supaya tidak N+1.
     */
    public function scopeWithTerisi(Builder $query): Builder
    {
        return $query->withCount([
            'registrations as terisi_count' => fn ($q) => $q->where('status_bayar', '!=', 'gagal'),
        ]);
    }

    // Sisa kursi = kuota - kursi terpakai
    public function getSisaAttribute(): int
    {
        $terisi = array_key_exists('terisi_count', $this->attributes)
            ? (int) $this->attributes['terisi_count']
            : $this->registrations()->where('status_bayar', '!=', 'gagal')->count();

        return max(0, $this->kuota - $terisi);
    }

    // Contoh: 22 Nov 2026
    public function getTanggalTampilAttribute(): string
    {
        return $this->tanggal->translatedFormat('d M Y');
    }

    // Contoh: 09.00 WIB
    public function getJamTampilAttribute(): string
    {
        return str_replace(':', '.', substr($this->jam, 0, 5)) . ' WIB';
    }
}