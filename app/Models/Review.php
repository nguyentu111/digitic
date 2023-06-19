<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TimeZone;
/**
 * Class Review
 * 
 * @property int $id
 * @property string $titile
 * @property string $content
 * @property Carbon $created_at
 * @property int $user_id
 * @property int $rating
 * 
 * @property User $user
 * @property Collection|OrderDetail[] $order_details
 *
 * @package App\Models
 */
class Review extends Model
{
	use TimeZone;
	protected $hidden = ['order_detail_id'];

	protected $casts = [
		// 'user_id' => 'int',
		// 'rating' => 'int'
	];

	protected $fillable = [
		'title',
		'content',
		'user_id',
		'rating',
		'order_detail_id',
		'parent_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function orderDetail()
	{
		return $this->belongsTo(OrderDetail::class);
	}
	public function responses(){
		return $this->hasMany(Review::class, 'parent_id','id');
	}
}
