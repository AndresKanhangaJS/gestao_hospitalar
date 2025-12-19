<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    public function store(Request $request)
    {
        $validado = $request->validate([
            'nome_completo'    => 'required|string|max:255',
            'data_nascimento'  => 'required|date',
            'genero'           => 'required|in:Masculino,Feminino',
            'tipo_documento'   => 'required|in:BI,Cedula,Assento,Passaporte,Cartao_Residente',
            'numero_documento' => [
                'required', 'string',
                Rule::unique('pacientes')->where(fn($q) => $q->where('tipo_documento', $request->tipo_documento))
            ],
            'telefone' => 'nullable|string',
            'email'    => 'nullable|email',
        ]);

        $validado['user_id_criacao'] = Auth::id();
        $validado['status'] = 'activo';

        Paciente::create($validado);

        return redirect()->route('pacientes.index')->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        // Validação similar ao store, mas ignorando o ID atual no unique
        $validado = $request->validate([
            'nome_completo' => 'required|string',
            'status'        => 'required|in:activo,inactivo',
            // ... outras validações
        ]);

        $validado['user_id_atualizacao'] = Auth::id();

        $paciente->update($validado);

        return redirect()->route('pacientes.index')->with('success', 'Dados atualizados!');
    }
}
