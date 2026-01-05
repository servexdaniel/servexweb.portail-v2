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
        Schema::table('servex_call_detail_columns', function (Blueprint $table) {
            $table->boolean('display_in_visualisation')->after('display_order')->default(true)->nullable();
            $table->boolean('display_in_creation')->after('display_in_visualisation')->default(true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servex_call_detail_columns', function (Blueprint $table) {
            $table->dropColumn('display_in_visualisation');
            $table->dropColumn('display_in_creation');
        });
    }
};
