<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seguradora;
use App\Models\SeguradoraHistoricoFundo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SeguradoraController extends Controller
{
   public function index(Request $request)
    {
        // Iniciamos a query já pedindo a contagem de pacientes e as regras relacionadas
        $query = Seguradora::withCount('pacientes')->with('regras');

        // Busca por texto (Nome, Código ou NIF)
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('nome', 'like', "%{$search}%")
                    ->orWhere('codigo_seguradora', 'like', "%{$search}%")
                    ->orWhere('nif', 'like', "%{$search}%");
            });
        });

        // Filtro por Tipo
        $query->when($request->tipo, function ($q, $tipo) {
            $q->where('tipo', $tipo);
        });

        // Filtro por Status
        $query->when($request->status, function ($q, $status) {
            $q->where('status', $status);
        });

        $seguradoras = $query->latest()->paginate(15)->withQueryString();

        return view('seguradoras.index', compact('seguradoras'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Validação dos Dados
            $request->validate([
                'nome' => 'required|string|max:255',
                'codigo_seguradora' => 'required|unique:seguradoras,codigo_seguradora',
                'tipo' => 'required|in:seguradora,empresa',
                'email' => 'nullable|email',
                'telefone' => 'nullable|string',
                'nif' => 'nullable|string|unique:seguradoras,nif',
                'fundo_global' => 'nullable|numeric|min:0',
                'limite_por_funcionario' => 'nullable|numeric|min:0',
                'regras.*.categoria' => 'required|string',
                'regras.*.empresa' => 'required|numeric|min:0|max:100',
                'regras.*.paciente' => 'required|numeric|min:0|max:100',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'unique' => 'Este valor já está em uso.',
                'email' => 'Insira um e-mail válido.',
                'numeric' => 'O valor deve ser um número.'
            ]);

            // Preparação de valores financeiros
            $fundoInicial = $request->fundo_global ?? 0;

            // 2. Criar a Seguradora
            $seguradora = Seguradora::create([
                'nome' => $request->nome,
                'tipo' => $request->tipo,
                'codigo_seguradora' => $request->codigo_seguradora,
                'nif' => $request->nif,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'status' => 'activo',
                'fundo_global' => $fundoInicial,
                'saldo_atual' => $fundoInicial, // No primeiro registo, saldo é igual ao fundo
                'limite_por_funcionario' => $request->limite_por_funcionario ?? 0,
            ]);

            // 3. Registo Interno do Histórico de Fundo (Abertura de conta)
            if ($fundoInicial >= 0) {
                SeguradoraHistoricoFundo::create([
                    'seguradora_id'   => $seguradora->id,
                    'valor_adicionado' => $fundoInicial,
                    'saldo_anterior'  => 0,
                    'saldo_posterior' => $fundoInicial,
                    'observacao'      => 'Abertura de convénio - Carga inicial de fundo global.'
                ]);
            }

            // 4. Criar as Regras de Co-pagamento
            if ($request->has('regras')) {
                foreach ($request->regras as $regra) {
                    $seguradora->regras()->create([
                        'categoria'      => $regra['categoria'],
                        'aplicavel_a'    => $regra['aplicavel_a'] ?? 'todos',
                        'tipo_valor'     => $regra['tipo_valor'],
                        'valor_empresa'  => $regra['empresa'],
                        'valor_paciente' => $regra['paciente'],
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Convénio registado com sucesso!'
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao registar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $seguradora = Seguradora::findOrFail($id);

            $validado = $request->validate([
                'nome' => 'required|string|max:255',
                'codigo_seguradora' => 'required|string|unique:seguradoras,codigo_seguradora,'.$id,
                'tipo' => 'required|in:seguradora,empresa',
                'nif' => 'nullable|string|unique:seguradoras,nif,'.$id,
                'email' => 'nullable|email',
                'telefone' => 'nullable|string',
                'status' => 'required|in:activo,inactivo',
                'fundo_global' => 'nullable|numeric',
                'limite_por_funcionario' => 'nullable|numeric',
            ]);

            \DB::transaction(function () use ($seguradora, $validado, $request) {
                $seguradora->update($validado);

                // Sincronização das Regras
                if ($request->has('regras')) {
                    // Remove as regras antigas para evitar duplicados ou lixo
                    $seguradora->regras()->delete();

                    foreach ($request->regras as $regra) {
                        $seguradora->regras()->create([
                            'categoria'      => $regra['categoria'],
                            'aplicavel_a'    => $regra['aplicavel_a'] ?? 'todos',
                            'tipo_valor'     => $regra['tipo_valor'],
                            'valor_empresa'  => $regra['empresa'],
                            'valor_paciente' => $regra['paciente'],
                        ]);
                    }
                }
            });

            return response()->json(['status' => 'success', 'message' => 'Convénio atualizado com sucesso!']);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
        }
    }
}
