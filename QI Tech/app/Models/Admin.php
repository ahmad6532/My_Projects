<?php

namespace App\Models;

use App\Models\databases\Database;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'first_name',
                  'surname',
                  'mobile_no',
                  'email',
                  'password',
                  'is_active'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['password_updated_at'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->surname;
    }

    public function getDatabaseAttribute()
    {
        return $this->hasMany(Database::class,'admin_id');
    }
}
