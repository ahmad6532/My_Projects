<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $primaryKey = 'feedbackId';
    protected $table = 'feedbacks';
    protected $fillable = [
        'feedback',
        'customerId',
        'riderId',
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
