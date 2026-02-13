<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashId;

class RequisicaoExame extends Model
{
    use HasHashId;

    protected $fillable = [
        'codigo_requisicao',
        'episodio_id',
        'medico_id',
        'status',
        'prioridade',
        'observacoes_clinicas',
        'data_solicitacao',
        'data_resultado',
    ];

    // Uma requisição tem vários itens (exames específicos)
    public function itens() {
        return $this->hasMany(RequisicaoItem::class, 'requisicao_id');
    }

    // O episódio contém o vínculo com o paciente
    public function episodio() {
        return $this->belongsTo(Episodio::class);
    }

    // Médico que realizou a solicitação
    public function medico() {
        //return $this->belongsTo(User::class, 'medico_id');
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
