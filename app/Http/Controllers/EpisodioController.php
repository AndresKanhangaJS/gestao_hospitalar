<?php

namespace App\Http\Controllers;

use App\Models\Episodio;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Medico;
use App\Models\TipoAtendimento;
use App\Models\ExameCategoria;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EpisodioController extends Controller
{
    public function index(Request $request)
    {
        $query = Episodio::with(['paciente', 'medico', 'profissionalTriagem', 'tipoAtendimento']);

        // Filtro por Nome do Paciente ou Documento (Relacionamento)
        $query->when($request->search, function ($q, $search) {
            $q->whereHas('paciente', function ($q) use ($search) {
                $q->where('nome_completo', 'like', "%{$search}%")
                ->orWhere('numero_documento', 'like', "%{$search}%");
            })
            // Também busca pelo código do atendimento se preferir
            ->orWhere('codigo_atendimento', 'like', "%{$search}%");
        });

        // Filtro por Tipo de Atendimento
        $query->when($request->tipo_id, function ($q, $tipo_id) {
            $q->where('tipo_atendimento_id', $tipo_id);
        });

        // Filtro por Médico
        $query->when($request->medico_id, function ($q, $medico_id) {
            $q->where('medico_id', $medico_id);
        });

        // Filtro por Status (Activo/Inactivo)
        $query->when($request->status, function ($q, $status) {
            $q->where('status', $status);
        });

        // Filtro por Situação (Aberto/Fechado) - Caso queira adicionar depois
        $query->when($request->situacao, function ($q, $situacao) {
            $q->where('situacao', $situacao);
        });

        // Filtro por Período de Data
        $query->when($request->data_inicio, function ($q, $inicio) {
            $q->whereDate('created_at', '>=', $inicio);
        });

        $query->when($request->data_fim, function ($q, $fim) {
            $q->whereDate('created_at', '<=', $fim);
        });

        // Filtro por Situação (Aberto/Fechado)
        $query->when($request->situacao, function ($q, $situacao) {
            $q->where('situacao', $situacao);
        });

        $episodios = $query->latest()->paginate(15)->withQueryString();

        $medicos = Medico::where('status', 'activo')->orderBy('nome_completo')->get();
        $tiposAtendimento = TipoAtendimento::where('status', 'activo')->orderBy('nome')->get();

        return view('episodios.index', compact('episodios', 'medicos', 'tiposAtendimento'));
    }

    public function create(Paciente $paciente)
    {
        $medicos = Medico::where('status', 'activo')
        ->whereHas('user.roles', function($query) {
            $query->where('name', 'Médico');
        })
        ->get();
        $tipos = TipoAtendimento::where('status', 'activo')->get();

        return view('episodios.registar', compact('paciente', 'medicos', 'tipos'));
    }

    // FLUXO DE ABERTURA DE EPISÓDIO COMPLETO
    // public function store(Request $request): JsonResponse
    // {
    //     try {
    //         $validado = $request->validate([
    //             'paciente_id'         => 'required|exists:pacientes,id',
    //             'medico_id'           => 'required|exists:medicos,id',
    //             'tipo_atendimento_id' => 'required|exists:tipos_atendimentos,id',
    //             'prioridade'          => 'required|string',
    //             // Validação dos novos dados de triagem
    //             'pa_sistolica'        => 'nullable|string|max:10',
    //             'pa_diastolica'       => 'nullable|string|max:10',
    //             'temperatura'         => 'nullable|numeric|between:30,45',
    //             'peso'                => 'nullable|numeric|between:0,500',
    //             'altura'              => 'nullable|numeric|between:0,3',
    //             'frequencia_cardiaca' => 'nullable|integer',
    //             'saturacao'           => 'nullable|integer|between:0,100',
    //         ], [
    //             'required' => 'O campo :attribute é obrigatório.',
    //             'exists'   => 'O valor selecionado para :attribute é inválido.',
    //             'numeric'  => 'O campo :attribute deve ser um número.',
    //         ]);

    //         DB::beginTransaction();

    //         // Gerar código único: EP-2026-0001
    //         $ano = date('Y');
    //         $ultimo = Episodio::whereYear('created_at', $ano)->latest()->first();
    //         $sequencia = $ultimo ? (int) substr($ultimo->codigo_atendimento, -4) + 1 : 1;
    //         $codigo = "EP-{$ano}-" . str_pad($sequencia, 4, '0', STR_PAD_LEFT);

    //         $episodio = Episodio::create([
    //             'paciente_id'         => $validado['paciente_id'],
    //             'medico_id'           => $validado['medico_id'],
    //             'tipo_atendimento_id' => $validado['tipo_atendimento_id'],
    //             'user_id_criacao'     => auth()->id(),
    //             'codigo_atendimento'  => $codigo,
    //             'data_abertura'       => now(),
    //             'situacao'            => 'Aberto',
    //             'status'              => 'activo',
    //             'prioridade'          => $request->prioridade ?? null,
    //             // Dados de triagem
    //             'pa_sistolica'        => $request->pa_sistolica,
    //             'pa_diastolica'       => $request->pa_diastolica,
    //             'temperatura'         => $request->temperatura,
    //             'peso'                => $request->peso,
    //             'altura'              => $request->altura,
    //             'frequencia_cardiaca' => $request->frequencia_cardiaca,
    //             'saturacao'           => $request->saturacao,
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Episódio de atendimento aberto com sucesso!',
    //             'id'      => codificar($episodio->id)
    //         ], 201);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'errors'  => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erro interno ao abrir episódio: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // FLUXO SIMPLIFICADO DE ABERTURA DE EPISÓDIO (APENAS PACIENTE E TIPO DE ATENDIMENTO)
    public function store(Request $request)
    {
        try {
            $validado = $request->validate([
                'paciente_id' => 'required|exists:pacientes,id',
                'tipo_atendimento_id' => 'required|exists:tipos_atendimentos,id',
                'medico_id'           => 'required|exists:medicos,id',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'exists'   => 'O valor selecionado para :attribute é inválido.',
            ]);

            DB::beginTransaction();

            // Gerar código único: EP-2026-0001
            $ano = date('Y');
            $ultimo = Episodio::whereYear('created_at', $ano)->latest()->first();
            $sequencia = $ultimo ? (int) substr($ultimo->codigo_atendimento, -4) + 1 : 1;
            $codigo = "EP-{$ano}-" . str_pad($sequencia, 4, '0', STR_PAD_LEFT);

            $episodio = Episodio::create([
                'paciente_id' => $validado['paciente_id'],
                'tipo_atendimento_id' => $validado['tipo_atendimento_id'],
                'codigo_atendimento' => $codigo,
                'medico_id'           => $validado['medico_id'],
                'situacao' => 'Aguardando Triagem', // <--- Estado inicial
                'user_id_criacao' => auth()->id(),
                'data_abertura' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paciente encaminhado para triagem!',
                'id'      => codificar($episodio->id)
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao abrir atendimento/episódio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function triagem(Request $request, $id)
    {
        try {
            // Decodificar o ID se estiveres a usar o helper de codificação
            // $id = descodificar($id);

            $validado = $request->validate([
                'pa_sistolica'        => 'required|string',
                'pa_diastolica'       => 'required|string',
                'temperatura'         => 'nullable|numeric',
                'peso'                => 'nullable|numeric',
                'altura'              => 'nullable|numeric',
                'frequencia_cardiaca' => 'nullable|integer',
                'saturacao'           => 'nullable|integer',
                //'medico_id'           => 'required|exists:medicos,id',
                'observacoes_triagem' => 'nullable|string',
                'prioridade'          => 'required|string',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'exists'   => 'O valor selecionado para :attribute é inválido.',
                'numeric'  => 'O campo :attribute deve ser um número.',
            ]);

            DB::beginTransaction();

            $episodio = Episodio::findOrFail($id);

            // Atualizar os dados do episódio
            $episodio->update([
                'pa_sistolica'        => $validado['pa_sistolica'],
                'pa_diastolica'       => $validado['pa_diastolica'],
                'temperatura'         => $validado['temperatura'],
                'peso'                => $validado['peso'],
                'altura'              => $validado['altura'],
                'frequencia_cardiaca' => $validado['frequencia_cardiaca'],
                'saturacao'           => $validado['saturacao'],
                //'medico_id'           => $validado['medico_id'],
                'observacoes_triagem' => $validado['observacoes_triagem'],
                'prioridade'          => $validado['prioridade'],
                'situacao'            => 'Aguardando Atendimento', // Avança para o próximo estado
                'user_id_triagem'     => auth()->id(),
                'data_triagem'        => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Triagem realizada com sucesso! Paciente encaminhado ao médico.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar triagem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Episodio $episodio)
    {
        $episodio->load(['paciente', 'medico', 'profissionalTriagem', 'criador', 'usuarioFechamento' ,'notasClinicas.criador']);
        $categoriasExames = ExameCategoria::with(['exames' => function($q) {
            $q->where('status', 'activo')->orderBy('nome');
        }])->get();

        return view('episodios.detalhes', compact('episodio', 'categoriasExames'));
    }

    public function destroy(Episodio $episodio)
    {
        try {
            // Regra de negócio: Talvez não queira apagar episódios que já tenham notas clínicas
            if ($episodio->notasClinicas()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Não é possível eliminar um episódio que já possui notas clínicas registadas.'
                ], 422);
            }

            $episodio->delete(); // SoftDelete (conforme sua migration)

            return response()->json([
                'status' => 'success',
                'message' => 'Episódio removido com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Erro ao eliminar registo.'], 500);
        }
    }

    public function finalizar(Request $request, $id)
    {
        try {
            // 1. Carrega o episódio com o médico e o usuário vinculado para evitar consultas extras (Eager Loading)
            $episodio = Episodio::with('medico')->findOrFail($id);
            $user = auth()->user();

            // 2. BUSCAR O REGISTRO DE MÉDICO DO USUÁRIO LOGADO
            $medicoLogado = Medico::where('user_id', $user->id)->first();

            // 3. VALIDAÇÃO DE IDENTIDADE: É o médico responsável?
            // Se o usuário não for médico OU o ID do registro de médico for diferente do médico do episódio
            if (!$medicoLogado || $medicoLogado->id !== $episodio->medico_id) {
                return response()->json([
                    'message' => 'Acesso negado. Apenas o médico responsável por este atendimento (' . $episodio->medico->nome_completo . ') pode finalizá-lo.'
                ], 403);
            }

            // 4. VALIDAÇÃO DE ESTADO: Já está fechado?
            if ($episodio->situacao === 'Fechado') {
                return response()->json(['message' => 'Este atendimento já foi encerrado anteriormente.'], 400);
            }

            // 5. EXECUÇÃO DO FECHAMENTO
            $episodio->update([
                'situacao'               => 'Fechado',
                'data_fecho'             => now(),
                'user_id_fechamento'     => $user->id,
                'observacoes_fechamento' => $request->nota_final,
                'user_id_atualizacao'    => $user->id
            ]);

            return response()->json([
                'message' => 'Atendimento finalizado com sucesso! O prontuário agora encontra-se em modo de leitura.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno ao processar: ' . $e->getMessage()], 500);
        }
    }
}
