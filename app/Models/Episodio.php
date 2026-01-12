<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasHashId;
use App\Models\User;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\TipoAtendimento;
use App\Models\NotaClinica;

class Episodio extends Model
{
    use SoftDeletes, HasHashId;

    protected $table = 'episodios';

    protected $fillable = [
        'paciente_id', 'medico_id', 'tipo_atendimento_id', 'user_id_criacao',
        'user_id_atualizacao', 'codigo_atendimento', 'data_abertura',
        'data_fecho', 'status', 'situacao', 'user_id_fechamento', 'observacoes_fechamento'
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

    public function usuarioFechamento()
    {
        return $this->belongsTo(User::class, 'user_id_fechamento');
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
