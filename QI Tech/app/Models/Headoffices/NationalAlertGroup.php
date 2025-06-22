<?php

namespace App\Models\Headoffices;

use App\Models\Headoffices\Organisation\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalAlertGroup extends Model
{
    use HasFactory;

    protected $table = 'national_alert_groups';

    public function national_alert(){
        return $this->belongsTo(NationalAlert::class, 'national_alert_id'); 
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_id'); 
    }
}
