<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdatePacienteRequest;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::query();

        // 1. Busca Inteligente (Nome, Email, Documento, Telefone)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome_completo', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('numero_documento', 'LIKE', "%{$search}%")
                ->orWhere('telefone', 'LIKE', "%{$search}%"); // Adicionado telefone
            });
        }

        // 2. Filtro por Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filtro por Género
        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        // 4. Filtro por Grupo Sanguíneo
        if ($request->filled('grupo_sanguineo')) {
            $query->where('grupo_sanguineo', $request->grupo_sanguineo);
        }

        // 5. Filtro por Intervalo de Data de Registo (Created_at)
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('created_at', [$request->data_inicio . " 00:00:00", $request->data_fim . " 23:59:59"]);
        }

        $pacientes = $query->latest()->paginate(12)->withQueryString();

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.registar');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'nome_completo'    => 'required|string|min:3|max:255',
                // before_or_equal:today impede datas futuras
                'data_nascimento'  => 'required|date|before_or_equal:today',
                'genero'           => 'required|in:Masculino,Feminino',
                'tipo_documento'   => 'required|in:BI,Cedula,Assento,Passaporte,Cartao_Residente',
                'numero_documento' => [
                    'required', 'string', 'max:30',
                    // Garante que o número seja único para aquele tipo de documento específico
                    Rule::unique('pacientes')->where(fn($q) =>
                        $q->where('tipo_documento', $request->tipo_documento)
                    )
                ],
                'telefone'         => 'nullable|string|min:9|max:20',
                'email'            => 'nullable|email|max:255|unique:pacientes,email',
                'morada'           => 'nullable|string|max:500',
                'grupo_sanguineo'  => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'alergias'         => 'nullable|string|max:1000',
            ], [
                // Mensagens customizadas
                'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser uma data futura.',
                'numero_documento.unique' => 'Este número de documento já está registado para este tipo.',
                'email.unique' => 'Este e-mail já pertence a outro paciente.',
                'required' => 'O campo :attribute é obrigatório.'
            ]);

            $validado['user_id_criacao'] = Auth::id();
            $validado['status']          = 'activo';

            $paciente = Paciente::create($validado);

            return response()->json([
                'success' => true,
                'message' => 'Paciente registado com sucesso!',
                'data'    => $paciente
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao processar a requisição: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Paciente $paciente)
    {
        // Carregamos quem criou e os episódios (ordenados pelos mais recentes)
        $paciente->load(['criador', 'atualizador', 'usuarioDeletou','episodios' => function($query) {
            $query->latest();
        }]);

        return view('pacientes.detalhes', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.editar', compact('paciente'));
    }

    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        try {
            $data = $request->validated();

            // Preenche o modelo com os novos dados MAS não guarda ainda
            $paciente->fill($data);

            // Verifica se, após o preenchimento, algo realmente mudou
            if (!$paciente->isDirty()) {
                return response()->json([
                    'status'  => 'info',
                    'message' => 'Nenhuma alteração foi detectada nos dados fornecidos.'
                ], 200);
            }

            // Se chegou aqui, houve mudança. Adicionamos quem alterou.
            $paciente->user_id_atualizacao = auth()->id();
            $paciente->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Os dados do paciente foram atualizados com sucesso!',
                'id'      => codificar($paciente->id)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Erro ao processar atualização: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        // 1. Validação
        $request->validate([
            'motivo' => 'required|string|min:10'
        ],
        [
            // Mensagens customizadas
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'required' => 'O campo :attribute é obrigatório.'
        ]);

        // 2. Localização do registro
        $paciente = Paciente::findOrFail(decodificar($id));

        // 3. Atualização dos dados de auditoria ANTES do soft delete
        $paciente->update([
            'motivo_exclusao' => $request->motivo,
            'status' => 'eliminado',
            'user_id_delete'  => auth()->id() // Captura o ID do utilizador logado
        ]);

        // 4. Execução do Soft Delete
        $paciente->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Paciente removido com sucesso.'
        ]);
    }
}
