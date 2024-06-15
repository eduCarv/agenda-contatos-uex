<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [ContactController::class, 'index'])->name('dashboard');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
});

Route::post('/login', [UserController::class, 'login'])->name('login');

// Views 
Route::view('/login', 'auth.login')->name('login');
Route::view('/esqueci-senha', 'auth.esqueci-senha')->name('password.request');
Route::view('/reset-senha/{token}', 'auth.reset-senha')->name('password.reset');
