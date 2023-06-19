<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Picture
 * 
 * @property int $id
 * @property string $source
 * @property int|null $product_id
 * @property int|null $color_id
 * @property int|null $collection_id
 * 
 * @property Product|null $product
 * @property Collection|ProductDetail[] $product_details
 *
 * @package App\Models
 */
class Picture extends Model
{
	public $timestamps = false;
	protected $hidden = ['product_id'];
	protected $casts = [
		// 'color_id' => 'int',
		// 'collection_id' => 'int'
	];

	protected $fillable = [
		'source',
		'product_id'
	];


	public function productDetail()
	{
		return $this->belongsTo(ProductDetail::class);
	}
	public function colection()
	{
		return $this->belongsTo(App\Models\Collection::class);
	}
}
