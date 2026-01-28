<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pacienteId = $this->route('paciente')->id;

        return [
            'nome_completo'    => 'required|string|max:255',
            'email'            => 'nullable|email|unique:pacientes,email,' . $pacienteId,
            'telefone'         => 'nullable|string|max:20',
            // Alterado para 'before_or_equal:today' para permitir a data de hoje
            'data_nascimento'  => 'required|date|before_or_equal:today',
            'genero'           => 'required|in:Masculino,Feminino',
            'tipo_documento'   => 'required|string',
            'numero_documento' => [
                'required',
                'string',
                Rule::unique('pacientes')->ignore($pacienteId),
            ],
            'grupo_sanguineo'  => 'nullable|string|max:5',
            'status'           => 'required|in:activo,inactivo',
            'seguradora_id'    => 'required_if:tem_seguro,on|nullable|exists:seguradoras,id',
            'numero_cartao_seguro' => 'nullable|string|max:50',
            'morada'           => 'nullable|string|max:500',
            'alergias'         => 'nullable|string',
        ];
    }

    /**
     * Personalização das mensagens de erro
     */
    public function messages(): array
    {
        return [
            'nome_completo.required'    => 'O nome completo é obrigatório.',
            'data_nascimento.required'  => 'A data de nascimento é obrigatória.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser uma data futura.',
            'email.email'               => 'Introduza um endereço de e-mail válido.',
            'email.unique'              => 'Este e-mail já está registado em outro paciente.',
            'numero_documento.required' => 'O número do documento é obrigatório.',
            'numero_documento.unique'   => 'Este número de documento já existe no sistema.',
            'genero.in'                 => 'Selecione um género válido.',
            'status.required'           => 'O estado do registo é obrigatório.',
        ];
    }
}
