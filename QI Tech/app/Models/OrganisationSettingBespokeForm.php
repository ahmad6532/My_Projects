<?php

namespace App\Models;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationSettingBespokeForm extends Model
{
    use HasFactory;

    public function form()
    {
        return $this->belongsTo(Form::class, 'be_spoke_form_id');

    }
    public function organisaitonSetting()
    {
        return $this->belongsTo(OrganisationSetting::class,'o_s_id');
    }
}