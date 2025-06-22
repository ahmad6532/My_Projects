<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationQuickLogin extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'location_quick_logins';

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
                  'user_id',
                  'location_id',
                  'pin'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_login_at'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }

    /**
     * Get the location for this model.
     *
     * @return App\Models\Location
     */
    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class,'location_id');
    }


}
