<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'ligne_budget_id',
        'year',
        'libelle',
        'reference',
        'mois',
    ];

    public function ligneBudget()
    {
        return $this->belongsTo(LigneBudget::class, 'ligne_budget_id');
    }
}
