<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsaAction extends Model
{
    use HasFactory;
    protected $table = 'psa_actions';


    public function hasStaffMember($user_id){
        return PsaActionStaff::where('action_id', $this->id)->where('user_id', $user_id)->count();
    }
    public function staff(){
        return $this->hasMany(PsaActionStaff::class, 'action_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments(){
        return $this->hasMany(PsaActionComment::class,'action_id');
    }
    public function allUsers(){

    }
}
