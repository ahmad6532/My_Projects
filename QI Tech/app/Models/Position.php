<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    
    protected $table = 'positions';

    protected $primaryKey = 'id';

    protected $fillable = [
                  'name'
              ];

    protected $dates = [];
    
    protected $casts = [];
    



}
