<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'company_id',
        'emp_id',
        'serial_no',
        'asset_name',
        'status',
        'asset_type_id',
        'asset_id',
        'purchase_date',
        'guarantee_date',
        'asset_price',
        'description',
        'image',
        'assigned_date',
        'disposed_date'
    ];
}
