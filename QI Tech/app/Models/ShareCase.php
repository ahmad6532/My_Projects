<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCase extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getDurationOfAccessAttribute($value)
    {
        return Carbon::parse($value);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function sharedBy()
    {
        return $this->belongsTo(User::class,'shared_by');
    }
    public function case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }
    public function share_case_extension()
    {
        return $this->hasMany(ShareCaseRequestExtension::class,'share_case_id');
    }
    public function getExtensionAttribute()
    {
        return $this->share_case_extension->where('status',0);
    }
    public function documents(){
        return $this->hasMany(ShareCaseDocument::class,'share_case_id');
    }
    public function share_case_data_radact()
    {
        return $this->hasMany(ShareCaseDataRadact::class,'share_case_id');
    }
    public function logs()
    {
        return $this->hasMany(ShareCaseLog::class,'share_case_id');
    }
    public function communications()
    {
        return $this->hasMany(ShareCaseCommunication::class,'share_case_id');
    }
}
