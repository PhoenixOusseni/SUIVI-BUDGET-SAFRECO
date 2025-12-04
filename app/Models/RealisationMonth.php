<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisationMonth extends Model
{
    protected $fillable = [
        'realisation_id',
        'month', // 1..12 
        'amount', // decimal
    ];

    public function realisation(): BelongsTo
    {
        return $this->belongsTo(Realisation::class);
    }
}
