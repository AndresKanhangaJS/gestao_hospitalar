<?php

namespace App\Imports;

use App\Models\Paciente;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PacientesImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $seguradora_id;

    public function __construct($seguradora_id = null)
    {
        $this->seguradora_id = $seguradora_id;
    }

    public function model(array $row)
    {
        // 1. Gerar código único de 5 dígitos
        do {
            $codigo = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Paciente::where('codigo_paciente', $codigo)->exists());

        // 2. Tratar a data (Excel serial ou String)
        $dataNascimento = $this->transformDate($row['data_nascimento']);

        return new Paciente([
            'codigo_paciente'      => $codigo,
            'nome_completo'        => $row['nome_completo'],
            'data_nascimento'      => $dataNascimento,
            'genero'               => $row['genero'],
            'tipo_documento'       => $row['tipo_documento'],
            'numero_documento'     => $row['numero_documento'],
            'telefone'             => $row['telefone'] ?? null,
            'email'                => $row['email'] ?? null,
            'morada'               => $row['morada'] ?? null,
            'seguradora_id'        => $this->seguradora_id,
            'numero_cartao_seguro' => $row['numero_cartao'] ?? null,
            'grupo_sanguineo'      => $row['grupo_sanguineo'] ?? null,
            'status'               => 'activo',
            'user_id_criacao'      => Auth::id(),
        ]);
    }

    /**
     * Auxiliar para converter data do Excel (numérica) para objeto Carbon
     */
    private function transformDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }
        return Carbon::parse($value);
    }

    public function rules(): array
    {
        return [
            'nome_completo'    => 'required|string|max:255',
            'data_nascimento'  => 'required',
            'genero'           => 'required|in:Masculino,Feminino',
            'tipo_documento'   => 'required|in:BI,Cedula,Assento,Passaporte,Cartao_Residente',
            'numero_documento' => 'required|unique:pacientes,numero_documento',
            'email'            => 'nullable|email|unique:pacientes,email',
        ];
    }
}
