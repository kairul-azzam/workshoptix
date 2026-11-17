<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Services\MidtransService;

class RegistrationController extends Controller
{
    // ---------- Daftar pendaftaran milik user yang login ----------
    public function index(Request $request)
    {
        // with('workshop') = eager loading, supaya tidak N+1
        $pendaftaran = $request->user()
            ->registrations()
            ->with('workshop')
            ->orderByDesc('id')
            ->get();

        return view('registrations.index', compact('pendaftaran'));
    }

    // ---------- Form pendaftaran ----------
    public function create($id)
    {
        $workshop = Workshop::withTerisi()->findOrFail($id);

        $sudahDaftar = Registration::where('user_id', auth()->id())
            ->where('workshop_id', $workshop->id)
            ->exists();

        if ($sudahDaftar) {
            return redirect()->route('workshops.show', $workshop->id)
                ->with('galat', 'Kamu sudah mendaftar di workshop ini.');
        }

        if ($workshop->sisa === 0) {
            return redirect()->route('workshops.show', $workshop->id)
                ->with('galat', 'Maaf, kursi workshop ini sudah habis.');
        }

        return view('registrations.create', compact('workshop'));
    }

    // ---------- Simpan pendaftaran ----------
    public function store(Request $request, $id): RedirectResponse
    {
        // Hanya kelas dan WhatsApp yang diambil dari form.
        // Nama dan email dari akun login, harga dari database. Bukan dari input.
        $data = $request->validate([
            'kelas'    => ['required', 'in:X RPL,XI RPL,XII RPL,Lainnya'],
            'whatsapp' => ['required', 'regex:/^08[0-9]{8,12}$/'],
            'setuju'   => ['accepted'],
        ], [
            'kelas.required'    => 'Pilih kelasmu.',
            'kelas.in'          => 'Kelas yang dipilih tidak valid.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex'    => 'Nomor WhatsApp harus diawali 08 dan hanya berisi angka (10 sampai 14 digit).',
            'setuju.accepted'   => 'Kamu harus menyetujui aturan workshop.',
        ]);

        $user = $request->user();
        $workshop = Workshop::findOrFail($id);

        // Tolak pendaftaran ganda (lapisan 1: cek di aplikasi)
        $sudahDaftar = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->exists();

        if ($sudahDaftar) {
            return redirect()->route('workshops.show', $workshop->id)
                ->with('galat', 'Kamu sudah mendaftar di workshop ini.');
        }

        try {
            $registrasi = DB::transaction(function () use ($workshop, $user, $data) {
                // Kunci baris workshop supaya dua pendaftaran bersamaan tidak melebihi kuota
                $terkunci = Workshop::whereKey($workshop->id)->lockForUpdate()->first();

                $terisi = $terkunci->registrations()->where('status_bayar', '!=', 'gagal')->count();
                if ($terisi >= $terkunci->kuota) {
                    return null;
                }

                return Registration::create([
                    'user_id'      => $user->id,
                    'workshop_id'  => $terkunci->id,
                    'kelas'        => $data['kelas'],
                    'whatsapp'     => $data['whatsapp'],
                    'order_id'     => 'WTX-ORD-' . strtoupper(Str::random(8)),
                    'status_bayar' => 'pending',
                ]);
            });
        } catch (UniqueConstraintViolationException $e) {
            // Tolak pendaftaran ganda (lapisan 2: unique di database)
            return redirect()->route('workshops.show', $workshop->id)
                ->with('galat', 'Kamu sudah mendaftar di workshop ini.');
        }

        if (! $registrasi) {
            return redirect()->route('workshops.show', $workshop->id)
                ->with('galat', 'Maaf, kursi workshop ini sudah habis.');
        }

        return redirect()->route('registrations.status', $registrasi->id)
            ->with('sukses', 'Pendaftaran berhasil. Silakan lanjut ke pembayaran.');
    }

    // ---------- Status pembayaran ----------
    public function status($id, MidtransService $midtrans)
    {
        $registrasi = Registration::with(['workshop', 'user'])->findOrFail($id);

        Gate::authorize('view', $registrasi);

        // Workshop gratis (harga 0): langsung lunas, tidak lewat Midtrans
        if ($registrasi->status_bayar === 'pending' && (int) $registrasi->workshop->harga === 0) {
            $midtrans->bayarGratis($registrasi);
        }

        $snapToken = null;

        if ($registrasi->status_bayar === 'pending') {
            try {
                $snapToken = $midtrans->tokenFor($registrasi);
            } catch (\Throwable $e) {
                report($e);
                session()->now('galat', 'Pembayaran belum bisa diproses saat ini. Hubungi panitia.');
            }
        }

        return view('registrations.status', compact('registrasi', 'snapToken'));
    }

    // ---------- Tiket digital ----------
    public function ticket($id)
    {
        $registrasi = Registration::with(['workshop', 'user'])->findOrFail($id);

        // Lapisan 1: hanya pemilik
        Gate::authorize('view', $registrasi);

        // Lapisan 2: data tiket hanya dikirim ke tampilan jika sudah lunas
        // (pengecekan ada di view lewat $registrasi->isLunas())
        return view('registrations.ticket', compact('registrasi'));
    }
}
