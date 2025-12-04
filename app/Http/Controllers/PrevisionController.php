<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prevision;
use App\Models\PrevisionMonth;
use App\Models\LigneBudget;

class PrevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // année filtrée (default = current year)
        $year = (int) $request->input('year', date('Y'));

        // Récupère toutes les prévisions pour l'année, avec leurs mois et la ligne budgétaire associée
        // Assure que la relation 'codeBudget' (ou 'ligneBudget') est définie sur Prevision
        $previsions = Prevision::with(['months', 'ligneBudget']) // ou 'ligneBudget' suivant votre relation
            ->where('year', $year)->get();

        // Grouper par id de ligne budgétaire (nullable possible)
        $grouped = $previsions->groupBy('ligne_budget_id'); // adapte le nom de la clé si c'est code_budget_id

        // Charger uniquement les lignes budgétaires qui ont une prévision (pour afficher code/intitulé)
        $ligneIds = $grouped->keys()->filter()->all(); // filtre les clés nulles si existantes
        $lignes = LigneBudget::whereIn('id', $ligneIds)->get()->keyBy('id');

        // Fournir list pour filtre dans la vue (toutes les lignes)
        $allLignes = LigneBudget::orderBy('code')->get();

        $lignesBudgets = LigneBudget::orderBy('code')->get();

        return view('clients.pages.data.prevision.index', [
            'groupedPrevisions' => $grouped,
            'lignesMap' => $lignes,
            'allLignes' => $allLignes,
            'year' => $year,
            'lignesBudgets' => $lignesBudgets,
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
                    ]
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
        // validation minimale
        $request->validate([
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'year' => ['required', 'integer'],
        ]);

        $year = (int) $request->input('year');

        $file = $request->file('import_file');

        $import = new PrevisionsImport();

        try {
            // Lire le fichier (stocke les rows dans l'import)
            Excel::import($import, $file);
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['import_file' => 'Impossible de lire le fichier : ' . $e->getMessage()]);
        }

        $rows = $import->getRows();
        if (!$rows || $rows->isEmpty()) {
            return redirect()
                ->back()
                ->withErrors(['import_file' => 'Fichier vide ou format non reconnu.']);
        }

        // Helpers pour la détection des colonnes et parsing des montants
        $monthNamesMap = [
            'janvier' => 1,
            'fevrier' => 2,
            'février' => 2,
            'mars' => 3,
            'avril' => 4,
            'mai' => 5,
            'juin' => 6,
            'juillet' => 7,
            'aout' => 8,
            'août' => 8,
            'septembre' => 9,
            'octobre' => 10,
            'novembre' => 11,
            'decembre' => 12,
            'décembre' => 12,
            // english fallback
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12,
        ];

        $errors = [];
        $successCount = 0;
        $rowIndex = 1; // for reporting (first data row = 1)

        // We'll iterate rows and process each prevision (one row = one ligne budgétaire)
        foreach ($rows as $row) {
            // $row is a Collection keyed by heading (WithHeadingRow => keys normalized to lower-case and spaces replaced)
            $rowIndex++;

            // find the header key that corresponds to "code" (loose matching)
            $keys = $row->keys()->map(fn($k) => trim((string) $k))->all();

            $codeKey = null;
            foreach ($keys as $k) {
                if (stripos($k, 'code') !== false) {
                    $codeKey = $k;
                    break;
                }
            }
            if (!$codeKey) {
                // try first column as fallback
                $codeKey = $keys[0] ?? null;
            }

            $codeRaw = $codeKey ? trim((string) $row->get($codeKey)) : null;
            if (!$codeRaw) {
                $errors[] = "Ligne {$rowIndex} : code introuvable ou vide.";
                continue;
            }

            // sanitize code (remove weird spaces)
            $codeNorm = preg_replace('/\s+/', '', str_replace(['.', ','], '', $codeRaw));

            // try to find the code budget:
            $codeBudget = CodeBudget::whereRaw("REPLACE(REPLACE(REPLACE(code, ' ', ''),'.',''),',','') = ?", [$codeNorm])
                ->orWhereRaw("REPLACE(REPLACE(REPLACE(code, ' ', ''),'.',''),',','') LIKE ?", ["%{$codeNorm}%"])
                ->first();

            if (!$codeBudget) {
                // try by name (libellé) if code not found
                $labelKey = null;
                foreach ($keys as $k) {
                    if (stripos($k, 'lib') !== false || stripos($k, 'intitule') !== false || stripos($k, 'label') !== false) {
                        $labelKey = $k;
                        break;
                    }
                }
                $label = $labelKey ? trim((string) $row->get($labelKey)) : null;
                if ($label) {
                    $codeBudget = CodeBudget::where('name', 'like', '%' . mb_substr($label, 0, 50) . '%')->first();
                }
            }

            if (!$codeBudget) {
                $errors[] = "Ligne {$rowIndex} : ligne budgétaire introuvable pour code='{$codeRaw}'.";
                continue;
            }

            // Build months array from row using matching headers
            $monthsAmounts = [];
            foreach ($keys as $k) {
                $kNorm = mb_strtolower(trim($k));
                // if key matches a month name
                if (isset($monthNamesMap[$kNorm])) {
                    $mNum = $monthNamesMap[$kNorm];
                    $val = $row->get($k);
                    $monthsAmounts[$mNum] = $this->parseAmount((string) $val);
                }
            }

            // If no month columns detected, try some common variants: 'm1','m01', 'jan'
            if (empty($monthsAmounts)) {
                // attempt to detect keys like 'm1', 'mois1' or numeric headings
                foreach ($keys as $k) {
                    if (preg_match('/\b(mois|m|mois_)?0?([1-9]|1[0-2])\b/i', $k, $m)) {
                        $mNum = (int) $m[2];
                        $monthsAmounts[$mNum] = $this->parseAmount((string) $row->get($k));
                    }
                }
            }

            // if still empty, as fallback consider columns 3..14 as months (common layout)
            if (empty($monthsAmounts)) {
                $idx = 0;
                foreach ($row as $val) {
                    $idx++;
                    if ($idx >= 3 && $idx <= 14) {
                        // 12 months
                        $mNum = $idx - 2;
                        $monthsAmounts[$mNum] = $this->parseAmount((string) $val);
                    }
                }
            }

            // ensure we have keys 1..12 (fill missing with 0)
            for ($m = 1; $m <= 12; $m++) {
                if (!array_key_exists($m, $monthsAmounts)) {
                    $monthsAmounts[$m] = 0.0;
                }
            }

            // Transaction: create/update prevision + upsert months
            try {
                DB::transaction(function () use ($codeBudget, $year, $monthsAmounts, &$codeRaw) {
                    // find or create prevision (unique key: ligne_budget_id + year)
                    $prevision = Prevision::firstOrCreate(['ligne_budget_id' => $codeBudget->id, 'year' => $year], ['date' => null, 'notes' => null]);

                    // prepare rows for upsert
                    $rowsForUpsert = [];
                    $now = now();
                    foreach ($monthsAmounts as $m => $amount) {
                        $rowsForUpsert[] = [
                            'prevision_id' => $prevision->id,
                            'month' => $m,
                            'amount' => $amount,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    // use upsert to insert or update existing months in one query
                    // unique keys: prevision_id + month
                    PrevisionMonth::upsert($rowsForUpsert, ['prevision_id', 'month'], ['amount', 'updated_at']);
                }, 3);
                $successCount++;
            } catch (Throwable $e) {
                $errors[] = "Ligne {$rowIndex} (code {$codeRaw}) : erreur BD - " . $e->getMessage();
                continue;
            }
        } // end foreach rows

        // redirect back with summary
        $msg = "Import terminé : {$successCount} lignes traitées.";
        if (!empty($errors)) {
            // add errors to session (could be a large array)
            return redirect()->back()->with('success', $msg)->with('import_errors', $errors);
        }

        return redirect()->back()->with('success', $msg);
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
     * Display the specified resource.
     */
    public function show(Prevision $prevision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prevision $prevision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prevision $prevision)
    {
        //
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

            return redirect()
                ->route('gestion_previsions.index')
                ->with('success', "Prévision supprimée avec succès.");
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
