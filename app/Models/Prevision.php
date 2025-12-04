<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class Prevision extends Model

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
    public function ligneBudget(): BelongsTo
    {
        return $this->belongsTo(LigneBudget::class, 'ligne_budget_id');
    }

    /**
     * Les 12 mois (1..12)
     */
    public function months(): HasMany
    {
        return $this->hasMany(PrevisionMonth::class);
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
     * Au moment de la création d'une Prevision, initialise 12 lignes month à 0
     */
    protected static function booted()
    {
        static::created(function (Prevision $prevision) {
            // Si déjà des months (rare) on skippe
            if ($prevision->months()->exists()) {
                return;
            }

            $months = [];
            for ($i = 1; $i <= 12; $i++) {
                $months[] = new PrevisionMonth([
                    'month' => $i,
                    'amount' => 0,
                ]);
            }

            $prevision->months()->saveMany($months);
        });
    } 
}
