<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class near_miss_manager extends Model
{
    use HasFactory;


    public function headOffice(){
        return $this->belongsTo(HeadOffice::class);
    }

    public function category()
    {
        return $this->belongsTo(BeSpokeFormCategory::class, 'be_spoke_form_category_id');
    }

    public function settings(){
        return $this->hasMany(near_miss_settings::class,'near_miss_id');
    }
}
