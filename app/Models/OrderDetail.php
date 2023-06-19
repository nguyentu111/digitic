<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use App\Cast\Money;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderDetail
 * 
 * @property int $id
 * @property int|null $order_id
 * @property int|null $product_detail_id
 * @property float|null $regular_price
 * @property int|null $quantity
 * @property int|null $review_id
 * 
 * @property Order|null $order
 * @property ProductDetail|null $product_detail
 * @property Review|null $review
 *
 * @package App\Models
 */
class OrderDetail extends Model
{

	protected $casts = [
		'order_id' => 'int',
		'product_detail_id' => 'int',
		'regular_price' => 'float',
		'sale_price' => 'float',
		'quantity' => 'int',
		'review_id' => 'int'
	];

	protected $fillable = [
		'order_id',
		'product_detail_id',
		'regular_price',
		'quantity',
		'review_id',
		'sale_price'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function productDetail()
	{
		return $this->belongsTo(ProductDetail::class);
	}

	public function review()
	{
		return $this->hasOne(Review::class);
	}
}
