<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'ligne_budget_id',
        'adherant_id',
        'fournisseur_id',
        'year',
        'libelle',
        'reference',
        'mois',
    ];

    public function ligneBudget()
    {
        return $this->belongsTo(LigneBudget::class, 'ligne_budget_id');
    }

    public function adherant()
    {
        return $this->belongsTo(Adherant::class, 'adherant_id');
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }
}
