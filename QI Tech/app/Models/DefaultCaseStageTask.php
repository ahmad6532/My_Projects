<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultCaseStageTask extends Model
{
    use HasFactory;

    public function documents() {
        return $this->hasMany(DefaultCaseStageTaskDocument::class,'default_case_stage_task_id');
    }

    public function deadline_records() {
        return $this->hasMany(task_deadline_records::class,'default_case_stage_tasks_id');  
    }

    public function stage(){
        return $this->belongsTo(DefaultCaseStage::class,'default_case_stage_id');
    }
}
