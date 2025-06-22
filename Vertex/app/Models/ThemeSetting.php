<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    // use HasFactory;
    protected $table = "theme_setting";
    protected $fillable =
    [

        'theme_name',
        'navbar_heading_color',
        'navbar_background_color',
        'body_background_color',
        'primary_color',
        'sidebar_background_color',
        'sidebar_text_color',
        'heading_color',
        'sub_heading_text_color',
        'paragraph_text_color',
        'is_active',
        'is_delete',
    ];
}
