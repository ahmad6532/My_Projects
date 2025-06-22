<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;


class module extends Model
{
    protected $table ='modules';
    protected $fillable =
    [
        'name',
    ];
    // use HasFactory;
    use HasRoles;
    public function modulesPermission()
    {
        return $this->hasMany(Permission::class,'module_id','id');
    }
}
