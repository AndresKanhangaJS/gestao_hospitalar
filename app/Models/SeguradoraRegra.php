<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguradoraRegra extends Model
{
    protected $fillable = [
        'seguradora_id', 'categoria', 'aplicavel_a', 'tipo_valor', 'valor_empresa', 'valor_paciente'
    ];

    public function seguradora() {
        return $this->belongsTo(Seguradora::class);
    }
}
