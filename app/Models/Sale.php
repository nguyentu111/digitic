<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;
use App\Traits\TimeZone;
/**
 * Class Sale
 * 
 * @property int $id
 * @property Carbon|null $from
 * @property Carbon|null $to
 * @property int|null $product_detail_id
 * @property float|null $sale_price
 * 
 * @property ProductDetail|null $product_detail
 *
 * @package App\Models
 */
class Sale extends Model
{
	use TimeZone;
	protected $casts = [
		'sale_price' => 'float',
		// 'created_at' => 'datetime:Y-m-d H:m:s',
		// 'updated_at' => 'datetime:Y-m-d H:m:s',
	];
	// protected $hidden = ['product_detail_id'];
	protected $fillable = [
		'from_time',
		'to_time',
		'product_detail_id',
		'sale_price',
		'quantity'
	];
	// public function productDetail()
	// {
	// 	return $this->belongsToMany(ProductDetail::class);
	// }
	public function scopeSaleValid($query,$salePrice, $productDetailId){
		return $query->where('product_detail_id',$productDetailId)->where('from_time','<=',date('Y-m-d H:i:s', time()))
		->where('to_time','>=',date('Y-m-d H:i:s', time()));
	}
}
