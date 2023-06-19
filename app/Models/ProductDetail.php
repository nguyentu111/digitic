<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductDetail
 * 
 * @property int $id
 * @property int|null $product_id
 * @property int|null $color_id
 * @property int|null $picture_id
 * @property float|null $regular_price
 * @property int|null $quantity
 * 
 * @property Product|null $product
 * @property Color|null $color
 * @property Collection|OrderDetail[] $order_details
 * @property Collection|Sale[] $sales
 *
 * @package App\Models
 */
class ProductDetail extends Model
{
	protected $hidden = ['pivot','color_id','product_id','created_at','updated_at'];
	protected $casts = [
		// 'id' => 'int',
		// 'product_id' => 'int',
		// 'color_id' => 'int',
		// 'picture_id' => 'int',
		// 'regular_price' => 'float',
		// 'quantity' => 'int'
		'created_at' => 'datetime:Y-m-d H:m:s',
		'updated_at' => 'datetime:Y-m-d H:m:s',
		'regular_price' => 'float',
		'active' => 'boolean'
	];

	protected $fillable = [
		'product_id',
		'color_id',
		'picture_id',
		'regular_price',
		'quantity',
		'active'
	];
	// public function getRegularPriceAttribute($value)
    // {
	// 		return number_format($value,2,'.','');
	// }
	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function color()
	{
		return $this->belongsToMany(Color::class,'product_details','id','color_id');
	}

	public function orderDetails()
	{
		return $this->hasMany(OrderDetail::class);
	}
	public function sales()
	{
		return $this->hasMany(Sale::class);
	}
	public function picture(){
		return $this->belongsTo(Picture::class,'picture_id');
	}
	public function isCompositeKeyExist($productId, $colorId){
		return $this->where(['product_id' => $productId, 'color_id' => $colorId])->exists();
	}
}
