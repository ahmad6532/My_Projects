<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppearanceSetting extends Model
{
    // use HasFactory;
    protected $table ='appearance_settings';
    protected $fillable =
    [
        
        'appearance_name',
        'navbar_heading_color',
        'navbar_background_color',
        'body_background_color',
        'primary_color',
        'side_menu_background_color',
        'side_menu_text_color',
        'heading_text_color',
        'sub_heading_text_color',
        'paragraph_text_color',
        'is_active',
        'is_deleted',
    ];
}
