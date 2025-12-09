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
        Schema::table('servex_contacts', function (Blueprint $table) {
            $table->rememberToken()->nullable()->after('connected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servex_contacts', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
