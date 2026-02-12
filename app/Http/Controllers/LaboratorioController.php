<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequisicaoExame;
use App\Models\ResultadoExame;
use App\Models\RequisicaoItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LaboratorioController extends Controller
{
    /**
     * Lista todas as requisições que aguardam o laboratório
     */
    public function index()
    {
        $requisicoes = RequisicaoExame::with(['episodio.paciente', 'medico'])
            ->whereIn('status', ['pendente', 'em_coleta', 'laboratorio'])
            ->orderBy('prioridade', 'desc') // Urgentes no topo
            ->orderBy('created_at', 'asc')   // Mais antigos primeiro
            ->get();

        return view('laboratorio.index', compact('requisicoes'));
    }

    /**
     * Abre a tela para digitação dos resultados
     */
    public function lancarResultados($id)
    {
        // Decodifica o ID e carrega os itens do exame e os componentes de cada item
        $requisicao = RequisicaoExame::with([
            'episodio.paciente',
            'itens.exame',
            'itens.resultados.exameItem'
        ])->findOrFail(decodificar($id));

        return view('laboratorio.lancar_resultados', compact('requisicao'));
    }

    /**
     * Salva os resultados digitados e arquivos anexos
     */
    public function storeResultados(Request $request)
    {
        // Validação básica
        $request->validate([
            'requisicao_id' => 'required|exists:requisicao_exames,id',
            'resultados' => 'required|array',
            'arquivo_anexo' => 'nullable|mimes:pdf,jpg,png|max:5120'
        ]);

        DB::beginTransaction();
        try {
            // 1. Salvar os resultados individuais
            foreach ($request->resultados as $resultadoId => $valor) {
                // Só atualizamos se o valor não for nulo (opcional, dependendo da sua regra)
                if (!is_null($valor)) {
                    ResultadoExame::where('id', $resultadoId)->update([
                        'valor_resultado' => $valor,
                        'tecnico_id'      => auth()->id(),
                        'data_resultado'  => now(),
                        'status'          => 'concluido' // Adicione um status por componente se tiver
                    ]);
                }
            }

            // 2. Upload de Anexo com nome padronizado
            $path = null;
            if ($request->hasFile('arquivo_anexo')) {
                $fileName = "req_{$request->requisicao_id}_" . time() . "." . $request->file('arquivo_anexo')->getClientOriginalExtension();
                $path = $request->file('arquivo_anexo')->storeAs('exames/anexos', $fileName, 'public');
            }

            // 3. Finalizar a Requisição
            $updateData = [
                'status' => 'concluido',
                'data_resultado' => now(),
                'observacoes_laboratorio' => $request->observacoes_laboratorio
            ];

            if ($path) $updateData['arquivo_anexo'] = $path;

            RequisicaoExame::where('id', $request->requisicao_id)->update($updateData);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Laudo publicado com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Falha técnica: ' . $e->getMessage()], 500);
        }
    }
}
