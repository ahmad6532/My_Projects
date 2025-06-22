<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    // use HasFactory;

    protected $table = 'user_devices';
    protected $fillable = [
        'user_id',
        'manufacturer',
        'model',
        'platform',
        'serial',
        'uuid',
        'version',
        'app_version',
        'token',
        'status',
        'entry_date',
        'updated_at'
    ];
    public $timestamps = false;
    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
