<?php

namespace App\Models;

use App\Models\Headoffices\Organisation\Group;
use App\Models\Headoffices\Organisation\LocationGroup;
use App\Models\Headoffices\Organisation\LocationTag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HeadOffice;
use App\Models\Location;
class HeadOfficeLocation extends Model
{
    use HasFactory;

    protected $table = 'head_office_locations';

    public function location(){
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function head_office(){
        return $this->belongsTo(HeadOffice::class, 'head_office_id');
    }

    public function groups(){
        return $this->hasMany(LocationGroup::class,'head_office_location_id');
    }
    public function group(){
        return $this->hasOne(LocationGroup::class,'head_office_location_id');
    }
    public function tags(){
        return $this->hasMany(LocationTag::class,'head_office_location_id');
    }

    public function findGroup($ids){
        return Group::whereIn('id', $ids)->pluck('group')->toArray();
    }

    public function org_settings(){
        $org_setting_assigned = OrganisationSettingAssignment::where('location_id',$this->location_id)->first();
        if(isset($org_setting_assigned)){
            $org_settings = OrganisationSetting::where('id',$org_setting_assigned->o_s_id)->first();
            return $org_settings;
        }
    }

    public function comments(){
        return $this->hasMany(location_comments::class,'ho_location_id');
    }

}
