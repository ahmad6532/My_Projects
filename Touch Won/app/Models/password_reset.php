<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class password_reset extends Model
{

    use HasFactory;

    public $table="password_reset";
    public $timestamps=false;
    protected $primaryKey='mail';

    protected $fillable=[
       'vendor_id',
        'mail',
      'token',
      'created_at'
    ];
}
