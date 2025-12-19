<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paciente extends Model
{
    use SoftDeletes;

    protected $table = 'pacientes';

    protected $fillable = [
        'nome_completo', 'data_nascimento', 'genero', 'tipo_documento',
        'numero_documento', 'telefone', 'email', 'morada',
        'grupo_sanguineo', 'alergias', 'user_id_criacao', 'status'
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
}
