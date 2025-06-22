<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FishBoneRootCauseAnalysis extends Model
{
    protected $table = 'fish_bone_root_cause_analysis';
    use HasFactory;

    public function answers()
    {
        return $this->hasMany(FishBoneRootCauseAnalysisAnswer::class,'fish_bone_root_cause_analysis_id');
    }
}
