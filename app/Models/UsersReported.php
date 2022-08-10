<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersReported extends Model
{
	protected $guarded = [];
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	 public function user_reported()
	 {
		return $this->belongsTo(User::class,'id_reported')->first();
	}

}
