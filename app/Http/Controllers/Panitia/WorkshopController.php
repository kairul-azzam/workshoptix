<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    // Dashboard: ringkasan angka + tabel kelola workshop
    public function dashboard()
    {
        $workshops = Workshop::withCount('registrations')
            ->orderBy('tanggal')
            ->get();

        $stat = [
            'workshop' => $workshops->count(),
            'pendaftar' => Registration::count(),
            'lunas' => Registration::where('status_bayar', 'lunas')->count(),
            'pending' => Registration::where('status_bayar', 'pending')->count(),
        ];

        return view('panitia.dashboard', compact('workshops', 'stat'));
    }

    public function create()
    {
        return view('panitia.workshops.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), $this->messages());

        Workshop::create($data);

        return redirect()->route('panitia.dashboard')
            ->with('sukses', 'Workshop berhasil ditambahkan.');
    }

    public function edit(Workshop $workshop)
    {
        return view('panitia.workshops.edit', compact('workshop'));
    }

    public function update(Request $request, Workshop $workshop): RedirectResponse
    {
        $data = $request->validate($this->rules(), $this->messages());

        $workshop->update($data);

        return redirect()->route('panitia.dashboard')
            ->with('sukses', 'Workshop berhasil diperbarui.');
    }

    public function destroy(Workshop $workshop): RedirectResponse
    {
        // Workshop yang sudah punya pendaftar tidak boleh dihapus,
        // karena data pendaftaran dan pembayaran ikut hilang.
        if ($workshop->registrations()->exists()) {
            return redirect()->route('panitia.dashboard')
                ->with('galat', 'Workshop tidak bisa dihapus karena sudah ada pendaftar.');
        }

        $workshop->delete();

        return redirect()->route('panitia.dashboard')
            ->with('sukses', 'Workshop berhasil dihapus.');
    }

    // Aturan validasi dipakai bersama oleh store dan update
    private function rules(): array
    {
        return [
            'judul'     => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string', 'max:2000'],
            'pemateri'  => ['required', 'string', 'max:100'],
            'tanggal'   => ['required', 'date'],
            'jam'       => ['required', 'date_format:H:i'],
            'lokasi'    => ['required', 'string', 'max:100'],
            'harga'     => ['required', 'integer', 'min:0', 'max:10000000'],
            'kuota'     => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }

    private function messages(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'max'         => ':attribute terlalu panjang atau terlalu besar.',
            'integer'     => ':attribute harus berupa angka bulat.',
            'min'         => ':attribute tidak boleh kurang dari batas minimum.',
            'date'        => 'Format :attribute tidak benar.',
            'date_format' => 'Format :attribute harus seperti 09:00.',
        ];
    }
}