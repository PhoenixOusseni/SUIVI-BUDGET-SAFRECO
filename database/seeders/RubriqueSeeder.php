<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RubriqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rubriques')->insert([
            [
                'code' => 'A',
                'intitule' => 'Encaissements',
                'description' => 'Rubrique pour les encaissements budgétaires',
            ],
            [
                'code' => 'D',
                'intitule' => 'Décaissements',
                'description' => 'Rubrique pour les décaissements budgétaires',
            ],
            [
                'code' => 'T',
                'intitule' => 'Transferts',
                'description' => 'Rubrique pour les transferts budgétaires',
            ]
        ]);
    }
}
