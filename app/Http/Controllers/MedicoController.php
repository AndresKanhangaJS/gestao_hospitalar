<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\DocumentoMedico;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateMedicoRequest;
use App\Models\Receita;
use App\Models\ReceitaItem;
use App\Models\Empresa;
use App\Models\TipoAtendimento;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;

class MedicoController extends Controller
{
    public function index(Request $request)
    {
        // 1. Adicionamos 'user.roles' ao eager loading para o filtro e a view funcionarem
        $query = Medico::query()->with(['user.roles', 'criador']);

        // 2. Busca Inteligente
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome_completo', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('numero_ordem', 'LIKE', "%{$search}%")
                ->orWhere('especialidade', 'LIKE', "%{$search}%");
            });
        }

        // 3. FILTRO POR PERFIL (ROLE) - A parte que faltava!
        if ($request->filled('role')) {
            $role = $request->role;
            // Filtra médicos que possuem o usuário com a role específica
            $query->whereHas('user.roles', function($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // 4. Outros Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('especialidade')) {
            $query->where('especialidade', $request->especialidade);
        }

        $medicos = $query->latest()->paginate(12)->withQueryString();

        return view('medicos.index', compact('medicos'));
    }

    public function create()
    {
        $rolesExcluidas = ['Super Administrador', 'Administrador'];
        $roles = Role::whereNotIn('name', $rolesExcluidas)
                ->orderBy('name', 'asc')
                ->get();

        $especialidades = TipoAtendimento::where('status', 'activo')
                        ->where('especialidade', true)
                        ->orderBy('nome')->get();

        return view('medicos.registar', compact('roles', 'especialidades'));
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'nome_completo'    => 'required|string|min:3|max:255',
                'email'            => 'required|email|max:255|unique:users,email',
                'role'             => 'required|exists:roles,name', // Validar o papel selecionado
                // Condicional: numero_ordem é obrigatório para Médico, Enfermeiro e Laboratorista
                'numero_ordem'     => 'required_if:role,Médico,Enfermeiro,Laboratorista|nullable|string|max:50|unique:medicos,numero_ordem',
                // Condicional: especialidade é obrigatória apenas para Médico
                'especialidade_id'    => 'required_if:role,Médico|nullable|string|max:255',
                'data_nascimento'  => 'nullable|date|before_or_equal:today',
                'genero'           => 'required|in:Masculino,Feminino',
                'tipo_documento'   => 'required|in:BI,Passaporte',
                'numero_documento' => 'required|string|max:30',
                'telefone'         => 'nullable|string|min:9|max:20',
                'morada'           => 'nullable|string|max:500',
            ], [
                'email.unique' => 'Este e-mail já está associado a um usuário.',
                'numero_ordem.unique' => 'Este número de ordem já está registado.',
                'numero_ordem.required_if' => 'O número de ordem é obrigatório para este perfil.',
                'especialidade_id.required_if' => 'A especialidade_id é obrigatória para médicos.',
                'required' => 'O campo :attribute é obrigatório.'
            ]);

            return DB::transaction(function () use ($validado, $request) {
                // Gerar código único
                do {
                    $codigo = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    $exists = User::where('codigo', $codigo)->exists() || Medico::where('codigo_medico', $codigo)->exists();
                } while ($exists);

                // 1. Criar o Usuário
                $user = User::create([
                    'codigo'   => $codigo,
                    'name'     => $validado['nome_completo'],
                    'email'    => $validado['email'],
                    'password' => Hash::make($validado['numero_documento']),
                    'status'   => 'activo',
                ]);

                // 2. Associar o Papel DINAMICAMENTE
                $user->assignRole($request->role);

                // 3. Criar registro na tabela medicos/profissionais
                $validado['codigo_medico'] = $codigo;
                $validado['user_id'] = $user->id;
                $validado['user_id_criacao'] = Auth::id();
                $validado['status'] = 'activo';

                $profissional = Medico::create($validado);

                return response()->json([
                    'success' => true,
                    'message' => "Profissional ({$request->role}) registado com sucesso!",
                    'data'    => $profissional
                ], 201);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500);
        }
    }

    public function show(Medico $medico)
    {
        // Carregamos as relações e contamos quantos episódios este médico já realizou
        $medico->load(['criador', 'atualizador', 'user.roles', 'especialidadeRelacao', 'episodios' => function($query) {
            $query->with('paciente')->latest()->take(50);
        }])->loadCount('episodios');

        return view('medicos.detalhes', compact('medico'));
    }

    public function edit(Medico $medico)
    {
        $especialidades = TipoAtendimento::where('status', 'activo')
                        ->where('especialidade', true)
                        ->orderBy('nome')->get();

        return view('medicos.editar', compact('medico', 'especialidades'));
    }

    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        try {
            $data = $request->validated();

            $result = DB::transaction(function () use ($data, $medico) {
                // 1. Atualizar dados do Usuário
                $medico->user->fill([
                    'name'  => $data['nome_completo'],
                    'email' => $data['email'],
                ]);

                // Capturamos se o usuário mudou (sem salvar ainda)
                $userDirty = $medico->user->isDirty();

                // 2. Lógica de campos por Cargo
                if ($data['role'] !== 'Médico') {
                    $data['especialidade_id'] = null;
                }
                if (!in_array($data['role'], ['Médico', 'Enfermeiro', 'Laboratorista'])) {
                    $data['numero_ordem'] = null;
                }

                // 3. Verificar alterações no Médico
                $medico->fill($data);

                // Verificamos se ALGO mudou em qualquer um dos dois modelos
                if (!$medico->isDirty() && !$userDirty) {
                    return 'no_changes';
                }

                // 4. Salvar ambos
                $medico->user->save();
                $medico->user_id_atualizacao = auth()->id();
                $medico->save();

                return 'success';
            });

            if ($result === 'no_changes') {
                return response()->json([
                    'status'  => 'info',
                    'message' => 'Nenhuma alteração foi detectada.'
                ], 200);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Dados atualizados com sucesso!',
                'id'      => codificar($medico->id)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Erro ao atualizar: ' . $e->getMessage()
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
        $empresa = Empresa::where('status', 'activo')->first();

        // Carrega a receita com todas as relações necessárias
        $receita = Receita::with(['itens', 'medico', 'episodio.paciente'])->findOrFail(decodificar($id));

        // Dados para o PDF
        $data = [
            'receita' => $receita,
            'paciente' => $receita->episodio->paciente,
            'medico' => $receita->medico,
            'data' => $receita->created_at->format('d/m/Y H:i'),
            'empresa' => $empresa
        ];

        $pdf = Pdf::loadView('docs.pdf.receita_pdf', $data);

        // Define o papel para A4 e a orientação
        return $pdf->setPaper('a5', 'portrait')->stream("receita-{$receita->codigo_receita}.pdf");
    }

   public function storeDocumento(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'episodio_id' => 'required|exists:episodios,id',
                'paciente_id' => 'required|exists:pacientes,id',
                'tipo'        => 'required|string|max:100',
                'titulo'      => 'nullable|string|max:255',
                'conteudo'    => 'required|string', // O HTML do Quill
            ]);

            return DB::transaction(function () use ($validado) {
                // 1. Obter o médico logado (vinculado ao user)
                $user = auth()->user();
                $medico = Medico::where('user_id', $user->id)->firstOrFail();

                // 2. Criar o documento médico
                $documento = DocumentoMedico::create([
                    'episodio_id' => $validado['episodio_id'],
                    'paciente_id' => $validado['paciente_id'],
                    'medico_id'   => $medico->id,
                    'origem'      => 'interna',
                    'tipo'        => $validado['tipo'],
                    'titulo'      => $validado['titulo'] ?? $validado['tipo'],
                    'conteudo'    => $validado['conteudo'],
                    'status'      => 'activo'
                ]);

                return response()->json([
                    'success'    => true,
                    'message'    => "{$validado['tipo']} gerado com sucesso!",
                    'id_doc'     => codificar($documento->id), // Usando sua função de codificação
                    'tipo_doc'   => $documento->tipo
                ], 201);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar documento: ' . $e->getMessage()
            ], 500);
        }
    }

    public function imprimirDocumento($id)
    {
        $empresa = Empresa::where('status', 'activo')->first();

        // Carrega o documento com as relações para o cabeçalho e rodapé
        $documento = DocumentoMedico::with(['medico', 'paciente', 'episodio'])
            ->findOrFail(decodificar($id));

        $data = [
            'documento' => $documento,
            'paciente'  => $documento->paciente,
            'medico'    => $documento->medico,
            'data'      => $documento->created_at->format('d/m/Y H:i'),
            'empresa'   => $empresa
        ];

        $pdf = Pdf::loadView('docs.pdf.documento_medico_pdf', $data);

        $nomeArquivo = Str::slug($documento->tipo . '-' . $documento->paciente->nome_completo);
        return $pdf->setPaper('a4', 'portrait')->stream("{$nomeArquivo}.pdf");
    }
}
