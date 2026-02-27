<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adherant extends Model
{
    protected $fillable = [
        'code',
        'nom_adherant',
        'contact_adherant',
        'email_adherant',
    ];
}
