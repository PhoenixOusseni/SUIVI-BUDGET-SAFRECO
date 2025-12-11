<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prevision;
use App\Models\CodeBudget;
use App\Models\LigneBudget;

class TresorerieController extends Controller
{
    // Suivi des dépenses par rations
    public function suivi_depenses_rations()
    {
        // Récupérer l'année depuis la requête (par défaut année courante)
        $year = request()->input('year', date('Y'));

        // Récupérer les lignes budgétaires avec les prévisions et leurs mois
        $lignesBudgets = LigneBudget::with(['codeBudget', 'previsions' => function($query) use ($year) {
            $query->where('year', $year)->with('months');
        }])->get();

        // Grouper les lignes par code budgétaire (pas par code de ligne)
        $groupedByCodeBudget = [];
        foreach ($lignesBudgets as $ligne) {
            $codeBudgetId = $ligne->code_budget_id;

            if (!isset($groupedByCodeBudget[$codeBudgetId])) {
                $groupedByCodeBudget[$codeBudgetId] = [
                    'codeBudget' => $ligne->codeBudget,
                    'lignes' => []
                ];
            }

            $groupedByCodeBudget[$codeBudgetId]['lignes'][] = $ligne;
        }

        // Récupérer tous les mois pour les en-têtes (1 à 12)
        $monthsLabels = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return view('clients.pages.suivi_treso.suivi_depenses_rations', compact('groupedByCodeBudget', 'monthsLabels', 'year'));
    }

    // Suivi de la situation financière
    public function suivi_situation_financiere(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));

        // Nom des relations attendues
        $previsionRelation = 'previsions';
        $previsionMonthsRelation = 'months';
        $consommationRelation = 'realisations';
        $consommationMonthsRelation = 'months';

        // Construire dynamiquement le tableau with() seulement pour les relations existantes
        $with = [];

        $cbModel = new LigneBudget();

        if (method_exists($cbModel, $previsionRelation)) {
            $with[$previsionRelation] = function ($q) use ($year, $previsionMonthsRelation) {
                $q->where('year', $year)->with($previsionMonthsRelation);
            };
        }

        if (method_exists($cbModel, $consommationRelation)) {
            $with[$consommationRelation] = function ($q) use ($year, $consommationMonthsRelation) {
                $q->where('year', $year)->with($consommationMonthsRelation);
            };
        }

        // Charger lignes budgets
        $qbQuery = LigneBudget::orderBy('code');
        if (!empty($with)) {
            $qbQuery = $qbQuery->with($with);
        }
        $ligneBudgets = $qbQuery->get();

        // helper pour extraire months d'une entité (prévision ou consommation)
        $extractMonths = function ($entity, $monthsRelation = 'months') {
            $months = array_fill(1, 12, 0.0);
            if (!$entity) {
                return $months;
            }

            if (method_exists($entity, 'relationLoaded') && $entity->relationLoaded($monthsRelation)) {
                foreach ($entity->{$monthsRelation} as $row) {
                    $idx = (int) $row->month;
                    if ($idx >= 1 && $idx <= 12) {
                        $months[$idx] = (float) $row->amount;
                    }
                }
                return $months;
            }

            if (method_exists($entity, $monthsRelation)) {
                foreach ($entity->{$monthsRelation}()->get() as $row) {
                    $idx = (int) $row->month;
                    if ($idx >= 1 && $idx <= 12) {
                        $months[$idx] = (float) $row->amount;
                    }
                }
            }

            return $months;
        };

        $rows = [];
        foreach ($ligneBudgets as $cb) {
            // Filtrer uniquement les lignes dont le code contient 'A.1'
            if (!isset($cb->code) || strpos($cb->code, 'A.1') === false) {
                continue;
            }

            // récupérer la prévision (first) si la relation est chargée ou via requête fallback
            $prevision = null;
            if (method_exists($cb, 'getRelationValue')) {
                $pv = $cb->getRelationValue($previsionRelation);
                if ($pv instanceof \Illuminate\Support\Collection) {
                    $prevision = $pv->first() ?: null;
                }
            }
            if ($prevision === null && method_exists($cb, $previsionRelation)) {
                $prevision = $cb->{$previsionRelation}()->where('year', $year)->with($previsionMonthsRelation)->first();
            }

            // idem pour la consommation
            $consommation = null;
            if (method_exists($cb, 'getRelationValue')) {
                $cv = $cb->getRelationValue($consommationRelation);
                if ($cv instanceof \Illuminate\Support\Collection) {
                    $consommation = $cv->first() ?: null;
                }
            }
            if ($consommation === null && method_exists($cb, $consommationRelation)) {
                $consommation = $cb->{$consommationRelation}()->where('year', $year)->with($consommationMonthsRelation)->first();
            }

            $preMonths = $extractMonths($prevision, $previsionMonthsRelation);
            $consMonths = $extractMonths($consommation, $consommationMonthsRelation);

            $rows[] = [
                'code' => $cb->code ?? null,
                'libelle' => $cb->intitule ?? ($cb->name ?? null),
                'preMonths' => $preMonths,
                'consMonths' => $consMonths,
            ];
        }

        $monthsLabels = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return view('clients.pages.suivi_treso.suivi_situation_financiere', compact('year', 'monthsLabels', 'rows'));
    }

    //export data to excel method stub
    public function export_excel()
    {
        // Implementation for exporting data to Excel will go here
    }

    // Print PDF method stub
    public function print_ration(Request $request)
    {
        // Récupérer l'année depuis la requête (par défaut année courante) - convertir en int
        $year = (int) $request->input('year', date('Y'));

        // Récupérer les lignes budgétaires avec les prévisions et leurs mois
        $lignesBudgets = LigneBudget::with(['codeBudget', 'previsions' => function($query) use ($year) {
            $query->where('year', $year)->with('months');
        }])->get();

        // Grouper les lignes par code budgétaire (pas par code de ligne)
        $groupedByCodeBudget = [];
        foreach ($lignesBudgets as $ligne) {
            $codeBudgetId = $ligne->code_budget_id;

            if (!isset($groupedByCodeBudget[$codeBudgetId])) {
                $groupedByCodeBudget[$codeBudgetId] = [
                    'codeBudget' => $ligne->codeBudget,
                    'lignes' => []
                ];
            }

            $groupedByCodeBudget[$codeBudgetId]['lignes'][] = $ligne;
        }

        // Récupérer tous les mois pour les en-têtes (1 à 12)
        $monthsLabels = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return view('clients.pages.suivi_treso.print.print_ration', compact('groupedByCodeBudget', 'monthsLabels', 'year'));
    }

    // print situation financiere method stub
    public function print_situation_financiere(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));

        // Nom des relations attendues
        $previsionRelation = 'previsions';
        $previsionMonthsRelation = 'months';
        $consommationRelation = 'realisations';
        $consommationMonthsRelation = 'months';

        // Construire dynamiquement le tableau with() seulement pour les relations existantes
        $with = [];

        $cbModel = new LigneBudget();

        if (method_exists($cbModel, $previsionRelation)) {
            $with[$previsionRelation] = function ($q) use ($year, $previsionMonthsRelation) {
                $q->where('year', (int)$year)->with($previsionMonthsRelation);
            };
        }

        if (method_exists($cbModel, $consommationRelation)) {
            $with[$consommationRelation] = function ($q) use ($year, $consommationMonthsRelation) {
                $q->where('year', (int)$year)->with($consommationMonthsRelation);
            };
        }

        // Charger lignes budgets
        $qbQuery = LigneBudget::orderBy('code');
        if (!empty($with)) {
            $qbQuery = $qbQuery->with($with);
        }
        $ligneBudgets = $qbQuery->get();

        // helper pour extraire months d'une entité (prévision ou consommation)
        $extractMonths = function ($entity, $monthsRelation = 'months') {
            $months = array_fill(1, 12, 0.0);
            if (!$entity) {
                return $months;
            }

            if (method_exists($entity, 'relationLoaded') && $entity->relationLoaded($monthsRelation)) {
                foreach ($entity->{$monthsRelation} as $row) {
                    $idx = (int) $row->month;
                    if ($idx >= 1 && $idx <= 12) {
                        $months[$idx] = (float) $row->amount;
                    }
                }
                return $months;
            }

            if (method_exists($entity, $monthsRelation)) {
                foreach ($entity->{$monthsRelation}()->get() as $row) {
                    $idx = (int) $row->month;
                    if ($idx >= 1 && $idx <= 12) {
                        $months[$idx] = (float) $row->amount;
                    }
                }
            }

            return $months;
        };

        $rows = [];
        foreach ($ligneBudgets as $cb) {
            // Filtrer uniquement les lignes dont le code contient 'A.1'
            if (!isset($cb->code) || strpos($cb->code, 'A.1') === false) {
                continue;
            }

            // récupérer la prévision (first) si la relation est chargée ou via requête fallback
            $prevision = null;
            if (method_exists($cb, 'getRelationValue')) {
                $pv = $cb->getRelationValue($previsionRelation);
                if ($pv instanceof \Illuminate\Support\Collection) {
                    $prevision = $pv->first() ?: null;
                }
            }
            if ($prevision === null && method_exists($cb, $previsionRelation)) {
                $prevision = $cb->{$previsionRelation}()->where('year', (int)$year)->with($previsionMonthsRelation)->first();
            }

            // idem pour la consommation
            $consommation = null;
            if (method_exists($cb, 'getRelationValue')) {
                $cv = $cb->getRelationValue($consommationRelation);
                if ($cv instanceof \Illuminate\Support\Collection) {
                    $consommation = $cv->first() ?: null;
                }
            }
            if ($consommation === null && method_exists($cb, $consommationRelation)) {
                $consommation = $cb->{$consommationRelation}()->where('year', (int)$year)->with($consommationMonthsRelation)->first();
            }

            $preMonths = $extractMonths($prevision, $previsionMonthsRelation);
            $consMonths = $extractMonths($consommation, $consommationMonthsRelation);

            $rows[] = [
                'code' => $cb->code ?? null,
                'libelle' => $cb->intitule ?? ($cb->name ?? null),
                'preMonths' => $preMonths,
                'consMonths' => $consMonths,
            ];
        }

        $monthsLabels = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return view('clients.pages.suivi_treso.print.print_situation_finance', compact('year', 'monthsLabels', 'rows'));
    }
}
