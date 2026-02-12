<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoExame extends Model
{
    protected $table = 'resultados_exames';

    protected $fillable = [
        'requisicao_item_id',
        'exame_item_id',
        'valor_resultado',
        'tecnico_id',
        'data_resultado',
        'arquivo_anexo',
        'observacoes_laboratorio',
        'status',
    ];

    // O item da requisição ao qual este resultado pertence
    public function requisicaoItem() {
        return $this->belongsTo(RequisicaoItem::class, 'requisicao_item_id');
    }

    // O parâmetro específico (ex: Glicemia, Creatinina) vindo da sua tabela de configuração
    public function exameItem() {
        return $this->belongsTo(ExameItem::class, 'exame_item_id');
    }

    // Técnico que digitou/validou o resultado
    public function tecnico() {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
}
