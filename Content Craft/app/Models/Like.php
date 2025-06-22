<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'likeId';
    protected $table = 'likes';
    protected $fillable = [
        'userId',
        'articleId',
        'createdAt',
        'updatedAt',
    ];



    // Many likes belongs to an Article
    public function likesToArticles()
    {
        return $this->belongsTo(Article::class, 'articleId');
    }
}
