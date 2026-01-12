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
            // ID do usuário que fechou (Auditoria)
            $table->foreignId('user_id_fechamento')
                ->after('user_id_atualizacao')
                ->nullable()
                ->constrained('users');

            // Notas sobre o encerramento
            $table->text('observacoes_fechamento')
                ->after('situacao')
                ->nullable();

            $table->dateTime('data_fecho')->nullable()->change()->after('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('episodios', function (Blueprint $table) {
            $table->dropForeign(['user_id_fechamento']);
            $table->dropColumn(['user_id_fechamento', 'observacoes_fechamento', 'data_fecho']);
        });
    }
};
