<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrganisationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'head_office_id',
        'name',
        'bg_color_code',
        'font', 
        'location_button_text_color', 
        'location_button_color', 
        'location_form_setting_color', 
        'location_section_heading_color'
    ];

    public function organisationSettingBespokeForms() {
        return $this->hasMany(OrganisationSettingBespokeForm::class,'o_s_id');
    }
    public function organisation_setting_logo() {
        if (file_exists(public_path('data_images/setting/logo/'.$this->id.'.png'))) {
            return '<img src="'.asset('data_images/setting/logo/'.$this->id.'.png').'" width="50" class="img-profile rounded-circle" />';
        }
        //$user = Auth::guard('web')->user();
        //return '<div class="rounded-circle action_person">'.$user->initials.'</div>';
        return "No Logo";
    }
    public function setting_logo() {
        if (file_exists(public_path('data_images/setting/logo/'.$this->id.'.png'))) {
            return asset('data_images/setting/logo/'.$this->id.'.png');
        }
        //$user = Auth::guard('web')->user();
        //return '<div class="rounded-circle action_person">'.$user->initials.'</div>';
        return null;
    }
    public function organisation_setting_bg_logo() {
        if (file_exists(public_path('data_images/setting/bg/'.$this->id.'.png'))) {
            return '<img src="'.asset('data_images/setting/bg/'.$this->id.'.png').'" width="50" class="img-profile rounded-circle" />';
        }
        //$user = Auth::guard('web')->user();
        //return '<div class="rounded-circle action_person">'.$user->initials.'</div>';
        return "No Logo";
    }
    public function setting_bg_logo() {
        if (file_exists(public_path('data_images/setting/bg/'.$this->id.'.png'))) {
            return asset('data_images/setting/bg/'.$this->id.'.png');
        }
        //$user = Auth::guard('web')->user();
        //return '<div class="rounded-circle action_person">'.$user->initials.'</div>';
        return null;
    }
    public function organisationSettingAssignments()
    {
        return $this->hasMany(OrganisationSettingAssignment::class,'o_s_id');
    }
}
