<?php

namespace App\Models\Headoffices\CaseManager;

use App\Models\Forms\Record;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeLinkedCase extends Model
{
    use HasFactory;

    protected $table = 'head_office_linked_cases';

    public function head_office_case()
    {
        return $this->belongsTo(HeadOfficeCase::class);
    }

    public function incident()
    {
        return $this->belongsTo(Record::class, 'be_spoke_form_record_id');
    }
}
