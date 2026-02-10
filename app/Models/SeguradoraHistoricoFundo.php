<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguradoraHistoricoFundo extends Model
{
    protected $fillable = [
        'seguradora_id', 'valor_adicionado', 'saldo_anterior', 'saldo_posterior', 'observacao'
    ];
}
