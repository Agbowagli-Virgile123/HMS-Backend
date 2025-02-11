<?php

namespace App\Models\Patients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    use HasFactory;

    protected $guarded = [];

    function patient(){
        return $this->belongsTo(Patient::class,'patientId','patientId');
    }
}
