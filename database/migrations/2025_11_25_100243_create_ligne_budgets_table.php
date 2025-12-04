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
        Schema::create('ligne_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('intitule');
            $table->text('description')->nullable();
            $table->integer('montant')->nullable();
            
            $table->foreignId('code_budget_id')->constrained('code_budgets')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_budgets');
    }
};
