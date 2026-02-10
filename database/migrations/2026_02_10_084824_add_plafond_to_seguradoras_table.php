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
        Schema::table('seguradoras', function (Blueprint $table) {
            // Valor total que a empresa depositou/contratou
            $table->decimal('fundo_global', 15, 2)->default(0)->after('status');

            // Quanto desse fundo ainda resta
            $table->decimal('saldo_atual', 15, 2)->default(0)->after('fundo_global');

            // Limite individual por funcionário (ex: cada um só pode gastar 50.000 AKZ)
            $table->decimal('limite_por_funcionario', 15, 2)->default(0)->after('saldo_atual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seguradoras', function (Blueprint $table) {
            //
        });
    }
};
