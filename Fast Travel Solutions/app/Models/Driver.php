<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    // Use guarded to specify fields that are not mass assignable
    protected $guarded = []; // This means all fields are mass assignable
}
