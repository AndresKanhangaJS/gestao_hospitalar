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
        Schema::table('requisicao_exames', function (Blueprint $table) {
            $table->enum('prioridade', ['normal', 'urgente'])->default('normal')->after('medico_id');
            $table->text('observacoes_clinicas')->nullable()->after('prioridade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisicao_exames', function (Blueprint $table) {
            $table->dropColumn(['prioridade', 'observacoes_clinicas']);
        });
    }
};
