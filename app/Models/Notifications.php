<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
	protected $guarded = [];
	const UPDATED_AT = null;

	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public static function send($destination, $sessionId, $type, $target)
	{
		self::create([
			'destination' => $destination,
			'author' => $sessionId,
			'type' => $type,
			'target' => $target
		]);
	}

}
