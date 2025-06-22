<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    // use HasFactory;
    protected $table = 'emp_documents';
    protected $fillable = [
        'emp_id',
        'document_path',
        'title',
        'discription',
    ];
    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'emp_id', 'id');
    }


    public function docToName(){
        return $this->belongsTo(DocumentsNames::class,'name_id');
    }
}
