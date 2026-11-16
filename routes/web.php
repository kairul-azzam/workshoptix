<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'beranda');
Route::get('/workshops/{id}', function ($id) {
    return view('workshops.show', ['id' => $id]);
});