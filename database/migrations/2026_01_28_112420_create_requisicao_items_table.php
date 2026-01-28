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
        Schema::create('requisicao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisicao_id')->constrained('requisicao_exames')->onDelete('cascade');
            $table->foreignId('exame_id')->constrained('exames');
            $table->enum('status', ['pendente', 'concluido'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicao_itens');
    }
};
