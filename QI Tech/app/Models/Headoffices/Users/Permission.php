<?php

namespace App\Models\Headoffices\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'head_office_permissions';

    public static $permissions = array(
        'manage_users'=> array(
            'add_users' => 'Add user to Head Office',
            'remove_users' => 'Remove user from Head Office',
            'block_users' => 'Can block/unblock users',
            'remove_user_profile_types' => 'Remove user profile types',
            'edit_default_user_profile_types' => 'Edit default user profiles types',
            'create_user_profile_types' => 'Create new user profile types'
        ),
        'manage_head_office_settings' => array(
            'can_access_settings' => 'Can Access Settings'
        ),
        'manage_locations' => array(
            'change_location_settings' => 'Can change settings',
            'can_remotely_access' => 'Can remotely access'
        ),
        'manager_access' => array(
            'assign_nickname' =>'Can assign nickname',
            'change_premises_account_setting' =>'Can change premises account settings',
            'change_premises_account_password' => 'Can change premises account password',
            'assign_to_groups' => 'Can assign to groups'
        ),
        'manage_case_manager' => array()

    );
    public static function allModules(){
        return array(
            'manage_users' => 'Manage Users',
            'manage_head_office_settings' => 'Head Office Settings',
            'manage_locations' => 'Locations',
            'manager_access' => 'Access',
            'manage_case_manager' => 'Case Manager'
        );
    }
    public static function getModuleTitle($key){
        if(isset(self::allModules()[$key])){
            return self::allModules()[$key];
        }else{
            throw new \Exception('Module not found.');
        }

    }
    public static function getModulePermissions($key){
        if(isset(self::$permissions[$key])){
            // dd(self::$permissions[$key]);
            return self::$permissions[$key];
        }else{
            return array();
        }
    }
}
