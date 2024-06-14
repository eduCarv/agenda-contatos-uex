<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Prefixo de versão para facilitar o versionamento da API
Route::prefix('v1')->group(function () {
    
    Route::post('/novo-usuario', [UserController::class, 'novoUsuario']);
    Route::post('/login', [UserController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/user', [UserController::class, 'getUser']);
    });
});
