<?php

use Illuminate\Support\Facades\Route;

Route::get('/reset-senha/{token}', function ($token) {
    return view('auth.reset-senha', ['token' => $token]);
})->name('password.reset');