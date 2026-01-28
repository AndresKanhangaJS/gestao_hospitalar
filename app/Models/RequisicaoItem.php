<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisicaoItem extends Model
{
    protected $table = 'requisicao_itens';
    protected $fillable = [
        'requisicao_id',
        'exame_id',
        'status'
    ];

    public function requisicao() {
        return $this->belongsTo(RequisicaoExame::class, 'requisicao_id');
    }

    public function exame() {
        return $this->belongsTo(Exame::class);
    }

    // Resultados detalhados (ex: num hemograma, são os valores de Hemoglobina, etc)
    public function resultados() {
        return $this->hasMany(ResultadoExame::class, 'requisicao_item_id');
    }
}
