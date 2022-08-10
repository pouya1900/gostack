<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
	protected $guarded = [];
	protected $table = 'stock';
	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function image()
	{
		return $this->belongsTo(Images::class)->first();
	}

}
