<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('previsions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ligne_budget_id')->nullable()->constrained('ligne_budgets')->onDelete('set null');
            $table->unsignedSmallInteger('year'); // ex: 2025
            $table->string('notes')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            $table->unique(['ligne_budget_id', 'year'], 'prevision_unique_lignebudget_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previsions');
    }
};
