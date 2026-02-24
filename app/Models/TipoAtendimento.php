<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoAtendimento extends Model
{
    use SoftDeletes;

    protected $table = 'tipos_atendimentos';

    protected $fillable = [
        'nome', 'codigo', 'user_id_criacao', 'user_id_atualizacao', 'especialidade', 'status'
    ];

    public function episodios(): HasMany
    {
        return $this->hasMany(Episodio::class, 'tipo_atendimento_id');
    }
}
