<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    // use HasFactory;
    protected $table ='emp_account_details';
    protected $fillable =
    [
    'emp_id' ,
    'bank_name',
    'account_no',
    'swift_code',
    'is_deleted',
    'branch_name',
    'iban_code',
    'acc_holder_name'
    ];
}
