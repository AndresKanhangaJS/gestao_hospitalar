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
        Schema::create('exame_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Ex: Hematologia, Imagiologia, Bioquímica
            $table->string('status', 20)->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exame_categorias');
    }
};
