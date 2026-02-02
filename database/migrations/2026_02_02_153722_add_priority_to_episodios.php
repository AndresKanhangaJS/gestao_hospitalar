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
            $table->string('prioridade')->nullable()->after('data_fecho');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('episodios', function (Blueprint $table) {
            $table->dropColumn('prioridade');
        });
    }
};
