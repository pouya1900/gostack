<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    public function user()
  	{
  		return $this->belongsTo(User::class)->first();
  	}

    public function purchase()
  	{
  		return $this->belongsTo(Purchases::class, 'purchases_id')->first();
  	}

    public function subscription()
  	{
  		return $this->belongsTo(Subscriptions::class, 'subscriptions_id')->first();
  	}

}
