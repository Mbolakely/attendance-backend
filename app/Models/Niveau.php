<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    protected $table = 'niveaux';

    protected $fillable = [
        'libelle',
        'code_niveau'
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
