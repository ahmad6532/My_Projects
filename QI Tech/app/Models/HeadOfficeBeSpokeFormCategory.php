<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeBeSpokeFormCategory extends Model
{
    use HasFactory;

    public function beSpokeFromCategory()
    {
        return $this->belongsTo(BeSpokeFormCategory::class,'b_s_f_c_id')->where('reference_type','head_office');
    }
}
