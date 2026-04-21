@extends('clients.layouts.master')

@section('content')
    @php
        $fmt = function ($v) {
            if ($v === null || $v == 0.0) {
                return '-';
            }
            return number_format((float) $v, 0, ',', ' ');
        };

        $fmtVar = function ($v) {
            if ($v === null || $v == 0.0) {
                return '-';
            }
            $formatted = number_format(abs((float) $v), 0, ',', ' ');
            return ($v < 0 ? '- ' : '') . $formatted;
        };

        $varClass = function ($v) {
            if ($v === null || $v == 0.0) {
                return '';
            }
            return $v < 0 ? 'sb-neg' : 'sb-pos';
        };
    @endphp

    <style>
        .sb-page-hero {
            background: linear-gradient(135deg, #f0a800 0%, #c47800 100%);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            color: #0f172a;
        }

        .sb-hero-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
        }

        .sb-hero-sub {
            font-size: .82rem;
            opacity: .75;
            margin: .2rem 0 0;
        }

        .sb-toolbar {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            background: #fff;
            border: 1px solid #e8edf4;
            border-radius: 10px;
            padding: .65rem 1rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
        }

        .sb-toolbar .form-select {
            font-size: .82rem;
            padding: .35rem .75rem;
            border-radius: 7px;
            border: 1px solid #d1d9e6;
        }

        .sb-btn-filter {
            background: #f0a800;
            color: #0f172a;
            border: none;
            padding: .38rem .95rem;
            border-radius: 7px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            transition: background .15s;
        }

        .sb-btn-filter:hover {
            background: #c47800;
        }

        /* Table wrapper */
        .sb-card {
            background: #fff;
            border: 1px solid #d0d7e3;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .sb-card-header {
            background: linear-gradient(90deg, #f0a800 0%, #c47800 100%);
            color: #0f172a;
            padding: .75rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .sb-card-title {
            font-size: .9rem;
            font-weight: 700;
            margin: 0;
        }

        .sb-card-sub {
            font-size: .74rem;
            opacity: .75;
            margin: .1rem 0 0;
        }

        /* Excel-style table */
        .sb-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
            font-family: Arial, sans-serif;
        }

        .sb-table th,
        .sb-table td {
            border: 1px solid #b0b8c8;
            padding: .42rem .75rem;
            vertical-align: middle;
            white-space: nowrap;
        }

        .sb-table thead tr th {
            background: #1a3a6b;
            color: #fff;
            font-weight: 700;
            text-align: center;
            font-size: .8rem;
            letter-spacing: .3px;
        }

        .sb-table thead tr th.th-chapitre {
            text-align: left;
            width: 300px;
            min-width: 220px;
        }

        /* Data rows */
        .sb-table tbody tr td {
            background: #fff;
        }

        .sb-table tbody tr:nth-child(odd) td {
            background: #eef6ec;
        }

        /* Amount cells green-ish */
        .sb-table tbody tr td.amt-cell {
            text-align: right;
            background: #d5ecd0;
            font-weight: 500;
        }

        .sb-table tbody tr:nth-child(even) td.amt-cell {
            background: #c2e3bc;
        }

        .sb-table tbody tr td.total-n-cell {
            text-align: right;
            background: #b8dab2;
            font-weight: 700;
        }

        .sb-table tbody tr td.total-n1-cell {
            text-align: right;
            font-weight: 500;
        }

        .sb-table tbody tr td.var-cell {
            text-align: right;
            font-weight: 500;
        }

        .sb-neg {
            color: #c0392b;
        }

        .sb-pos {
            color: #1a7a34;
        }

        /* Total row */
        .sb-table tfoot tr td {
            background: #1a3a6b !important;
            color: #fff;
            font-weight: 700;
            font-size: .84rem;
        }

        .sb-table tfoot tr td.tfoot-label {
            text-align: left;
        }

        .sb-table tfoot tr td.tfoot-num {
            text-align: right;
        }

        /* Footnote & equilibre */
        .sb-footnote {
            font-size: .76rem;
            color: #c0392b;
            padding: .5rem .75rem;
            font-style: italic;
        }

        .sb-equilibre {
            display: flex;
            align-items: stretch;
            border: 1px solid #b0b8c8;
            border-top: none;
        }

        .sb-eq-label {
            flex: 0 0 300px;
            min-width: 220px;
            padding: .55rem .75rem;
            font-size: .8rem;
            font-weight: 600;
            color: #7d3c00;
            background: #fffbe6;
            border-right: 1px solid #b0b8c8;
        }

        .sb-eq-value {
            flex: 1;
            padding: .55rem .75rem;
            text-align: center;
            font-size: .85rem;
            font-weight: 700;
            background: #ffff00;
            color: #333;
            border-right: 1px solid #b0b8c8;
        }

        .sb-eq-empty {
            flex: 1;
            background: #fff;
            border-right: 1px solid #b0b8c8;
        }
    </style>

    <div class="container-fluid px-3 pb-5">

        {{-- Hero --}}
        <div class="sb-page-hero">
            <p class="sb-hero-title"><i class="bi bi-graph-up me-2"></i>Sous Budget Initial — Exercice {{ $year }}</p>
            <p class="sb-hero-sub">Répartition semestrielle des prévisions de recettes et dépenses · Comparaison N / N-1</p>
        </div>

        {{-- Toolbar --}}
        <div class="sb-toolbar">
            <i class="bi bi-funnel text-muted" style="font-size:.9rem;"></i>
            <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('budget.sous_budget') }}">
                <select name="year" class="form-select" style="width: 150px;">
                    @for ($y = date('Y') - 3; $y <= date('Y') + 3; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="sb-btn-filter"><i class="bi bi-filter"></i> Filtrer</button>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════════════
         TABLE 1 : SOUS BUDGET INITIAL DES RECETTES
    ══════════════════════════════════════════════════════ --}}
        <div class="sb-card">
            <div class="sb-card-header">
                <i class="bi bi-arrow-down-circle-fill" style="font-size:1.1rem;"></i>
                <div>
                    <p class="sb-card-title">SOUS BUDGET INITIAL DES RECETTES (Encaissements)</p>
                    <p class="sb-card-sub">Prévisions N={{ $year }} · N-1={{ $yearPrev }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th class="th-chapitre">CHAPITRES</th>
                            <th style="width:140px;">SEMESTRE 1<br><small style="font-weight:400;opacity:.8;">Jan – Juin
                                    {{ $year }}</small></th>
                            <th style="width:140px;">SEMESTRE 2<br><small style="font-weight:400;opacity:.8;">Juil – Déc
                                    {{ $year }}</small></th>
                            <th style="width:130px;">TOTAL N<br><small
                                    style="font-weight:400;opacity:.8;">{{ $year }}</small></th>
                            <th style="width:130px;">TOTAL N-1<br><small
                                    style="font-weight:400;opacity:.8;">{{ $yearPrev }}</small></th>
                            <th style="width:130px;">VARIATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($encaissements as $row)
                            <tr>
                                <td>
                                    <span style="color:#888;font-size:.74rem;font-weight:600;">{{ $row['code'] }}</span>
                                    &nbsp; {{ $row['libelle'] }}
                                </td>
                                <td class="amt-cell">{{ $fmt($row['s1']) }}</td>
                                <td class="amt-cell">{{ $fmt($row['s2']) }}</td>
                                <td class="total-n-cell">{{ $fmt($row['total_n']) }}</td>
                                <td class="total-n1-cell">{{ $fmt($row['total_n1']) }}</td>
                                <td class="var-cell {{ $varClass($row['variation']) }}">{{ $fmtVar($row['variation']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:1.5rem;">
                                    Aucune prévision de recette pour {{ $year }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="tfoot-label">TOTAL SOUS BUDGET DES RECETTES</td>
                            <td class="tfoot-num">{{ $fmt($totEncS1) }}</td>
                            <td class="tfoot-num">{{ $fmt($totEncS2) }}</td>
                            <td class="tfoot-num">{{ $fmt($totEncN) }}</td>
                            <td class="tfoot-num">{{ $fmt($totEncN1) }}</td>
                            <td class="tfoot-num">{{ $fmtVar($totEncN - $totEncN1) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Footnote --}}
            <div class="sb-footnote">
                (*) Dans les limites de 5% du total des cotisations des clubs
            </div>

            {{-- Contrôle équilibre --}}
            <div class="sb-equilibre">
                <div class="sb-eq-label">
                    CONTRÔLE ÉQUILIBRE DU BUDGET<br>
                    <span style="font-weight:400;font-size:.74rem;">(total sous budget des recettes – total sous budget des
                        dépenses = 0)</span>
                </div>
                <div class="sb-eq-value">
                    @if ($equilibre == 0)
                        -
                    @elseif ($equilibre < 0)
                        <span class="sb-neg">{{ $fmtVar($equilibre) }}</span>
                    @else
                        <span class="sb-pos">{{ $fmtVar($equilibre) }}</span>
                    @endif
                </div>
                <div class="sb-eq-empty"></div>
                <div class="sb-eq-empty"></div>
                <div class="sb-eq-empty"></div>
                <div class="sb-eq-empty" style="border-right:none;"></div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
         TABLE 2 : SOUS BUDGET INITIAL DES DEPENSES
    ══════════════════════════════════════════════════════ --}}
        <div class="sb-card">
            <div class="sb-card-header" style="background: linear-gradient(90deg, #7d0a0a 0%, #b03030 100%);">
                <i class="bi bi-arrow-up-circle-fill" style="font-size:1.1rem;"></i>
                <div>
                    <p class="sb-card-title">SOUS BUDGET INITIAL DES DÉPENSES (Décaissements)</p>
                    <p class="sb-card-sub">Prévisions N={{ $year }} · N-1={{ $yearPrev }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th class="th-chapitre">CHAPITRES</th>
                            <th style="width:140px;">SEMESTRE 1<br><small style="font-weight:400;opacity:.8;">Jan – Juin
                                    {{ $year }}</small></th>
                            <th style="width:140px;">SEMESTRE 2<br><small style="font-weight:400;opacity:.8;">Juil – Déc
                                    {{ $year }}</small></th>
                            <th style="width:130px;">TOTAL N<br><small
                                    style="font-weight:400;opacity:.8;">{{ $year }}</small></th>
                            <th style="width:130px;">TOTAL N-1<br><small
                                    style="font-weight:400;opacity:.8;">{{ $yearPrev }}</small></th>
                            <th style="width:130px;">VARIATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($decaissements as $row)
                            <tr>
                                <td>
                                    <span style="color:#888;font-size:.74rem;font-weight:600;">{{ $row['code'] }}</span>
                                    &nbsp; {{ $row['libelle'] }}
                                </td>
                                <td class="amt-cell">{{ $fmt($row['s1']) }}</td>
                                <td class="amt-cell">{{ $fmt($row['s2']) }}</td>
                                <td class="total-n-cell">{{ $fmt($row['total_n']) }}</td>
                                <td class="total-n1-cell">{{ $fmt($row['total_n1']) }}</td>
                                <td class="var-cell {{ $varClass($row['variation']) }}">{{ $fmtVar($row['variation']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding:1.5rem;">
                                    Aucune prévision de dépense pour {{ $year }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="tfoot-label">TOTAL SOUS BUDGET DES DÉPENSES</td>
                            <td class="tfoot-num">{{ $fmt($totDecS1) }}</td>
                            <td class="tfoot-num">{{ $fmt($totDecS2) }}</td>
                            <td class="tfoot-num">{{ $fmt($totDecN) }}</td>
                            <td class="tfoot-num">{{ $fmt($totDecN1) }}</td>
                            <td class="tfoot-num">{{ $fmtVar($totDecN - $totDecN1) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
         TABLE 3 : RECAPITULATIF DU BUDGET
    ══════════════════════════════════════════════════════ --}}
        @php
            $varPct = function ($n, $n1) {
                if (!$n1) return null;
                return round((($n - $n1) / $n1) * 100, 0);
            };
            $fmtPct = function ($v) {
                if ($v === null || $v == 0) return '-';
                return ($v > 0 ? '+' : '') . $v . '%';
            };
            $balanceS1  = $totEncS1 - $totDecS1;
            $balanceS2  = $totEncS2 - $totDecS2;
            $balanceN   = $totEncN  - $totDecN;
            $balanceN1  = $totEncN1 - $totDecN1;
            $varRecPct  = $varPct($totEncN, $grandEncN1);
            $varDecPct  = $varPct($totDecN, $grandDecN1);
        @endphp

        <div class="sb-card">
            <div class="sb-card-header" style="background: linear-gradient(90deg, #1a3a1a 0%, #2e6b2e 100%);">
                <i class="bi bi-table" style="font-size:1.1rem;"></i>
                <div>
                    <p class="sb-card-title">RÉCAPITULATIF DU BUDGET {{ $year }}-{{ $year + 1 }}</p>
                    <p class="sb-card-sub">Synthèse recettes / dépenses · Balance budgétaire</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="sb-table" style="font-family: Arial, sans-serif;">
                    <thead>
                        {{-- Ligne de groupe --}}
                        <tr>
                            <th rowspan="2" class="th-chapitre"
                                style="background:#c8d3e0;color:#000;text-align:center;vertical-align:middle;font-weight:700;">
                                Rubrique</th>
                            <th rowspan="2"
                                style="background:#c8d3e0;color:#000;text-align:center;vertical-align:middle;font-weight:700;width:110px;">
                                Légende</th>
                            <th colspan="3"
                                style="background:#c8d3e0;color:#000;text-align:center;font-weight:700;border-bottom:2px solid #8a9ab0;">
                                Mandat {{ $year }}-{{ $year + 1 }}</th>
                            <th rowspan="2"
                                style="background:#c8d3e0;color:#000;text-align:center;vertical-align:middle;font-weight:700;width:130px;">
                                BUDGET Mandat<br>{{ $yearPrev }}-{{ $year }}<br>
                                <span style="font-weight:400;font-size:.75rem;">Montant (F/CFA)</span></th>
                            <th rowspan="2"
                                style="background:#c8d3e0;color:#000;text-align:center;vertical-align:middle;font-weight:700;width:90px;">
                                Variation<br><span style="font-weight:400;font-size:.75rem;">%</span></th>
                        </tr>
                        <tr>
                            <th style="background:#c8d3e0;color:#000;text-align:center;font-weight:700;width:130px;">
                                1er semestre</th>
                            <th style="background:#c8d3e0;color:#000;text-align:center;font-weight:700;width:130px;">
                                2ème Semestre</th>
                            <th style="background:#c8d3e0;color:#000;text-align:center;font-weight:700;width:130px;">
                                Montant (F/CFA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ligne recettes --}}
                        <tr>
                            <td style="font-weight:700;color:#000;background:#fff;">TOTAL GÉNÉRAL DES RECETTES</td>
                            <td style="text-align:center;color:#c0392b;font-weight:700;background:#fff;">(a)</td>
                            <td style="text-align:right;font-weight:600;background:#fff;">{{ $fmt($totEncS1) }}</td>
                            <td style="text-align:right;font-weight:600;background:#fff;">{{ $fmt($totEncS2) }}</td>
                            <td style="text-align:right;font-weight:700;background:#fff;">{{ $fmt($totEncN) }}</td>
                            <td style="text-align:right;font-weight:600;background:#fff;">{{ $fmt($grandEncN1) }}</td>
                            <td style="text-align:center;font-weight:700;background:#fff;
                                {{ $varRecPct !== null && $varRecPct < 0 ? 'color:#c0392b;' : ($varRecPct !== null && $varRecPct > 0 ? 'color:#1a7a34;' : '') }}">
                                {{ $fmtPct($varRecPct) }}
                            </td>
                        </tr>
                        {{-- Ligne dépenses --}}
                        <tr>
                            <td style="font-weight:700;color:#000;background:#eef6ec;">TOTAL GÉNÉRAL DES DÉPENSES</td>
                            <td style="text-align:center;color:#c0392b;font-weight:700;background:#eef6ec;">(b)</td>
                            <td style="text-align:right;font-weight:600;background:#eef6ec;">{{ $fmt($totDecS1) }}</td>
                            <td style="text-align:right;font-weight:600;background:#eef6ec;">{{ $fmt($totDecS2) }}</td>
                            <td style="text-align:right;font-weight:700;background:#eef6ec;">{{ $fmt($totDecN) }}</td>
                            <td style="text-align:right;font-weight:600;background:#eef6ec;">{{ $fmt($grandDecN1) }}</td>
                            <td style="text-align:center;font-weight:700;background:#eef6ec;
                                {{ $varDecPct !== null && $varDecPct < 0 ? 'color:#c0392b;' : ($varDecPct !== null && $varDecPct > 0 ? 'color:#1a7a34;' : '') }}">
                                {{ $fmtPct($varDecPct) }}
                            </td>
                        </tr>
                        {{-- Ligne balance --}}
                        <tr>
                            <td style="font-weight:700;color:#000;background:#ffd700;">BALANCE (RECETTES - DÉPENSES)</td>
                            <td style="text-align:center;color:#c0392b;font-weight:700;background:#ffd700;font-size:.75rem;">
                                (c)&nbsp;=&nbsp;(a)&nbsp;-&nbsp;(b)</td>
                            <td style="text-align:right;font-weight:700;background:#ffd700;">{{ $fmt($balanceS1) }}</td>
                            <td style="text-align:right;font-weight:700;background:#ffd700;">{{ $fmt($balanceS2) }}</td>
                            <td style="text-align:right;font-weight:700;background:#ffd700;">{{ $fmt($balanceN) }}</td>
                            <td style="background:#ffd700;text-align:center;">-</td>
                            <td style="background:#ffd700;text-align:center;">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
