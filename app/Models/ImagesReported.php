<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesReported extends Model
{
	protected $guarded = [];
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	 public function image()
	 {
		return $this->belongsTo(Images::class)->first();
	}

}
