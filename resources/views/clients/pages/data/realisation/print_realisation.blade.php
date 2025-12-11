<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réalisations Budgétaires - {{ $year }}</title>
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
            background-color: #2196F3;
            color: white;
            font-size: 11px;
        }

        td {
            font-size: 11px;
        }

        .code-column {
            width: 100px;
            font-weight: bold;
        }

        .label-column {
            min-width: 250px;
        }

        .amount-column {
            text-align: right;
            width: 90px;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .ligne-row:hover {
            background-color: #f9f9f9;
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
            <h1>RÉALISATIONS BUDGÉTAIRES</h1>
            <p>Année: <strong>{{ $year }}</strong></p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="print-info">
            <p>Page générée pour l'impression</p>
        </div>

        <!-- Tableau principal -->
        <table>
            <thead>
                <tr>
                    <th class="code-column">CODE</th>
                    <th class="label-column">Libellés</th>
                    @foreach ($monthsLabels as $month)
                        <th class="amount-column">{{ $month }}</th>
                    @endforeach
                    <th class="amount-column">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Initialise totaux colonne
                    $colTotals = array_fill(1, 12, 0.0);
                    $grandTotal = 0.0;
                @endphp

                @forelse($groupedRealisations as $ligneId => $realis)
                    @php
                        // Prenons la première réalisation (normalement une réalisation par ligne / année)
                        $realisation = $realis->first();

                        // Récupérer info de la ligne budgétaire (si existante)
                        $ligne = $lignesMap->get($ligneId);

                        // Construire tableau months 1..12 initialisé à 0
                        $rowMonths = array_fill(1, 12, 0.0);

                        // Remplir à partir des RealisationMonth
                        if ($realisation && $realisation->relationLoaded('months')) {
                            foreach ($realisation->months as $rm) {
                                $rowMonths[(int) $rm->month] = (float) $rm->amount;
                            }
                        } elseif ($realisation) {
                            foreach ($realisation->months()->get() as $rm) {
                                $rowMonths[(int) $rm->month] = (float) $rm->amount;
                            }
                        }

                        $rowTotal = array_sum($rowMonths);

                        // Accumulation totaux colonnes
                        foreach ($rowMonths as $k => $v) {
                            $colTotals[$k] += $v;
                        }
                        $grandTotal += $rowTotal;
                    @endphp

                    <tr class="ligne-row">
                        <td class="code-column">{{ $ligne ? $ligne->code : '—' }}</td>
                        <td class="label-column">
                            {{ $ligne ? $ligne->intitule ?? ($ligne->name ?? '') : $realisation->description ?? '' }}
                        </td>

                        @foreach ($rowMonths as $amt)
                            <td class="amount-column">{{ $amt ? number_format($amt, 0, ',', ' ') : '0' }}</td>
                        @endforeach

                        <td class="amount-column">{{ number_format($rowTotal, 0, ',', ' ') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" style="text-align: center;">Aucune réalisation enregistrée pour l'année {{ $year }}.</td>
                    </tr>
                @endforelse

                <!-- Ligne de totaux -->
                @if($groupedRealisations->isNotEmpty())
                    <tr class="total-row">
                        <td colspan="2" style="text-align: center;"><strong>TOTAL GÉNÉRAL</strong></td>
                        @foreach ($colTotals as $total)
                            <td class="amount-column"><strong>{{ number_format($total, 0, ',', ' ') }}</strong></td>
                        @endforeach
                        <td class="amount-column"><strong>{{ number_format($grandTotal, 0, ',', ' ') }}</strong></td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Pied de page - Résumé -->
        @if($groupedRealisations->isNotEmpty())
            <div class="print-info" style="margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
                <p><strong>Résumé des totaux mensuels:</strong></p>
                <table style="width: auto; margin-top: 10px;">
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; background-color: #f5f5f5; font-weight: bold;">Mois</td>
                        @foreach ($monthsLabels as $month)
                            <td style="border: 1px solid #ddd; padding: 5px; background-color: #f5f5f5; text-align: center; font-weight: bold;">
                                {{ substr($month, 0, 3) }}
                            </td>
                        @endforeach
                        <td style="border: 1px solid #ddd; padding: 5px; background-color: #f5f5f5; text-align: center; font-weight: bold;">Total</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 5px; background-color: #e3f2fd; font-weight: bold;">Montants</td>
                        @foreach ($colTotals as $total)
                            <td style="border: 1px solid #ddd; padding: 5px; background-color: #e3f2fd; text-align: right;">
                                {{ number_format($total, 0, ',', ' ') }}
                            </td>
                        @endforeach
                        <td style="border: 1px solid #ddd; padding: 5px; background-color: #e3f2fd; text-align: right; font-weight: bold;">
                            {{ number_format($grandTotal, 0, ',', ' ') }}
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>

    <script>
        // Afficher la boîte de dialogue d'impression automatiquement
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
