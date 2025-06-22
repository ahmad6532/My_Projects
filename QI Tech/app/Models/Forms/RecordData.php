<?php

namespace App\Models\Forms;

use App\Models\DataRadact;
use App\Models\RecordDataEditedHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordData extends Model
{
    use HasFactory;

    protected $table = 'be_spoke_form_record_data';

    public function record(){
        return $this->belongsTo(Record::class,'record_id');
    }

    public function question(){
        return $this->belongsTo(StageQuestion::class, 'question_id');
    }
    public function updated_values()
    {
        return $this->hasMany(RecordDataEditedHistory::class,'record_data_id');
    }
    public function radact()
    {
        return $this->hasOne(DataRadact::class,'data_id');
    }

    

}
