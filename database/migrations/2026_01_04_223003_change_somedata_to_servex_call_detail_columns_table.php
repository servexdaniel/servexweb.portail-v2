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
            $table->boolean('ismandatory')->default(false)->change();
            $table->boolean('isdefault')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servex_call_detail_columns', function (Blueprint $table) {
            //
        });
    }
};
