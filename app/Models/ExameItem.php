<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExameItem extends Model
{
    protected $table = 'exame_itens';

    protected $fillable = ['exame_id', 'descricao', 'unidade_medida', 'referencia_minimo'];
}
