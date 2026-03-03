<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrevisionMonth;
use App\Models\RealisationMonth;
use App\Models\Operation;
use App\Models\Tache;
use App\Models\Engagement;
use App\Models\LigneBudget;

class PageController extends Controller
{
    public function auth_admin()
    {
        return view('login-admin');
    }

    public function home()
    {
        $currentYear = (int) date('Y');
        $monthLabels  = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];

        /* ── Prévisions mensuelles (année courante) ── */
        $prevByMonth = PrevisionMonth::whereHas('prevision', fn($q) => $q->where('year', $currentYear))
            ->selectRaw('month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        /* ── Réalisations mensuelles (année courante) ── */
        $realByMonth = RealisationMonth::whereHas('realisation', fn($q) => $q->where('year', $currentYear))
            ->selectRaw('month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        /* ── Opérations par mois ── */
        $opsByMonth = Operation::where('year', $currentYear)
            ->selectRaw('mois as month, SUM(amount) as total')
            ->groupBy('mois')
            ->pluck('total', 'month');

        $prevData  = [];
        $realData  = [];
        $opsData   = [];
        $cumPrev   = [];
        $cumReal   = [];
        $cumP = 0; $cumR = 0;

        for ($i = 1; $i <= 12; $i++) {
            $p = round((float)($prevByMonth[$i] ?? 0), 2);
            $r = round((float)($realByMonth[$i] ?? 0), 2);
            $o = round((float)($opsByMonth[$i]  ?? 0), 2);
            $prevData[] = $p;
            $realData[] = $r;
            $opsData[]  = $o;
            $cumP += $p; $cumR += $r;
            $cumPrev[] = round($cumP, 2);
            $cumReal[] = round($cumR, 2);
        }

        $totalPrevisions   = array_sum($prevData);
        $totalRealisations = array_sum($realData);
        $tauxExecution     = $totalPrevisions > 0
            ? round(($totalRealisations / $totalPrevisions) * 100, 1) : 0;

        $totalOperations = Operation::where('year', $currentYear)->sum('amount');
        $countOperations = Operation::where('year', $currentYear)->count();

        $totalEngagements = Engagement::sum('montant');
        $countEngagements = Engagement::count();

        /* ── Top 5 lignes budget par opérations ── */
        $topLignes = LigneBudget::withSum(['operations' => fn($q) => $q->where('year', $currentYear)], 'amount')
            ->having('operations_sum_amount', '>', 0)
            ->orderByDesc('operations_sum_amount')
            ->take(5)
            ->get();

        /* ── Dernières opérations ── */
        $recentOperations = Operation::with(['ligneBudget', 'fournisseur'])
            ->where('year', $currentYear)
            ->latest()
            ->take(8)
            ->get();

        /* ── Tâches ── */
        $taches = Tache::orderBy('date_echeance')->take(6)->get();

        return view('clients.pages.home', compact(
            'monthLabels', 'prevData', 'realData', 'opsData',
            'cumPrev', 'cumReal',
            'totalPrevisions', 'totalRealisations', 'tauxExecution',
            'totalOperations', 'countOperations',
            'totalEngagements', 'countEngagements',
            'recentOperations', 'taches', 'topLignes', 'currentYear'
        ));
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function config()
    {
        return view('clients.pages.configs.index');
    }

    public function prevision()
    {
        return view('clients.pages.data.prevision');
    }

    public function realisation()
    {
        return view('clients.pages.data.realisation');
    }

    public function collaborateurs()
    {
        return view('clients.pages.collaborateurs.index');
    }
}
