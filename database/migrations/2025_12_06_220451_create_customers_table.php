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
        Schema::create('servex_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('rabbimq_host')->nullable();
            $table->string('rabbitmq_port')->nullable();
            $table->string('serialnumber')->nullable();
            $table->string('securitykey')->nullable();
            $table->timestamps();
            $table->unique(['serialnumber', 'domain', 'securitykey'], 'customer_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_customers');
    }
};
