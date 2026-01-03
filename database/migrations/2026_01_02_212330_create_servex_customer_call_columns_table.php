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
        Schema::create('servex_customer_call_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('servex_customers');
            $table->foreignId('column_id')->constrained('servex_call_columns');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_customer_call_columns');
    }
};
