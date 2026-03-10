<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prevision;
use App\Models\PrevisionMonth;
use App\Models\LigneBudget;
use App\Exports\PrevisionsExport;
use Maatwebsite\Excel\Facades\Excel;

class PrevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year          = (int) $request->input('year', date('Y'));
        $ligneBudgetId = $request->input('ligne_budget_id');

        $query = Prevision::with(['months', 'ligneBudget'])
            ->where('year', $year);

        // Filtre par ligne budgétaire si sélectionnée
        if ($ligneBudgetId) {
            $query->where('ligne_budget_id', $ligneBudgetId);
        }

        $previsions = $query->orderBy('id')->get();

        // Grouper par ligne_budget_id ; pour chaque groupe on fusionne les montants mensuels
        // (cas rare de doublons : on additionne plutôt que de perdre des lignes)
        $grouped = collect();
        foreach ($previsions->groupBy('ligne_budget_id') as $ligneId => $group) {
            $mergedMonths = array_fill(1, 12, 0.0);
            foreach ($group as $prev) {
                if ($prev->relationLoaded('months')) {
                    foreach ($prev->months as $pm) {
                        $mergedMonths[(int) $pm->month] += (float) $pm->amount;
                    }
                }
            }
            $first = $group->first();
            $grouped->push((object)[
                'prevision'    => $first,
                'ligneBudget'  => $first->ligneBudget,   // relation déjà eager-loadée
                'mergedMonths' => $mergedMonths,
            ]);
        }

        $lignesBudgets = LigneBudget::orderBy('intitule')->get();

        return view('clients.pages.data.prevision.index', [
            'groupedPrevisions' => $grouped,
            'year'              => $year,
            'lignesBudgets'     => $lignesBudgets,
        ]);
    }

    public function saisi_prevision()
    {
        // Provide code budgets to populate the "Ligne budgétaire" select
        $lignesBudgets = LigneBudget::orderBy('code')->get();

        // Prepare year options (e.g. current -3 .. current +3)
        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear - 3; $y <= $currentYear + 3; $y++) {
            $years[] = $y;
        }

        return view('clients.pages.data.prevision.saisi_prevision', ['lignesBudgets' => $lignesBudgets, 'years' => $years]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // lecture directe (sans validate)
        $ligneBudgetId = $request->input('ligne_budget_id', null);
        $year = (int) $request->input('year', date('Y'));
        $date = $request->input('date', null);
        $notes = $request->input('notes', null);
        $monthsInput = $request->input('months', []);

        // normalisation simple (attend months[1]..months[12] depuis la vue)
        $normalizedMonths = [];
        if (is_array($monthsInput)) {
            foreach ($monthsInput as $k => $v) {
                if (is_numeric($k)) {
                    $m = (int) $k;
                    if ($m >= 1 && $m <= 12) {
                        $normalizedMonths[$m] = (float) $v;
                    }
                }
            }
        }

        try {
            $prevision = DB::transaction(function () use ($ligneBudgetId, $year, $date, $notes, $normalizedMonths) {
                // Utiliser firstOrCreate pour créer ou récupérer la prévision
                // Clé unique : ligne_budget_id + year (permet différentes années)
                $prevision = Prevision::firstOrCreate(
                    [
                        'ligne_budget_id' => $ligneBudgetId,
                        'year' => $year,
                    ],
                    [
                        'date' => $date,
                        'notes' => $notes,
                    ],
                );

                // update date/notes si fournis (et différents)
                if ($date !== null && $prevision->date != $date) {
                    $prevision->date = $date;
                    $prevision->save();
                }
                if ($notes !== null && $prevision->notes != $notes) {
                    $prevision->notes = $notes;
                    $prevision->save();
                }

                // Préparer les lignes pour upsert (batch) — Laravel >= 8
                $rows = [];
                for ($m = 1; $m <= 12; $m++) {
                    $rows[] = [
                        'prevision_id' => $prevision->id,
                        'month' => $m,
                        'amount' => $normalizedMonths[$m] ?? 0.0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Utiliser upsert (insert ou update selon existence) — évite les doublons
                // Clés uniques : ['prevision_id', 'month'] ; colonnes à mettre à jour : ['amount','updated_at']
                try {
                    PrevisionMonth::upsert($rows, ['prevision_id', 'month'], ['amount', 'updated_at']);
                } catch (Throwable $e) {
                    // Si upsert non supporté (vieille version de Laravel) : fallback sur updateOrCreate
                    foreach ($rows as $row) {
                        PrevisionMonth::updateOrCreate(['prevision_id' => $row['prevision_id'], 'month' => $row['month']], ['amount' => $row['amount']]);
                    }
                }

                $prevision->load('months');
                return $prevision;
            }, 5);

            return redirect()
                ->route('gestion_previsions.index')
                ->with('success', "Prévision enregistrée (ID: {$prevision->id}).");
        } catch (QueryException $e) {
            // gestion spécifique d'erreur de doublon (MySQL 1062, Postgres 23505) : on peut réessayer ou retourner erreur lisible
            $sqlState = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;
            if (in_array($sqlState, ['23000', '23505']) || $driverCode == 1062) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['error' => 'Une prévision existe déjà pour cette ligne budgétaire et cette année.']);
            }

            // autre erreur SQL
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Importer des prévisions depuis un fichier Excel/CSV.
     */
    public function import(Request $request)
    {
        // Validation du fichier
        $request->validate(
            [
                'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // max 10MB
            ],
            [
                'file.required' => 'Veuillez sélectionner un fichier à importer.',
                'file.mimes' => 'Le fichier doit être au format Excel (xlsx, xls) ou CSV.',
                'file.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
            ],
        );

        try {
            $file = $request->file('file');

            // Import using PrevisionsImport class
            $import = new \App\Imports\PrevisionsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);

            // Get results
            $results = $import->getResults();

            // Prepare success message
            $message = "Import terminé : {$results['success']} prévision(s) importée(s)";

            if ($results['skipped'] > 0) {
                $message .= ", {$results['skipped']} ligne(s) ignorée(s)";
            }

            if ($results['errors'] > 0) {
                $message .= ", {$results['errors']} erreur(s)";
            }

            // Return with appropriate message type
            if ($results['errors'] > 0) {
                return redirect()->route('gestion_previsions.index')->with('warning', $message)->with('import_messages', $results['messages']);
            }

            return redirect()->route('gestion_previsions.index')->with('success', $message)->with('import_messages', $results['messages']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Ligne {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()
                ->back()
                ->withErrors(['file' => 'Erreurs de validation dans le fichier'])
                ->with('import_errors', $errors);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withErrors(['file' => 'Erreur lors de l\'import : ' . $e->getMessage()]);
        }
    }

    /**
     * Helper: normalise une valeur de cellule vers float.
     * Supporte formats: "1 234 567,89" ou "1,234,567.89" ou "1234567.89"
     */
    private function parseAmount(string $raw): float
    {
        $s = trim($raw);
        if ($s === '') {
            return 0.0;
        }

        // remove non numeric except digits, dot, comma, minus
        $s = preg_replace('/[^\d\-,\.]/u', '', $s);

        // if number contains both comma and dot, assume dot thousands and comma decimal or vice-versa:
        if (strpos($s, ',') !== false && strpos($s, '.') !== false) {
            // assume dot thousands, comma decimal -> replace dot then comma to dot
            // detect which is right by the last separator position
            $lastComma = strrpos($s, ',');
            $lastDot = strrpos($s, '.');
            if ($lastComma > $lastDot) {
                // comma decimal
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                // dot decimal
                $s = str_replace(',', '', $s);
            }
        } else {
            // only comma present -> comma as decimal separator
            if (strpos($s, ',') !== false) {
                $s = str_replace(' ', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                // only dot present or none -> just remove spaces
                $s = str_replace(' ', '', $s);
            }
        }

        return (float) $s;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prevision $prevision)
    {
        try {
            DB::transaction(function () use ($prevision) {
                // Supprimer tous les mois associés à cette prévision
                PrevisionMonth::where('prevision_id', $prevision->id)->delete();

                // Supprimer la prévision elle-même
                $prevision->delete();
            });

            return redirect()->route('gestion_previsions.index')->with('success', 'Prévision supprimée avec succès.');
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }

    /**
     * Imprimer les prévisions
     */
    public function print(Request $request)
    {
        // Année filtrée (default = current year)
        $year = (int) $request->input('year', date('Y'));
        $ligneBudgetId = $request->input('ligne_budget_id');

        // Query de base
        $query = Prevision::with(['months', 'ligneBudget'])->where('year', $year);

        // Filtre par ligne budgétaire si spécifié
        if ($ligneBudgetId) {
            $query->where('ligne_budget_id', $ligneBudgetId);
        }

        $previsions = $query->get();

        // Grouper par id de ligne budgétaire
        $grouped = $previsions->groupBy('ligne_budget_id');

        // Charger les lignes budgétaires concernées
        $ligneIds = $grouped->keys()->filter()->all();
        $lignes = LigneBudget::whereIn('id', $ligneIds)->get()->keyBy('id');

        // Labels des mois
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

        return view('clients.pages.data.prevision.print_prevision', [
            'groupedPrevisions' => $grouped,
            'lignesMap' => $lignes,
            'year' => $year,
            'monthsLabels' => $monthsLabels,
        ]);
    }

    /**
     * Exporter des prévisions vers un fichier Excel/CSV.
     */
    public function export(Request $request)
    {
        $year = (int) $request->input('year', date('Y'));
        $ligneBudgetId = $request->input('ligne_budget_id');
        $export = new PrevisionsExport($year, $ligneBudgetId);
        $fileName = "previsions_{$year}.xlsx";
        return Excel::download($export, $fileName);
    }
}
