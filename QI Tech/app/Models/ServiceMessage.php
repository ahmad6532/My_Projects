<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceMessage extends Model
{
    
    use SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'service_messages';

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
                  'title',
                  'message',
                  'send_to',
                  'countries',
                  'duration',
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
               'deleted_at',
                'created_at',
           ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getReceiverListAttribute(){
        //dd(json_decode($this->send_to));
        return json_decode($this->send_to);
    }
    public function getCountryListAttribute(){
        return json_decode($this->countries);
    }
    public function getDurationExpiryAttribute(){
        return Carbon::parse($this->updated_at)->addDays($this->duration);
          }



}
