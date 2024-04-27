<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'notificationId';
    protected $fillable = [
        'senderId',
        'receiverId',
        'createdAt',
        'updatedAt',
    ];
    // many notifications belongs to multiple users
    public function notificationsToUsers()
    {
        return $this->belongsTo(User::class, 'senderId');
    }
}
