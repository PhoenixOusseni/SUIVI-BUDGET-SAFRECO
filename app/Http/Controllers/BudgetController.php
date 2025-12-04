<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prevision;
use App\Models\CodeBudget;
use App\Models\LigneBudget;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));

        // Charger toutes les prévisions de l'année avec leurs months et la ligne budgétaire + rubrique
        $previsions = Prevision::with(['months', 'ligneBudget.codeBudget.rubrique'])
            ->where('year', $year)->get();

        $encaissementsByRubrique = [];
        $decaissementsByRubrique = [];

        // Helper: construit tableau months 1..12 initialisé à 0
        $buildRowMonths = function ($prevision) {
            $row = array_fill(1, 12, 0.0);
            if (!$prevision) {
                return $row;
            }
            if ($prevision->relationLoaded('months')) {
                foreach ($prevision->months as $pm) {
                    $m = (int) $pm->month;
                    if ($m >= 1 && $m <= 12) {
                        $row[$m] = (float) $pm->amount;
                    }
                }
            } else {
                foreach ($prevision->months()->get() as $pm) {
                    $m = (int) $pm->month;
                    if ($m >= 1 && $m <= 12) {
                        $row[$m] = (float) $pm->amount;
                    }
                }
            }
            return $row;
        };

        // Parcourir les prévisions et les classer par groupe (Décaissement/Encaissement) et rubrique
        foreach ($previsions as $prevision) {
            $cb = $prevision->ligneBudget ?? null;
            if ($cb) {
                $ligneCode = $cb->code ?? null;
                $ligneLabel = $cb->intitule ?? ($cb->name ?? null);

                $rub = $cb->rubrique ?? null;
                $rubId = $rub->id ?? null;

                // déterminer le groupe en se basant uniquement sur l'id de la rubrique
                // robust : cast en int puis comparaison stricte
                $rubId = isset($rub->id) ? (int) $rub->id : null;

                $group = $rubId === 1 ? 'Encaissements' : 'Décaissements';

                // clé de regroupement pour affichage par rubrique (on utilise l'id réel s'il existe)
                $rubriqueIdKey = $rubId ? 'rub-' . $rubId : 'cb-' . $cb->id;
                $rubriqueLabel = $rub->libelle ?? ($rub->name ?? ($cb->intitule ?? ($cb->name ?? ($cb->code ?? "Ligne {$cb->id}"))));
            }

            // préparer mois/total
            $rowMonths = $buildRowMonths($prevision);
            $rowTotal = array_sum($rowMonths);

            $item = [
                'ligne_id' => $cb->id ?? null,
                'ligne_code' => $ligneCode,
                'ligne_label' => $ligneLabel,
                'prevision_id' => $prevision->id,
                'months' => $rowMonths,
                'total' => $rowTotal,
                'prevision' => $prevision,
            ];

            // Ajouter selon le code de la ligne budgétaire
            if ($ligneCode && strpos($ligneCode, 'A.1') !== false) {
                if (!isset($encaissementsByRubrique[$rubriqueIdKey])) {
                    $encaissementsByRubrique[$rubriqueIdKey] = ['label' => $rubriqueLabel, 'items' => []];
                }
                $encaissementsByRubrique[$rubriqueIdKey]['items'][] = $item;
            } elseif ($ligneCode && strpos($ligneCode, 'A.2') !== false) {
                if (!isset($decaissementsByRubrique[$rubriqueIdKey])) {
                    $decaissementsByRubrique[$rubriqueIdKey] = ['label' => $rubriqueLabel, 'items' => []];
                }
                $decaissementsByRubrique[$rubriqueIdKey]['items'][] = $item;
            }
        }

        $months = [
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

        return view('clients.pages.suivi_budgetaire.suivi_budget', compact('year', 'months', 'encaissementsByRubrique', 'decaissementsByRubrique'));
    }

    // Rendu fictif du taux d'exécution budgetaire
    public function execution(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));

        // Nom des relations attendues (change si tu as d'autres noms)
        $previsionRelation = 'previsions';
        $previsionMonthsRelation = 'months';
        $realisationRelation = 'realisations';
        $realisationMonthsRelation = 'months';

        // Construire dynamiquement le tableau with() seulement pour les relations existantes
        $with = [];

        $cbModel = new LigneBudget();

        if (method_exists($cbModel, $previsionRelation)) {
            $with[$previsionRelation] = function ($q) use ($year, $previsionMonthsRelation) {
                $q->where('year', $year)->with($previsionMonthsRelation);
            };
        }

        if (method_exists($cbModel, $realisationRelation)) {
            $with[$realisationRelation] = function ($q) use ($year, $realisationMonthsRelation) {
                $q->where('year', $year)->with($realisationMonthsRelation);
            };
        }

        // Charger code budgets en évitant d'appeler with() sur une relation inexistante
        $qbQuery = LigneBudget::orderBy('code');
        if (!empty($with)) {
            $qbQuery = $qbQuery->with($with);
        }
        $ligneBudgets = $qbQuery->get();

        // helper pour extraire months d'une entité (prévision ou réalisation)
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

            // idem pour la réalisation
            $realisation = null;
            if (method_exists($cb, 'getRelationValue')) {
                $rv = $cb->getRelationValue($realisationRelation);
                if ($rv instanceof \Illuminate\Support\Collection) {
                    $realisation = $rv->first() ?: null;
                }
            }
            if ($realisation === null && method_exists($cb, $realisationRelation)) {
                $realisation = $cb->{$realisationRelation}()->where('year', $year)->with($realisationMonthsRelation)->first();
            }

            $preMonths = $extractMonths($prevision, $previsionMonthsRelation);
            $realMonths = $extractMonths($realisation, $realisationMonthsRelation);

            // calcul écarts et taux
            $ecarts = [];
            $taux = [];
            for ($m = 1; $m <= 12; $m++) {
                $p = (float) ($preMonths[$m] ?? 0.0);
                $r = (float) ($realMonths[$m] ?? 0.0);
                $ecarts[$m] = $p - $r;
                $taux[$m] = $p != 0.0 ? $r / $p : null;
            }

            $rows[] = [
                'code' => $cb->code ?? null,
                'libelle' => $cb->intitule ?? ($cb->name ?? null),
                'preMonths' => $preMonths,
                'realMonths' => $realMonths,
                'ecarts' => $ecarts,
                'taux' => $taux,
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

        return view('clients.pages.suivi_budgetaire.taux_execution', compact('year', 'monthsLabels', 'rows'));
    }

    public function consommation()
    {
        return view('clients.pages.suivi_budgetaire.taux_consommation');
    }
}
