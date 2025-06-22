<?php

namespace App\Models;

use App\Models\Forms\RecordData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordDataEditedHistory extends Model
{
    use HasFactory;
    public function record_data()
    {
        return $this->belongsTo(RecordData::class,'record_data_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}
