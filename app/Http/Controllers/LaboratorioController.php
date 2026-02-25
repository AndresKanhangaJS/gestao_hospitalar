<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequisicaoExame;
use App\Models\ResultadoExame;
use App\Models\RequisicaoItem;
use App\Models\Empresa;
use App\Models\Medico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LaboratorioController extends Controller
{
    /**
     * Lista todas as requisições que aguardam o laboratório
     */
    public function index(Request $request)
    {
        $query = RequisicaoExame::with(['episodio.paciente', 'itens.exame', 'medico']);

        // Filtro de Busca (Paciente, Código REQ ou Médico)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo_requisicao', 'like', "%{$search}%")
                ->orWhereHas('episodio.paciente', function($q) use ($search) {
                    $q->where('nome_completo', 'like', "%{$search}%")
                        ->orWhere('codigo_paciente', 'like', "%{$search}%");
                })
                ->orWhereHas('medico', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filtro de Data Inicial
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        // Filtro de Data Final
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // Filtro de Prioridade
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        // Filtro de Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordenação: Prioriza Urgentes Pendentes, depois os mais recentes
        $requisicoes = $query->orderByRaw("CASE WHEN prioridade = 'urgente' AND status = 'pendente' THEN 1 ELSE 2 END")
                            ->orderBy('created_at', 'desc')
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

    public function imprimirResultadosExames($id)
    {
        $empresa = Empresa::where('status', 'activo')->first();

        $requisicao = RequisicaoExame::with([
            'episodio.paciente',
            'medico',
            'itens.exame',
            'itens.resultados.exameItem',
            'itens.resultados.tecnico'
        ])->findOrFail(decodificar($id));

        // Pegar o técnico do primeiro item de resultado que existir
        $tecnico = null;
        foreach ($requisicao->itens as $item) {
            if ($item->resultados->first() && $item->resultados->first()->tecnico) {
                $tecnico = $item->resultados->first()->tecnico;
                break; // Para no primeiro técnico encontrado
            }
        }


        $prof_tecnico = Medico::where('user_id', $tecnico->id)
                    ->select('id', 'nome_completo', 'numero_ordem')
                    ->first();

        $data = [
            'requisicao' => $requisicao,
            'paciente'   => $requisicao->episodio->paciente,
            'medico'     => $requisicao->medico,
            'tecnico'    => $prof_tecnico,
            'data_emissao' => now(),
            'empresa'    => $empresa,
        ];

        $pdf = Pdf::loadView('docs.pdf.laudo_laboratorio_pdf', $data);

        return $pdf->setPaper('a4', 'portrait')->stream("laudo-{$requisicao->codigo_requisicao}.pdf");
    }
}
