<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sceance extends Model
{
    protected $table = 'sceances';


    protected $fillable = [
        'classe_id',
        'date',
        'debut_sceance',
        'fin_sceance',
        'salle',
        'matiere',
        'professeur',
        'status'
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
