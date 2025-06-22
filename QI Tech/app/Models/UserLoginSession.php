<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'ip', 'country', 'city', 'lat', 'long', 'browser', 'location_user_id','head_office_id','location_id'
    ];

    protected $dates = [];

    protected $casts = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
