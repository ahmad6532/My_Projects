<?php

namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class new_contact_comments extends Model
{
    use HasFactory;
    public function addIntoCommentViews(){
        $user = Auth::guard('web')->user();
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $headOfficeUser = HeadOfficeUser::where('head_office_id',$headOffice->id)->where('user_id',$user->id)->first();
        $view = new_contact_comments_views::where('head_office_user_id',$headOfficeUser->id)->where('comment_id',$this->id)->first();
        if(!$view){
            $view = new new_contact_comments_views();
           
            $view->head_office_user_id = $headOfficeUser->id;
            $view->comment_id = $this->id;
            $view->save();
        }
    }

    public function currentUserIsAuthor(){
        $user = Auth::guard('web')->user();
        if($this->user_id == $user->id){
            return true;
        }
        return false;
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function days_ago(){
        return Helper::time_elapsed_string(strtotime($this->created_at)). " ago";
    }
    public function views(){
        return $this->hasMany(new_contact_comments_views::class,'comment_id');
    }
    public function documents(){
        return $this->hasMany(new_contact_documents::class,'comment_id');
    }

    public function allowedHtmlTags(){
        return array('a','h1','h2','h3','h4','h5','h6','span','b','<br>','<hr>','p','div','em','ul','li','ol','i','strong');
    }
    public function replies(){
        return $this->hasMany(new_contact_comments::class,'parent_id');
    }
}
