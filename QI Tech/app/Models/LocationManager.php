<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationManager extends Model
{
    use HasFactory;

    protected $table = 'location_managers';

    public function location(){
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
