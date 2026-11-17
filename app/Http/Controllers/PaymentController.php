<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function __construct(private MidtransService $midtrans)
    {
    }

    // Tombol "Cek status pembayaran": tanya ke Midtrans lewat backend
    public function cekStatus($id): RedirectResponse
    {
        $registrasi = Registration::with(['workshop', 'user'])->findOrFail($id);

        Gate::authorize('view', $registrasi);

        try {
            $status = $this->midtrans->sinkronkan($registrasi);
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('registrations.status', $registrasi->id)
                ->with('galat', 'Belum bisa menghubungi Midtrans. Coba lagi beberapa saat.');
        }

        $pesan = match ($status) {
            'lunas' => ['sukses', 'Pembayaran terverifikasi. Tiketmu sudah bisa dibuka.'],
            'gagal' => ['galat', 'Pembayaran gagal atau sudah kedaluwarsa. Kamu bisa mencoba lagi.'],
            default => ['info', 'Pembayaran belum terdeteksi. Jika sudah membayar, tunggu sebentar lalu cek lagi.'],
        };

        return redirect()->route('registrations.status', $registrasi->id)
            ->with($pesan[0], $pesan[1]);
    }

    // Tombol "Coba bayar lagi": hanya untuk pendaftaran yang gagal
    public function bayarUlang($id): RedirectResponse
    {
        $registrasi = Registration::findOrFail($id);

        Gate::authorize('view', $registrasi);

        if ($registrasi->status_bayar !== 'gagal') {
            return redirect()->route('registrations.status', $registrasi->id)
                ->with('galat', 'Pembayaran ini tidak bisa diulang.');
        }

        $this->midtrans->siapkanUlang($registrasi);

        return redirect()->route('registrations.status', $registrasi->id)
            ->with('info', 'Silakan lakukan pembayaran lagi.');
    }
}