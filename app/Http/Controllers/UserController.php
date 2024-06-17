<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function novoUsuario(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'senha' => 'required|string|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        $usuario = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
        ]);

        return response()->json($usuario, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'senha' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->senha, $user->password)) {
            return response()->json(['message' => 'Usuário ou senha incorretos'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Usuário deslogado com sucesso'], 200);
    }    

    public function getUser(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function excluirConta(Request $request)
    {                
        $request->validate([
            'senha' => 'required|string',
        ]);
     
        $usuario = Auth::user();

        // Verifica se a senha fornecida corresponde à senha do usuário
        if (!Hash::check($request->senha, $usuario->password)) {
            return response()->json(['message' => 'Senha incorreta'], 401);
        }

        // Deleta todos os contatos associados ao usuário
        $usuario->contacts()->delete();

        // Deleta o usuário
        $usuario->delete();

        return response()->json(['message' => 'Conta excluída com sucesso']);
    }
}
