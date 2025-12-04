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
        Schema::create('realisation_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisation_id')->constrained('realisations')->onDelete('cascade');
            $table->unsignedTinyInteger('month'); // 1..12
            $table->decimal('amount', 15, 2)->nullable(); // montant prévu
            $table->timestamps();

            $table->unique(['realisation_id', 'month'], 'realisation_month_unique_realisation_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisation_months');
    }
};
