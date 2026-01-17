<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class 
Classe extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'libelle',
        'code_classe',
        'niveau_id',
        'parcours_id'
    ];

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function parcours()
    {
        return $this->belongsTo(Parcours::class);
    }
}
