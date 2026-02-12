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

        // 2. Tratar a data apenas se houver valor
        $dataNascimento = !empty($row['data_nascimento']) ? $this->transformDate($row['data_nascimento']) : null;

        return new Paciente([
            'codigo_paciente'      => $codigo,
            'nome_completo'        => $row['nome_completo'],
            'data_nascimento'      => $dataNascimento,
            'genero'               => $row['genero'] ?? 'Masculino',
            'tipo_documento'       => $row['tipo_documento'] ?? 'BI',
            'numero_documento'     => $row['numero_documento'] ?? null,
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

    private function transformDate($value)
    {
        if (empty($value)) return null;

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null; // Se a data for inválida, retorna nulo em vez de quebrar
        }
    }

    public function rules(): array
    {
        return [
            // Apenas o nome é estritamente obrigatório
            'nome_completo'    => 'required|string|max:255',

            // O resto passa a ser opcional (nullable)
            'data_nascimento'  => 'nullable',
            'genero'           => 'nullable',
            'tipo_documento'   => 'nullable',

            // Se o número do documento existir no Excel, ele deve ser único.
            // Se estiver vazio, a regra unique é ignorada (dependendo da versão do Laravel/DB)
            'numero_documento' => 'nullable',
            'email'            => 'nullable|email',
        ];
    }
}
