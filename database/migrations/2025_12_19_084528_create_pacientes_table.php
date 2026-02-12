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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->date('data_nascimento')->nullable();
            $table->string('genero');

            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento', 30)->nullable();

            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('morada')->nullable();
            $table->string('grupo_sanguineo', 5)->nullable();
            $table->text('alergias')->nullable();

            $table->foreignId('user_id_criacao')->constrained('users');
            $table->foreignId('user_id_atualizacao')->nullable()->constrained('users');
            $table->foreignId('user_id_delete')->nullable()->constrained('users')->onDelete('set null');
            $table->text('motivo_exclusao')->nullable();

            $table->string('status', 20)->default('activo');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tipo_documento', 'numero_documento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
