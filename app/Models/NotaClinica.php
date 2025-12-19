<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaClinica extends Model
{
    use SoftDeletes;

    protected $table = 'notas_clinicas';

    protected $fillable = [
        'episodio_id', 'user_id_criacao', 'user_id_atualizacao',
        'queixa_principal', 'historia_doenca', 'exame_fisico',
        'diagnostico_hipotese', 'plano_tratamento', 'status', 'situacao'
    ];

    public function episodio(): BelongsTo
    {
        return $this->belongsTo(Episodio::class, 'episodio_id');
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_criacao');
    }
}
