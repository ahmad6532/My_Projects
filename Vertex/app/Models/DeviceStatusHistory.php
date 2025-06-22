<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceStatusHistory extends Model
{
    use HasFactory;
    protected $table = 'devices_status_history';
    protected $fillable = [
        'device_id',
        'from_date',
        'to_date',
        'sync_date',
        'offline'
    ];

    public function historyToDevice(){
        return $this->belongsTo(DeviceManagement::class,'device_id');
    }
}
