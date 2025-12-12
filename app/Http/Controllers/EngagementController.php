<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tache;
use App\Models\Fournisseur;
use App\Models\Engagement;

class EngagementController extends Controller
{
    public function gestion_engagements()
    {
        $engagements = Engagement::with('fournisseur')->orderBy('created_at', 'desc')->get();
        $fournisseurs = Fournisseur::all();
        return view('clients.pages.engagement.gestion_engagement', compact('engagements', 'fournisseurs'));
    }

    public function store_engagements(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'date_depot' => 'required|date',
            'montant' => 'required|integer',
            'j_1' => 'nullable|string|max:255',
            'j_2' => 'nullable|string|max:255',
            'j_3' => 'nullable|string|max:255',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'piece_joint' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        // Handle file upload if present
        $pieceJointPath = null;
        if ($request->hasFile('piece_joint')) {
            $pieceJointPath = $request->file('piece_joint')->store('engagements', 'public');
        }

        // Create a new Engagement record
        $engagement = new Engagement();
        $engagement->code = 'ENG-' . strtoupper(uniqid());
        $engagement->date_depot = $validatedData['date_depot'];
        $engagement->montant = $validatedData['montant'];
        $engagement->j_1 = $validatedData['j_1'] ?? null;
        $engagement->j_2 = $validatedData['j_2'] ?? null;
        $engagement->j_3 = $validatedData['j_3'] ?? null;
        $engagement->fournisseur_id = $validatedData['fournisseur_id'];
        $engagement->piece_joint = $pieceJointPath;
        $engagement->save();

        // Redirect back with a success message
        return redirect()->route('engagement.gestion_engagements')->with('success', 'Engagement ajouté avec succès.');
    }

    public function suivi_fournisseurs()
    {
        $engagements = Engagement::with('fournisseur')->orderBy('created_at', 'desc')->get();
        $fournisseurs = Fournisseur::all();
        return view('clients.pages.engagement.suivi_fournisseurs', compact('engagements', 'fournisseurs'));
    }

    public function suivi_audits(Request $request)
    {
        // Get year from request, default to current year
        $year = $request->get('year', date('Y'));

        // Retrieve tasks filtered by year
        $taches = Tache::whereYear('date_debut', $year)
            ->orderBy('date_debut', 'desc')->get();

        // Calculate execution states based on rate conditions
        $etats_execution = [
            'conformite_parfaite' => $taches->where('taux', '=', 100)->count(),
            'progres_partiel' => $taches->where('taux', '>=', 80)->where('taux', '<', 100)->count(),
            'risque_eleve' => $taches->where('taux', '<', 80)->count(),
        ];

        return view('clients.pages.engagement.suivi_audit_traites', compact('taches', 'etats_execution', 'year'));
    }

    public function gestion_fournisseurs()
    {
        $fournisseurs = Fournisseur::all(); // Replace with actual data retrieval logic if needed
        return view('clients.pages.engagement.gestion_fournisseur', compact('fournisseurs'));
    }

    public function store_fournisseurs(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nom_fournisseur' => 'required|string|max:255',
            'contact_fournisseur' => 'required|string|max:255',
            'email_fournisseur' => 'required|email|max:255',
        ]);

        // Create a new Fournisseur record
        $fournisseur = new Fournisseur();
        $fournisseur->code = 'FOUR-' . strtoupper(uniqid());
        $fournisseur->nom_fournisseur = $validatedData['nom_fournisseur'];
        $fournisseur->contact_fournisseur = $validatedData['contact_fournisseur'];
        $fournisseur->email_fournisseur = $validatedData['email_fournisseur'];
        $fournisseur->save();

        // Redirect back with a success message
        return redirect()->route('engagement.gestion_fournisseurs')->with('success', 'Fournisseur ajouté avec succès.');
    }
}
