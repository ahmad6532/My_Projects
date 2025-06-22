<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CompanyDocuments extends Model
{
    protected $table = 'company_documents';
    protected  $fillable = [
        'id', 
        'company_id',
        'branch_id',
        'document_type', 
        'document_name', 
        'document_extension',
        'files', 
        'role_id', 
        'location', 
        'description', 
        'expiry_date', 
        'show_before_expire', 
        'status',
        'is_deleted',
        'created_at', 
        'updated_at'
    ];
}
