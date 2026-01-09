<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NotaClinica;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use DB;

class NotaClinicaController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $validado = $request->validate([
                'episodio_id'          => 'required|exists:episodios,id',
                'queixa_principal'     => 'required|string|min:5',
                'historia_doenca'      => 'required|string|min:10',
                'diagnostico_hipotese' => 'required|string',
                'exame_fisico'         => 'nullable|string',
                'plano_tratamento'     => 'nullable|string',
            ], [
                'required' => 'O campo :attribute é indispensável para o registro clínico.',
                'min'      => 'O campo :attribute está muito curto.',
            ]);

            DB::beginTransaction();

            $nota = NotaClinica::create([
                'episodio_id'          => $validado['episodio_id'],
                'queixa_principal'     => $validado['queixa_principal'],
                'historia_doenca'      => $validado['historia_doenca'],
                'exame_fisico'         => $validado['exame_fisico'],
                'diagnostico_hipotese' => $validado['diagnostico_hipotese'],
                'plano_tratamento'     => $validado['plano_tratamento'],
                'user_id_criacao'      => auth()->id(),
                'status'               => 'activo',
                'situacao'             => 'Finalizado'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nota clínica registrada e anexada ao prontuário!',
                'data'    => $nota
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar nota: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            // 1. Validação (Mantendo o padrão do store, mas sem a necessidade do episodio_id na edição)
            $validado = $request->validate([
                'queixa_principal'     => 'required|string|min:5',
                'historia_doenca'      => 'required|string|min:10',
                'diagnostico_hipotese' => 'required|string',
                'exame_fisico'         => 'nullable|string',
                'plano_tratamento'     => 'nullable|string',
            ], [
                'required' => 'O campo :attribute é indispensável para a atualização clínica.',
                'min'      => 'O campo :attribute está muito curto.',
            ]);

            DB::beginTransaction();

            // 2. Localização da nota
            $nota = NotaClinica::findOrFail($id);

            // 3. Atualização dos campos
            $nota->update([
                'queixa_principal'     => $validado['queixa_principal'],
                'historia_doenca'      => $validado['historia_doenca'],
                'exame_fisico'         => $validado['exame_fisico'],
                'diagnostico_hipotese' => $validado['diagnostico_hipotese'],
                'plano_tratamento'     => $validado['plano_tratamento'],
                'user_id_edicao'    => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Evolução clínica atualizada com sucesso!',
                'data'    => $nota
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar nota: ' . $e->getMessage()
            ], 500);
        }
    }
}
