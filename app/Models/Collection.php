<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Collection
 * 
 * @property int $id
 * @property string $name
 * 
 * @property \Illuminate\Database\Eloquent\Collection|Tag[] $tags
 *
 * @package App\Models
 */
class Collection extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'name',
		'picture_id'
	];

	public function tags()
	{
		return $this->hasMany(Tag::class);
	}
	public function picture(){
		return $this->belongsTo(Picture::class);
	}
}
