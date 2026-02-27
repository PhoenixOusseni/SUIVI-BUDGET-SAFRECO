<?php

namespace App\Http\Controllers;

use App\Models\Adherant;
use Illuminate\Http\Request;

class AdherantController extends Controller
{
    public function index()
    {
        $adherants = Adherant::orderByDesc('id')->get();

        return view('clients.pages.adherants.index', compact('adherants'));
    }

    public function create()
    {
        return view('clients.pages.adherants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:255'],
            'nom_adherant' => ['required', 'string', 'max:255'],
            'contact_adherant' => ['nullable', 'string', 'max:255'],
            'email_adherant' => ['nullable', 'email', 'max:255'],
        ]);

        Adherant::create($validated);

        return redirect()->route('gestion_adherants.index')->with('success', 'Adhérant créé avec succès.');
    }

    public function show(Adherant $adherant)
    {
        return redirect()->route('gestion_adherants.index');
    }

    public function edit(string $id)
    {
        $adherant = Adherant::findOrFail($id);

        return view('clients.pages.adherants.edit', compact('adherant'));
    }

    public function update(Request $request, string $id)
    {
        $adherant = Adherant::findOrFail($id);

        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:255'],
            'nom_adherant' => ['required', 'string', 'max:255'],
            'contact_adherant' => ['nullable', 'string', 'max:255'],
            'email_adherant' => ['nullable', 'email', 'max:255'],
        ]);

        $adherant->update($validated);

        return redirect()->route('gestion_adherants.index')->with('success', 'Adhérant mis à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $adherant = Adherant::findOrFail($id);
        $adherant->delete();

        return redirect()->route('gestion_adherants.index')->with('success', 'Adhérant supprimé avec succès.');
    }
}
