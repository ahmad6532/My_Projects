<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'articleId';
    protected $table = 'articles';
    protected $fillable = [
        'title',
        'content',
        'userId',
        'createdAt',
        'updatedAt',
    ];

    // Many Articles belongs to a user
    public function articlesToUser()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    // Articlea has many Likes
    public function articleToLikes()
    {
        return $this->hasMany(Like::class, 'articleId');
    }

    
}
