<?php
namespace App\Models;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Location extends Model
{
    // use HasFactory;
    protected $table ='locations';
    protected $fillable =
    [
    'branch_name' ,
    'branch_id',
    'company_id',
    'country_id',
    'city_id',
    'is_deleted'
    ];
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


    //start me

    // public function leaves()
    // {
    //     return $this->hasMany(Leave::class, 'branch_id');
    // }
}
