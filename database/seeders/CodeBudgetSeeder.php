<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodeBudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('code_budgets')->insert([
            [
                'code' => 'A.1',
                'rubrique_id' => 1,
                'intitule' => 'Budget de fonctionnement',
                'montant' => 10900000,
                'description' => 'Code budget pour les dépenses de fonctionnement',
            ],
            [
                'code' => 'A.2',
                'rubrique_id' => 1,
                'intitule' => 'Budget d\'investissement',
                'montant' => 4500000,
                'description' => 'Code budget pour les dépenses d\'investissement',
            ],
            [
                'code' => 'A.3',
                'rubrique_id' => 1,
                'intitule' => 'Budget des subventions',
                'montant' => 2000000,
                'description' => 'Code budget pour les subventions accordées',
            ]
        ]);
    }
}
