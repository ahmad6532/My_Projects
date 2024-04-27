<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'subscriptionId';
    protected $table = 'subscriptions';
    protected $fillable = [
        'userId',
        'planId',
        'status',
        'articles',
        'createdAt',
        'updatedAt',
    ];
}
