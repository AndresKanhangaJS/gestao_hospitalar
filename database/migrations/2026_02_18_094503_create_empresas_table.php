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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nif')->unique();
            $table->string('telefone')->nullable();
            $table->string('telefone_alternativo_a')->nullable();
            $table->string('telefone_alternativo_b')->nullable();
            $table->string('email')->nullable();
            $table->string('email_alternativo')->nullable();
            $table->text('logo')->nullable();
            $table->text('localizacao')->nullable();
            $table->string('status', 20)->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
