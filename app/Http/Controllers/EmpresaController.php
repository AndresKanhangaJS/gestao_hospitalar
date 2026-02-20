<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $empresas = Empresa::latest()->paginate(12)->withQueryString();

        return view('empresas.index', compact('empresas'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Validação dos Dados
            $request->validate([
                'nome' => 'required|string|max:255',
                'nif'  => 'required|string|unique:empresas,nif',
                'logotipo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'telefone' => 'nullable|string',
                'telefone_alternativo_1' => 'nullable|string',
                'telefone_alternativo_2' => 'nullable|string',
                'email' => 'nullable|email',
                'email_alternativo' => 'nullable|email',
                'localizacao' => 'nullable|string',
                'status' => 'nullable|string',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'unique' => 'Este valor já está em uso.',
                'email' => 'Insira um e-mail válido.',
                'logotipo.image' => 'O ficheiro deve ser uma imagem válida.'
            ]);

            // 2. Tratamento do Logotipo
            $nomeArquivoLogo = null;
            if ($request->hasFile('logotipo')) {
                $file = $request->file('logotipo');
                // Nome limpo: logo_empresa_timestamp.extensao
                $nomeArquivoLogo = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                // Salva em storage/app/public/logos_empresas
                $file->storeAs('logos_empresas', $nomeArquivoLogo, 'public');
            }

            // 2. Criar a Empresa
            $empresa = Empresa::create([
                'nome'                   => $request->nome,
                'nif'                    => $request->nif,
                'telefone'               => $request->telefone,
                'telefone_alternativo_a' => $request->telefone_alternativo_1,
                'telefone_alternativo_b' => $request->telefone_alternativo_2,
                'email'                  => $request->email,
                'email_alternativo'      => $request->email_alternativo,
                'localizacao'            => $request->localizacao,
                'logo'                   => $nomeArquivoLogo,
                'status'                 => 'activo',
            ]);

            DB::commit();
                return response()->json([
                'success' => true,
                'message' => 'Empresa registada com sucesso!'
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
        DB::beginTransaction();
        try {
            $empresa = Empresa::findOrFail($id);

            // 1. Validação (Mesma lógica do Store, mas com exceção do NIF para o ID atual)
            $validado = $request->validate([
                'nome'                   => 'required|string|max:255',
                'nif'                    => 'required|string|unique:empresas,nif,' . $id,
                'logotipo'               => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'telefone'               => 'nullable|string',
                'telefone_alternativo_1' => 'nullable|string',
                'telefone_alternativo_2' => 'nullable|string',
                'email'                  => 'nullable|email',
                'email_alternativo'      => 'nullable|email',
                'localizacao'            => 'nullable|string',
                'status'                 => 'required|in:activo,inactivo',
            ], [
                'required' => 'O campo :attribute é obrigatório.',
                'unique'   => 'Este valor já está em uso.',
                'email'    => 'Insira um e-mail válido.',
            ]);

            // 2. Mapeamento de campos para o Banco de Dados
            $dadosUpdate = [
                'nome'                   => $request->nome,
                'nif'                    => $request->nif,
                'telefone'               => $request->telefone,
                'telefone_alternativo_a' => $request->telefone_alternativo_1,
                'telefone_alternativo_b' => $request->telefone_alternativo_2,
                'email'                  => $request->email,
                'email_alternativo'      => $request->email_alternativo,
                'localizacao'            => $request->localizacao,
                'status'                 => $request->status,
            ];

            // 3. Tratamento do Logotipo
            if ($request->hasFile('logotipo')) {
                // Deletar antigo
                if ($empresa->logo && Storage::disk('public')->exists('logos_empresas/' . $empresa->logo)) {
                    Storage::disk('public')->delete('logos_empresas/' . $empresa->logo);
                }

                $file = $request->file('logotipo');
                $nomeArquivoLogo = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('logos_empresas', $nomeArquivoLogo, 'public');

                $dadosUpdate['logo'] = $nomeArquivoLogo;
            }

            $empresa->update($dadosUpdate);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Dados da empresa atualizados com sucesso!'
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar: ' . $e->getMessage()
            ], 500);
        }
    }
}
