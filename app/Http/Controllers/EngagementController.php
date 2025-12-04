<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function suivi_fournisseurs()
    {
        // Logic to retrieve and process data for supplier engagement tracking
        return view('clients.pages.engagement.suivi_fournisseurs');
    }

    public function suivi_audits()
    {
        // Logic to retrieve and process data for audit engagement tracking
        return view('clients.pages.engagement.suivi_audit_traites');
    }
}
