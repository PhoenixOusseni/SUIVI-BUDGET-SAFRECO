<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Budgétaire - {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
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

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #d32f2f;
            text-align: center;
            margin: 30px 0 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #d32f2f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead tr {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            font-weight: bold;
            background-color: #e0e0e0;
            color: #333;
            font-size: 10px;
        }

        td {
            font-size: 10px;
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
            width: 85px;
        }

        .total-column {
            text-align: center;
            width: 120px;
            background-color: #f1b46e !important;
            font-weight: bold;
        }

        .ligne-row:hover {
            background-color: #f9f9f9;
        }

        .page-break {
            page-break-after: always;
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
                margin-bottom: 15px;
            }

            th, td {
                padding: 4px;
                font-size: 9px;
            }

            .section-title {
                font-size: 14px;
                margin: 20px 0 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>ÉTAT DE SUIVI BUDGÉTAIRE</h1>
            <p>Année: <strong>{{ $year }}</strong></p>
            <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="print-info">
            <p>Page générée pour l'impression</p>
        </div>

        {{-- SECTION ENCAISSEMENTS --}}
        @if (!empty($encaissementsByRubrique))
            <div class="section-title">SUIVI PRÉVISIONS DES RECETTES</div>

            <table>
                <thead>
                    <tr>
                        <th class="code-column">Code</th>
                        <th class="label-column">Libellés</th>
                        @foreach ($monthsLabels as $month)
                            <th class="amount-column">{{ $month }}</th>
                        @endforeach
                        <th class="total-column">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $encColTotals = array_fill(1, 12, 0.0);
                        $encGrandTotal = 0.0;
                    @endphp

                    @foreach ($encaissementsByRubrique as $rubId => $group)
                        @foreach ($group['items'] as $row)
                            <tr class="ligne-row">
                                <td class="code-column">{{ $row['ligne_code'] }}</td>
                                <td class="label-column">{{ $row['ligne_label'] }}</td>
                                @foreach ($row['months'] as $m => $amt)
                                    @php
                                        $encColTotals[$m] += $amt;
                                    @endphp
                                    <td class="amount-column">
                                        {{ $amt ? number_format($amt, 0, ',', ' ') : '' }}
                                    </td>
                                @endforeach
                                @php
                                    $encGrandTotal += $row['total'];
                                @endphp
                                <td class="total-column">
                                    {{ $row['total'] ? number_format($row['total'], 0, ',', ' ') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    {{-- Ligne de totaux pour encaissements --}}
                    <tr style="background-color: #f0f0f0; font-weight: bold; border-top: 2px solid #333;">
                        <td colspan="2" style="text-align: center;"><strong>TOTAL RECETTES</strong></td>
                        @foreach ($encColTotals as $total)
                            <td class="amount-column"><strong>{{ number_format($total, 0, ',', ' ') }}</strong></td>
                        @endforeach
                        <td class="total-column"><strong>{{ number_format($encGrandTotal, 0, ',', ' ') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="section-title">SUIVI PRÉVISIONS DES RECETTES</div>
            <p style="text-align: center; color: #999; padding: 20px;">Aucune prévision d'encaissement pour {{ $year }}.</p>
        @endif

        {{-- SECTION DÉCAISSEMENTS --}}
        @if (!empty($decaissementsByRubrique))
            <div class="section-title page-break">SUIVI PRÉVISIONS DES DÉPENSES</div>

            <table>
                <thead>
                    <tr>
                        <th class="code-column">Code</th>
                        <th class="label-column">Libellés</th>
                        @foreach ($monthsLabels as $month)
                            <th class="amount-column">{{ $month }}</th>
                        @endforeach
                        <th class="total-column">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $decColTotals = array_fill(1, 12, 0.0);
                        $decGrandTotal = 0.0;
                    @endphp

                    @foreach ($decaissementsByRubrique as $rubId => $group)
                        @foreach ($group['items'] as $row)
                            <tr class="ligne-row">
                                <td class="code-column">{{ $row['ligne_code'] }}</td>
                                <td class="label-column">{{ $row['ligne_label'] }}</td>
                                @foreach ($row['months'] as $m => $amt)
                                    @php
                                        $decColTotals[$m] += $amt;
                                    @endphp
                                    <td class="amount-column">
                                        {{ $amt ? number_format($amt, 0, ',', ' ') : '' }}
                                    </td>
                                @endforeach
                                @php
                                    $decGrandTotal += $row['total'];
                                @endphp
                                <td class="total-column">
                                    {{ $row['total'] ? number_format($row['total'], 0, ',', ' ') : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    {{-- Ligne de totaux pour décaissements --}}
                    <tr style="background-color: #f0f0f0; font-weight: bold; border-top: 2px solid #333;">
                        <td colspan="2" style="text-align: center;"><strong>TOTAL DÉPENSES</strong></td>
                        @foreach ($decColTotals as $total)
                            <td class="amount-column"><strong>{{ number_format($total, 0, ',', ' ') }}</strong></td>
                        @endforeach
                        <td class="total-column"><strong>{{ number_format($decGrandTotal, 0, ',', ' ') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="section-title">SUIVI PRÉVISIONS DES DÉPENSES</div>
            <p style="text-align: center; color: #999; padding: 20px;">Aucune prévision de décaissement pour {{ $year }}.</p>
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
