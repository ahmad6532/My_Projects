<?php

namespace App\Models\Headoffices\Organisation;

use App\Models\HeadOffice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagCategory extends Model
{
    use HasFactory;

    protected $table = 'head_office_orginisation_tag_categories';

    public function head_office(){
        return $this->belongsTo(HeadOffice::class, 'head_office_id');
    }
    public function tags(){
        return $this->hasMany(Tag::class,'category_id');
    }
    public function alreadyAssignToLocation($head_office_location){
        $tags = $this->tags;
        if(count($tags)){
            foreach($tags as $tag){
               $count =  LocationTag::where('head_office_location_id',$head_office_location)->where('tag_id',$tag->id)->count();
               if($count){
                # One tag is assigned to the location.
                return true;
               }
            }
           
        }   
        return false;
    }
}
