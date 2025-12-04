<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeBudget extends Model
{
    protected $fillable = [
        'code',
        'intitule',
        'description',
        'rubrique_id',
        'montant',
    ];

    public function rubrique()
    {
        return $this->belongsTo(Rubrique::class, 'rubrique_id');
    }

    public function ligneBudgets()
    {
        return $this->hasMany(LigneBudget::class);
    }
}
