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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ligne_budget_id')->nullable()->constrained('ligne_budgets')->onDelete('set null');
            $table->unsignedSmallInteger('year'); // ex: 2025
            $table->date('date')->nullable();
            $table->string('libelle')->nullable();
            $table->string('reference')->nullable();
            $table->string('mois')->nullable();
            $table->decimal('amount', 15, 2)->default(0.0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
