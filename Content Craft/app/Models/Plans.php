<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'planId';
    protected $table = 'plans';
    protected $fillable = [
        'name',
        'articles',
        'amount',
        'createdAt',
        'updatedAt',
    ];
    // A plan can be multiple in history
    public function planToHistory(){
        return $this->hasMany(History::class,'planId');
    }
}
