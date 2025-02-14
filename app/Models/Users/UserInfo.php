<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class, 'employeeId', 'employeeId');
    }
}
