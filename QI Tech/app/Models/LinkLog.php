<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkLog extends Model
{
    use HasFactory;

    public function link() {
        return $this->belongsTo(Link::class,'link_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
