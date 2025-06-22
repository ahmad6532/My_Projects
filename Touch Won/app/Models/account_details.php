<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account_details extends Model
{
    protected $table = 'account_details';
    protected $primaryKey = 'account_id';
    public $timestamps = false;

    protected $casts = [
        'player_id' => 'int',
        'is_deleted' => 'bool',
        'points' => 'int',
        'credits' => 'int',
        'is_active' => 'bool'
    ];

    protected $dates = [
        'created_on',
        'updated_on',
        'last_login_credit'
    ];

    protected $fillable = [
        'vendor_id',
        'player_PIN',
        'player_id',
        'is_verified',
        'is_deleted',
        'points',
        'credits',
        'created_on',
        'updated_on',
        'is_active',
        'last_login_credit'
    ];
    use HasFactory;
}
