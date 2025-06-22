<?php

namespace App\Models\databases;

use App\Traits\BelongsToDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyList extends Model
{
    use HasFactory,BelongsToDatabase;
    protected $table="pharmacies_lists";
}
