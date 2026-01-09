<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Episodio extends Model
{
    use SoftDeletes;

    protected $table = 'episodios';

    protected $fillable = [
        'paciente_id', 'medico_id', 'tipo_atendimento_id', 'user_id_criacao',
        'user_id_atualizacao', 'codigo_atendimento', 'data_abertura',
        'data_fecho', 'status', 'situacao'
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fecho' => 'datetime',
    ];

    // Relacionamentos

    public function criador()
    {
        return $this->belongsTo(User::class, 'user_id_criacao');
    }
    
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    public function tipoAtendimento(): BelongsTo
    {
        return $this->belongsTo(TipoAtendimento::class, 'tipo_atendimento_id');
    }

    public function notasClinicas(): HasMany
    {
        return $this->hasMany(NotaClinica::class, 'episodio_id');
    }
}
