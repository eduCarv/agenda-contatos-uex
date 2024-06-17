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

        //Como não consegui usar a Geocoding API do google vou deixar apenas comentado
        //Usando o endereço fornecido no request faria um acesso a GeocodingAPI 
        //para pegar a latitude e longitude e colocar nos campos designados antes de salvar.
        //Assim o front pode posicionar o pin no mapa de acordo com essas coordenadas

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
