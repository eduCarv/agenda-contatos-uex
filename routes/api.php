<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EsqueciSenhaController;

// Prefixo de versão para facilitar o versionamento da API
Route::prefix('v1')->group(function () {
    
    Route::post('/novo-usuario', [UserController::class, 'novoUsuario']);
    Route::post('/login', [UserController::class, 'login']);

    //Rotas para recuperação de senha
    Route::post('/esqueci-senha', [EsqueciSenhaController::class, 'enviaResetLinkEmail']);
    Route::post('/reset-senha', [UserController::class, 'resetSenha']);
    
    //Rotas autenticadas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/user', [UserController::class, 'getUser']);
    });
});
