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
        Schema::create('episodios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('users');
            $table->foreignId('tipo_atendimento_id')->constrained('tipos_atendimentos');

            $table->foreignId('user_id_criacao')->constrained('users');
            $table->foreignId('user_id_atualizacao')->nullable()->constrained('users');

            $table->string('codigo_atendimento')->unique();
            $table->dateTime('data_abertura');
            $table->dateTime('data_fecho')->nullable();

            $table->enum('status', ['activo', 'inactivo'])->default('activo');
            $table->enum('situacao', ['Aberto', 'Em Espera', 'Concluido', 'Cancelado'])->default('Aberto')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodios');
    }
};
