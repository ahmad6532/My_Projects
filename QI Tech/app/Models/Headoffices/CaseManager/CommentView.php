<?php

namespace App\Models\Headoffices\CaseManager;

use App\Models\HeadOfficeUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentView extends Model
{
    use HasFactory;

    protected $table = 'case_manager_case_comment_views';
    public function head_office_user(){
        return $this->belongsTo(HeadOfficeUser::class,'head_office_user_id');
    }
  
}
