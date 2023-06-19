<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use App\Traits\TimeZone;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Product
 * 
 * @property int $id
 * @property string $name
 * @property string $brand
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $update_at
 * 
 * @property Collection|Color[] $colors
 * @property Collection|Picture[] $pictures
 * @property Collection|ProductDetail[] $product_details
 * @property Collection|Tag[] $tags
 * @property Collection|Size[] $sizes
 *
 * @package App\Models
 */
class Product extends Model
{
	use SoftDeletes, TimeZone;
	protected $casts = [
	];
	protected $hidden = ['pivot','deleted_at'];
	protected $fillable = [
		'name',
		'brand',
		'description',
		'slug'
	];


	public function tags()
	{
		return $this->belongsToMany(Tag::class,'product_tags','product_id','tag_id');
	}
	public function details()
	{
		$data = $this->hasMany(ProductDetail::class);
		return $data;
	}
	public function sizes()
	{
		return $this->hasMany(Size::class);
	}
	public function sales()
	{
		return $this->hasManyThrough(Sale::class,ProductDetail::class);
	}
	public function colors()
	{
		return $this->belongsToMany(Color::class,'product_details','product_id','color_id')
		->withPivot(['picture_url','regular_price','quantity','active']);
	}
	public function pictures(){
		return $this->hasMany(Picture::class);
	}
}
