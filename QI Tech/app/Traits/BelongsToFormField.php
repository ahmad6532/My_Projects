<?php

namespace App\Traits;

use App\Models\forms\FormField;

trait BelongsToFormField{

    public function form_field()
    {
        return $this->belongsTo(FormField::class,'form_field_id');
    }
}