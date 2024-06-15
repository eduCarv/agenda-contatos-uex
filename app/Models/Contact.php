<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'cpf', 'fone', 'endereco', 'cep', 'gps'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
