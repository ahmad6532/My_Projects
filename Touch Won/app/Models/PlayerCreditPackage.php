<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerCreditPackage
 * 
 * @property int $credit_package_id
 * @property string $package_type
 * @property string $package_name
 * @property int $credit_cost
 * @property int $credits_value_count
 * @property bool $is_enabled
 *
 * @package App\Models
 */
class PlayerCreditPackage extends Model
{
	protected $table = 'player_credit_packages';
	protected $primaryKey = 'credit_package_id';
	public $timestamps = false;

	protected $casts = [
		'credit_cost' => 'int',
		'credits_value_count' => 'int',
		'is_enabled' => 'bool'
	];

	protected $fillable = [
		'package_type',
		'package_name',
		'credit_cost',
		'credits_value_count',
		'is_enabled'
	];
}
