<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Hashids\Hashids;
use App\Traits\HasHashId;

class Paciente extends Model
{
    use SoftDeletes; use HasHashId;

    protected $table = 'pacientes';

    protected $fillable = [
        'nome_completo', 'data_nascimento', 'genero', 'tipo_documento',
        'numero_documento', 'telefone', 'email', 'morada',
        'grupo_sanguineo', 'alergias', 'user_id_criacao', 'status', 'motivo_exclusao',
        'user_id_delete', 'user_id_atualizacao'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    // Relacionamentos
    public function episodios(): HasMany
    {
        return $this->hasMany(Episodio::class, 'paciente_id');
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_criacao');
    }

    public function atualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_atualizacao');
    }

    public function usuarioDeletou()
    {
        return $this->belongsTo(User::class, 'user_id_delete');
    }

    // // Hashids para rotas amigáveis
    // public function getRouteKey()
    // {
    //     // Quando o Laravel gerar a URL, ele codifica o ID
    //     return codificar($this->id);
    // }

    // public function resolveRouteBinding($value, $field = null)
    // {
    //     // Quando o Laravel recebe a URL, ele decodifica antes de chegar no Controller
    //     $id = decodificar($value);

    //     if (!$id) return null;

    //     return $this->where('id', $id)->firstOrFail();
    // }
}
