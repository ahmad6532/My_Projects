<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxYear extends Model
{
    use HasFactory;
    protected $fillable = [
        'from_year',
        'to_year',
        'country_id',
    ];
    // a year has multiple slabs
    public function yearToSlabs(){
        return $this->hasMany(TaxSlab::class, 'year_id');
    }
    // a year belongs to a country
    public function yearToCountry(){
    return $this->belongsTo(ComCountries::class, 'country_id');
    }
}
