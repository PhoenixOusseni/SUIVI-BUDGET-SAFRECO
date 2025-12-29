<?php

namespace App\Imports;

use App\Models\Realisation;
use App\Models\RealisationMonth;
use App\Models\LigneBudget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

/**
 * Import class for Realisations from Excel/CSV.
 * Expected columns (French format):
 * - code (Code budgétaire)
 * - annee (Année)
 * - janvier, fevrier, mars, avril, mai, juin, juillet, aout, septembre, octobre, novembre, decembre
 *
 * Alternative English columns also supported:
 * - code, year, january, february, march, april, may, june, july, august, september, october, november, december
 */
class RealisationsImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected Collection $rows;
    protected array $results = [
        'success' => 0,
        'errors' => 0,
        'skipped' => 0,
        'messages' => [],
    ];

    public function collection(Collection $rows)
    {
        // store rows collection (one sheet)
        $this->rows = $rows;

        // Process each row
        foreach ($rows as $index => $row) {
            try {
                $this->processRow($row, $index + 2); // +2 because of header and 0-index
            } catch (\Exception $e) {
                $this->results['errors']++;
                $this->results['messages'][] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                Log::error("Import error on row " . ($index + 2), [
                    'error' => $e->getMessage(),
                    'row' => $row->toArray()
                ]);
            }
        }
    }

    protected function processRow(Collection $row, int $rowNumber)
    {
        // Extract code and year
        $code = $this->getRowValue($row, ['code', 'code_budgetaire', 'ligne']);
        $year = $this->getRowValue($row, ['annee', 'year', 'année']);

        if (!$code || !$year) {
            $this->results['skipped']++;
            $this->results['messages'][] = "Ligne {$rowNumber}: Code ou année manquant (code: {$code}, année: {$year})";
            return;
        }

        // Find ligne budget by code
        $ligneBudget = LigneBudget::where('code', $code)->first();
        if (!$ligneBudget) {
            $this->results['errors']++;
            $this->results['messages'][] = "Ligne {$rowNumber}: Code budgétaire '{$code}' introuvable";
            return;
        }

        // Extract monthly amounts (supports both French and English column names)
        $monthsData = [
            1 => $this->parseAmount($this->getRowValue($row, ['janvier', 'january', 'jan'])),
            2 => $this->parseAmount($this->getRowValue($row, ['fevrier', 'february', 'fev', 'feb'])),
            3 => $this->parseAmount($this->getRowValue($row, ['mars', 'march', 'mar'])),
            4 => $this->parseAmount($this->getRowValue($row, ['avril', 'april', 'avr', 'apr'])),
            5 => $this->parseAmount($this->getRowValue($row, ['mai', 'may'])),
            6 => $this->parseAmount($this->getRowValue($row, ['juin', 'june', 'jun'])),
            7 => $this->parseAmount($this->getRowValue($row, ['juillet', 'july', 'juil', 'jul'])),
            8 => $this->parseAmount($this->getRowValue($row, ['aout', 'august', 'aug', 'août'])),
            9 => $this->parseAmount($this->getRowValue($row, ['septembre', 'september', 'sep', 'sept'])),
            10 => $this->parseAmount($this->getRowValue($row, ['octobre', 'october', 'oct'])),
            11 => $this->parseAmount($this->getRowValue($row, ['novembre', 'november', 'nov'])),
            12 => $this->parseAmount($this->getRowValue($row, ['decembre', 'december', 'dec', 'déc', 'décembre'])),
        ];

        // Insert or update realisation
        DB::transaction(function () use ($ligneBudget, $year, $monthsData, $rowNumber) {
            $realisation = Realisation::firstOrCreate(
                [
                    'ligne_budget_id' => $ligneBudget->id,
                    'year' => $year,
                ],
                [
                    'date' => now(),
                    'notes' => 'Importé depuis Excel',
                ]
            );

            // Upsert monthly amounts
            $rows = [];
            foreach ($monthsData as $month => $amount) {
                $rows[] = [
                    'realisation_id' => $realisation->id,
                    'month' => $month,
                    'amount' => $amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            RealisationMonth::upsert($rows, ['realisation_id', 'month'], ['amount', 'updated_at']);

            $this->results['success']++;
        });
    }

    /**
     * Get value from row with multiple possible column names
     */
    protected function getRowValue(Collection $row, array $possibleKeys): mixed
    {
        foreach ($possibleKeys as $key) {
            $normalizedKey = strtolower(trim($key));
            if ($row->has($normalizedKey)) {
                return $row[$normalizedKey];
            }
        }
        return null;
    }

    /**
     * Parse amount from string to float
     * Supports formats: "1 234 567,89" or "1,234,567.89" or "1234567.89"
     */
    protected function parseAmount($raw): float
    {
        if ($raw === null || $raw === '') {
            return 0.0;
        }

        $s = trim((string) $raw);
        if ($s === '') {
            return 0.0;
        }

        // Remove non-numeric except digits, dot, comma, minus
        $s = preg_replace('/[^\d\-,\.]/u', '', $s);

        // If number contains both comma and dot
        if (strpos($s, ',') !== false && strpos($s, '.') !== false) {
            $lastComma = strrpos($s, ',');
            $lastDot = strrpos($s, '.');

            if ($lastComma > $lastDot) {
                // Comma is decimal separator
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                // Dot is decimal separator
                $s = str_replace(',', '', $s);
            }
        } else {
            // Only comma present -> comma as decimal separator
            if (strpos($s, ',') !== false) {
                $s = str_replace(' ', '', $s);
                $s = str_replace(',', '.', $s);
            } else {
                // Only dot present or none
                $s = str_replace(' ', '', $s);
            }
        }

        return (float) $s;
    }

    public function getRows(): ?Collection
    {
        return $this->rows ?? null;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
