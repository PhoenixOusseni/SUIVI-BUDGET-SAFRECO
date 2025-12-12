<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'nom_fournisseur',
        'type_fournisseur',
        'contact_fournisseur',
        'email_fournisseur',
    ];

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }
}
