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
        Schema::table('episodios', function (Blueprint $table) {
            // 1. Remover a foreign key antiga
            $table->dropForeign(['medico_id']);

            // 2. Alterar a restrição para apontar para a tabela 'medicos'
            $table->foreign('medico_id')
                  ->nullable()
                  ->references('id')
                  ->on('medicos')
                  ->onDelete('restrict'); // Evita apagar médico com episódios vinculados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('episodios', function (Blueprint $table) {
            $table->dropForeign(['medico_id']);
            $table->foreign('medico_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
