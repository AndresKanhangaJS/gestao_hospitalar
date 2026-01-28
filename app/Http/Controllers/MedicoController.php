<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use Illuminate\Http\Request;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateMedicoRequest;
use App\Models\Receita;
use App\Models\ReceitaItem;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        $query = Medico::query()->with(['user', 'criador']);

        // 1. Busca Inteligente (Nome, Email, Número de Ordem, Especialidade)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome_completo', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('numero_ordem', 'LIKE', "%{$search}%")
                ->orWhere('especialidade', 'LIKE', "%{$search}%");
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

        // 4. Filtro por Especialidade (Específico para Médicos)
        if ($request->filled('especialidade')) {
            $query->where('especialidade', $request->especialidade);
        }

        $medicos = $query->latest()->paginate(12)->withQueryString();

        return view('medicos.index', compact('medicos'));
    }

    public function create()
    {
        return view('medicos.registar');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'nome_completo'    => 'required|string|min:3|max:255',
                'email'            => 'required|email|max:255|unique:users,email',
                'numero_ordem'     => 'required|string|max:50|unique:medicos,numero_ordem',
                'especialidade'    => 'required|string|max:255',
                'data_nascimento'  => 'nullable|date|before_or_equal:today',
                'genero'           => 'required|in:Masculino,Feminino',
                'tipo_documento'   => 'required|in:BI,Passaporte',
                'numero_documento' => 'required|string|max:30',
                'telefone'         => 'nullable|string|min:9|max:20',
                'morada'           => 'nullable|string|max:500',
            ], [
                'email.unique' => 'Este e-mail já está associado a um usuário no sistema.',
                'numero_ordem.unique' => 'Este número de ordem já está registado.',
                'numero_documento.required' => 'O número do documento é obrigatório (será a senha de acesso).',
                'required' => 'O campo :attribute é obrigatório.'
            ]);

            return DB::transaction(function () use ($validado) {
                // Gerar código único de 5 dígitos
                do {
                    $codigo = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

                    $codigoExisteNoMedico = Medico::where('codigo_medico', $codigo)->exists();
                    $codigoExisteNoUser   = User::where('codigo', $codigo)->exists();
                } while ($codigoExisteNoMedico || $codigoExisteNoUser);
                // 1. Criar o Usuário para login
                $user = User::create([
                    'codigo'     => $codigo,
                    'name'     => $validado['nome_completo'],
                    'email'    => $validado['email'],
                    'password' => Hash::make($validado['numero_documento']),
                    'status'   => 'activo',
                ]);
                // --- Associar o Papel de Médico ---
                $user->assignRole('Médico');

                // 2. Criar o registro do Médico vinculado ao Usuário
                $validado['codigo_medico'] = $codigo;
                $validado['user_id'] = $user->id;
                $validado['user_id_criacao'] = Auth::id();
                $validado['status'] = 'activo';

                $medico = Medico::create($validado);

                return response()->json([
                    'success' => true,
                    'message' => "Médico registado com sucesso! As credenciais de acesso estão defenidos com o e-mail e o número do documento.",
                    'data'    => $medico
                ], 201);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function show(Medico $medico)
    {
        // Carregamos as relações e contamos quantos episódios este médico já realizou
        $medico->load(['criador', 'atualizador', 'episodios' => function($query) {
            $query->with('paciente')->latest()->take(50); // Pegar os últimos 50 atendimentos
        }])->loadCount('episodios');

        return view('medicos.detalhes', compact('medico'));
    }

    public function edit(Medico $medico)
    {
        return view('medicos.editar', compact('medico'));
    }

    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        try {
            $data = $request->validated();

            // Preenche o modelo com os novos dados
            $medico->fill($data);

            // Verifica se houve alteração real (comparando com os dados originais do banco)
            if (!$medico->isDirty()) {
                return response()->json([
                    'status'  => 'info',
                    'message' => 'Nenhuma alteração foi detectada nos dados do médico.'
                ], 200);
            }

            // Se houver campos de log de auditoria:
            $medico->user_id_atualizacao = auth()->id();
            $medico->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Os dados do médico foram atualizados com sucesso!',
                'id'      => codificar($medico->id)
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
        $medico = Medico::findOrFail(decodificar($id));

        // 3. Atualização dos dados de auditoria ANTES do soft delete
        $medico->update([
            'motivo_exclusao' => $request->motivo,
            'status' => 'eliminado',
            'user_id_delete'  => auth()->id() // Captura o ID do utilizador logado
        ]);

        // 4. Execução do Soft Delete
        $medico->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Médico removido com sucesso.'
        ]);
    }

    public function storeReceita(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'episodio_id' => 'required|exists:episodios,id',
                'itens' => 'required|array|min:1',
                'itens.*.medicamento' => 'required|string|max:255',
                'itens.*.dosagem'     => 'required|string|max:100',
                'itens.*.frequencia'  => 'required|string|max:100',
                'itens.*.duracao'     => 'required|string|max:100',
                'itens.*.quantidade'  => 'required|string|max:100',
                'observacoes_gerais'  => 'nullable|string|max:2000',
            ]);

            return DB::transaction(function () use ($validado) {
                // 1. Obter o médico logado e validar se ele existe
                $user = auth()->user();
                $medico = Medico::where('user_id', $user->id)->firstOrFail();

                // 2. Gerar código único para a receita
                do {
                    $codigo_receita = 'R-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
                } while (Receita::where('codigo_receita', $codigo_receita)->exists());

                // 3. Criar a receita (Pai)
                $receita = Receita::create([
                    'episodio_id'        => $validado['episodio_id'],
                    'medico_id'          => $medico->id,
                    'codigo_receita'     => $codigo_receita,
                    'observacoes_gerais' => $validado['observacoes_gerais'] ?? null,
                ]);

                // 4. Criar os itens da receita (Filhos)
                // Usando createMany para melhor performance se houver muitos itens
                $receita->itens()->createMany($validado['itens']);

                return response()->json([
                    'success' => true,
                    'message' => "Receita médica {$codigo_receita} criada com sucesso!",
                    'data'    => $receita->load('itens'),
                    'id_receita'      => codificar($receita->id)
                ], 201);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro interno ao salvar receita: ' . $e->getMessage()], 500);
        }
    }

    public function imprimirReceita($id)
    {
        // Carrega a receita com todas as relações necessárias
        $receita = Receita::with(['itens', 'medico', 'episodio.paciente'])->findOrFail(decodificar($id));

        // Dados para o PDF
        $data = [
            'receita' => $receita,
            'paciente' => $receita->episodio->paciente,
            'medico' => $receita->medico,
            'data' => $receita->created_at->format('d/m/Y H:i')
        ];

        $pdf = Pdf::loadView('docs.pdf.receita_pdf', $data);

        // Define o papel para A4 e a orientação
        return $pdf->setPaper('a5', 'portrait')->stream("receita-{$receita->codigo_receita}.pdf");
    }
}
