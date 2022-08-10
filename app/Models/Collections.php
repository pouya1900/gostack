<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model
{
	protected $guarded = [];
	const UPDATED_AT = 'update_at';

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function creator()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function collectionImages()
	{
		return $this->hasMany(CollectionsImages::class)->orderBy('id','desc');
	}

}
