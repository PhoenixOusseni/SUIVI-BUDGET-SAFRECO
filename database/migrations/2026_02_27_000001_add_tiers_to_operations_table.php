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
        Schema::table('operations', function (Blueprint $table) {
            if (!Schema::hasColumn('operations', 'adherant_id')) {
                $table->foreignId('adherant_id')
                    ->nullable()
                    ->after('ligne_budget_id')
                    ->constrained('adherants')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }

            if (!Schema::hasColumn('operations', 'fournisseur_id')) {
                $table->foreignId('fournisseur_id')
                    ->nullable()
                    ->after('adherant_id')
                    ->constrained('fournisseurs')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            if (Schema::hasColumn('operations', 'adherant_id')) {
                $table->dropForeign(['adherant_id']);
                $table->dropColumn('adherant_id');
            }

            if (Schema::hasColumn('operations', 'fournisseur_id')) {
                $table->dropForeign(['fournisseur_id']);
                $table->dropColumn('fournisseur_id');
            }
        });
    }
};
