<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Rules\ValidCpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule as ValidationRule;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->contacts()->get();
        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'cpf' => ['required', new ValidCpf],
            'nome' => 'required|string|max:255',
            'fone' => 'required|string|max:15',
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'uf' => 'required|string|max:2',
            'estado' => 'required|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $contact = Auth::user()->contacts()->create([
                "nome" => $request->nome,
                "cpf" => $request->cpf,
                "fone" => $request->fone,
                "cep" => $request->cep,
                "logradouro" => $request->logradouro,
                "complemento" => $request->complemento,
                "bairro" => $request->bairro,
                "uf" => $request->uf,
                "estado" => $request->estado,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
            ]);

            return response()->json(['contato' => $contact], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro inesperado ' . $e], 500);
        }
    }

    public function show($id)
    {
        $contact = Auth::user()->contacts()->findOrFail($id);
        return response()->json($contact);
    }

    public function update(Request $request, $id)
    {        
        // Validação dos dados
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'cpf' => [
                'required',
                'string',
                'max:14',
                ValidationRule::unique('contacts')->ignore($id),
                new ValidCpf
            ],
            'fone' => 'required|string|max:15',
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'uf' => 'required|string|max:2',
            'estado' => 'required|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Encontrar o contato do usuário autenticado
            $contact = Auth::user()->contacts()->findOrFail($id);

            // Atualizar o contato
            $contact->update($request->all());

            return response()->json($contact, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Contato não encontrado'], 404);
        } catch (\Exception $e) {
            // Tratamento de exceções genéricas
            return response()->json(['error' => 'Ocorreu um erro inesperado'], 500);
        }
    }

    public function destroy($id)
    {
        $contact = Auth::user()->contacts()->findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Contato excluído com sucesso']);
    }

    public function filtrarContatos(Request $request)
    {
        $termo = $request->termo;

        $contatos = Contact::where(function ($query) use ($termo) {
            $query->where('nome', 'like', "%$termo%")
                ->orWhere('cpf', 'like', "%$termo%");
        })->get();

        return response()->json($contatos);
    }
}
