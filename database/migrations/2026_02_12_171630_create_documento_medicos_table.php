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
        Schema::create('documentos_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episodio_id')->constrained('episodios')->onDelete('cascade')->nullable();
            $table->foreignId('paciente_id')->constrained('pacientes')->nullable();
            $table->foreignId('medico_id')->constrained('medicos');
            $table->string('origem')->default('interna');
            $table->string('tipo');
            $table->string('titulo')->nullable();
            $table->longText('conteudo');
            $table->string('status', 20)->default('activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_medicos');
    }
};
