<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panitia\WorkshopController as PanitiaWorkshopController;
use App\Http\Controllers\Panitia\PendaftarController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WorkshopController;

// ---------- Terbuka untuk semua ----------
Route::get('/', [WorkshopController::class, 'index'])->name('home');
Route::get('/workshops/{id}', [WorkshopController::class, 'show'])->name('workshops.show');

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

    Route::get('/workshops/{id}/daftar', [RegistrationController::class, 'create'])->name('registrations.create');
    Route::post('/workshops/{id}/daftar', [RegistrationController::class, 'store'])->name('registrations.store');

    // Masih dummy, diganti di Langkah 04
    Route::get('/pendaftaran/{id}/status', function ($id) {
        return view('registrations.status', ['id' => $id]);
    })->name('registrations.status');

    Route::get('/pendaftaran/{id}/tiket', function ($id) {
        return view('registrations.ticket', ['id' => $id]);
    })->name('registrations.ticket');
});

// ---------- Hanya panitia ----------
// ---------- Hanya panitia ----------
Route::middleware(['auth', 'role:panitia'])
    ->prefix('panitia')
    ->name('panitia.')
    ->group(function () {
        Route::get('/', [PanitiaWorkshopController::class, 'dashboard'])->name('dashboard');

        Route::get('/workshops/create', [PanitiaWorkshopController::class, 'create'])->name('workshops.create');
        Route::post('/workshops', [PanitiaWorkshopController::class, 'store'])->name('workshops.store');
        Route::get('/workshops/{workshop}/edit', [PanitiaWorkshopController::class, 'edit'])->name('workshops.edit');
        Route::put('/workshops/{workshop}', [PanitiaWorkshopController::class, 'update'])->name('workshops.update');
        Route::delete('/workshops/{workshop}', [PanitiaWorkshopController::class, 'destroy'])->name('workshops.destroy');
        Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
    });
