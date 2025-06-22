<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiveWhysRootCauseAnalysis extends Model
{
    protected $table = 'five_whys_root_cause_analysis';
    use HasFactory;
    public function answers()
    {
        return $this->hasOne(FiveWhysRootCauseAnalysisAnswer::class,'five_whys_root_cause_analysis_id');
    }
}
