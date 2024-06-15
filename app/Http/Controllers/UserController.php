<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

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
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'senha' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        $usuario = User::where('email', $request->email)->first();        

        if (!$usuario || !Hash::check($request->senha, $usuario->password)) {                        
            return response()->json('Usuário ou senha incorreto(s)', 401);
        }

        $token = $usuario->createToken('api-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {        
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Usuário deslogado com sucesso'], 200);
    }

    public function resetSenha(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'senha' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'senha', 'senha_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 200);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
