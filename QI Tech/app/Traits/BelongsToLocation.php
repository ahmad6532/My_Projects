<?php

namespace App\Traits;

use App\Models\Location;
use Illuminate\Http\Request;
trait BelongsToLocation{

    public function location()
    {
        return $this->belongsTo(Location::class,'location_id');
    }
}