<?php

namespace App\Models\Headoffices\Organisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'head_office_organisation_tags';

    public function category(){
        return $this->belongsTo(TagCategory::class,'category_id');
    }
    public function location_tags(){
        return $this->hasMany(LocationTag::class,'tag_id');
    }
    public function isAlreadyAssigned($head_office_location){
        if(LocationTag::where('head_office_location_id',$head_office_location)->where('tag_id',$this->id)->count()){
            return true;
        }
        return false;
    }
}
