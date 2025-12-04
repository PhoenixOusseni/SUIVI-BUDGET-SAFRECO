<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrevisionMonth extends Model
{
    protected $fillable = [
        'prevision_id',
        'month', // 1..12 
        'amount', // decimal
    ];

    public function prevision(): BelongsTo
    {
        return $this->belongsTo(Prevision::class);
    }
}
