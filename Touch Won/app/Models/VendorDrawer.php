<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VendorDrawer
 *
 * @property int $drawer_id
 * @property int $vendor_id
 * @property Carbon|null $drawer_started_on
 * @property Carbon|null $drawer_ended_on
 * @property int $is_active
 *
 * @package App\Models
 */
class VendorDrawer extends Model
{
	protected $table = 'vendor_drawer';
	protected $primaryKey = 'drawer_id';
	public $timestamps = false;

	protected $casts = [
		'vendor_id' => 'int',
		'is_active' => 'int'
	];

	protected $dates = [
		'drawer_started_on',
		'drawer_ended_on',
        'match_date'
	];

	protected $fillable = [
		'vendor_id',
		'drawer_started_on',
		'drawer_ended_on',
        'match_date',
		'is_active'
	];

	public function vendorProfileDetails_Drawers(){
		return $this->belongsTo(VendorProfileDetail::class);
	}

    public function scopeCreatedBetweenDates($query, array $dates)
    {
        return $query->whereDate('drawer_started_on', '>=', $dates[0])
            ->whereDate('drawer_started_on', '<=', $dates[1]);
    }
}
