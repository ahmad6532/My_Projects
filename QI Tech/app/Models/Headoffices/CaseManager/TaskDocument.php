<?php

namespace App\Models\Headoffices\CaseManager;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDocument extends Model
{
    use HasFactory;

    protected $table = 'case_manager_case_task_documents';

    public function document(){
        return $this->belongsTo(Document::class,'document_id');
    }
}
