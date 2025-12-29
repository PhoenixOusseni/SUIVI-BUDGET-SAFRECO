<?php

namespace App\Exports;

use App\Models\Realisation;
use App\Models\RealisationMonth;
use App\Models\LigneBudget;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * Export class for Realisations to Excel/CSV.
 * Exports columns: Code, Année, Janvier, Février, Mars, Avril, Mai, Juin, Juillet, Août, Septembre, Octobre, Novembre, Décembre
 */
class RealisationsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected int $year;
    protected ?int $ligneBudgetId;

    public function __construct(int $year, ?int $ligneBudgetId = null)
    {
        $this->year = $year;
        $this->ligneBudgetId = $ligneBudgetId;
    }

    /**
     * Récupérer les réalisations à exporter
     */
    public function collection()
    {
        $query = Realisation::with(['ligneBudget', 'months'])
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
     * Mapper chaque réalisation vers une ligne du fichier
     */
    public function map($realisation): array
    {
        // Récupérer les montants mensuels
        $months = $realisation->months->keyBy('month');

        $monthlyAmounts = [];
        $total = 0;

        for ($month = 1; $month <= 12; $month++) {
            $amount = $months->has($month) ? (float) $months->get($month)->amount : 0;
            $monthlyAmounts[] = $amount;
            $total += $amount;
        }

        return [
            $realisation->ligneBudget->code ?? 'N/A',
            $realisation->year,
            ...$monthlyAmounts,
            $total
        ];
    }

    /**
     * Titre de la feuille Excel
     */
    public function title(): string
    {
        return "Réalisations {$this->year}";
    }
}
