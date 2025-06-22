<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Forms\QuestionGroup;
class FormStage extends Model
{
    use HasFactory;


    protected $table="be_spoke_form_stages";

    public function form(){
        return $this->belongsTo(Form::class,'form_id');
    }
    public function groups(){
        return $this->hasMany(QuestionGroup::class,'stage_id');
    }

}
