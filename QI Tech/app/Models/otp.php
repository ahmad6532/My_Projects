<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otp extends Model
{
    use HasFactory;

    protected $table = 'otp';

    protected $fillable = [
        'user_id',
        'user_type',
        'otp_code',
        'otp_created_at',
        'otp_expires_at',
        'otp_retries',
        'isVerified',
        'isEnabled'
    ];

    public function otp_user()
    {
        return $this->morphTo();
    }


    public function generate_code(){
        $this->otp_code = rand(100000,999999);
        $this->otp_retries = 3;
        $this->isEnabled = true;
        $this->otp_created_at = Carbon::now();
        $this->otp_expires_at = Carbon::now()->addMinutes(10) ;
    }

    public function reset_code(){
        $this->otp_code = null;
        $this->otp_created_at = null;
        $this->otp_expires_at = null;
        $this->save();
    }

    public function otp_time_left()
    {
        $current_time = now();
        $expires_at = Carbon::parse($this->otp_expires_at); 
        if ($current_time > $expires_at) {
            return 0; 
        }
        $differenceInSeconds = $expires_at->diffInSeconds($current_time);
            $tenMinutesInSeconds = 10 * 60;
        if ($differenceInSeconds > $tenMinutesInSeconds) {
            return 0; 
        }
    
        return $differenceInSeconds;
    }
    
}
