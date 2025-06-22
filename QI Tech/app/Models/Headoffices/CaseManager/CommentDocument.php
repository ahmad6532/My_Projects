<?php

namespace App\Models\Headoffices\CaseManager;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentDocument extends Model
{
    use HasFactory;
    protected $table = 'case_manager_case_comment_documents';

    public function document(){
        return $this->belongsTo(Document::class,'document_id');
    }

    public function doc_get(){
        return Document::find($this->document_id);
    }
}
