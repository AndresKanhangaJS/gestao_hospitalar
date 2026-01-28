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
        Schema::create('exames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exame_categoria_id')->constrained('exame_categorias');
            $table->string('codigo')->unique(); // Ex: HEM001
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('requer_jejum')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exames');
    }
};
