<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'orderId';
    protected $fillable = [
        'productName',
        'quantity',
        'customerId',
        'riderId',
        'status',
        'createdAt',
        'updatedAt',
    ];

    // Customer  Relation
    public function customers()
    {
        return $this->belongsTo(User::class, 'customerId');
    }
    // Rider  Relation
    public function riders()
    {
        return $this->belongsTo(User::class, 'riderId');
    }
    
}
