<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'id';
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'gender',
        'address',
        'phone',
        'uuid',
        'country',
        'postalCode',
        'managerId',
        'avatar',
        'createdAt',
        'updatedAt',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
       // A user belong to one manager
   public function userToManager()
    {
        return $this->belongsTo(User::class, 'managerId');
    }
    // A manager can have many users
    public function managerToUsers()
    {
        return $this->hasMany(User::class, 'managerId');
    }
    // A user have many articles
    public function articles()
    {
        return $this->hasMany(Article::class, 'userId');
    }
    // A user have many articles
    public function articlesToUsers()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    // A user has one subscription plan
    public function userToPlan()
    {
        return $this->hasOne(Subscriptions::class, 'userId');
    }

    // A user has multiple plan in history
    public function userToHistory()
    {
        return $this->hasMany(History::class, 'userId');
    }
    // A user can send multiple notifications
    public function userToNotifications()
    {
        return $this->hasMany(Notification::class, 'senderId');
    }
}