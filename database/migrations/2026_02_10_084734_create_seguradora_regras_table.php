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
        Schema::create('seguradora_regras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seguradora_id')->constrained('seguradoras')->onDelete('cascade');
            $table->string('categoria'); // Consulta, Medicamento, etc
            $table->string('aplicavel_a')->default('todos'); // todos, titular, dependente
            $table->enum('tipo_valor', ['percentagem', 'fixo'])->default('percentagem');
            $table->decimal('valor_empresa', 15, 2);
            $table->decimal('valor_paciente', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguradora_regras');
    }
};
