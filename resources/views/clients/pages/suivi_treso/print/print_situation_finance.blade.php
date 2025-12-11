<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de la Situation Financière - {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .print-info {
            text-align: right;
            font-size: 10px;
            color: #999;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        thead tr {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        th {
            font-weight: bold;
            background-color: #f9f3e6;
        }

        tr.month-header th {
            background-color: #f39053;
            text-align: center;
            font-weight: bold;
            color: white;
        }

        tr.sub-header th {
            background-color: #fff9e6;
            text-align: center;
            font-size: 9px;
        }

        td {
            font-size: 10px;
        }

        .code-column {
            width: 60px;
            font-weight: bold;
            text-align: center;
        }

        .label-column {
            min-width: 180px;
        }

        .amount-column {
            text-align: right;
            width: 70px;
        }

        .ecart-column {
            text-align: right;
            width: 70px;
            border-right: 1px solid #333 !important;
        }

        .bg-success {
            background-color: #d4edda;
        }

        .bg-warning {
            background-color: #fff3cd;
        }

        .bg-danger {
            background-color: #f8d7da;
        }

        .bg-light {
            background-color: #e2e3e5;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            background-color: #e8f4f8;
            padding: 8px;
            border-left: 4px solid #0066cc;
        }

        .summary-table {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .summary-table th, .summary-table td {
            padding: 8px;
            font-size: 10px;
            text-align: right;
        }

        .summary-table .label {
            text-align: left;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 10px;
            }

            .container {
                padding: 8px;
                max-width: 100%;
            }

            .page-break {
                page-break-after: always;
            }

            table {
                margin-bottom: 8px;
                page-break-inside: avoid;
            }

            th, td {
                padding: 4px;
                font-size: 9px;
            }

            .header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>Suivi de la Situation Financière</h1>
            <p>Année: <strong>{{ (int)$year }}</strong></p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="print-info">
            <p>Page générée pour l'impression - Tous les montants en devise locale</p>
        </div>

        @php
            // Formatting helpers
            $fmtAmount = fn($v) => $v !== null && $v != 0.0 ? number_format((float) $v, 0, ',', ' ') : '-';

            // Calculer les taux de consommation pour chaque ligne
            $rowsWithTaux = [];
            foreach ($rows as $row) {
                $taux = [];
                $totalMontant = 0;
                $totalCons = 0;

                for ($m = 1; $m <= 12; $m++) {
                    $montant = (float) ($row['preMonths'][$m] ?? 0.0);
                    $cons = (float) ($row['consMonths'][$m] ?? 0.0);

                    $taux[$m] = $montant != 0.0 ? $cons / $montant : null;
                    $totalMontant += $montant;
                    $totalCons += $cons;
                }

                $rowsWithTaux[] = array_merge($row, [
                    'taux' => $taux,
                    'totalMontant' => $totalMontant,
                    'totalCons' => $totalCons,
                ]);
            }
        @endphp

        <!-- Tableau : Montant et Consommation -->
        <div class="section-title">Etat du suivi de la situation financière</div>

        <table>
            <thead>
                <tr class="month-header">
                    <th colspan="2">Éléments</th>
                    @foreach ($monthsLabels as $m)
                        <th colspan="3" style="text-align: center;">{{ $m }}</th>
                    @endforeach
                </tr>
                <tr class="sub-header">
                    <th class="code-column">Code</th>
                    <th class="label-column">Libellé</th>
                    @foreach ($monthsLabels as $m)
                        <th class="amount-column">Prévision</th>
                        <th class="amount-column">Réalisation</th>
                        <th class="ecart-column">Écart</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rowsWithTaux as $row)
                    <tr>
                        <td class="code-column">{{ $row['code'] }}</td>
                        <td class="label-column">{{ $row['libelle'] }}</td>

                        @for ($mo = 1; $mo <= 12; $mo++)
                            @php
                                $montant = $row['preMonths'][$mo] ?? 0;
                                $cons = $row['consMonths'][$mo] ?? 0;
                                $ecart = $montant - $cons;
                            @endphp
                            <td class="amount-column">{{ $fmtAmount($montant) }}</td>
                            <td class="amount-column">{{ $fmtAmount($cons) }}</td>
                            <td class="ecart-column"><strong>{{ $fmtAmount($ecart) }}</strong></td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="38" class="text-center">Aucune donnée disponible</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Résumé par ligne -->
        <div class="section-title">Résumé - Total par ligne budgétaire</div>

        <table class="summary-table">
            <thead>
                <tr style="background-color: #f9f3e6;">
                    <th class="label">Ligne budgétaire</th>
                    <th>Prévision Totale</th>
                    <th>Réalisation Totale</th>
                    <th>Écart Total</th>
                    <th>Taux de Consommation</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotalPrev = 0; $grandTotalCons = 0; @endphp
                @foreach ($rowsWithTaux as $row)
                    @php
                        $grandTotalPrev += $row['totalMontant'];
                        $grandTotalCons += $row['totalCons'];
                        $ecartTotal = $row['totalMontant'] - $row['totalCons'];
                        $tauxTotal = $row['totalMontant'] != 0 ? ($row['totalCons'] / $row['totalMontant']) : 0;
                    @endphp
                    <tr>
                        <td class="label"><strong>{{ $row['libelle'] }}</strong></td>
                        <td style="text-align: right;">{{ number_format($row['totalMontant'], 0, ',', ' ') }}</td>
                        <td style="text-align: right;">{{ number_format($row['totalCons'], 0, ',', ' ') }}</td>
                        <td style="text-align: right;"><strong>{{ number_format($ecartTotal, 0, ',', ' ') }}</strong></td>
                        <td style="text-align: right;">{{ number_format($tauxTotal * 100, 1, ',', ' ') }}%</td>
                    </tr>
                @endforeach
                <tr style="background-color: #e8b8a0; font-weight: bold; border-top: 2px solid #333;">
                    <td class="label">TOTAL GÉNÉRAL</td>
                    <td style="text-align: right;">{{ number_format($grandTotalPrev, 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($grandTotalCons, 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($grandTotalPrev - $grandTotalCons, 0, ',', ' ') }}</td>
                    <td style="text-align: right;">
                        @if ($grandTotalPrev > 0)
                            {{ number_format(($grandTotalCons / $grandTotalPrev) * 100, 1, ',', ' ') }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pied de page -->
        <div class="print-info" style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
            <p><strong>Légende:</strong></p>
            <ul style="margin-left: 20px; font-size: 10px; list-style-position: inside;">
                <li>Prévision: Montant budgété pour la période</li>
                <li>Réalisation: Montant réellement consommé</li>
                <li>Écart: Différence entre prévision et réalisation</li>
                <li>Taux de consommation: Réalisation / Prévision × 100</li>
            </ul>
        </div>
    </div>

    <script>
        // Afficher la boîte de dialogue d'impression automatiquement
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
