@extends('clients.layouts.master')

@section('content')

{{-- ══════════════════════════════════════════════════════
     STYLES SPÉCIFIQUES AU DASHBOARD
══════════════════════════════════════════════════════ --}}
<style>
    :root {
        --primary:       #f0a800;
        --primary-light: #c47800;
        --orange:        #003d82;
        --orange-light:  #1a5bb0;
        --green:         #16a34a;
        --green-light:   #22c55e;
        --purple:        #7c3aed;
        --purple-light:  #a78bfa;
        --teal:          #0891b2;
        --teal-light:    #22d3ee;
        --amber:         #d97706;
        --amber-light:   #fbbf24;
        --surface:       #f8fafc;
        --card-bg:       #ffffff;
        --border:        #e2e8f0;
        --text-main:     #0f172a;
        --text-muted:    #64748b;
        --text-xs:       #94a3b8;
        --radius-sm:     8px;
        --radius:        12px;
        --radius-lg:     16px;
        --shadow-sm:     0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow:        0 4px 16px rgba(0,0,0,.07);
        --shadow-lg:     0 8px 32px rgba(0,0,0,.10);
    }

    /* ── RESET / BASE ── */
    .db-wrap { background: var(--surface); padding: 1.5rem 0 3rem; }

    /* ── HEADER ── */
    .db-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.75rem; flex-wrap: wrap; gap: .75rem;
    }
    .db-header-left h1 {
        font-size: 1.4rem; font-weight: 700; color: var(--text-main);
        letter-spacing: -.4px; margin: 0;
    }
    .db-header-left h1 span { color: var(--orange); }
    .db-header-left p { margin: .1rem 0 0; font-size: .82rem; color: var(--text-muted); }
    .db-year-badge {
        background: var(--primary); color: #0f172a;
        padding: .35rem .9rem; border-radius: 20px;
        font-size: .8rem; font-weight: 600; letter-spacing: .5px;
        display: flex; align-items: center; gap: .4rem;
    }

    /* ── KPI CARDS ── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem; margin-bottom: 1.5rem;
    }
    .kpi-card {
        background: var(--card-bg); border-radius: 5px;
        padding: 1.25rem 1.25rem 1rem;
        box-shadow: var(--shadow-sm); border: 1px solid var(--border);
        display: flex; flex-direction: column; gap: .55rem;
        position: relative; overflow: hidden;
        transition: transform .18s, box-shadow .18s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
    .kpi-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; right: 0; height: 3px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    .kpi-card.blue::before    { background: var(--primary); }
    .kpi-card.orange::before  { background: var(--orange); }
    .kpi-card.green::before   { background: var(--green); }
    .kpi-card.purple::before  { background: var(--purple); }
    .kpi-card.teal::before    { background: var(--teal); }
    .kpi-card.amber::before   { background: var(--amber); }

    .kpi-top { display: flex; align-items: flex-start; justify-content: space-between; }
    .kpi-icon {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; flex-shrink: 0;
    }
    .kpi-icon.blue   { background: #fffbeb; color: var(--primary); }
    .kpi-icon.orange { background: #eff6ff; color: var(--orange); }
    .kpi-icon.green  { background: #f0fdf4; color: var(--green); }
    .kpi-icon.purple { background: #f5f3ff; color: var(--purple); }
    .kpi-icon.teal   { background: #ecfeff; color: var(--teal); }
    .kpi-icon.amber  { background: #fffbeb; color: var(--amber); }

    .kpi-value {
        font-size: 1.45rem; font-weight: 700; color: var(--text-main);
        letter-spacing: -.5px; line-height: 1.2;
    }
    .kpi-label {
        font-size: .75rem; font-weight: 600; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .5px;
    }
    .kpi-sub {
        font-size: .74rem; color: var(--text-xs); margin-top: -.2rem;
    }

    /* Gauge (taux d'exécution) */
    .kpi-gauge-wrap { margin-top: .25rem; }
    .kpi-gauge-bar {
        height: 6px; background: #e2e8f0; border-radius: 99px; overflow: hidden;
    }
    .kpi-gauge-fill {
        height: 100%; border-radius: 99px;
        transition: width .6s ease;
    }
    .kpi-gauge-fill.green  { background: var(--green); }
    .kpi-gauge-fill.orange { background: var(--orange); }
    .kpi-gauge-fill.red    { background: #ef4444; }
    .kpi-gauge-pct { font-size: .72rem; color: var(--text-muted); margin-top: .25rem; }

    /* ── SECTION TITLE ── */
    .section-title {
        font-size: .92rem; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: .5rem;
        margin-bottom: 1rem;
    }
    .section-title .dot {
        width: 10px; height: 10px; border-radius: 50%;
        flex-shrink: 0;
    }

    /* ── CHART CARD ── */
    .chart-card {
        background: var(--card-bg); border-radius: 5px;
        padding: 1.25rem; box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }
    .chart-card-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1rem; flex-wrap: wrap; gap: .5rem;
    }
    .chart-card-title {
        font-size: .88rem; font-weight: 700; color: var(--text-main);
        display: flex; align-items: center; gap: .45rem;
    }
    .chart-legend {
        display: flex; align-items: center; gap: .8rem; flex-wrap: wrap;
    }
    .chart-legend-item {
        display: flex; align-items: center; gap: .3rem;
        font-size: .74rem; color: var(--text-muted); font-weight: 500;
    }
    .chart-legend-dot {
        width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0;
    }
    .chart-inner { position: relative; }

    /* ── TOP LIGNES TABLE ── */
    .top-lignes-item {
        display: flex; flex-direction: column; gap: .3rem;
        padding: .6rem 0;
        border-bottom: 1px solid var(--border);
    }
    .top-lignes-item:last-child { border-bottom: none; }
    .top-lignes-label { font-size: .8rem; color: var(--text-main); font-weight: 500; }
    .top-lignes-bar { height: 6px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
    .top-lignes-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
    .top-lignes-meta {
        display: flex; justify-content: space-between;
        font-size: .72rem; color: var(--text-muted);
    }

    /* ── OPERATIONS TABLE ── */
    .ops-table-wrap {
        background: var(--card-bg); border-radius: 5px;
        padding: 1.25rem; box-shadow: var(--shadow-sm);
        border: 1px solid var(--border); margin-bottom: 1.5rem;
    }
    .ops-table {
        width: 100%; border-collapse: collapse; font-size: .82rem;
    }
    .ops-table th {
        font-size: .72rem; font-weight: 600; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .5px;
        padding: .55rem .75rem; border-bottom: 1px solid var(--border);
        background: var(--surface); white-space: nowrap;
    }
    .ops-table td {
        padding: .65rem .75rem; color: var(--text-main);
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .ops-table tbody tr:last-child td { border-bottom: none; }
    .ops-table tbody tr:hover td { background: #f8fafc; }
    .ops-badge {
        display: inline-flex; align-items: center;
        padding: .22rem .6rem; border-radius: 20px;
        font-size: .7rem; font-weight: 600; white-space: nowrap;
    }
    .ops-badge.blue   { background: #fffbeb; color: var(--primary); }
    .ops-badge.orange { background: #eff6ff; color: var(--orange); }
    .ops-amount { font-weight: 600; color: var(--text-main); }
    .ops-no-data {
        text-align: center; padding: 2.5rem; color: var(--text-muted);
        font-size: .85rem;
    }
    .ops-no-data i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .4; }

    /* ── TÂCHES ── */
    .taches-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
    }
    .tache-card {
        background: var(--card-bg); border-radius: 5px;
        padding: 1rem 1.1rem; box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        display: flex; flex-direction: column; gap: .55rem;
        transition: transform .18s, box-shadow .18s;
    }
    .tache-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
    .tache-header { display: flex; align-items: flex-start; justify-content: space-between; }
    .tache-code {
        font-size: .7rem; font-weight: 600; color: var(--text-muted);
        text-transform: uppercase; letter-spacing: .5px;
    }
    .tache-title { font-size: .84rem; font-weight: 600; color: var(--text-main); margin-top: .1rem; }
    .tache-pct-badge {
        font-size: .75rem; font-weight: 700; padding: .2rem .55rem;
        border-radius: 20px; white-space: nowrap; flex-shrink: 0;
    }
    .tache-pct-badge.done   { background: #f0fdf4; color: var(--green); }
    .tache-pct-badge.good   { background: #fffbeb; color: var(--primary); }
    .tache-pct-badge.mid    { background: #fffbeb; color: var(--amber); }
    .tache-pct-badge.low    { background: #eff6ff; color: var(--orange); }
    .tache-progress-bar { height: 6px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
    .tache-progress-fill { height: 100%; border-radius: 99px; transition: width .5s ease; }
    .tache-footer {
        display: flex; justify-content: space-between; align-items: center;
        font-size: .72rem; color: var(--text-muted);
    }
    .tache-date { display: flex; align-items: center; gap: .3rem; }
    .tache-empty {
        grid-column: 1/-1; text-align: center;
        padding: 2.5rem; color: var(--text-muted); font-size: .85rem;
    }
    .tache-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; opacity: .35; }
</style>

<div class="db-wrap">
<div class="container-fluid px-3 px-lg-4">

    {{-- ── HEADER ── --}}
    <div class="db-header">
        <div class="db-header-left">
            <h1><span>Tableau</span> de Bord</h1>
            <p>Vue d'ensemble du suivi budgétaire · Exercice {{ $currentYear }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="db-year-badge">
                <i class="bi bi-calendar3"></i>
                Exercice {{ $currentYear }}
            </div>
        </div>
    </div>

    {{-- ══ KPI CARDS ══ --}}
    <div class="kpi-grid">

        {{-- Prévisions --}}
        <div class="kpi-card blue">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Total Prévisions</div>
                    <div class="kpi-value">{{ number_format($totalPrevisions, 0, ',', ' ') }}</div>
                    <div class="kpi-sub">FCFA · {{ $currentYear }}</div>
                </div>
                <div class="kpi-icon blue"><i class="bi bi-bullseye"></i></div>
            </div>
        </div>

        {{-- Réalisations --}}
        <div class="kpi-card orange">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Total Réalisations</div>
                    <div class="kpi-value">{{ number_format($totalRealisations, 0, ',', ' ') }}</div>
                    <div class="kpi-sub">FCFA · {{ $currentYear }}</div>
                </div>
                <div class="kpi-icon orange"><i class="bi bi-check2-circle"></i></div>
            </div>
        </div>

        {{-- Taux d'exécution --}}
        <div class="kpi-card green">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Taux d'Exécution</div>
                    <div class="kpi-value">{{ $tauxExecution }} %</div>
                </div>
                <div class="kpi-icon green"><i class="bi bi-activity"></i></div>
            </div>
            <div class="kpi-gauge-wrap">
                <div class="kpi-gauge-bar">
                    <div class="kpi-gauge-fill {{ $tauxExecution >= 80 ? 'green' : ($tauxExecution >= 50 ? 'orange' : 'red') }}"
                         style="width: {{ min($tauxExecution, 100) }}%"></div>
                </div>
                <div class="kpi-gauge-pct">{{ $tauxExecution }}% exécuté</div>
            </div>
        </div>

        {{-- Opérations --}}
        <div class="kpi-card teal">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Opérations</div>
                    <div class="kpi-value">{{ number_format($totalOperations, 0, ',', ' ') }}</div>
                    <div class="kpi-sub">{{ $countOperations }} enregistrées</div>
                </div>
                <div class="kpi-icon teal"><i class="bi bi-arrow-left-right"></i></div>
            </div>
        </div>

        {{-- Engagements --}}
        <div class="kpi-card purple">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Engagements</div>
                    <div class="kpi-value">{{ number_format($totalEngagements, 0, ',', ' ') }}</div>
                    <div class="kpi-sub">{{ $countEngagements }} fournisseurs</div>
                </div>
                <div class="kpi-icon purple"><i class="bi bi-file-earmark-text"></i></div>
            </div>
        </div>

        {{-- Tâches --}}
        <div class="kpi-card amber">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Tâches Projet</div>
                    <div class="kpi-value">{{ $taches->count() }}</div>
                    <div class="kpi-sub">
                        @php $done = $taches->where('taux', 100)->count(); @endphp
                        {{ $done }} terminées / {{ $taches->count() }}
                    </div>
                </div>
                <div class="kpi-icon amber"><i class="bi bi-kanban"></i></div>
            </div>
        </div>

    </div>{{-- /kpi-grid --}}

    {{-- ══ CHARTS ROW 1 : Barre mensuelle + Top lignes ══ --}}
    <div class="row g-3 mb-3">

        {{-- Bar chart Prévisions vs Réalisations --}}
        <div class="col-lg-8">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">
                        <i class="bi bi-bar-chart-fill" style="color:var(--primary)"></i>
                        Prévisions vs Réalisations — par mois
                    </div>
                    <div class="chart-legend">
                        <span class="chart-legend-item">
                            <span class="chart-legend-dot" style="background:var(--primary)"></span>Prévisions
                        </span>
                        <span class="chart-legend-item">
                            <span class="chart-legend-dot" style="background:var(--orange)"></span>Réalisations
                        </span>
                    </div>
                </div>
                <div class="chart-inner" style="height:280px">
                    <canvas id="chartBarMonthly"></canvas>
                </div>
            </div>
        </div>

        {{-- Top 5 lignes budget --}}
        <div class="col-lg-4">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">
                        <i class="bi bi-list-ol" style="color:var(--orange)"></i>
                        Top Lignes Budgétaires
                    </div>
                </div>
                @if($topLignes->isEmpty())
                    <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:.82rem;">
                        <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:.5rem;opacity:.35"></i>
                        Aucune opération enregistrée
                    </div>
                @else
                    @php $maxOps = $topLignes->max('operations_sum_amount') ?: 1; @endphp
                    @foreach($topLignes as $i => $lb)
                        <div class="top-lignes-item">
                            <div class="top-lignes-label">{{ Str::limit($lb->intitule ?? $lb->code, 38) }}</div>
                            <div class="top-lignes-bar">
                                <div class="top-lignes-fill" style="width:{{ round(($lb->operations_sum_amount / $maxOps) * 100) }}%"></div>
                            </div>
                            <div class="top-lignes-meta">
                                <span>{{ $lb->code }}</span>
                                <span style="font-weight:600;color:var(--text-main)">{{ number_format($lb->operations_sum_amount, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>{{-- /row --}}

    {{-- ══ CHARTS ROW 2 : Ligne cumulative + Opérations par mois ══ --}}
    <div class="row g-3 mb-3">

        {{-- Line chart cumulative --}}
        <div class="col-lg-6">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">
                        <i class="bi bi-graph-up-arrow" style="color:var(--green)"></i>
                        Évolution Cumulative
                    </div>
                    <div class="chart-legend">
                        <span class="chart-legend-item">
                            <span class="chart-legend-dot" style="background:var(--primary)"></span>Prévisions cumulées
                        </span>
                        <span class="chart-legend-item">
                            <span class="chart-legend-dot" style="background:var(--orange)"></span>Réalisations cumulées
                        </span>
                    </div>
                </div>
                <div class="chart-inner" style="height:240px">
                    <canvas id="chartLineCumul"></canvas>
                </div>
            </div>
        </div>

        {{-- Opérations par mois --}}
        <div class="col-lg-6">
            <div class="chart-card h-100">
                <div class="chart-card-header">
                    <div class="chart-card-title">
                        <i class="bi bi-cash-stack" style="color:var(--teal)"></i>
                        Opérations par Mois
                    </div>
                    <div class="chart-legend">
                        <span class="chart-legend-item">
                            <span class="chart-legend-dot" style="background:var(--teal)"></span>Montant
                        </span>
                    </div>
                </div>
                <div class="chart-inner" style="height:240px">
                    <canvas id="chartOpsMonthly"></canvas>
                </div>
            </div>
        </div>

    </div>{{-- /row --}}

    {{-- ══ DERNIÈRES OPÉRATIONS ══ --}}
    <div class="ops-table-wrap mb-3">
        <div class="chart-card-header" style="margin-bottom:.9rem">
            <div class="chart-card-title" style="font-size:.92rem">
                <i class="bi bi-clock-history" style="color:var(--primary)"></i>
                Dernières Opérations — {{ $currentYear }}
            </div>
            <a href="{{ route('gestion_operations.index') }}" class="d-flex align-items-center gap-1" style="font-size:.78rem;color:var(--primary);text-decoration:none;font-weight:600;">
                Voir tout <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($recentOperations->isEmpty())
            <div class="ops-no-data">
                <i class="bi bi-inbox"></i>
                Aucune opération enregistrée pour {{ $currentYear }}
            </div>
        @else
            <div class="table-responsive">
                <table class="ops-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Libellé</th>
                            <th>Ligne Budget</th>
                            <th>Fournisseur</th>
                            <th>Mois</th>
                            <th style="text-align:right">Montant (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOperations as $i => $op)
                            <tr>
                                <td style="color:var(--text-muted);font-size:.75rem;">{{ $i + 1 }}</td>
                                <td>{{ $op->date ? \Carbon\Carbon::parse($op->date)->format('d/m/Y') : '—' }}</td>
                                <td>
                                    <span class="ops-badge blue">{{ $op->reference ?? 'N/A' }}</span>
                                </td>
                                <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ Str::limit($op->libelle ?? 'N/A', 30) }}
                                </td>
                                <td>
                                    @if($op->ligneBudget)
                                        <span class="ops-badge orange">{{ $op->ligneBudget->code }}</span>
                                    @else
                                        <span style="color:var(--text-muted)">—</span>
                                    @endif
                                </td>
                                <td style="font-size:.79rem;color:var(--text-muted)">
                                    {{ $op->fournisseur?->nom_fournisseur ?? '—' }}
                                </td>
                                <td style="font-size:.79rem;">
                                    @php
                                        $moisLabels = ['','Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
                                    @endphp
                                    {{ $moisLabels[$op->mois] ?? $op->mois }}
                                </td>
                                <td style="text-align:right">
                                    <span class="ops-amount">{{ number_format($op->amount, 0, ',', ' ') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ══ TÂCHES PROJET ══ --}}
    <div class="mb-2">
        <div class="section-title">
            <span class="dot" style="background:var(--amber)"></span>
            Avancement des Tâches Projet
        </div>
    </div>
    <div class="taches-grid mb-3">
        @if($taches->isEmpty())
            <div class="tache-empty">
                <i class="bi bi-kanban"></i>
                Aucune tâche enregistrée
            </div>
        @else
            @foreach($taches as $tache)
                @php
                    $taux = (int)($tache->taux ?? 0);
                    $pctClass = $taux >= 100 ? 'done' : ($taux >= 70 ? 'good' : ($taux >= 40 ? 'mid' : 'low'));
                    $barColor = $taux >= 100 ? 'var(--green)' : ($taux >= 70 ? 'var(--primary)' : ($taux >= 40 ? 'var(--amber)' : 'var(--orange)'));
                @endphp
                <div class="tache-card">
                    <div class="tache-header">
                        <div>
                            <div class="tache-code">{{ $tache->code ?? 'TÂCHE' }}</div>
                            <div class="tache-title">{{ Str::limit($tache->libelle ?? 'Sans titre', 40) }}</div>
                        </div>
                        <span class="tache-pct-badge {{ $pctClass }}">{{ $taux }}%</span>
                    </div>
                    <div class="tache-progress-bar">
                        <div class="tache-progress-fill" style="width:{{ min($taux, 100) }}%;background:{{ $barColor }}"></div>
                    </div>
                    <div class="tache-footer">
                        @if($tache->date_debut)
                            <div class="tache-date"><i class="bi bi-play-circle"></i> {{ \Carbon\Carbon::parse($tache->date_debut)->format('d/m/Y') }}</div>
                        @endif
                        @if($tache->date_echeance)
                            <div class="tache-date"><i class="bi bi-flag"></i> {{ \Carbon\Carbon::parse($tache->date_echeance)->format('d/m/Y') }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div>{{-- /container --}}
</div>{{-- /db-wrap --}}

{{-- ══ CHART.JS SCRIPTS ══ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const months = @json($monthLabels);
    const prevData = @json($prevData);
    const realData = @json($realData);
    const opsData  = @json($opsData);
    const cumPrev  = @json($cumPrev);
    const cumReal  = @json($cumReal);

    const gridColor = 'rgba(0,0,0,.05)';
    const fontColor = '#64748b';
    const baseFont  = { family: "'Inter','Segoe UI',sans-serif", size: 11 };

    Chart.defaults.font = baseFont;
    Chart.defaults.color = fontColor;

    /* ── 1. BAR CHART : Prévisions vs Réalisations ── */
    new Chart(document.getElementById('chartBarMonthly'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Prévisions',
                    data: prevData,
                    backgroundColor: 'rgba(240,168,0,.8)',
                    borderRadius: 5,
                    borderSkipped: false,
                    barPercentage: .75,
                },
                {
                    label: 'Réalisations',
                    data: realData,
                    backgroundColor: 'rgba(0,61,130,.8)',
                    borderRadius: 5,
                    borderSkipped: false,
                    barPercentage: .75,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#fff',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label} : ${ctx.parsed.y.toLocaleString('fr-FR')} FCFA`
                    }
                }
            },
            scales: {
                x: { grid: { color: gridColor } },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        callback: v => v >= 1000000 ? (v/1000000).toFixed(1)+'M' : (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                    }
                }
            }
        }
    });

    /* ── 2. LINE CHART : Évolution cumulative ── */
    new Chart(document.getElementById('chartLineCumul'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Prévisions cumulées',
                    data: cumPrev,
                    borderColor: '#f0a800',
                    backgroundColor: 'rgba(240,168,0,.08)',
                    fill: true,
                    tension: .4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Réalisations cumulées',
                    data: cumReal,
                    borderColor: '#003d82',
                    backgroundColor: 'rgba(0,61,130,.08)',
                    fill: true,
                    tension: .4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#fff',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label} : ${ctx.parsed.y.toLocaleString('fr-FR')} FCFA`
                    }
                }
            },
            scales: {
                x: { grid: { color: gridColor } },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        callback: v => v >= 1000000 ? (v/1000000).toFixed(1)+'M' : (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                    }
                }
            }
        }
    });

    /* ── 3. BAR CHART : Opérations par mois ── */
    new Chart(document.getElementById('chartOpsMonthly'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Opérations',
                data: opsData,
                backgroundColor: ctx => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                    g.addColorStop(0, 'rgba(8,145,178,.8)');
                    g.addColorStop(1, 'rgba(8,145,178,.2)');
                    return g;
                },
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#fff',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` Opérations : ${ctx.parsed.y.toLocaleString('fr-FR')} FCFA`
                    }
                }
            },
            scales: {
                x: { grid: { color: gridColor } },
                y: {
                    grid: { color: gridColor },
                    ticks: {
                        callback: v => v >= 1000000 ? (v/1000000).toFixed(1)+'M' : (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                    }
                }
            }
        }
    });

});
</script>
@endpush

@endsection
