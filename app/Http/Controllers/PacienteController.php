<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Seguradora;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PacientesImport;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Paciente::with('seguradora');

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

        // 4. Filtro por Intervalo de Data de Registo (Created_at)
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('created_at', [$request->data_inicio . " 00:00:00", $request->data_fim . " 23:59:59"]);
        }

        // 5. Filtro Por Seguradora ou Particular
        if ($request->has('seguradora_id') && $request->seguradora_id !== null) {
            if ($request->seguradora_id === 'particular') {
                // Filtra onde não há seguradora vinculada
                $query->whereNull('seguradora_id');
            } else {
                // Filtra pela ID da seguradora selecionada
                $query->where('seguradora_id', $request->seguradora_id);
            }
        }

        $pacientes = $query->orderBy('nome_completo', 'asc')
                       ->paginate(12)
                       ->withQueryString();

        $seguradoras = Seguradora::all();

        return view('pacientes.index', compact('pacientes', 'seguradoras'));
    }

    public function create()
    {
        $seguradoras = Seguradora::where('status', 'activo')->get();
        return view('pacientes.registar', compact('seguradoras'));
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
                'seguradora_id'        => 'required_if:tem_seguro,on|nullable|exists:seguradoras,id',
                'numero_cartao_seguro' => 'nullable|string|max:50',
                'grupo_sanguineo'  => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
                'alergias'         => 'nullable|string|max:1000',
            ], [
                // Mensagens customizadas
                'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser uma data futura.',
                'numero_documento.unique' => 'Este número de documento já está registado para este tipo.',
                'email.unique' => 'Este e-mail já pertence a outro paciente.',
                'required' => 'O campo :attribute é obrigatório.',
                'seguradora_id.required_if' => 'Selecione a seguradora.',
                //'numero_cartao_seguro.required_if' => 'O número do cartão é obrigatório para quem tem seguro.',
            ]);

            // Gerar código único de 5 dígitos
            do {
                $codigo = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            } while (Paciente::where('codigo_paciente', $codigo)->exists());

            $validado['codigo_paciente']  = $codigo;
            $validado['user_id_criacao'] = Auth::id();
            $validado['status']          = 'activo';

            // Se o switch estiver OFF, garantiR que os dados de seguro sejam nulos
            if (!$request->has('tem_seguro')) {
                $validado['seguradora_id'] = null;
                $validado['numero_cartao_seguro'] = null;
            }

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
        $paciente->load(['criador', 'atualizador', 'usuarioDeletou', 'seguradora', 'episodios' => function($query) {
            $query->latest();
        }]);

        return view('pacientes.detalhes', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        $seguradoras = Seguradora::where('status', 'activo')->get();
        return view('pacientes.editar', compact('paciente', 'seguradoras'));
    }

    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        try {
            $data = $request->validated();

            if (!$request->has('tem_seguro')) {
                $data['seguradora_id'] = null;
                $data['numero_cartao_seguro'] = null;
            }

            $paciente->fill($data);

            if (!$paciente->isDirty()) {
                return response()->json([
                    'status'  => 'info',
                    'message' => 'Nenhuma alteração foi detectada nos dados fornecidos.'
                ], 200);
            }

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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'seguradora_id' => 'required_if:tipo_importacao,segurado'
        ]);

        try {
            $seguradora_id = ($request->tipo_importacao == 'segurado') ? $request->seguradora_id : null;

            Excel::import(new PacientesImport($seguradora_id), $request->file('file'));

            return back()->with('success', 'Importação concluída!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures(); // Captura os erros detalhados

            // Retorna para a página anterior com os erros específicos
            return back()->with('import_errors', $failures);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro crítico: ' . $e->getMessage());
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
