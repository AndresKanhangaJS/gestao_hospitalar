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
            $table->text('observacoes_laboratorio')->nullable()->after('observacoes_clinicas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisicao_exames', function (Blueprint $table) {
            $table->dropColumn([
                'observacoes_laboratorio',
            ]);
        });
    }
};
