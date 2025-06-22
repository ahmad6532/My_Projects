<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxSlab extends Model
{
    use HasFactory;
    protected $fillable = [
        'year_id',
        'start_range',
        'end_range',
        'fixed_amount',
        'amount_exceed',
        'tax_percent',
        'is_deleted'
    ];

    // Each slab belongs to a year
    public function slabToYear(){
        return $this->belongsTo(TaxYear::class, 'year_id');
    }
}
