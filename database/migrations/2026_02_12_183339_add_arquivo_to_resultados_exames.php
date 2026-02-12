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
        Schema::table('resultados_exames', function (Blueprint $table) {
            $table->string('arquivo_anexo')->nullable()->after('valor_resultado');
            $table->text('observacoes_laboratorio')->nullable()->after('arquivo_anexo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resultados_exames', function (Blueprint $table) {
            $table->dropColumn([
                'arquivo_anexo', 'observacoes_laboratorio',
            ]);
        });
    }
};
