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
        Schema::create('servex_call_columns', function (Blueprint $table) {
            $table->id();
            $table->string('column');
            $table->string('description')->nullable();
            $table->boolean('isdefault')->nullable();
            $table->boolean('ismandatory')->nullable();
            $table->boolean('display_in_grid')->nullable();
            $table->boolean('display_in_form')->nullable();
            $table->integer('display_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_call_columns');
    }
};
