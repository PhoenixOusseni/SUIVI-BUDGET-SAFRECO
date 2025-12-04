<?php

namespace App\Http\Controllers;

use App\Models\Rubrique;
use Illuminate\Http\Request;

class RubriqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rubriques = Rubrique::all();
        return view('clients.pages.configs.rubrique.index', compact('rubriques'));
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
            'code' => 'required|unique:rubriques,code',
            'intitule' => 'required',
            'description' => 'nullable',
        ]);

        Rubrique::create([
            'code' => $request->code,
            'intitule' => $request->intitule,
            'description' => $request->description,
        ]);

        return redirect()->route('gestion_rubriques.index')->with('success', 'Rubrique créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rubrique $rubrique)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rubrique $rubrique)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rubrique $rubrique)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $rubrique = Rubrique::findOrFail($id);
        $rubrique->delete();

        return redirect()->route('gestion_rubriques.index')->with('success', 'Rubrique supprimée avec succès.');
    }
}
