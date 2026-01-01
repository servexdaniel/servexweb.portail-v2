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
        Schema::create('servex_datax', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('servex_customers');
            $table->string('dataxname');
            $table->string('fieldtype');
            $table->string('fieldlabel');
            $table->string('fieldname');
            $table->longText('fielditems');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_datax');
    }
};
