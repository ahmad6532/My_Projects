<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $table = 'history';
    protected $primaryKey = 'historyId';
    protected $fillable = [
        'planId',
        'userId',
        'createdAt',
        'updatedAt',
    ];
    // Many subscribed plans belongs to a single plan
    public function historyToPlan()
    {
        return $this->belongsTo(Plans::class, 'planId');
    }

    // Many histories belongs to a single user
    public function historyToUser()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
