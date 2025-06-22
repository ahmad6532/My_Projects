<?php

namespace App\Models\Headoffices\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\head_office_access_rights;

class AccessRight extends Model
{
    use HasFactory;

    protected $table = 'head_office_user_access_rights';

    public function head_office_access_rights (){
        
         return $this->hasOne(head_office_access_rights::class, 'custom_access_rights_id');
    }

    public function hasPerm($name){
        $perm = Permission::where('name',$name)->first();
        if(!$perm){
            $perm = new Permission();
            $perm->name = $name;
            $perm->save();
        }
        return AccessRightPermission::where('access_rights_id',$this->id)->where('permission_id',$perm->id)->first();
    }
}
