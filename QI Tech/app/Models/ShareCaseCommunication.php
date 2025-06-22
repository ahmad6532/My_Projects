<?php

namespace App\Models;

use App\Helpers\Helper;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCaseCommunication extends Model
{
    use HasFactory;
    public function documents(){
        return $this->hasMany(ShareCaseCommunicationDocument::class,'share_case_communication_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function replies(){
        return $this->hasMany(ShareCaseCommunication::class,'parent_id');
    }

    public function views(){
        return $this->hasMany(share_case_communications_views::class,'comment_id');
    }

    public function currentUserIsAuthor(){
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if($this->user_id == $user->id){
            return true;
        }
        return false;
    }
    public function days_ago(){
        return Helper::time_elapsed_string(strtotime($this->created_at)). " ago";
    }

    public function addIntoCommentViews(){
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if(!isset($user->selected_head_office)){
            $headOffice = $this->share_case->case->case_head_office;
        }else{
            $headOffice = $user->selected_head_office;
        }
        $headOfficeUser = HeadOfficeUser::where('head_office_id',$headOffice->id)->where('user_id',$user->id)->first();
        $view = share_case_communications_views::where('head_office_user_id',$headOfficeUser->id)->where('comment_id',$this->id)->first();
        if($headOfficeUser->is_active == true && isset($view) && $view->is_seen == false){
            $view->is_seen = true;
            $view->save();
        }
        if(!$view){
            $view = new share_case_communications_views();
           
            $view->head_office_user_id = $headOfficeUser->id;
            $view->comment_id = $this->id;
            if($headOfficeUser->is_active == false){
                $view->is_seen = false;
            }
            $view->save();
        }
    }

    public function share_case(){
        return $this->belongsTo(ShareCase::class,'share_case_id');
    }

    public function allowedHtmlTags(){
        return array('a','h1','h2','h3','h4','h5','h6','span','b','<br>','<hr>','p','div','em','ul','li','ol','i','strong');
    }

    public function is_all_seen(){
        $user = Auth::guard('web')->user() ?? Auth::guard('user')->user();
        if(!isset($user->selected_head_office)){
            $headOffice = $this->share_case->case->case_head_office;
        }else{
            $headOffice = $user->selected_head_office;
        }
        $views = $this->views->where('is_seen',true);
        $ho_users = $headOffice->users;
        if($views->count() == $ho_users->count()){
            return true;
        }
        return false;
    }
}
