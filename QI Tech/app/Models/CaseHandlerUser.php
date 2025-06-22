<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaseHandlerUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }
    public function case_head_office_user()
    {
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
}
