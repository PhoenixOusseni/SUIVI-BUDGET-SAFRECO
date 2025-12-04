<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TresorerieController extends Controller
{
    public function suivi_depenses_rations()
    {
        // Logic to retrieve and process data for expense tracking in terms of rations
        return view('clients.pages.suivi_treso.suivi_depenses_rations');
    }

    public function suivi_situation_financiere()
    {
        // Logic to retrieve and process data for financial situation tracking
        return view('clients.pages.suivi_treso.suivi_situation_financiere');
    }
}
