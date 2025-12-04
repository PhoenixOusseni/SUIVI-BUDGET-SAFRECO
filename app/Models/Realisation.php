<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realisation extends Model
{
    protected $fillable = [
        'ligne_budget_id',
        'year',
        'notes',
        'date',
    ];

    /**
     * Relation vers LigneBudget (optionnel)
     */
    public function ligneBudget()
    {
        return $this->belongsTo(LigneBudget::class, 'ligne_budget_id');
    }

    /**
     * Les 12 mois (1..12)
     */
    public function months()
    {
        return $this->hasMany(RealisationMonth::class);
    }
    /**
     * Récupère le montant pour un mois donné (1..12)
     */
    public function amountForMonth(int $month): float
    {
        $m = $this->months()->where('month', $month)->first();
        return $m ? (float) $m->amount : 0.0;
    }

    /**
     * Au moment de la création d'une Realisation, initialise 12 lignes month à 0
     */
    protected static function booted()
    {
        static::created(function (Realisation $realisation) {
            // Si déjà des months (rare) on skippe
            if ($realisation->months()->exists()) {
                return;
            }

            $months = [];
            for ($i = 1; $i <= 12; $i++) {
                $months[] = new RealisationMonth([
                    'month' => $i,
                    'amount' => 0,
                ]);
            }

            $realisation->months()->saveMany($months);
        });
    }
}