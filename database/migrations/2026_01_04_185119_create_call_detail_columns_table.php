<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servex_call_detail_columns', function (Blueprint $table) {
            $table->id();
            $table->string('column');
            $table->string('description')->nullable();
            $table->boolean('isdefault')->nullable();
            $table->boolean('ismandatory')->nullable();
            $table->integer('display_order')->nullable();

            $table->boolean('is_cbo')->default(false)->nullable();
            $table->boolean('is_input')->default(false)->nullable();

            $table->string('cbo_type')->nullable();
            $table->string('cbo_items')->nullable();
            $table->foreignId('section_id')->constrained('servex_call_sections');
            $table->timestamps();
        });

        // Règle 1 : is_cbo et is_input ne peuvent pas être true en même temps
        DB::statement('ALTER TABLE servex_call_detail_columns 
            ADD CONSTRAINT check_not_both_cbo_and_input 
            CHECK (NOT (is_cbo AND is_input))');

        // Règle 2 : Si is_cbo = true, alors cbo_type et cbo_items DOIVENT être remplis
        DB::statement('ALTER TABLE servex_call_detail_columns 
            ADD CONSTRAINT check_cbo_requires_fields 
            CHECK (
                (is_cbo = false) 
                OR (
                    is_cbo = true 
                    AND cbo_type IS NOT NULL 
                    AND cbo_type <> "" 
                    AND cbo_items IS NOT NULL 
                    AND cbo_items <> ""
                )
            )');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des contraintes avant de dropper la table (optionnel mais propre)
        DB::statement('ALTER TABLE servex_call_detail_columns DROP CONSTRAINT IF EXISTS check_not_both_cbo_and_input');
        DB::statement('ALTER TABLE servex_call_detail_columns DROP CONSTRAINT IF EXISTS check_cbo_requires_fields');

        Schema::dropIfExists('servex_call_detail_columns');
    }
};
