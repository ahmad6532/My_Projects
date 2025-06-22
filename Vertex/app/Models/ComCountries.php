<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComCountries extends Model
{
    use HasFactory;
    protected $primaryKey = 'country_id';

    public function countryToTaxYear(){
        return $this->hasMany(TaxYear::class, 'country_id');
    }
}
