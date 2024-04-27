<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $p_name
 * @property string $p_image
 * @property float $price
 * @property bool $status
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';
	public $timestamps = false;

	protected $casts = [
		'price' => 'float',
		'status' => 'bool'
	];

	protected $fillable = [
		'p_name',
		'p_image',
		'price',
		'status'
	];
}
