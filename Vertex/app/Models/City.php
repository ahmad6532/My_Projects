<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table ='com_cities';
    protected $fillable =
    [
        
        'country_id',
        'city_name',
        'state_id',
        'is_deleted',
    ];
    public function Branche(){
        return $this->hasMany(Location::class, 'city_id','city_id');
    }
    public function companies()
    {
        return $this->hasMany(Company::class, 'country_id', 'id');
    }
}
