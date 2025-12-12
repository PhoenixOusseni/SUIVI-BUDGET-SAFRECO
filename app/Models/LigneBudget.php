<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneBudget extends Model
{
    protected $fillable = [
        'code',
        'intitule',
        'description',
        'code_budget_id',
        'montant',
    ];

    public function codeBudget()
    {
        return $this->belongsTo(CodeBudget::class, 'code_budget_id');
    }

    public function previsions()
    {
        return $this->hasMany(Prevision::class, 'ligne_budget_id');
    }

    public function realisations()
    {
        return $this->hasMany(Realisation::class, 'ligne_budget_id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'ligne_budget_id');
    }
}
