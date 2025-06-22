<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
trait BelongsToUser{

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}