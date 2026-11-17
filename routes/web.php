<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::view('/', 'beranda');
// Hanya untuk yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Hanya untuk yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/workshops/{id}', function ($id) {
    return view('workshops.show', ['id' => $id]);
});

Route::get('/workshops/{id}/daftar', function ($id) {
    return view('registrations.create', ['id' => $id]);
});

Route::get('/pendaftaran/{id}/status', function ($id) {
    return view('registrations.status', ['id' => $id]);
});

Route::get('/pendaftaran/{id}/tiket', function ($id) {
    return view('registrations.ticket', ['id' => $id]);
});

Route::view('/panitia', 'panitia.dashboard');