<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeUserBankHolidaySelection extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'h_o_user_bank_holiday_selections';
    use HasFactory;
    protected $fillable = [
        'head_office_user_id',
        'reference_id',
        'name',
        'date',
        'is_working',
    ];

    protected $dates = ['date'];
}
