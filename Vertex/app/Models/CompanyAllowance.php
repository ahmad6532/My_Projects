<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAllowance extends Model
{
    protected $table ='company_allowances';
    protected $fillable =
    [
        
        'allowance_name',
        'amount',
        'is_active',
        'is_deleted',
    ];
}
