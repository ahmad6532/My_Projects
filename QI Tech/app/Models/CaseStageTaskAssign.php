<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStageTaskAssign extends Model
{
    use HasFactory;
    public function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
    public function task(){
        return $this->belongsTo(CaseStageTask::class,'task_id');
    }
    public function assigned() {
    return $this->hasMany(CaseStageTaskAssign::class, 'task_id');
    }
}
