<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'fullname',
        'company_id',
        'branch_id',
        'emp_id',
        'expiry_date',
        'is_active',
        'is_pin_enable',
        'can_update_face',
        'is_attendance_allowed'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_devices()
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(role::class,'model_has_roles','role_id');
    }

    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }
    public function userToEmp(){
        return $this->belongsTo(EmployeeDetail::class,'emp_id');
    }
}
