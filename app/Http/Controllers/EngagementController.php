<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tache;

class EngagementController extends Controller
{
    public function suivi_fournisseurs()
    {
        // Logic to retrieve and process data for supplier engagement tracking
        return view('clients.pages.engagement.suivi_fournisseurs');
    }

    public function suivi_audits(Request $request)
    {
        // Get year from request, default to current year
        $year = $request->get('year', date('Y'));

        // Retrieve tasks filtered by year
        $taches = Tache::whereYear('date_debut', $year)
            ->orderBy('date_debut', 'desc')
            ->get();

        // Calculate execution states based on rate conditions
        $etats_execution = [
            'conformite_parfaite' => $taches->where('taux', '=', 100)->count(),
            'progres_partiel' => $taches->where('taux', '>=', 80)->where('taux', '<', 100)->count(),
            'risque_eleve' => $taches->where('taux', '<', 80)->count(),
        ];

        return view('clients.pages.engagement.suivi_audit_traites', compact('taches', 'etats_execution', 'year'));
    }
}
