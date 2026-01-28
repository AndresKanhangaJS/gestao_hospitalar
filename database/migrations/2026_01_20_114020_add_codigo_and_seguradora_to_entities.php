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
        // Adicionando código e seguradora aos Pacientes
        Schema::table('pacientes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            $table->string('codigo_paciente')->unique()->after('user_id');
            $table->foreignId('seguradora_id')->nullable()->constrained('seguradoras')->onDelete('set null')->after('codigo_paciente');
            $table->string('numero_cartao_seguro')->nullable()->after('seguradora_id');
        });

        // Adicionando código aos Médicos
        Schema::table('medicos', function (Blueprint $table) {
            $table->string('codigo_medico')->unique()->after('id');
        });

        // Adicionando código aos Usuários
        Schema::table('users', function (Blueprint $table) {
            $table->string('codigo')->unique()->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Reverter alterações na tabela de Pacientes
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['seguradora_id']);
            $table->dropColumn([
                'user_id',
                'codigo_paciente',
                'seguradora_id',
                'numero_cartao_seguro'
            ]);
        });

        // 2. Reverter alterações na tabela de Médicos
        Schema::table('medicos', function (Blueprint $table) {
            $table->dropColumn('codigo_medico');
        });

        // 3. Reverter alterações na tabela de Usuários
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('codigo');
        });
    }
};
