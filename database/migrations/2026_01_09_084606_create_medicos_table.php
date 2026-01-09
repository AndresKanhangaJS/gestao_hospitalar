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
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->string('genero');

            $table->string('tipo_documento');
            $table->string('numero_documento', 30);

            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('morada')->nullable();

            // Dados Profissionais Específicos
            $table->string('numero_ordem', 30)->unique();
            $table->string('especialidade')->nullable();

            $table->foreignId('user_id_criacao')->constrained('users');
            $table->foreignId('user_id_atualizacao')->nullable()->constrained('users');
            $table->foreignId('user_id_delete')->nullable()->constrained('users')->onDelete('set null');
            $table->text('motivo_exclusao')->nullable();

            $table->string('status', 20)->default('activo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};
