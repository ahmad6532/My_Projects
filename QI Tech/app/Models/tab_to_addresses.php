<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tab_to_addresses extends Model
{
    use HasFactory;

    public function tag(){
        return $this->belongsTo(address_tags::class, 'tag_id');
    }
}
