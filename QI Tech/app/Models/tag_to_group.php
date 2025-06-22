<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag_to_group extends Model
{
    use HasFactory;
    public function contact_group(){
        return $this->belongsTo(contact_groups::class,'group_id','id');
    }
}
