<?php

namespace App\Models;
use App\Models\UserAttendence;
// use Laravel\passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use App\Models\Salary;
use App\Models\SalaryComponent;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDetail extends Model
{
    // use HasFactory;
    protected $table ='employee_details';
    protected $fillable =
    [
        'company_id',
        'branch_id',
        'emp_id',
        'emp_name',
        'emp_email',
        'emp_gender',
        'dob',
        'emp_phone',
        'cnic',
        'emp_image',
        'added_by',
        'father_name',
        'mother_name',
        'personal_email',
        'emp_address',
        'nationality',
        'city_of_birth',
        'religion',
        'blood_group',
        'marital_status',
        'spouse_name',
        'is_licensed',
        'is_independant',
        'has_transport',
        'has_home',
        'transport_type',
        'registration_no',
        'driving_license',
        'license_no',
        'status',
        'is_active',
        'attend_pin',
        'fingerprint',
        'is_deleted',
        'id',
        'emp_cv',
        'emp_nic'
    ];
    protected $hidden = [
        'fingerprint',
        // 'dob',
        // 'cnic',
        // 'mother_name',
        // 'emp_address',
        'join_date',
        'added_by',
        // 'is_licensed',
        // 'is_independant',
        // 'has_transport',
        // 'has_home',
        // 'transport_type',
        // 'registration_no',
        // 'driving_license',
        // 'license_no',
        'attend_pin',
        'created_at',
        // 'spouse_name',
        // 'marital_status',
        // 'city_of_birth',
        'ApprovedByCEO',
        // 'blood_group',
    ];

    public function getdobAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id','id');
    }
    public function branch(){
        return $this->belongsTo(Location::class, 'branch_id','id')->where('is_deleted','0');
    }
    public function approval(){
        return $this->hasOne(user_approval::class, 'emp_id');
    }
    public function user_attendance(){
        return $this->hasMany(UserAttendence::class,'emp_id');
    }

    // public function user_attendance(){
    //     return $this->hasMany(UserAttendence::class,'id');
    // }

    // public function user_daily_attendance(){
    //     return $this->hasOne(UserAttendence::class,'emp_id');
    // }
    public function get_user_daily_attendance(){
        return $this->hasMany(UserDailyRecord::class,'emp_id');
    }
    public function leaves(){
        return $this->hasMany(Leave::class, 'emp_id', 'id')->where('is_approved','1');
    }
    public function holidays(){
        return $this->hasMany(Holiday::class,'branch_id','branch_id')
        ->where('is_active','1');
    }
    public function resignations(){
        return $this->hasOne(EmployeeResignation::class,'emp_id','id')->where('is_approved','1');
    }

    public function terminations(){
        return $this->hasOne(Emp_termination::class,'emp_id','id')->where('is_approved','1');
    }

    public function designation(){
        return $this->hasOne(Designation::class,'designation_id','id');
    }

    public function employee_salary()
    {
        return $this->hasMany(Emp_salary::class,'emp_id','id');
    }

    //start me

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'emp_id', 'id');
    }
    public function empToDailyRecord(){
        return $this->hasMany(UserDailyRecord::class,'emp_id');
    }
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    // One-to-Many relationship with SalaryComponent
    public function salaryComponents()
    {
        return $this->hasMany(SalaryComponent::class);
    }
}
