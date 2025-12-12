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
        Schema::create('engagements', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->date('date_depot')->nullable();
            $table->integer('montant')->nullable();
            $table->string('j_1')->nullable();
            $table->string('j_2')->nullable();
            $table->string('j_3')->nullable();
            $table->string('piece_joint')->nullable();

            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagements');
    }
};
