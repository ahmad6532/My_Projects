<?php

namespace App\Models\Headoffices\CaseManager;

use App\Models\HeadOfficeUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssign extends Model
{
    use HasFactory;

    protected $table = 'case_manager_case_task_assigns';

    public function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
}
