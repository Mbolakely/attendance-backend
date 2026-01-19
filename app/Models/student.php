<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class student extends Model
{
    use Notifiable;
    
     protected $table = 'students';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'classe_id',
        'num_matricule',
        'cin',
        'sexe',
        'telephone',
        'adresse',
        'date_naissance',
        'date_inscription',
        'encodage_facial'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'encodage_facial' => 'array'
    ];

     public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
