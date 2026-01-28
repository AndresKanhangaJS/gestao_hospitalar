<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requisicao_exames', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_requisicao')->unique();
            $table->foreignId('episodio_id')->constrained('episodios'); // Vínculo com o atendimento atual
            $table->foreignId('medico_id')->constrained('users'); // Médico que solicitou
            $table->enum('status', ['pendente', 'em_coleta', 'laboratorio', 'concluido', 'cancelado'])->default('pendente');
            $table->timestamp('data_solicitacao')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicao_exames');
    }
};
