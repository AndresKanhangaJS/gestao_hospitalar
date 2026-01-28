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
        Schema::table('episodios', function (Blueprint $table) {
            $table->string('pa_sistolica', 10)->nullable()->after('tipo_atendimento_id');
            $table->string('pa_diastolica', 10)->nullable()->after('pa_sistolica');
            $table->decimal('temperatura', 4, 1)->nullable()->after('pa_diastolica'); // Ex: 36.5
            $table->decimal('peso', 5, 2)->nullable()->after('temperatura');         // Ex: 120.50
            $table->decimal('altura', 3, 2)->nullable()->after('peso');            // Ex: 1.75
            $table->integer('frequencia_cardiaca')->nullable()->after('altura');
            $table->integer('saturacao')->nullable()->after('frequencia_cardiaca');  // SpO2 %
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('episodios', function (Blueprint $table) {
            $table->dropColumn([
                'pa_sistolica', 'pa_diastolica', 'temperatura',
                'peso', 'altura', 'frequencia_cardiaca', 'saturacao'
            ]);
        });
    }
};
