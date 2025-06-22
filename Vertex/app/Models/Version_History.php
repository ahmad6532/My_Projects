<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Version_History extends Model
{
    // use HasFactory;
    protected $table ='version_history';
    protected $fillable =
    [
        'version',
        'reason',
        'type'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    protected $hidden = [
        'created_at',
    ];
}
