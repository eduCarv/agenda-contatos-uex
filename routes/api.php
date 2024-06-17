<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\EnderecoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EsqueciSenhaController;

// Prefixo de versão para facilitar o versionamento da API
Route::prefix('v1')->group(function () {

    Route::post('/novo-usuario', [UserController::class, 'novoUsuario']);
    Route::post('/login', [UserController::class, 'login']);

    //Rotas para recuperação de senha
    Route::post('/esqueci-senha', [EsqueciSenhaController::class, 'enviaResetLinkEmail']);
    Route::post('/reset-senha', [EsqueciSenhaController::class, 'resetSenha']);

    //Rotas autenticadas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::get('/user', [UserController::class, 'getUser']);

        //Rotas de contatos
        Route::get('/contacts', [ContactController::class, 'index']); //Mostra todos os contatos
        Route::post('/contacts', [ContactController::class, 'store']); //cadastra novo contato
        Route::get('/contacts/{id}', [ContactController::class, 'show']); //mostra contato específico
        Route::put('/contacts/{id}', [ContactController::class, 'update']); //edita contato epecífico
        Route::delete('/contacts/{id}', [ContactController::class, 'destroy']); //exlcui contato específico
        Route::get('/filtrar-contatos', [ContactController::class, 'filtrarContatos']); //Filtro dos contatos
    

        //Rota de endereços
        Route::get('/autocompletar-enderecos', [EnderecoController::class, 'pesquisarEnderecosViaCEP']);    
    });
});
