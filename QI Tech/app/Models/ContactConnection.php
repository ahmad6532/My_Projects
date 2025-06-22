<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactConnection extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'connected_with_id', 'relation_type'];

    public function contact()
    {
        return $this->belongsTo(Contact::class,'contact_id');
    }

    public function connected_with()
    {
        return $this->belongsTo(Contact::class,'connected_with_id');
    }

}
