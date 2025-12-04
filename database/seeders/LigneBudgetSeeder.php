<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LigneBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ligne_budgets')->insert([
            [
                'code' => 'A.1.1',
                'code_budget_id' => 1,
                'intitule' => 'Fournitures de bureau',
                'montant' => 500000,
                'description' => 'Dépenses liées aux fournitures de bureau',
            ],
            [
                'code' => 'A.1.2',
                'code_budget_id' => 1,
                'intitule' => 'Services informatiques',
                'montant' => 2000000,
                'description' => 'Dépenses pour les services et le support informatique',
            ],
            [
                'code' => 'A.2.1',
                'code_budget_id' => 2,
                'intitule' => 'Achat de matériel',
                'montant' => 1500000,
                'description' => 'Dépenses pour l\'achat de matériel informatique et mobilier',
            ],
            [
                'code' => 'A.2.2',
                'code_budget_id' => 2,
                'intitule' => 'Subventions aux associations',
                'montant' => 3000000,
                'description' => 'Fonds alloués aux associations locales',
            ]
        ]);
    }
}
