<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class analytics extends Model
{



    protected $table="analytics";
    protected $primaryKey = 'id';
    public  $timestamps=false;

    use HasFactory;
}
