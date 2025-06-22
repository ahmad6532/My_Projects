<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceManagement extends Model
{
    // use HasFactory;
    protected $table = 'devices';
    protected $fillable = ['company_id','branch_id','device_name','device_model','asset_no','expiry_date','device_type','enrolled_users','device_ip','serial_number','heartbeat','status'];

//  each device belongs to a location
public function deviceToLocation(){
    return $this->belongsTo(Location::class,'branch_id');
}

//  each device belongs to a company
public function deviceToCompany(){
    return $this->belongsTo(Company::class,'company_id');
}

//  each device belongs to a type
public function deviceToType(){
    return $this->belongsTo(DeviceType::class,'device_type_id');
}

// a device has many zkrole emp
public function deviceToZkRoledEmp(){
    return $this->hasMany(ZkRoledEmployee::class,'device_id');
}
// a device has many history record
public function deviceToHistory(){
    return $this->hasMany(DeviceStatusHistory::class,'device_id');
}
}
