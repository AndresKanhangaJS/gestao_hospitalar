<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExameCategoria;
use App\Models\Exame;
use App\Models\ExameItem;
use Illuminate\Support\Facades\DB;

class ExameModuloSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar tabelas para evitar duplicados (opcional)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('exame_itens')->truncate();
        DB::table('exames')->truncate();
        DB::table('exame_categorias')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- CATEGORIA: HEMATOLOGIA ---
        $hematologia = ExameCategoria::create(['nome' => 'Hematologia', 'status' => 'activo']);

        $hemograma = Exame::create([
            'exame_categoria_id' => $hematologia->id,
            'codigo' => 'HEM001',
            'nome' => 'Hemograma Completo',
            'descricao' => 'Avaliação das células sanguíneas (Série vermelha e branca)',
            'requer_jejum' => true
        ]);

        $itensHemograma = [
            ['descricao' => 'Hemácias', 'unidade_medida' => 'milhões/mm³', 'referencia_minimo' => '4.5', 'referencia_maximo' => '5.9', 'tipo_campo' => 'numerico'],
            ['descricao' => 'Hemoglobina', 'unidade_medida' => 'g/dL', 'referencia_minimo' => '13.5', 'referencia_maximo' => '17.5', 'tipo_campo' => 'numerico'],
            ['descricao' => 'Leucócitos Totais', 'unidade_medida' => '/mm³', 'referencia_minimo' => '4000', 'referencia_maximo' => '11000', 'tipo_campo' => 'numerico'],
            ['descricao' => 'Plaquetas', 'unidade_medida' => '/mm³', 'referencia_minimo' => '150000', 'referencia_maximo' => '450000', 'tipo_campo' => 'numerico'],
        ];

        foreach ($itensHemograma as $item) {
            ExameItem::create(array_merge($item, ['exame_id' => $hemograma->id]));
        }

        // --- CATEGORIA: BIOQUÍMICA ---
        $bioquimica = ExameCategoria::create(['nome' => 'Bioquímica', 'status' => 'activo']);

        // Exame: Glicose
        $glicose = Exame::create([
            'exame_categoria_id' => $bioquimica->id,
            'codigo' => 'BIO001',
            'nome' => 'Glicemia em Jejum',
            'descricao' => 'Medição do nível de açúcar no sangue',
            'requer_jejum' => true
        ]);

        ExameItem::create([
            'exame_id' => $glicose->id,
            'descricao' => 'Glicose',
            'unidade_medida' => 'mg/dL',
            'referencia_minimo' => '70',
            'referencia_maximo' => '99',
            'tipo_campo' => 'numerico'
        ]);

        // Exame: Perfil Lipídico
        $lipidico = Exame::create([
            'exame_categoria_id' => $bioquimica->id,
            'codigo' => 'BIO002',
            'nome' => 'Perfil Lipídico',
            'descricao' => 'Colesterol total e frações',
            'requer_jejum' => true
        ]);

        $itensLipidico = [
            ['descricao' => 'Colesterol Total', 'unidade_medida' => 'mg/dL', 'referencia_minimo' => null, 'referencia_maximo' => '190', 'tipo_campo' => 'numerico'],
            ['descricao' => 'HDL (Bom)', 'unidade_medida' => 'mg/dL', 'referencia_minimo' => '40', 'referencia_maximo' => null, 'tipo_campo' => 'numerico'],
            ['descricao' => 'LDL (Mau)', 'unidade_medida' => 'mg/dL', 'referencia_minimo' => null, 'referencia_maximo' => '130', 'tipo_campo' => 'numerico'],
        ];

        foreach ($itensLipidico as $item) {
            ExameItem::create(array_merge($item, ['exame_id' => $lipidico->id]));
        }

        // --- CATEGORIA: IMAGIOLOGIA ---
        $imagiologia = ExameCategoria::create(['nome' => 'Imagiologia', 'status' => 'activo']);

        $raiox = Exame::create([
            'exame_categoria_id' => $imagiologia->id,
            'codigo' => 'IMG001',
            'nome' => 'Raio-X de Tórax (PA e Perfil)',
            'descricao' => 'Exame de imagem dos pulmões e coração',
            'requer_jejum' => false
        ]);

        ExameItem::create([
            'exame_id' => $raiox->id,
            'descricao' => 'Relatório do Laudo',
            'unidade_medida' => null,
            'referencia_minimo' => null,
            'referencia_maximo' => null,
            'tipo_campo' => 'long_text' // Para o médico escrever o texto do laudo
        ]);
    }
}
