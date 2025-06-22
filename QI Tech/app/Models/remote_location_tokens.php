<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;
use Str;

class remote_location_tokens extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guraded = [];


}
