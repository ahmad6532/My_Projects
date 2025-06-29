<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedLocationLocationUser extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function is_location()
    {
        return true;
    }
    public function location()
    {
        return $this->belongsTo(Location::class,'location_id');
    }
}
