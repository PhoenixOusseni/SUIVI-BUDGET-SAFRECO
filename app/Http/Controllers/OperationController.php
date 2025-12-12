<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\LigneBudget;
use App\Models\Realisation;
use App\Models\RealisationMonth;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operations = Operation::all();
        $ligneBudgets = LigneBudget::all();

        // Prepare year options (e.g. current -3 .. current +3)
        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear - 3; $y <= $currentYear + 3; $y++) {
            $years[] = $y;
        }

        return view('clients.pages.operation.index', ['operations' => $operations, 'ligneBudgets' => $ligneBudgets, 'years' => $years]);
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
        $libelle = $request->input('libelle', null);
        $reference = $request->input('reference', null);
        $mois = $request->input('mois', null);
        $amount = (float) $request->input('amount', 0.0);

        $operation = new Operation();
        $operation->ligne_budget_id = $ligneBudgetId;
        $operation->year = $year;
        $operation->date = $date;
        $operation->libelle = $libelle;
        $operation->reference = $reference;
        $operation->mois = $mois;
        $operation->amount = $amount;
        $operation->save();

        // Mettre à jour la RealisationMonth correspondante si elle existe
        $realisation = Realisation::where('ligne_budget_id', $ligneBudgetId)
            ->where('year', $year)->first();

        if ($realisation) {
            $realMonth = RealisationMonth::where('realisation_id', $realisation->id)
                ->where('month', (int)$mois)->first();
            if ($realMonth) {
                $realMonth->amount += $amount;
                $realMonth->save();
            }
        }

        return redirect()->route('gestion_operations.index')->with('success', 'Opération créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $operationFind = Operation::findOrFail($id);
        $operations = Operation::all();
        $ligneBudgets = LigneBudget::all();

        // Prepare year options (e.g. current -3 .. current +3)
        $currentYear = date('Y');
        $years = [];
        for ($y = $currentYear - 3; $y <= $currentYear + 3; $y++) {
            $years[] = $y;
        }

        return view('clients.pages.operation.edit', ['operationFind' => $operationFind, 'ligneBudgets' => $ligneBudgets, 'years' => $years, 'operations' => $operations]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $operation = Operation::findOrFail($id);

        // lecture directe (sans validate)
        $ligneBudgetId = $request->input('ligne_budget_id', null);
        $year = (int) $request->input('year', date('Y'));
        $date = $request->input('date', null);
        $libelle = $request->input('libelle', null);
        $reference = $request->input('reference', null);
        $mois = $request->input('mois', null);
        $amount = (float) $request->input('amount', 0.0);

        $operation->ligne_budget_id = $ligneBudgetId;
        $operation->year = $year;
        $operation->date = $date;
        $operation->libelle = $libelle;
        $operation->reference = $reference;
        $operation->mois = $mois;
        $operation->amount = $amount;
        $operation->save();

        return redirect()->back()->with('success', 'Opération mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $operation = Operation::findOrFail($id);
        $operation->delete();

        return redirect()->back()->with('success', 'Opération supprimée avec succès.');
    }
}
