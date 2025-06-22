<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table ='com_countries';
    protected $fillable =
    [
        
        'country_name',
        'sortname',
        'phonecode',
        'is_deleted',
    ];

   
    public function Branche(){
        return $this->hasMany(Location::class, 'country_id','country_id');
    }
    public function companies()
    {
        return $this->hasMany(Company::class, 'country_id', 'id');
    }
}
