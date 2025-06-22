<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stage_case_handler extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function case_handler(){
        return $this->belongsTo(CaseHandlerUser::class,'case_handler_id');
    }
}
