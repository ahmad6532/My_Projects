<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupBanner extends Model
{
    protected $table ='pop-up-banners';
    protected $fillable =
    [
        'image',
        'type',
        'start_time',
        'end_time',
        
    ];
    // use HasFactory;
}
