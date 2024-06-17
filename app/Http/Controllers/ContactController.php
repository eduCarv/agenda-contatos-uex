<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Rules\ValidCpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->contacts()->get();
        return response()->json($contacts);
    }

    public function store(Request $request)
    {        
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', 'unique:contacts', new ValidCpf],
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

        $contact = Auth::user()->contacts()->create($request->all());

        return response()->json($contact, 201);
    }

    public function show($id)
    {
        $contact = Auth::user()->contacts()->findOrFail($id);
        return response()->json($contact);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', 'unique:contacts,cpf,' . $id, new ValidCpf],
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

        $contact = Auth::user()->contacts()->findOrFail($id);
        $contact->update($request->all());

        return response()->json($contact);
    }
    public function destroy($id)
    {
        $contact = Auth::user()->contacts()->findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Contato excluído com sucesso']);
    }
}
