<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequisicaoExame;
use App\Models\RequisicaoItem;
use App\Models\ExameItem;
use App\Models\ResultadoExame;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExameController extends Controller
{
    public function requisicaoExameStore(Request $request)
    {
        // Validação robusta
        $request->validate([
            'exames_ids' => 'required|array',
            'episodio_id' => 'required|exists:episodios,id',
            'prioridade' => 'required|in:normal,urgente',
            'observacoes_clinicas' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // 1. Criar o cabeçalho
            $requisicao = RequisicaoExame::create([
                'codigo_requisicao' => 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'episodio_id' => $request->episodio_id,
                'medico_id' => auth()->id(),
                'prioridade' => $request->prioridade,
                'observacoes_clinicas' => $request->observacoes_clinicas,
                'status' => 'pendente',
                'data_solicitacao' => now(),
            ]);

            // 2. Loop pelos exames selecionados (Ex: Hemograma, Glicose)
            foreach ($request->exames_ids as $exame_id) {
                $item = RequisicaoItem::create([
                    'requisicao_id' => $requisicao->id,
                    'exame_id' => $exame_id,
                    'status' => 'pendente'
                ]);

                // 3. REGISTRO AUTOMÁTICO DE RESULTADOS
                // Buscamos os componentes desse exame (Ex: Hemograma tem Hemoglobina, HT, etc)
                // Se sua tabela de configuração for 'exame_itens'
                $componentes = DB::table('exame_itens')->where('exame_id', $exame_id)->get();

                foreach ($componentes as $comp) {
                    ResultadoExame::create([
                        'requisicao_item_id' => $item->id,
                        'exame_item_id'      => $comp->id,
                        'valor_resultado'    => null, // Aguardando laboratório
                        'status'             => 'pendente'
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Requisição ' . $requisicao->codigo_requisicao . ' enviada com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar: ' . $e->getMessage()
            ], 500);
        }
    }
}
