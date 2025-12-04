<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubrique extends Model
{
    protected $fillable = [
        'code',
        'intitule',
        'description',
    ];

    public function codeBudgets()
    {
        return $this->hasMany(CodeBudget::class);
    }
}
