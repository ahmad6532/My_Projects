<?php

namespace App\Models\Headoffices\Organisation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $table= 'head_office_orginisation_levels';

    public static function generateLevels($depth, $headOffice){
        $to_return = array();
        for($i = 1; $i <= $depth; $i++ ){
            $level = self::where('level_number',$i)->where('head_office_id',$headOffice->id)->first();
            if(!$level){
                $level = new self();
                $level->level_number = $i;
                $level->head_office_id = $headOffice->id;
                $level->level_name = 'Lv Name';
                $level->save();
            }
            $to_return[$i] = $level;
        }
        return $to_return;
    }
}
