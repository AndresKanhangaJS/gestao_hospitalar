<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('episodios', function (Blueprint $table) {
            // 1. Tornar campos existentes nullable para permitir a abertura sem triagem
            $table->foreignId('medico_id')->nullable()->change();
            $table->string('prioridade')->nullable()->change();

            // 2. Rastreabilidade da Triagem (Enfermagem)
            $table->foreignId('user_id_triagem')
                  ->nullable()
                  ->after('user_id_criacao')
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('data_triagem')
                  ->nullable()
                  ->after('data_abertura');

            // 3. Rastreabilidade do Atendimento (Médico)
            $table->timestamp('data_inicio_atendimento')
                  ->nullable()
                  ->comment('Quando o médico clicou em iniciar consulta')
                  ->after('data_triagem');

            $table->text('observacoes_triagem')
                  ->nullable()
                  ->after('data_inicio_atendimento');
            // 4. Melhoria na Situação
            // Sugestão de valores: 'Aguardando Triagem', 'Aguardando Atendimento', 'Em Consulta', 'Finalizado'
            $table->string('situacao')->default('Aguardando Triagem')->change();
        });
    }

    public function down(): void
    {
        Schema::table('episodios', function (Blueprint $table) {
            $table->dropForeign(['user_id_triagem']);
            $table->dropColumn(['user_id_triagem', 'data_triagem', 'data_inicio_atendimento', 'observacoes_triagem']);
        });
    }
};
