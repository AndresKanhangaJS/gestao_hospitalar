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
        Schema::table('tipos_atendimentos', function (Blueprint $table) {
            $table->boolean('especialidade')->default(false)->nullable()->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_atendimentos', function (Blueprint $table) {
            $table->dropColumn('especialidade');
        });
    }
};
