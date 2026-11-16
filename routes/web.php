<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'beranda');
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