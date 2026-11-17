<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ---------- Terbuka untuk semua ----------
Route::view('/', 'beranda');

Route::get('/workshops/{id}', function ($id) {
    return view('workshops.show', ['id' => $id]);
});

// ---------- Hanya yang belum login ----------
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ---------- Harus login (peserta dan panitia) ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/workshops/{id}/daftar', function ($id) {
        return view('registrations.create', ['id' => $id]);
    });

    Route::get('/pendaftaran/{id}/status', function ($id) {
        return view('registrations.status', ['id' => $id]);
    });

    Route::get('/pendaftaran/{id}/tiket', function ($id) {
        return view('registrations.ticket', ['id' => $id]);
    });
});

// ---------- Hanya panitia ----------
Route::middleware(['auth', 'role:panitia'])->group(function () {
    Route::view('/panitia', 'panitia.dashboard');
});