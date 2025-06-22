<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RootCauseAnalysis extends Model
{
    protected $table = 'root_cause_analysis'; 
    use HasFactory;

    public function getRootCauseAnalysisTypeAttribute()
    {
        return $this->type == 'five_whys' ? '5 Whys' : 'Fish Bone';
    }
    public function fish_bone_questions()
    {
        return $this->hasMany(FishBoneRootCauseAnalysis::class,'root_cause_analysis_id');
    }
    public function five_whys_questions()
    {
        return $this->hasMany(FiveWhysRootCauseAnalysis::class,'root_cause_analysis_id');
    }
    public function root_cause_analysis_case()
    {
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'completed_by');
    }
}
