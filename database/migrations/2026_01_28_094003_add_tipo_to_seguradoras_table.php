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
            $table->enum('tipo', ['seguradora', 'empresa'])->default('seguradora')->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seguradoras', function (Blueprint $table) {
            $table->dropColumn(['tipo']);
        });
    }
};
