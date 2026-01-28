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
        Schema::create('resultados_exames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicao_item_id')->constrained('requisicao_itens')->onDelete('cascade');
            $table->foreignId('exame_item_id')->constrained('exame_itens');
            $table->text('valor_resultado')->nullable();
            $table->foreignId('tecnico_id')->nullable()->constrained('users');
            $table->timestamp('data_resultado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultado_exames');
    }
};
