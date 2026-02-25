<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposAtendimentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nome' => 'Clínica Geral', 'codigo' => 'CG', 'especialidade' => false],
            ['nome' => 'Odontologia', 'codigo' => 'ODO', 'especialidade' => true],
            ['nome' => 'Pediatria', 'codigo' => 'PED', 'especialidade' => true],
            ['nome' => 'Ginecologia e Obstetrícia', 'codigo' => 'GO', 'especialidade' => true],
            ['nome' => 'Ortopedia', 'codigo' => 'ORT', 'especialidade' => true],
            ['nome' => 'Cardiologia', 'codigo' => 'CARD', 'especialidade' => true],
            ['nome' => 'Urgência / Emergência', 'codigo' => 'URG', 'especialidade' => false],
            ['nome' => 'Enfermagem (Curativos/Injeções)', 'codigo' => 'ENF', 'especialidade' => false],
            ['nome' => 'Oftalmologia', 'codigo' => 'OFT', 'especialidade' => true],
            ['nome' => 'Dermatologia', 'codigo' => 'DERM', 'especialidade' => true],
            ['nome' => 'Psicologia', 'codigo' => 'PSI', 'especialidade' => true],
            ['nome' => 'Fisioterapia', 'codigo' => 'FISIO', 'especialidade' => true],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_atendimentos')->updateOrInsert(
                ['codigo' => $tipo['codigo']],
                [
                    'nome' => $tipo['nome'],
                    'especialidade' => $tipo['especialidade'],
                    'user_id_criacao' => 1,
                    'status' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
