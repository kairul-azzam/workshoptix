<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Workshop;

class WorkshopController extends Controller
{
    // Beranda: daftar semua workshop
    public function index()
    {
        $workshops = Workshop::withTerisi()->orderBy('tanggal')->get();

        return view('beranda', compact('workshops'));
    }

    // Detail satu workshop
    public function show($id)
    {
        $workshop = Workshop::withTerisi()->findOrFail($id);

        // Jika pengunjung sudah login dan sudah mendaftar, tampilkan statusnya
        $registrasi = auth()->check()
            ? Registration::where('user_id', auth()->id())->where('workshop_id', $workshop->id)->first()
            : null;

        return view('workshops.show', compact('workshop', 'registrasi'));
    }
}