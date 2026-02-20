<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'nome', 'telefone', 'email',
        'nif', 'telefone_alternativo_a', 'telefone_alternativo_b',
        'email_alternativo', 'logo', 'localizacao', 'status'
    ];
}
