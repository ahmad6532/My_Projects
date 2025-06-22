<?php

namespace App\Models;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Branch extends Model
{
    // use HasFactory;
    protected $table ='branches';
    protected $fillable =
    [
    'branch_name' ,
    'branch_id',
    'company_id',
    'country_id',
    'city_id',
    'is_deleted'
    ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }
    public function employee_details(){
        return $this->hasmany(EmployeeDetail::class);
    }
    public function employee_details_with_attendance(){
        return $this->hasmany(EmployeeDetail::class,'branch_id','id');
    }
    public function users(){
        return $this->hasmany(User::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id','country_id');
    }
    public function city(){
        return $this->belongsTo(City::class, 'city_id','city_id');
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'branch_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id','id');
    }
    public function resignation(){
        return $this->hasmany(EmployeeResignation::class);
    }
}