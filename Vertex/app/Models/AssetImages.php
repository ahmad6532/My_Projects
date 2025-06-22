<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetImages extends Model
{
    use HasFactory;

    protected $table = 'asset_images';

    protected $fillable = [
        'asset_id',
        'image_url'
    ];
}
