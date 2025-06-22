<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Company extends Model
{
    protected $table ='companies';
    protected $fillable =
    [
        'company_name',
        'email',
        'phone_number',
        'phone',
        'landline',
        'contact_person',
        'country_id',
        'city_id',
        'address',
        'logo',
        'website',
        'is_active',
        'is_deleted',
        'state',
    ];

    protected $hidden = [
        'country_id', 'city_id',
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

    public function branches(){
        return $this->hasmany(Location::class);
    }

    public function leaveSettings()
    {
        return $this->hasMany(Leave_setting::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
