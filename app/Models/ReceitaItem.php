<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceitaItem extends Model
{
    protected $table = 'receita_itens';
    
    protected $fillable = ['receita_id', 'medicamento', 'dosagem', 'frequencia', 'duracao', 'quantidade'];

    public function receita() {
        return $this->belongsTo(Receita::class);
    }
}
