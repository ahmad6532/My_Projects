<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class near_miss_settings extends Model
{
    use HasFactory;

    public function near_miss(){
        return $this->belongsTo(near_miss_manager::class);
    }
    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
}
