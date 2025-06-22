<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GdprTag extends Model
{
    use HasFactory;
    public function gdpr_tag_remove_action() {
        return $this->hasOne(GdprTagRemoveAction::class,'gdpr_tag_id');
    }
    public function gdpr_form_fields(){
        return $this->hasMany(GdprFormField::class,'gdpr_tag_id');
    }
}
