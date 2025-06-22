<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class PsaActionComment extends Model
{
    use HasFactory;

    protected $table = 'psa_action_comments';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function canEditAndDelete(){
        $user = Auth::guard('web')->user();
        if($this->user_id != $user->id){
            return false;
        }
        $timestamp = $this->created_at->getTimestamp();
        $now = time();
        $secondsIn24Hours = 60 * 60 * 24;
        $difference = $now-$timestamp;
        if( $difference < $secondsIn24Hours){
            return true;
        }
        return false;
    }
}
