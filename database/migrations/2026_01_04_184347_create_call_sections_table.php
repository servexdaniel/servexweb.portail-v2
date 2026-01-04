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
        Schema::create('servex_call_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('code', ['DETAIL','BILLTO', 'SHIPTO','SERIAL','ADDRESS'])->unique();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_call_sections');
    }
};
