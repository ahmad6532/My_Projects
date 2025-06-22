<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    protected $fillable = ['user_id', 'action', 'timestamp','type','head_office_id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function comment()
{
    return $this->belongsTo(Comment::class, 'comment_id');
}

}
