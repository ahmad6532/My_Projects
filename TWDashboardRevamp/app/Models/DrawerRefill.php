<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DrawerRefill
 *
 * @property int $id
 * @property int $drawer_id
 * @property float $refill_amount
 * @property Carbon $refill_done_on
 *
 * @package App\Models
 */
class DrawerRefill extends Model
{
	protected $table = 'drawer_refill';
	public $timestamps = false;

	protected $casts = [
		'drawer_id' => 'int',
		'refill_amount' => 'float'
	];

	protected $dates = [
		'refill_done_on',
        'match_date'
	];

	protected $fillable = [
		'drawer_id',
		'refill_amount',
		'refill_done_on',
        'match_date'
	];
}
