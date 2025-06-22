<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DrawerWithdraw
 *
 * @property int $id
 * @property int $drawer_id
 * @property float $withdraw_amount
 * @property Carbon $withdraw_done_on
 *
 * @package App\Models
 */
class DrawerWithdraw extends Model
{
	protected $table = 'drawer_withdraw';
	public $timestamps = false;

	protected $casts = [
		'drawer_id' => 'int',
		'withdraw_amount' => 'float'
	];

	protected $dates = [
		'withdraw_done_on',
        'match_date'
	];

	protected $fillable = [
		'drawer_id',
		'withdraw_amount',
		'withdraw_done_on',
        'match_date'
	];
}
