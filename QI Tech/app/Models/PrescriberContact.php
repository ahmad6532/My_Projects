<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriberContact extends Model
{
    use HasFactory;
    public function linked_cases()
    {
        return $this->hasMany(CaseContact::class,'contact_id');
    }

    public function getCasesCountAttribute()
    {
        return count($this->linked_cases);
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class,'contact_id');
    }
}
