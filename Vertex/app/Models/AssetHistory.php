<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetHistory extends Model
{
    use HasFactory;

    protected $table = 'asset_history';
    protected $fillable = ['type','user_id','msg','status','asset_id'];
    protected $guarded = ['id'];

    public function user(){
        return $this->hasone(User::class,'id','user_id');
    }
}
