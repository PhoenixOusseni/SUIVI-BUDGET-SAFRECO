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
        Schema::create('prevision_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prevision_id')->constrained('previsions')->onDelete('cascade');
            $table->unsignedTinyInteger('month'); // 1..12
            $table->decimal('amount', 15, 2)->nullable(); // montant prévu
            $table->timestamps();

            $table->unique(['prevision_id', 'month'], 'prevision_month_unique_prevision_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prevision_months');
    }
};
