<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationPasswordUpdateRequest extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'location_password_update_requests';

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
                  'new_password',
                  'user_id',
                  'location_id',
                  'token'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the location for this model.
     *
     * @return App\Models\Location
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Location','location_id');
    }

    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }



}
