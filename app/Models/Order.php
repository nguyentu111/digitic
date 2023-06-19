<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TimeZone;
/**
 * Class Order
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property bool|null $paid
 * @property string|null $note
 * @property int $status
 * @property int|null $user_id
 * 
 * @property Collection|OrderDetail[] $order_details
 *
 * @package App\Models
 */
class Order extends Model
{
	use HasFactory, TimeZone;

	protected $casts = [
		'shipped_at' => 'datetime:Y-m-d H:m:s',
		'paid' => 'boolean'
	];

	protected $fillable = [
		'paid',
		'status',
		'user_id',
		'shipped_at'
		
	];

	public function orderDetails()
	{
		return $this->hasMany(OrderDetail::class);
	}
	public function user()
	{
		return $this->belongsToMany(User::class);
	}
	public function productDetails(){
		return $this->belongsToMany(ProductDetail::class, 'order_details',  'order_id','product_detail_id')->withPivot(['quantity', 'regular_price','sale_price']);
}
}
