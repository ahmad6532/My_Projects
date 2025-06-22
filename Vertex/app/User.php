<?php

// namespace App\Http\Models;
namespace App;

use App\Models\EmployeeDetail;
use App\Models\Role;
use App\Models\Branch;
use App\Models\permission;
use Laravel\Passport\HasApiTokens;
use App\Models\role_has_permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens , Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'role_id',
        'company_id',
        'branch_id',
        'emp_id',
        'fullname',
        'gender',
        'phone',
        'email',
        'password',
        'expiry_date',
        'image',
        'is_active',
        'is_deleted',
        'is_pin_enable',
        'can_update_face',

    ];

    protected $table = 'users';
    protected $guard_name = 'web';
    protected $primaryKey = 'id';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id','id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id','id');
    }

    public function role_has_permission(){
        return $this->hasMany(role_has_permission::class, 'role_id','role_id');
    }

    public function haspermission($permissions)
    {
        return $this->hasManyThrough(Permission::class, role_has_permission::class, 'role_id', 'id', 'role_id', 'permission_id')
                    ->whereIn('permissions.name', $permissions)
                    ->exists();
    }

    public function userToEmp(){
        return $this->belongsTo(EmployeeDetail::class,'emp_id');
    }
}
