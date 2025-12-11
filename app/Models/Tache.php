<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    protected $fillable = [
        'code',
        'libelle',
        'description',
        'date_debut',
        'date_echeance',
        'taux',
        'file',
    ];
}
