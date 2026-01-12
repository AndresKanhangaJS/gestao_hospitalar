<?php

namespace App\Http\Controllers;

use App\Models\Episodio;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Medico;
use App\Models\TipoAtendimento;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EpisodioController extends Controller
{
    public function index(Request $request)
    {
        $query = Episodio::with(['paciente', 'medico', 'tipoAtendimento']);

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
        $medicos = Medico::where('status', 'activo')->get();
        $tipos = TipoAtendimento::where('status', 'activo')->get();

        return view('episodios.registar', compact('paciente', 'medicos', 'tipos'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'paciente_id'         => 'required|exists:pacientes,id',
                'medico_id'           => 'required|exists:medicos,id',
                'tipo_atendimento_id' => 'required|exists:tipos_atendimentos,id',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'exists'   => 'O valor selecionado para :attribute é inválido.'
            ]);

            DB::beginTransaction();

            // Gerar código único: EP-2026-0001
            $ano = date('Y');
            $ultimo = Episodio::whereYear('created_at', $ano)->latest()->first();
            $sequencia = $ultimo ? (int) substr($ultimo->codigo_atendimento, -4) + 1 : 1;
            $codigo = "EP-{$ano}-" . str_pad($sequencia, 4, '0', STR_PAD_LEFT);

            $episodio = Episodio::create([
                'paciente_id'         => $validado['paciente_id'],
                'medico_id'           => $validado['medico_id'],
                'tipo_atendimento_id' => $validado['tipo_atendimento_id'],
                'user_id_criacao'     => auth()->id(),
                'codigo_atendimento'  => $codigo,
                'data_abertura'       => now(),
                'situacao'            => 'Aberto',
                'status'              => 'activo'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Episódio de atendimento aberto com sucesso!',
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
                'message' => 'Erro interno ao abrir episódio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Episodio $episodio)
    {
        $episodio->load(['paciente', 'medico', 'criador', 'usuarioFechamento' ,'notasClinicas.criador']);

        return view('episodios.detalhes', compact('episodio'));
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
            $episodio = Episodio::findOrFail($id);

            if ($episodio->situacao === 'Fechado') {
                return response()->json(['message' => 'Este atendimento já está fechado.'], 400);
            }

            $episodio->update([
                'situacao'               => 'Fechado',
                'data_fecho'             => now(),
                'user_id_fechamento'     => auth()->id(),
                'observacoes_fechamento' => $request->nota_final,
                'user_id_atualizacao'    => auth()->id()
            ]);

            return response()->json([
                'message' => 'Atendimento finalizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar: ' . $e->getMessage()], 500);
        }
    }
}
