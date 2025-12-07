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
        Schema::create('servex_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('sessionid')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('servex_customers')->onDelete('cascade')->onUpdate('cascade');
            $table->string('connected_at')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('CcUnique')->nullable();
            $table->string('CcName')->nullable();
            $table->string('CuNumber')->nullable();
            $table->string('CuName')->nullable();
            $table->string('CuAddress')->nullable();
            $table->string('CuCity')->nullable();
            $table->string('CuPostalCode')->nullable();
            $table->string('CcIsManager')->nullable();
            $table->string('CcPortailAdmin')->nullable();
            $table->string('CcPhoneNumber')->nullable();
            $table->string('CcPhoneExt')->nullable();
            $table->string('CcCellNumber')->nullable();
            $table->string('CcEmail')->nullable();
            $table->string('LoginSuccess')->nullable();
            $table->string('ReasonLogin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servex_contacts');
    }
};
