<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LfpseOption extends Model
{
    use HasFactory;

    public $fillable = ['code', 'val', 'collection_name', 'version'];
}
