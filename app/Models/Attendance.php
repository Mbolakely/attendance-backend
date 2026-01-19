<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    
    protected $fillable = [
        'sceance_id',
        'student_id',
        'status',
        'confiance',
        'justificatif',
        'notes',
    ];

    public function sceance()
    {
        return $this->belongsTo(Sceance::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
