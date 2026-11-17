<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    // Daftar semua pendaftar, bisa disaring per workshop dan status bayar
    public function index(Request $request)
    {
        $query = Registration::query()->orderByDesc('id');

        if ($request->filled('workshop')) {
            $query->where('workshop_id', $request->integer('workshop'));
        }

        if (in_array($request->status, ['pending', 'lunas', 'gagal'], true)) {
            $query->where('status_bayar', $request->status);
        }

        // PERHATIAN (disengaja untuk Langkah 05):
        // Di sini belum ada with(['user', 'workshop']). Di view, $r->user dan
        // $r->workshop dipanggil di setiap baris, jadi tiap baris memicu 2 query
        // tambahan (masalah N+1). Hitung jumlah querynya dulu, lalu perbaiki
        // di Langkah 05 dengan eager loading.
        $pendaftar = $query->paginate(20)->withQueryString();

        $workshops = Workshop::orderBy('judul')->get(['id', 'judul']);

        return view('panitia.pendaftar.index', compact('pendaftar', 'workshops'));
    }
}