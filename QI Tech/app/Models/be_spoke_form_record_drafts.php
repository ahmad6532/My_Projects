<?php

namespace App\Models;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class be_spoke_form_record_drafts extends Model
{
    use HasFactory;

    public function form(){
        return $this->belongsTo(Form::class,'form_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function location(){
        return $this->belongsTo(Location::class,'location_id');
    }
}
