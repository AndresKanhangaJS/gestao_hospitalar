<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicoRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true; // Altere para auth()->check() se necessário
    }

    /**
     * Regras de validação para a atualização do médico.
     */
    public function rules(): array
    {
        $medicoId = $this->route('medico')->id;
        $userId = $this->route('medico')->user_id;

        return [
            'role'             => ['required', 'string'],
            'nome_completo'    => ['required', 'string', 'max:255'],
            'email'            => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'numero_ordem'     => [
                'required_if:role,Médico,Enfermeiro,Laboratorista',
                'nullable',
                'string',
                Rule::unique('medicos', 'numero_ordem')->ignore($medicoId)
            ],
            'especialidade_id'    => [
                'required_if:role,Médico',
                'nullable',
                'string',
                'max:150'
            ],
            'genero'           => ['required', 'in:Masculino,Feminino'],
            'data_nascimento'  => ['nullable', 'date', 'before_or_equal:today'],
            'telefone'         => ['nullable', 'string', 'max:20'],
            'tipo_documento'   => ['nullable', 'in:BI,Passaporte'],
            'numero_documento' => ['nullable', 'string', 'max:50'],
            'morada'           => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'role' => $this->route('medico')->user->roles->first()->name ?? 'Médico',
        ]);
    }

    /**
     * Mensagens personalizadas de erro.
     */
    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'numero_ordem.required'  => 'O número de ordem (CRM) é obrigatório.',
            'numero_ordem.unique'    => 'Este número de ordem já está registado para outro médico.',
            'especialidade_id.required' => 'A especialidade deve ser informada.',
            'email.required'         => 'O e-mail é obrigatório.',
            'email.email'            => 'Insira um endereço de e-mail válido.',
            'email.unique'           => 'Este e-mail já está a ser utilizado por outro utilizador.',
            'genero.required'        => 'Selecione o género.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser uma data futura.',
        ];
    }
}
