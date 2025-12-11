<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Dépenses Rationnelles - {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            color: #666;
        }

        .print-info {
            text-align: right;
            font-size: 11px;
            color: #999;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead tr {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
            background-color: #fff9e6;
            font-size: 11px;
        }

        tr.month-header th {
            background-color: #e8b8a0;
            text-align: center;
            font-weight: bold;
        }

        tr.sub-header th {
            background-color: #fff9e6;
            text-align: center;
            font-size: 10px;
        }

        td {
            font-size: 11px;
        }

        .code-column {
            width: 80px;
            font-weight: bold;
        }

        .label-column {
            min-width: 200px;
        }

        .amount-column {
            text-align: right;
            width: 70px;
        }

        .ratio-column {
            text-align: center;
            width: 60px;
        }

        .total-row {
            background-color: #d3d3d3;
            font-weight: bold;
        }

        .grand-total-row {
            background-color: #e8b8a0;
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .group-total-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .ligne-row td {
            background-color: #fafafa;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 10px;
            }

            .page-break {
                page-break-after: always;
            }

            table {
                margin-bottom: 10px;
            }

            th, td {
                padding: 5px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>Suivi des Dépenses Rationnelles</h1>
            <p>Année: <strong>{{ $year }}</strong></p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="print-info">
            <p>Page générée pour l'impression</p>
        </div>

        <!-- Tableau principal -->
        <table>
            <thead>
                <tr class="month-header">
                    <th colspan="2" style="background-color: #fff9e6;"></th>
                    @foreach (range(1, 12) as $month)
                        <th colspan="2" class="text-center" style="background-color: #e8b8a0;">
                            {{ $monthsLabels[$month] }}
                        </th>
                    @endforeach
                </tr>
                <tr class="sub-header">
                    <th class="code-column">Code</th>
                    <th class="label-column">Libellé</th>
                    @foreach (range(1, 12) as $month)
                        <th class="amount-column">Montant</th>
                        <th class="ratio-column">Ratio</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    // Helper function pour obtenir le montant prévisionnel pour une ligne et un mois
                    $getMontantPrevision = function ($ligne, $month) {
                        if ($ligne->previsions && $ligne->previsions->count() > 0) {
                            $prevision = $ligne->previsions->first();
                            $monthData = $prevision->months->where('month', $month)->first();
                            return $monthData ? (float) $monthData->amount : 0;
                        }
                        return 0;
                    };

                    // Calcul des totaux par mois
                    $totaux = [];
                    foreach (range(1, 12) as $month) {
                        $totaux[$month] = 0;
                        foreach ($groupedByCodeBudget as $groupData) {
                            foreach ($groupData['lignes'] as $ligne) {
                                $totaux[$month] += $getMontantPrevision($ligne, $month);
                            }
                        }
                    }
                @endphp

                @forelse ($groupedByCodeBudget as $codeBudgetId => $groupData)
                    @php
                        $codeBudget = $groupData['codeBudget'];
                        $lignes = $groupData['lignes'];
                    @endphp

                    <!-- Lignes du code budgétaire -->
                    @foreach ($lignes as $index => $ligne)
                        <tr class="ligne-row">
                            <td class="code-column">{{ $ligne->code }}</td>
                            <td class="label-column">{{ $ligne->intitule }}</td>
                            @foreach (range(1, 12) as $month)
                                @php $montant = $getMontantPrevision($ligne, $month); @endphp
                                <td class="amount-column">{{ number_format($montant, 0, ',', ' ') }}</td>
                                <td class="ratio-column">
                                    @if ($totaux[$month] > 0)
                                        {{ number_format(($montant / $totaux[$month]) * 100, 2, ',', ' ') }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    <!-- Total par code budgétaire -->
                    <tr class="group-total-row">
                        <td class="code-column">{{ $codeBudget->code }}</td>
                        <td class="label-column">Total {{ $codeBudget->intitule }}</td>
                        @foreach (range(1, 12) as $month)
                            @php
                                $totalCodeBudget = 0;
                                foreach ($lignes as $ligne) {
                                    $totalCodeBudget += $getMontantPrevision($ligne, $month);
                                }
                            @endphp
                            <td class="amount-column">
                                {{ number_format($totalCodeBudget, 0, ',', ' ') }}
                            </td>
                            <td class="ratio-column">
                                @if ($totaux[$month] > 0)
                                    {{ number_format(($totalCodeBudget / $totaux[$month]) * 100, 2, ',', ' ') }}%
                                @else
                                    0%
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="26" class="text-center">Aucune donnée disponible</td>
                    </tr>
                @endforelse

                <!-- Total général -->
                <tr class="grand-total-row">
                    <td colspan="2" style="text-align: center;">DÉCAISSEMENTS TOTAUX</td>
                    @foreach (range(1, 12) as $month)
                        <td class="amount-column">
                            {{ number_format($totaux[$month], 0, ',', ' ') }}
                        </td>
                        <td class="ratio-column">100%</td>
                    @endforeach
                </tr>
            </tbody>
        </table>

        <!-- Pied de page -->
        <div class="print-info" style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
            <p><strong>Résumé des totaux mensuels:</strong></p>
            <table style="width: auto; margin-top: 10px;">
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Mois</td>
                    @foreach (range(1, 12) as $month)
                        <td style="border: 1px solid #ddd; padding: 5px; background-color: #f5f5f5; text-align: center; font-weight: bold;">
                            {{ substr($monthsLabels[$month], 0, 3) }}
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 5px; background-color: #fff9e6; font-weight: bold;">Total</td>
                    @foreach (range(1, 12) as $month)
                        <td style="border: 1px solid #ddd; padding: 5px; background-color: #fff9e6; text-align: right;">
                            {{ number_format($totaux[$month], 0, ',', ' ') }}
                        </td>
                    @endforeach
                </tr>
            </table>
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
