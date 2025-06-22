<?php

namespace App\Models\Headoffices\Users;

use App\Models\HeadOffice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'head_office_user_profiles';

    public function user_profile_assign(){
        return $this->hasMany(UserProfileAssign::class,'user_profile_id');
    }
    public static $defaultProfiles = array(
            array(
                'profile_name' => 'Super User',
                'system_default_profile' => 1,
                'super_access' => 1
            )
        );
    public function hasPerm($name){
        $perm = Permission::where('name',$name)->first();
        if(!$perm){
            $perm = new Permission();
            $perm->name = $name;
            $perm->save();
        }
        return ProfilePermission::where('user_profile_id',$this->id)->where('permission_id',$perm->id)->first();
    }
    public function head_office(){
        return $this->hasOne(HeadOffice::class,'head_office_id');
    }

}
