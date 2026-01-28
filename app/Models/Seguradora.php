<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguradora extends Model
{
    protected $fillable = ['nome', 'tipo', 'codigo_seguradora', 'telefone', 'email','nif', 'status'];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
