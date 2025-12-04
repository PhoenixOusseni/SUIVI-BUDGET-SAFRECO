<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Simple import class that stores the rows (with headings).
 * We use WithHeadingRow so headings become array keys (lowercased and trimmed).
 */
class PrevisionsImport implements ToCollection, WithHeadingRow
{
    protected Collection $rows;

    public function collection(Collection $rows)
    {
        // store rows collection (one sheet)
        $this->rows = $rows;
    }

    public function getRows(): ?Collection
    {
        return $this->rows ?? null;
    }
}