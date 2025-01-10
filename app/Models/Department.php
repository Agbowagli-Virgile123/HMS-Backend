<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function users(){
        return $this->hasMany(User::class);
    }


    public function departmentHead()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }
}

