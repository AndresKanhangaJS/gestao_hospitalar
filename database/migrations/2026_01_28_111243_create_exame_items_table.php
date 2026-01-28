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
        Schema::create('exame_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exame_id')->constrained('exames')->onDelete('cascade');
            $table->string('descricao'); // Ex: Hemoglobina, Glicose
            $table->string('unidade_medida')->nullable(); // Ex: mg/dL, u/L
            $table->string('referencia_minimo')->nullable();
            $table->string('referencia_maximo')->nullable();
            $table->enum('tipo_campo', ['numerico', 'texto', 'long_text', 'formula'])->default('numerico');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exame_itens');
    }
};
