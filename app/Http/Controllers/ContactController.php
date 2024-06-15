<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Auth::user()->contacts()->latest()->take(3)->get();

        return view('dashboard', compact('contacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:contacts',
            'fone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'cep' => 'required|string|max:9',
            'gps' => 'required|string|max:255',
        ]);

        $contact = Auth::user()->contacts()->create($request->all());

        return redirect()->route('dashboard')->with('success', 'Contato criado com sucesso!');
    }
}
