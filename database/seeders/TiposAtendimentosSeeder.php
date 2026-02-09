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
            ['nome' => 'Clínica Geral', 'codigo' => 'CG'],
            ['nome' => 'Odontologia', 'codigo' => 'ODO'],
            ['nome' => 'Pediatria', 'codigo' => 'PED'],
            ['nome' => 'Ginecologia e Obstetrícia', 'codigo' => 'GO'],
            ['nome' => 'Ortopedia', 'codigo' => 'ORT'],
            ['nome' => 'Cardiologia', 'codigo' => 'CARD'],
            ['nome' => 'Urgência / Emergência', 'codigo' => 'URG'],
            ['nome' => 'Enfermagem (Curativos/Injeções)', 'codigo' => 'ENF'],
            ['nome' => 'Oftalmologia', 'codigo' => 'OFT'],
            ['nome' => 'Dermatologia', 'codigo' => 'DERM'],
            ['nome' => 'Psicologia', 'codigo' => 'PSI'],
            ['nome' => 'Fisioterapia', 'codigo' => 'FISIO'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos_atendimentos')->updateOrInsert(
                ['codigo' => $tipo['codigo']],
                [
                    'nome' => $tipo['nome'],
                    'user_id_criacao' => 1,
                    'status' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
