<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetTypes extends Model
{
    use HasFactory;

    protected $table = 'asset_types';
    protected $fillable = [
        'name'
    ];
}
