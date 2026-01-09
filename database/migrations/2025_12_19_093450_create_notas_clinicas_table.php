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
        Schema::create('notas_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episodio_id')->constrained('episodios')->onDelete('cascade');

            $table->mediumText('queixa_principal');
            $table->mediumText('historia_doenca');
            $table->mediumText('exame_fisico')->nullable();
            $table->mediumText('diagnostico_hipotese');
            $table->mediumText('plano_tratamento')->nullable();


            $table->foreignId('user_id_criacao')->constrained('users');
            $table->foreignId('user_id_atualizacao')->nullable()->constrained('users');
            $table->foreignId('user_id_delete')->nullable()->constrained('users')->onDelete('set null');
            $table->text('motivo_exclusao')->nullable();
            
            $table->string('status', 20)->default('activo');
            $table->string('situacao')->default('Finalizado')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_clinicas');
    }
};
