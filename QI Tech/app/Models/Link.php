<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $dates = ['date_to_be_removed'];

    public function link_logs() {
        return $this->hasMany(LinkLog::class,'link_id');
    }
    public function link_case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'head_office_case_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
