<?php

namespace App\Models;

use App\Models\Forms\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalenderEvent extends Model
{
    use HasFactory;

    protected $table = 'calender_events';
    protected $guarded = [];


    public function scheduleByDate()
    {
        return $this->belongsTo(Form::class, 'id');
    }
}
