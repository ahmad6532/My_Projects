<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave_Type extends Model
{
    // use HasFactory;
    protected $table ='leave_types';
    protected $fillable =
    [
    'types',
    'type_index',
    'is_deleted'
    ];

    // start me

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'leave_type');
    }
}
