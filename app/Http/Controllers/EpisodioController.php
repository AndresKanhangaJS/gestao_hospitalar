<?php

namespace App\Http\Controllers;

use App\Models\Episodio;
use App\Models\Paciente;
use App\Models\User;
use App\Models\TipoAtendimento;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class EpisodioController extends Controller
{
    public function index(Request $request)
    {
        $query = Episodio::with(['paciente', 'medico', 'tipoAtendimento']);

        // ... aplique os filtros similares ao do Paciente ...

        $episodios = $query->latest()->paginate(15)->withQueryString();

        // Para alimentar os filtros:
        $medicos = User::get();
        $tiposAtendimento = TipoAtendimento::all();

        return view('episodios.index', compact('episodios', 'medicos', 'tiposAtendimento'));
    }

    public function create(Paciente $paciente)
    {
        $medicos = User::all();
        $tipos = TipoAtendimento::where('status', 'activo')->get();

        return view('episodios.registar', compact('paciente', 'medicos', 'tipos'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'paciente_id'         => 'required|exists:pacientes,id',
                'medico_id'           => 'required|exists:users,id',
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
                'id'      => $episodio->id
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
        // Carrega as notas clínicas vinculadas a este episódio
        $episodio->load(['paciente', 'medico', 'notasClinicas.criador']);

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
}
