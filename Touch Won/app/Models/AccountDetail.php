<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AccountDetail
 * 
 * @property int $account_id
 * @property string $vendor_id
 * @property string $player_PIN
 * @property int $player_id
 * @property string $is_verified
 * @property bool $is_deleted
 * @property int $points
 * @property int $credits
 * @property Carbon $created_on
 * @property Carbon $updated_on
 * @property bool $is_active
 * @property Carbon|null $last_login_credit
 *
 * @package App\Models
 */
class AccountDetail extends Model
{
	protected $table = 'account_details';
	protected $primaryKey = 'account_id';
	public $timestamps = false;

	protected $casts = [
		'player_id' => 'int',
		'is_deleted' => 'bool',
		'points' => 'int',
		'credits' => 'int',
		'is_active' => 'bool'
	];

	protected $dates = [
		'created_on',
		'updated_on',
		'last_login_credit'
	];

	protected $fillable = [
		'vendor_id',
		'player_PIN',
		'player_id',
		'is_verified',
		'is_deleted',
		'points',
		'credits',
		'created_on',
		'updated_on',
		'is_active',
		'last_login_credit'
	];
	public function accountDetails()
	{
		return $this->belongsTo(VendorProfileDetail::class);
	}
}
