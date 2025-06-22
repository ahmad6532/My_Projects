<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const CREATED_AT='createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey ='id';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'riderId',
        'password',
        'role',
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
    public function customer()
    {
        return $this->hasOne(User::class, 'riderId');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'riderId');
    }
    
    // Order Table Relation
    public function order(){
        return $this->hasMany(Order::class,'customerId');
    }

    // Order Table Relation
    public function orders()
    {
        return $this->hasMany(Order::class, 'riderId');
    }


    // feedback Table Relation
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'customerId');
    }

    // feedback Table Relation
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'riderId');
    }



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
