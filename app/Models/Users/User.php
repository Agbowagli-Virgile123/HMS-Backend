<?php

namespace App\Models\Users;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Patients\Appointment;
use App\Models\Patients\Vital;
use App\Models\Patients\Diagnosis;
use App\Models\Patients\Prescription;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;
    protected $primaryKey = 'employeeId'; // Set employeeId as the primary key
    public $incrementing = false; // Disable auto-incrementing for the primary key
    protected $keyType = 'string'; // Set key type to string if using employeeId as a string
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            if (!$user->employeeId) {
                $user->employeeId = self::generateEmployeeId();
            }
        });
    }

    public static function generateEmployeeId()
    {
        $lastId = self::max('employeeId'); // Get the last employeeId
        $nextId = $lastId ? ((int) substr($lastId, 3) + 1) : 1;
        return 'EMP' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }


    // public function getAuthIdentifier()
    // {
    //     return $this->employeeId;
    // }
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

     /**
     * Get the appointments managed by the user (employee).
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'employeeId');
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class, 'employeeId');
    }
    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class, 'employeeId');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'employeeId');
    }

    public function employeeSchedules() {
        return $this->hasMany(EmployeeSchedule::class, 'employeeId');
    }
    
}
