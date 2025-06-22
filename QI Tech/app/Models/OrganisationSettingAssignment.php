<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationSettingAssignment extends Model
{
    use HasFactory;

    public function organization_setting()
    {
        return $this->belongsTo(OrganisationSetting::class,'o_s_id');
    }
    public function location() {
        return $this->belongsTo(Location::class,'location_id');
    }
}
