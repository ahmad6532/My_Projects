<?php

namespace App\Models\Headoffices\Organisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory;

    protected $table = 'head_office_organisation_groups';

    public function children(){
        return $this->hasMany(Group::class,'parent_id')->with('children');
    }
    public function parent(){
        return $this->belongsTo(Group::class,'parent_id','id')->with('parent');
    }
    public function location_groups(){
        return $this->hasMany(LocationGroup::class,'group_id');
    }
    public static function generateParentsArray($parent_id){
        $g = self::where('id',$parent_id)->first(['id','parent_id']);
        $ids = array();
        while($g){
            array_push($ids, $g->id);
            $g = self::where('id',$g->parent_id)->first(['id','parent_id']);
            
        }
       return array_reverse($ids);
    }
    public static function generateParentsArrayFromNode($node_id) {
        $parents = [];
    
        $node = self::find($node_id);
        if ($node) {
            $parent = self::find($node->parent_id);
            if ($parent) {
                $parents = array_merge($parents, self::generateParentsArray($parent->id));
            }
    
            $parents[] = $node->id;
        }
    
        return $parents;
    }
    public static function maximumDepthOfLevels($parent_id = null, $level = 0) {
        $max_depth = $level;
        $headOffice = Auth::guard('web')->user()->selected_head_office;
        $groups = Group::where('parent_id', $parent_id)->where('head_office_id', $headOffice->id)->get();
    
        foreach ($groups as $group) {
            $child_depth = self::maximumDepthOfLevels($group->id, $level + 1);
            $max_depth = max($max_depth, $child_depth);
        }
    
        return $max_depth;
    }

    // Chat gpt thank you code for helping
    public static function deleteNodeAndChildren($parent_id) {
        $groups = self::where('parent_id', $parent_id)->get();
        foreach($groups as $g) {
            self::deleteNodeAndChildren($g->id);
        }
        self::where('id', $parent_id)->delete();
}

}
