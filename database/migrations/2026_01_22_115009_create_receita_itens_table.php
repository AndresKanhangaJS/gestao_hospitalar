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
        Schema::create('receita_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receita_id')->constrained('receitas')->onDelete('cascade');
            $table->string('medicamento');
            $table->string('dosagem');
            $table->string('frequencia');
            $table->string('duracao');
            $table->string('quantidade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receita_itens');
    }
};
