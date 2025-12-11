<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use Illuminate\Http\Request;

class TacheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taches = Tache::all();
        return view('clients.pages.tache.index', compact('taches'));
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
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'nullable|date',
            'date_echeance' => 'nullable|date',
            'taux' => 'nullable|numeric|min:0|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
        ]);

        $tache = new Tache();
        $tache->code = 'TACH-' . strtoupper(uniqid());
        $tache->libelle = $request->libelle;
        $tache->description = $request->description;
        $tache->date_debut = $request->date_debut;
        $tache->date_echeance = $request->date_echeance;
        $tache->taux = $request->taux;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('taches_files', 'public');
            $tache->file = $filePath;
        }

        $tache->save();

        return redirect()->route('gestion_taches.index')->with('success', 'Tâche créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tache $tache)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tache $tache)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tache $tache)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tache $tache)
    {
        //
    }
}
