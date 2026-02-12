<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoMedico extends Model
{
    protected $table = 'documentos_medicos';

    protected $fillable = [
        'episodio_id',
        'paciente_id',
        'medico_id',
        'origem',
        'tipo',
        'titulo',
        'conteudo',
        'status'
    ];

    // Relacionamento com o Médico
    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    // Relacionamento com o Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relacionamento com o Episódio
    public function episodio(): BelongsTo
    {
        return $this->belongsTo(Episodio::class, 'episodio_id');
    }
}
