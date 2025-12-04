<?php

namespace App\Http\Controllers;

use App\Models\LigneBudget;
use App\Models\CodeBudget;
use Illuminate\Http\Request;

class LigneBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ligneBudgets = LigneBudget::all();
        $codeBudgets = CodeBudget::all();
        return view('clients.pages.configs.ligne_budget.index', compact('ligneBudgets', 'codeBudgets'));
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
        $request->validate([
            //'code' => 'required|unique:ligne_budgets,code',
            'intitule' => 'required',
            'description' => 'nullable',
            'code_budget_id' => 'required|exists:code_budgets,id',
            'montant' => 'nullable|integer',
        ]);
        LigneBudget::create([
            //'code' => $request->code,
            'intitule' => $request->intitule,
            'description' => $request->description,
            'code_budget_id' => $request->code_budget_id,
            'montant' => $request->montant,
        ]);
        return redirect()->route('gestion_ligne_budgets.index')->with('success', 'Ligne budgetaire ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LigneBudget $ligneBudget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LigneBudget $ligneBudget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LigneBudget $ligneBudget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LigneBudget $ligneBudget)
    {
        //
    }
}
