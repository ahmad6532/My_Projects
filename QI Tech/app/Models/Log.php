<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected static $defaultType = 'normal';
    protected function type(): Attribute
    {
        return Attribute::make(
            set: fn ($value) =>(empty($value))?self::$defaultType:$value,
        );
    }
    // protected function user_role(): Attribute
    // {

    //     // return Attribute::make(
    //     //     set: fn ($value) =>(empty($value))?self::$defaultType:$value,
    //     // );
    // }

    public static function log($action,$user_id = null,$user_role = null,$type = null){
        $log = new self();
        $log->action = $action;
        $log->user_id = $user_id;
        $log->user_role = $user_role;
        $log->type = $type;
        $log->save();
    }
}
