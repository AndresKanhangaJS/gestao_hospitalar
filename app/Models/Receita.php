<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReceitaItem;
use App\Models\Medico;

class Receita extends Model
{
    protected $fillable = ['episodio_id', 'medico_id', 'codigo_receita', 'observacoes_gerais'];

    public function itens() {
        return $this->hasMany(ReceitaItem::class);
    }

    public function medico() {
        return $this->belongsTo(Medico::class);
    }

    public function episodio()
    {
        return $this->belongsTo(Episodio::class);
    }
}
