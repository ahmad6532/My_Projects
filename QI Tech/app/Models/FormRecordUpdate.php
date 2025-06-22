<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRecordUpdate extends Model
{
    Protected $table = "be_spoke_form_record_updates";
    use HasFactory;
    public function documents()
    {
        return $this->hasMany(FormRecordUpdateDocument::class,'be_spoke_form_record_update_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
