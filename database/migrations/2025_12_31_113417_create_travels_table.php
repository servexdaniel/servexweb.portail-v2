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
        Schema::create('servex_travels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('servex_customers');
            $table->string('TrNumber');
            $table->string('TrDesc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_travels');
    }
};
