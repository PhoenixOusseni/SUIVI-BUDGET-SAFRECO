<?php

namespace App\Http\Controllers;

use App\Models\Realisation;
use App\Models\RealisationMonth;
use App\Models\LigneBudget;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Throwable;

use Illuminate\Http\Request;

class RealisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // année filtrée (default = current year)
        $year = (int) $request->input('year', date('Y'));

        // Récupère toutes les realisations pour l'année, avec leurs mois et la ligne budgétaire associée
        // Assure que la relation 'codeBudget' (ou 'ligneBudget') est définie sur Realisation
        $realisations = Realisation::with(['months', 'ligneBudget']) // ou 'ligneBudget' suivant votre relation
            ->where('year', $year)->get();

        // Grouper par id de ligne budgétaire (nullable possible)
        $grouped = $realisations->groupBy('ligne_budget_id'); // adapte le nom de la clé si c'est code_budget_id

        // Charger uniquement les lignes budgétaires qui ont une realisation (pour afficher code/intitulé)
        $ligneIds = $grouped->keys()->filter()->all(); // filtre les clés nulles si existantes
        $lignes = LigneBudget::whereIn('id', $ligneIds)->get()->keyBy('id');

        // Fournir list pour filtre dans la vue (toutes les lignes)
        $allLignes = LigneBudget::orderBy('code')->get();

        $lignesBudgets = LigneBudget::orderBy('code')->get();

        return view('clients.pages.data.realisation.index', [
            'groupedRealisations' => $grouped,
            'lignesMap' => $lignes,
            'allLignes' => $allLignes,
            'year' => $year,
            'lignesBudgets' => $lignesBudgets,
        ]);
    }

    public function saisi_realisation()
    {
        // Provide code budgets to populate the "Ligne budgétaire" select
        $lignesBudgets = LigneBudget::orderBy('code')->get();

        // Prepare year options (e.g. current -3 .. current +3)
        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear - 3; $y <= $currentYear + 3; $y++) {
            $years[] = $y;
        }

        return view('clients.pages.data.realisation.saisi_realisation', ['lignesBudgets' => $lignesBudgets, 'years' => $years]);
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
            $realisation = DB::transaction(function () use ($ligneBudgetId, $year, $date, $notes, $normalizedMonths) {
                // Utiliser firstOrCreate pour créer ou récupérer la réalisation
                // Clé unique : ligne_budget_id + year (permet différentes années)
                $realisation = Realisation::firstOrCreate(
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
                if ($date !== null && $realisation->date != $date) {
                    $realisation->date = $date;
                    $realisation->save();
                }
                if ($notes !== null && $realisation->notes != $notes) {
                    $realisation->notes = $notes;
                    $realisation->save();
                }

                // Préparer les lignes pour upsert (batch) — Laravel >= 8
                $rows = [];
                for ($m = 1; $m <= 12; $m++) {
                    $rows[] = [
                        'realisation_id' => $realisation->id,
                        'month' => $m,
                        'amount' => $normalizedMonths[$m] ?? 0.0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Utiliser upsert (insert ou update selon existence) — évite les doublons
                // Clés uniques : ['realisation_id', 'month'] ; colonnes à mettre à jour : ['amount','updated_at']
                try {
                    RealisationMonth::upsert($rows, ['realisation_id', 'month'], ['amount', 'updated_at']);
                } catch (Throwable $e) {
                    // Si upsert non supporté (vieille version de Laravel) : fallback sur updateOrCreate
                    foreach ($rows as $row) {
                        RealisationMonth::updateOrCreate(['realisation_id' => $row['realisation_id'], 'month' => $row['month']], ['amount' => $row['amount']]);
                    }
                }

                $realisation->load('months');
                return $realisation;
            }, 5);

            return redirect()->route('gestion_realisations.index')
                ->with('success', "Réalisation enregistrée (ID: {$realisation->id}).");
        } catch (QueryException $e) {
            // gestion spécifique d'erreur de doublon (MySQL 1062, Postgres 23505) : on peut réessayer ou retourner erreur lisible
            $sqlState = $e->errorInfo[0] ?? null;
            $driverCode = $e->errorInfo[1] ?? null;
            if (in_array($sqlState, ['23000', '23505']) || $driverCode == 1062) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['error' => 'Une réalisation existe déjà pour cette ligne budgétaire et cette année.']);
            }

            // autre erreur SQL
            return redirect()->back()->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            return redirect()->back()->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Realisation $realisation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Realisation $realisation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Realisation $realisation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Realisation $realisation)
    {
        try {
            DB::transaction(function () use ($realisation) {
                // Supprimer tous les mois associés à cette réalisation
                RealisationMonth::where('realisation_id', $realisation->id)->delete();
                
                // Supprimer la réalisation elle-même
                $realisation->delete();
            });

            return redirect()
                ->route('gestion_realisations.index')
                ->with('success', "Réalisation supprimée avec succès.");
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
