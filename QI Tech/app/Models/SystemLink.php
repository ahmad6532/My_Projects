<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'random',
        'is_active',
        'case_id',
        'comment_id'
    ];

    public function comment(){
        return Comment::where('id',$this->comment_id)->first();
    }

    public function link_access_log(){
        return $this->hasMany(link_access_logs::class,'link_id');
    }
}
