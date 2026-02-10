<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguradora extends Model
{
    protected $fillable = [
        'nome', 'tipo', 'codigo_seguradora', 'telefone', 'email',
        'nif', 'status', 'fundo_global', 'saldo_atual', 'limite_por_funcionario'
    ];

    public function regras() {
        return $this->hasMany(SeguradoraRegra::class);
    }
    
    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
