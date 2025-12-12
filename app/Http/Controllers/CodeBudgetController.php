<?php

namespace App\Http\Controllers;

use App\Models\CodeBudget;
use Illuminate\Http\Request;
use App\Models\Rubrique;

class CodeBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $codeBudgets = CodeBudget::all();
        $rubriques = Rubrique::all();
        return view('clients.pages.configs.code_budget.index', compact('codeBudgets', 'rubriques'));
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
            //'code' => 'required|unique:code_budgets,code',
            'intitule' => 'required',
            'description' => 'nullable',
            'rubrique_id' => 'required|exists:rubriques,id',
            'montant' => 'nullable|numeric',
        ]);

        CodeBudget::create([
            //'code' => $request->code,
            'intitule' => $request->intitule,
            'description' => $request->description,
            'rubrique_id' => $request->rubrique_id,
            'montant' => $request->montant,
        ]);

        return redirect()->route('gestion_code_budgets.index')->with('success', 'Code budgetaire ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CodeBudget $codeBudget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $findCodeBudget = CodeBudget::findOrFail($id);
        $rubriques = Rubrique::all();
        $codeBudgets = CodeBudget::all();
        return view ('clients.pages.configs.code_budget.edit', compact('findCodeBudget', 'rubriques', 'codeBudgets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $request->validate([
            'intitule' => 'required',
            'description' => 'nullable',
            'rubrique_id' => 'required|exists:rubriques,id',
            'montant' => 'nullable|numeric',
        ]);

        $codeBudget = CodeBudget::findOrFail($id);
        $codeBudget->update([
            'intitule' => $request->intitule,
            'description' => $request->description,
            'rubrique_id' => $request->rubrique_id,
            'montant' => $request->montant,
        ]);

        return redirect()->back()->with('success', 'Code budgetaire mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $codeBudget = CodeBudget::findOrFail($id);
        $codeBudget->delete();
        return redirect()->back()->with('success', 'Code budgetaire supprimé avec succès.');
    }
}
