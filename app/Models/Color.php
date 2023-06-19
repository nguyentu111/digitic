<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Color
 * 
 * @property int $id
 * @property string $name
 * @property int $product_id
 * @property string $hex_value
 * 
 * @property Product $product
 * @property Collection|ProductDetail[] $product_details
 *
 * @package App\Models
 */
class Color extends Model
{
	protected $casts = [
		'created_at' => 'datetime:Y-m-d h:m:s',
		'updated_at' => 'datetime:Y-m-d h:m:s',
	];
	protected $hidden = ['pivot','created_at','updated_at'];
	protected $fillable = [
		'name',
		'hex_value'
	];
	public function productDetails()
	{
		return $this->hasMany(ProductDetail::class);
	}
	public function product(){
		return $this->belongsToMany(Product::class,'product_details','color_id','product_id');
	}
}
