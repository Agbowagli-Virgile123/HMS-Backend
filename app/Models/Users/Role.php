<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Role extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function users()
    {
        return $this->hasMany(User::class,'employeeId');
    }
}
