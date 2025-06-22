<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationOpeningHours extends Model
{
    use HasFactory;
    protected $table = 'location_opening_hours'; 

    /*
    * Return true, if any weekday is seleted.
    */
    public function set(){
        if($this->open_monday || $this->open_tuesday || $this->open_wednesday ||$this->open_thursday ||
        $this->open_friday || $this->open_saturday || $this->open_sunday){
            return true;
        }
        return false;
    }
}
