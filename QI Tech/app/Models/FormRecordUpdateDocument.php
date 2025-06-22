<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRecordUpdateDocument extends Model
{
    protected $table = "be_spoke_form_record_update_documents";
    use HasFactory;

    public function document()
    {
        return $this->belongsTo(Document::class,'document_id');
    }
}
