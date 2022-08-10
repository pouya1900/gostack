<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
	protected $guarded = [];
	
	public function user()
	{
		return $this->belongsTo(User::class)->first();
	}

	public function plan()
	{
		return $this->hasOne(Plans::class, 'plan_id', 'stripe_price');
	}

	public function invoice()
	{
		return $this->hasOne(Invoices::class)->whereStatus('paid')->first();
	}
}
