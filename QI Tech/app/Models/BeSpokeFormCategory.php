<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeSpokeFormCategory extends Model
{
    use HasFactory;

    public function Location()
    {
        return $this->belongsTo(Location::class,'reference_id')->where('reference_type','location');
    }
    
    public function headOffice()
    {
        return $this->belongsTo(HeadOffice::class,'reference_id')->where('reference_type','head_office');
    }

    public function forms()
    {
        //return $this->hasMany()
    }
}
