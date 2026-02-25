<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequisicaoExame;
use App\Models\RequisicaoItem;
use App\Models\ExameItem;
use App\Models\ResultadoExame;
use App\Models\Medico;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Exame;
use App\Models\ExameCategoria;
use Illuminate\Validation\ValidationException;

class ExameController extends Controller
{
    public function index(Request $request)
    {
        $query = Exame::with('categoria');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                  ->orWhere('codigo', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('exame_categoria_id', $request->categoria_id);
        }

        $dados = $query->latest()->paginate(12)->withQueryString();
        $categorias = ExameCategoria::where('status', 'activo')
                    ->withCount('exames')
                    ->get();

        return view('exames.index', compact('dados', 'categorias'));
    }

    public function store(Request $request)
    {
        try {
            // 1. Validação robusta incluindo os itens
            $validados = $request->validate([
                'nome'               => 'required|string|max:255',
                'codigo'             => 'required|string|unique:exames,codigo',
                'exame_categoria_id' => 'required|exists:exame_categorias,id',
                'requer_jejum'       => 'nullable',
                'item_nome'          => 'required|array|min:1', // Pelo menos um parâmetro
                'item_nome.*'        => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            // 2. Criar o Exame Pai
            $exame = Exame::create([
                'nome'               => $request->nome,
                'codigo'             => $request->codigo,
                'exame_categoria_id' => $request->exame_categoria_id,
                'requer_jejum'       => $request->has('requer_jejum'),
                'descricao'          => $request->descricao,
                'status'             => 'activo'
            ]);

            // 3. Criar os Itens (Parâmetros) do Exame
            // Percorremos o array item_nome enviado pelo formulário dinâmico
            foreach ($request->item_nome as $key => $nomeItem) {
                ExameItem::create([
                    'exame_id'   => $exame->id,
                    'descricao'       => $nomeItem,
                    'unidade_medida'    => $request->item_unidade[$key] ?? null,
                    'referencia_minimo' => $request->item_referencia[$key] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exame e parâmetros registrados com sucesso!'
            ]);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $exame = Exame::findOrFail($id);

            $request->validate([
                'nome'               => 'required|string|max:255',
                'codigo'             => 'required|string|unique:exames,codigo,' . $id,
                'exame_categoria_id' => 'required|exists:exame_categorias,id',
                'item_nome'          => 'required|array|min:1',
                'item_nome.*'        => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            // 1. Atualiza os dados do Exame Pai
            $exame->update([
                'nome'               => $request->nome,
                'codigo'             => $request->codigo,
                'exame_categoria_id' => $request->exame_categoria_id,
                'requer_jejum'       => $request->has('requer_jejum'),
                'descricao'          => $request->descricao,
                'status'             => $request->status ?? 'activo',
            ]);

            // 2. Sincroniza os Itens: Remove os antigos e insere os novos
            // Isso garante que se o usuário apagou uma linha no modal, ela suma do banco.
            $exame->itens()->delete();

            foreach ($request->item_nome as $key => $nomeItem) {
                ExameItem::create([
                    'exame_id'   => $exame->id,
                    'descricao'       => $nomeItem,
                    'unidade_medida'    => $request->item_unidade[$key] ?? null,
                    'referencia_minimo' => $request->item_referencia[$key] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exame e parâmetros atualizados com sucesso!'
            ]);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para retornar os itens de um exame via AJAX (usado no modal de edição)
     */
    public function getItens($id)
    {
        $itens = ExameItem::where('exame_id', $id)->get();
        return response()->json($itens);
    }

    public function storeExameCat(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:exame_categorias,nome,' . $request->categoria_id,
        ]);

        // O updateOrCreate procura pelo ID. Se for null, ele cria.
        $categoria = ExameCategoria::updateOrCreate(
            ['id' => $request->categoria_id],
            ['nome' => $request->nome]
        );

        return response()->json([
            'message' => $request->categoria_id ? 'Categoria atualizada!' : 'Categoria criada com sucesso!',
            'data' => $categoria
        ]);
    }

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
            // 1. Busca o médico cujo user_id é o ID do usuário logado
            $medico = Medico::where('user_id', auth()->id())->first();

            // 2.
            if (!$medico) {
                return redirect()->back()->with('error', 'Apenas usuários registrados como médicos podem solicitar exames.');
            }

            // 1. Criar o cabeçalho
            $requisicao = RequisicaoExame::create([
                'codigo_requisicao' => 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'episodio_id' => $request->episodio_id,
                'medico_id' => $medico->id,
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
                'message' => 'Erro ao guardar: ' . $e->getMessage()
            ], 500);
        }
    }
}
