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
            // Filtrer uniquement les lignes dont le code contient 'A.2'
            if (!isset($cb->code) || strpos($cb->code, 'A.2') === false) {
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

    public function consommation(Request $request)
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

        return view('clients.pages.suivi_budgetaire.taux_consommation', compact('year', 'monthsLabels', 'rows'));
    }

    /**
     * Sous Budget Initial des Recettes & Dépenses (par semestre)
     */
    public function sousBudget(Request $request)
    {
        $year     = (int) $request->input('year', date('Y'));
        $yearPrev = $year - 1;

        // Charger les prévisions N et N-1 avec la chaîne complète rubrique
        $previsions     = Prevision::with(['months', 'ligneBudget.codeBudget.rubrique'])
                            ->where('year', $year)->get();
        $previsionsPrev = Prevision::with(['months', 'ligneBudget.codeBudget.rubrique'])
                            ->where('year', $yearPrev)->get();

        // Construire un lookup N-1 : ligne_budget_id => total annuel
        $prevN1 = [];
        foreach ($previsionsPrev as $p) {
            $total = 0.0;
            foreach ($p->months as $m) {
                $total += (float) $m->amount;
            }
            $id = $p->ligne_budget_id;
            $prevN1[$id] = ($prevN1[$id] ?? 0.0) + $total;
        }

        $encaissements = [];
        $decaissements = [];

        // Helper : détermine si une ligne est encaissement ou décaissement
        // Priorité 1 : rubrique de la ligne (via codeBudget.rubrique)
        // Priorité 2 : code de la ligne budgétaire (données seedées A.1.x / A.2.x)
        $classifyRow = function (LigneBudget $lb): string {
            $cb  = $lb->codeBudget;
            $rub = $cb ? $cb->rubrique : null;

            if ($rub) {
                $rubIntitule = mb_strtolower($rub->intitule ?? '');
                $rubCode     = mb_strtoupper($rub->code ?? '');

                // Correspondance par intitulé de rubrique
                if (str_contains($rubIntitule, 'encaissement') || str_contains($rubIntitule, 'recette')) {
                    return 'enc';
                }
                if (str_contains($rubIntitule, 'décaissement') || str_contains($rubIntitule, 'decaissement')
                    || str_contains($rubIntitule, 'dépense') || str_contains($rubIntitule, 'depense')) {
                    return 'dec';
                }
                // Correspondance par code de rubrique (A=encaissement, D=décaissement selon seeder)
                if ($rubCode === 'A') return 'enc';
                if ($rubCode === 'D') return 'dec';
            }

            // Fallback : code de la LigneBudget (données seedées)
            $code = $lb->code ?? '';
            if ($code && str_contains($code, 'A.1')) return 'enc';
            if ($code && str_contains($code, 'A.2')) return 'dec';

            return 'unknown';
        };

        foreach ($previsions as $prevision) {
            $lb = $prevision->ligneBudget;
            if (!$lb) continue;

            $type = $classifyRow($lb);
            if ($type === 'unknown') continue;

            $code = $lb->code ?? '';

            // Calcul des semestres
            $s1 = 0.0;
            $s2 = 0.0;
            foreach ($prevision->months as $m) {
                $mo  = (int) $m->month;
                $amt = (float) $m->amount;
                if ($mo >= 1 && $mo <= 6)       $s1 += $amt;
                elseif ($mo >= 7 && $mo <= 12)  $s2 += $amt;
            }

            $totalN    = $s1 + $s2;
            $totalN1   = $prevN1[$prevision->ligne_budget_id] ?? 0.0;
            $variation = $totalN - $totalN1;

            $row = [
                'code'      => $code,
                'libelle'   => $lb->intitule ?? ($lb->name ?? $code),
                's1'        => $s1,
                's2'        => $s2,
                'total_n'   => $totalN,
                'total_n1'  => $totalN1,
                'variation' => $variation,
            ];

            if ($type === 'enc') {
                $encaissements[] = $row;
            } else {
                $decaissements[] = $row;
            }
        }

        // Trier par code
        usort($encaissements, fn($a, $b) => strcmp($a['code'], $b['code']));
        usort($decaissements, fn($a, $b) => strcmp($a['code'], $b['code']));

        // Totaux généraux
        $totEncN  = array_sum(array_column($encaissements, 'total_n'));
        $totEncN1 = array_sum(array_column($encaissements, 'total_n1'));
        $totDecN  = array_sum(array_column($decaissements, 'total_n'));
        $totDecN1 = array_sum(array_column($decaissements, 'total_n1'));

        $totEncS1 = array_sum(array_column($encaissements, 's1'));
        $totEncS2 = array_sum(array_column($encaissements, 's2'));
        $totDecS1 = array_sum(array_column($decaissements, 's1'));
        $totDecS2 = array_sum(array_column($decaissements, 's2'));

        $equilibre = $totEncN - $totDecN;

        // Totaux N-1 indépendants (toutes lignes N-1, même sans correspondance en N)
        $grandEncN1 = 0.0;
        $grandDecN1 = 0.0;
        foreach ($previsionsPrev as $p) {
            $lb = $p->ligneBudget;
            if (!$lb) continue;
            $type = $classifyRow($lb);
            if ($type === 'unknown') continue;
            $total = 0.0;
            foreach ($p->months as $m) {
                $total += (float) $m->amount;
            }
            if ($type === 'enc') $grandEncN1 += $total;
            else                  $grandDecN1 += $total;
        }

        return view('clients.pages.suivi_budgetaire.sous_budget', compact(
            'year', 'yearPrev',
            'encaissements', 'decaissements',
            'totEncS1', 'totEncS2', 'totEncN', 'totEncN1',
            'totDecS1', 'totDecS2', 'totDecN', 'totDecN1',
            'grandEncN1', 'grandDecN1',
            'equilibre'
        ));
    }

    /**
     * Imprimer le suivi budgétaire
     */
    public function print(Request $request)
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
                $rubId = isset($rub->id) ? (int) $rub->id : null;

                $group = $rubId === 1 ? 'Encaissements' : 'Décaissements';

                // clé de regroupement pour affichage par rubrique
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

        return view('clients.pages.suivi_budgetaire.print_suivi_budget', [
            'year' => $year,
            'monthsLabels' => $monthsLabels,
            'encaissementsByRubrique' => $encaissementsByRubrique,
            'decaissementsByRubrique' => $decaissementsByRubrique,
        ]);
    }
}
