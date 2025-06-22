<?php

namespace App\Models\Headoffices\Organisation;

use App\Models\HeadOfficeLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationTag extends Model
{
    use HasFactory;

    protected $table = 'head_office_location_tags';

    public function tag(){
        return $this->belongsTo(Tag::class,'tag_id');
    }
    public function head_office_location(){
        return $this->belongsTo(HeadOfficeLocation::class,'head_office_location_id');
    }
}

