<?php

namespace App\Models;

use App\Models\Headoffices\CaseManager\HeadOfficeCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class linked_cases extends Model
{
    use HasFactory;
    protected $fillable = [
        'head_office_id',
        'case_id_1',
        'case_id_2',
        'message',
        'linked_manually',
    ];

    /**
     *
     *
     * @param int $currentCaseId
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function otherCase($currentCaseId)
    {
        if ($this->case_id_1 == $currentCaseId) {
            return $this->belongsTo(HeadOfficeCase::class, 'case_id_2');
        } elseif ($this->case_id_2 == $currentCaseId) {
            return $this->belongsTo(HeadOfficeCase::class, 'case_id_1');
        }

        return null; // Or throw an exception if necessary
    }
}
