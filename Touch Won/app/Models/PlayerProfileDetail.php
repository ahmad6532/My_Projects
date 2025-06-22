<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerProfileDetail
 *
 * @property int $player_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone_number
 * @property string|null $street_name
 * @property string|null $state
 * @property string|null $country
 * @property string|null $zip_code
 * @property Carbon $created_on
 * @property Carbon $updated_on
 *
 * @package App\Models
 */
class PlayerProfileDetail extends Model
{
	protected $table = 'player_profile_details';
	protected $primaryKey = 'player_id';
	public $timestamps = false;

	protected $dates = [
		'created_on',
		'updated_on'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'phone_number',
		'street_name',
		'state',
		'country',
		'zip_code',
		'created_on',
		'updated_on'
	];
}
