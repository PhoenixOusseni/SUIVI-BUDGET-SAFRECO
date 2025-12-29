<?php

namespace App\Exports;

use App\Models\Prevision;
use App\Models\PrevisionMonth;
use App\Models\LigneBudget;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * Export class for Previsions to Excel/CSV.
 * Exports columns: Code, Année, Janvier, Février, Mars, Avril, Mai, Juin, Juillet, Août, Septembre, Octobre, Novembre, Décembre
 */
class PrevisionsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected int $year;
    protected ?int $ligneBudgetId;

    public function __construct(int $year, ?int $ligneBudgetId = null)
    {
        $this->year = $year;
        $this->ligneBudgetId = $ligneBudgetId;
    }

    /**
     * Récupérer les prévisions à exporter
     */
    public function collection()
    {
        $query = Prevision::with(['ligneBudget', 'months'])
            ->where('year', $this->year);

        // Filtrer par ligne budgétaire si spécifié
        if ($this->ligneBudgetId) {
            $query->where('ligne_budget_id', $this->ligneBudgetId);
        }

        return $query->orderBy('ligne_budget_id')->get();
    }

    /**
     * Définir les en-têtes de colonnes
     */
    public function headings(): array
    {
        return [
            'Code',
            'Année',
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre',
            'Total'
        ];
    }

    /**
     * Mapper chaque prévision vers une ligne du fichier
     */
    public function map($prevision): array
    {
        // Récupérer les montants mensuels
        $months = $prevision->months->keyBy('month');

        $monthlyAmounts = [];
        $total = 0;

        for ($month = 1; $month <= 12; $month++) {
            $amount = $months->has($month) ? (float) $months->get($month)->amount : 0;
            $monthlyAmounts[] = $amount;
            $total += $amount;
        }

        return [
            $prevision->ligneBudget->code ?? 'N/A',
            $prevision->year,
            ...$monthlyAmounts,
            $total
        ];
    }

    /**
     * Titre de la feuille Excel
     */
    public function title(): string
    {
        return "Prévisions {$this->year}";
    }
}
