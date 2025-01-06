<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    protected $primaryKey = 'employeeId'; // Define the primary key
    public $incrementing = false;        // Indicate it's not an auto-incrementing key
    protected $keyType = 'string';       // Define the key type as string

    public function getAuthIdentifier()
    {
        return $this->employeeId;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'employeeId',
    //     'role_id',
    //     'department_id',
    //     'firstName',
    //     'lastName',
    //     'email',	
    //     'phone',
    //     'password',
    //     'status',

    // ];

    protected $guarded=[];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

       // Define the relationship with Department
    // User model
public function department()
{
    return $this->belongsTo(Department::class, 'department_id');
}

public function role()
{
    return $this->belongsTo(Role::class);
}

public function userInfo()
{
    return $this->hasOne(UserInfo::class, 'employeeId', 'employeeId');
}

}
