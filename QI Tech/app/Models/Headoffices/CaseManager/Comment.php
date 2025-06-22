<?php

namespace App\Models\Headoffices\CaseManager;

use App\Helpers\Helper;
use App\Models\FormRecordUpdate;
use App\Models\HeadOfficeUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Comment extends Model
{
    use HasFactory;
    protected $table = 'case_manager_case_comments';
    public function replies(){
        return $this->hasMany(Comment::class,'parent_id');
    }

    public function views(){
        return $this->hasMany(CommentView::class,'comment_id');
    }
    public function documents(){
        return $this->hasMany(CommentDocument::class,'comment_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function case(){
        return $this->belongsTo(HeadOfficeCase::class,'case_id');
    }

    public function days_ago(){
        return Helper::time_elapsed_string(strtotime($this->created_at)). " ago";
    }

    public function allowedHtmlTags(){
        return array('a','h1','h2','h3','h4','h5','h6','span','b','<br>','<hr>','p','div','em','ul','li','ol','i','strong');
    }
    public function addIntoCommentViews(){
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if(!isset($user)){
            $headOffice = $this->case->case_head_office;
        }else{
            $headOffice = $user->selected_head_office;
        }
        $headOfficeUser = HeadOfficeUser::where('head_office_id',$headOffice->id)->where('user_id',$user->id)->first();
        $view = CommentView::where('head_office_user_id',$headOfficeUser->id)->where('comment_id',$this->id)->first();
        if($headOfficeUser->is_active == true && isset($view) && $view->is_seen == false){
            $view->is_seen = true;
            $view->save();
        }
        if(!$view){
            $view = new CommentView();
           
            $view->head_office_user_id = $headOfficeUser->id;
            $view->comment_id = $this->id;
            if($headOfficeUser->is_active == false){
                $view->is_seen = false;
            }
            $view->save();
        }
    }

    public function currentUserIsAuthor(){
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if($this->user_id == $user->id){
            return true;
        }
        return false;
    }

    public function is_all_seen(){
        $views = $this->views->where('is_seen',true);
        $ho_users = $this->case->case_head_office->users;
        if($views->count() == $ho_users->count()){
            return true;
        }
        return false;
    }

    public function record_update(){
        return $this->belongsTo(FormRecordUpdate::class,'record_update_id');
    }
}
