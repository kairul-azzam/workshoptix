<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

/**
 * Semua urusan Midtrans ada di sini, supaya controller tetap pendek.
 *
 * Prinsip penting:
 *  1. Nominal SELALU dari database (harga workshop), bukan dari input pengguna.
 *  2. Status lunas SELALU diverifikasi ke server Midtrans lewat backend.
 *     Hasil di browser (callback snap.js) tidak dipercaya.
 */
class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // ---------- 1. Buat (atau pakai ulang) Snap token ----------

    public function tokenFor(Registration $registrasi): string
    {
        if (blank(config('midtrans.server_key'))) {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diisi di .env');
        }

        // Token yang sudah dibuat dipakai lagi. order_id yang sama tidak boleh dibuat dua kali.
        if ($registrasi->snap_token) {
            return $registrasi->snap_token;
        }

        $workshop = $registrasi->workshop;
        $user     = $registrasi->user;
        $harga    = (int) $workshop->harga;   // dari database

        $params = [
            'transaction_details' => [
                'order_id'     => $registrasi->order_id,
                'gross_amount' => $harga,
            ],
            'item_details' => [[
                'id'       => 'WS-' . $workshop->id,
                'price'    => $harga,
                'quantity' => 1,
                'name'     => Str::limit($workshop->judul, 50, ''),
            ]],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $registrasi->whatsapp,
            ],
        ];

        $token = Snap::getSnapToken($params);

        $registrasi->update(['snap_token' => $token]);

        return $token;
    }

    // ---------- 2. Verifikasi status ke Midtrans, lalu simpan ----------

    /**
     * @return string status terbaru: 'pending', 'lunas', atau 'gagal'
     */
    public function sinkronkan(Registration $registrasi): string
    {
        // Yang sudah lunas tidak diubah lagi
        if ($registrasi->isLunas()) {
            return 'lunas';
        }

        $hasil = $this->ambilStatus($registrasi->order_id);

        // Transaksi belum tercatat di Midtrans (pengguna belum memilih metode bayar)
        if ($hasil === null) {
            return $registrasi->status_bayar;
        }

        $baru = $this->petakanStatus($hasil);

        if ($baru === 'lunas') {
            // Pastikan nominal yang dibayar sama dengan harga di database
            $dibayar = (int) round((float) ($hasil->gross_amount ?? 0));

            if ($dibayar !== (int) $registrasi->workshop->harga) {
                Log::warning('Nominal Midtrans tidak cocok', [
                    'order_id' => $registrasi->order_id,
                    'dibayar'  => $dibayar,
                    'harga'    => $registrasi->workshop->harga,
                ]);

                return $registrasi->status_bayar;
            }

            $this->tandaiLunas($registrasi);

            return 'lunas';
        }

        if ($baru !== $registrasi->status_bayar) {
            $registrasi->update(['status_bayar' => $baru]);
        }

        return $baru;
    }

    // ---------- 3. Coba bayar lagi setelah gagal ----------

    public function siapkanUlang(Registration $registrasi): void
    {
        // Midtrans tidak mengizinkan order_id lama dipakai lagi, jadi dibuat yang baru
        $registrasi->update([
            'order_id'     => 'WTX-ORD-' . strtoupper(Str::random(8)),
            'snap_token'   => null,
            'status_bayar' => 'pending',
        ]);
    }

    // ---------- 4. Workshop gratis (harga 0) ----------

    public function bayarGratis(Registration $registrasi): void
    {
        $this->tandaiLunas($registrasi);
    }

    // ---------- Bagian internal ----------

    private function tandaiLunas(Registration $registrasi): void
    {
        $registrasi->update([
            'status_bayar' => 'lunas',
            'kode_tiket'   => $registrasi->kode_tiket
                ?? 'WTX-' . now()->year . '-' . str_pad($registrasi->id, 5, '0', STR_PAD_LEFT),
        ]);
    }

    // Mengembalikan null jika transaksi belum ada di Midtrans
    private function ambilStatus(string $orderId): ?object
    {
        try {
            $hasil = Transaction::status($orderId);
        } catch (\Exception $e) {
            if ((int) $e->getCode() === 404) {
                return null;
            }
            throw $e;
        }

        if (isset($hasil->status_code) && (string) $hasil->status_code === '404') {
            return null;
        }

        return $hasil;
    }

    // Terjemahkan status Midtrans ke status di aplikasi kita
    private function petakanStatus(object $hasil): string
    {
        $status = $hasil->transaction_status ?? '';
        $fraud  = $hasil->fraud_status ?? 'accept';

        return match (true) {
            $status === 'settlement'                                  => 'lunas',
            $status === 'capture' && $fraud === 'accept'              => 'lunas',
            in_array($status, ['deny', 'cancel', 'expire', 'failure'], true) => 'gagal',
            default                                                   => 'pending',
        };
    }
}