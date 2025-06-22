<?php

namespace App\Models;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultCaseStage extends Model
{
    use HasFactory;
    public function default_tasks()
    {
        return $this->hasMany(DefaultCaseStageTask::class,'default_case_stage_id');
    }

    public function form(){
        return $this->belongsTo(Form::class,'be_spoke_form_id');
    }
}

