<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // use HasFactory;
    protected $table = 'logs';
    protected $fillable = ['type','user_id','msg','status'];
    protected $guarded = ['id'];

    static $log_type = [
        'employee_action' => ['msg'=>'Employee ','icon'=>'fontello icon-briefcase-compound'],
        'timesheet_action' => ['msg'=>'Time Sheet ','icon'=>'fontello icon-attendance'],
        'leave_action' => ['msg'=>'Leave Request ','icon'=>'fontello icon-leave1'],
        'holidays_action' => ['msg'=>'Holidays ','icon'=>'fontello icon-holidays1'],
        'user_action' => ['msg'=>'User Management ','icon'=>'fontello icon-users'],
        'branch_action' => ['msg'=>'Location Management ','icon'=>'fontello icon-branch1'],
        'comm_action' => ['msg'=>'Communication ','icon'=>'fontello icon-Communication1'],
        'global_action' => ['msg'=>'Global Setting ','icon'=>'fontello icon-users1'],
        'login_action' => ['msg'=>'','icon'=>'fontello icon-account-Users'],
        'logout_action' => ['msg'=>'','icon'=>'fontello icon-account-Users'],
        'shift_management_action' => ['msg'=>'Shift','icon'=>'fontello icon-account-Users'],
        'promotion_action' => ['msg'=>'','icon'=>'fontello icon-briefcase-compound'],
        'termination_action' => ['msg'=>'','fontello icon-briefcase-compound'],
        'version_action' => ['msg'=>'','icon'=>'fontello icon-account-Users'],
        'device_action' => ['msg'=>'','icon'=>'fontello icon-device'],
        'company_action' => ['msg'=>'','icon'=>'fontello icon-branch1'],
        'notification_action' => ['msg'=>'','icon'=>'fontello icon-Communication1'],
        'emp_salary_action' => ['msg'=>'','icon'=>'fontello icon-dollar'],
    ];

    public function user(){
        return $this->hasone(User::class,'id','user_id');
    }
}
