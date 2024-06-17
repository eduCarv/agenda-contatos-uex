<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'cpf', 'fone', 'cep', 'logradouro', 'complemento', 'bairro', 'uf', 'estado', 'latitude', 'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
