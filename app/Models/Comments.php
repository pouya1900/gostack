<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	protected $guarded = [];
	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function images()
	{
		return $this->belongsTo(Images::class)->first();
	}

	public function total_likes()
	{
		return $this->hasMany(CommentsLikes::class,'comment_id')->where('status','1');
	}

}
