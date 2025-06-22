<?php

namespace App\Models\Headoffices\Organisation;

use App\Models\HeadOfficeLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationGroup extends Model
{
    use HasFactory;

    protected $table= 'head_office_location_groups';

    public function group(){
        return $this->belongsTo(Group::class,'group_id');
    }
    public function location(){
        return HeadOfficeLocation::findMany($this->head_office_location_id);
    }
}
