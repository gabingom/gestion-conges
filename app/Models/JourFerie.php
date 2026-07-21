<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourFerie extends Model
{
    protected $fillable = [
        'nom',
        'date',
        'annuel',
    ];

    protected $casts = [
        'date' => 'date',
        'annuel' => 'boolean',
    ];
}
