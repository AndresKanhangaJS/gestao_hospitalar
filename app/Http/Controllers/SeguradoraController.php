<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seguradora;
use Illuminate\Validation\ValidationException;

class SeguradoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Seguradora::query();

        $query->when($request->search, function ($q, $search) {
            $q->where('nome', 'like', "%{$search}%")
              ->orWhere('codigo_seguradora', 'like', "%{$search}%")
              ->orWhere('nif', 'like', "%{$search}%");
        });

        $seguradoras = $query->latest()->paginate(10)->withQueryString();
        return view('seguradoras.index', compact('seguradoras'));
    }

    public function store(Request $request)
    {
        try {
            $validado = $request->validate([
                'nome' => 'required|string|max:255',
                'codigo_seguradora' => 'required|string|unique:seguradoras,codigo_seguradora',
                'tipo' => 'required|in:seguradora,empresa',
                'nif' => 'nullable|string|unique:seguradoras,nif',
                'telefone' => 'nullable|string',
                'email' => 'nullable|email',
            ]);

            $validado['status'] = 'activo';
            Seguradora::create($validado);

            return response()->json(['success' => true, 'message' => 'Registo criado com sucesso!'], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro interno ao salvar.'], 500);
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
            ]);

            $seguradora->fill($validado);
            if (!$seguradora->isDirty()) {
                return response()->json(['status' => 'info', 'message' => 'Nenhuma alteração detectada.'], 200);
            }

            $seguradora->save();
            return response()->json(['status' => 'success', 'message' => 'Actualizado com sucesso!'], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Erro ao atualizar.'], 500);
        }
    }
}
