<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class EnderecoController extends Controller
{
    public function pesquisarEnderecosViaCEP(Request $request)
    {
        $request->validate([
            'uf' => 'required|string|max:2',
            'cidade' => 'required|string|max:255',
            'trecho_endereco' => 'nullable|string|min:3|max:255',
        ]);

        $uf = $request->uf;
        $cidade = $request->cidade;
        $trechoEndereco = $request->trecho_endereco;
        
        $url = "https://viacep.com.br/ws/{$uf}/{$cidade}/{$trechoEndereco}/json/";

        try {
            $client = new Client();
            $response = $client->request('GET', $url);

            $enderecos = json_decode($response->getBody(), true);

            return response()->json($enderecos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar endereços.'], 500);
        }
    }
}
