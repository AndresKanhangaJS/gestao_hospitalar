<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exame extends Model
{
    protected $fillable = ['exame_categoria_id', 'codigo', 'nome', 'descricao', 'requer_jejum', 'status'];

    public function categoria()
    {
        return $this->belongsTo(ExameCategoria::class, 'exame_categoria_id');
    }

    public function itens()
    {
        return $this->hasMany(ExameItem::class);
    }
}
