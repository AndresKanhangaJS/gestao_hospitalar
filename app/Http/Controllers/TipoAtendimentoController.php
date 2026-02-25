<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoAtendimento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TipoAtendimentoController extends Controller
{
    public function index(Request $request)
    {
        $query = TipoAtendimento::query();

        // Filtro de busca (Nome ou Código)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->search . '%')
                ->orWhere('codigo', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro de Especialidade
        if ($request->filled('especialidade')) {
            $query->where('especialidade', $request->especialidade);
        }

        // Filtro de Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dados = $query->latest()->paginate(12)->withQueryString();

        return view('tipos_atendimentos.index', compact('dados'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'nome'   => 'required|string|max:255|unique:tipos_atendimentos,nome',
                'codigo' => 'required|string|max:50|unique:tipos_atendimentos,codigo',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'unique'   => 'Este dado (nome ou código) já está registado.',
            ]);

            TipoAtendimento::create([
                'nome'              => $request->nome,
                'codigo'            => $request->codigo,
                'especialidade'     => $request->has('especialidade'), // Retorna true se estiver marcado
                'status'            => 'activo',
                'user_id_criacao'   => Auth::id(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tipo de atendimento registado com sucesso!']);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $registro = TipoAtendimento::findOrFail($id);

            $request->validate([
                'nome'   => 'required|string|max:255|unique:tipos_atendimentos,nome,' . $id,
                'codigo' => 'required|string|max:50|unique:tipos_atendimentos,codigo,' . $id,
                'status' => 'required|in:activo,inactivo',
            ]);

            $registro->update([
                'nome'                => $request->nome,
                'codigo'              => $request->codigo,
                'especialidade'       => $request->has('especialidade'),
                'status'              => $request->status,
                'user_id_atualizacao' => Auth::id(), // Rastreabilidade
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Atualizado com sucesso!']);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar: ' . $e->getMessage()], 500);
        }
    }
}
