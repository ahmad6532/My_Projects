<?php

namespace App\Models;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedBespokeForm extends Model
{
    use HasFactory;

    function form() {
        return $this->belongsTo(Form::class,'be_spoke_form_id');
    }
    
    public function locations(){
        $location = Location::where('id',$this->location_id)->first();
        return $location;
    }
}
