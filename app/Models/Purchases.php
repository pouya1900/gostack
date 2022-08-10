<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Purchases extends Model
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

  public function invoice()
	{
		return $this->hasOne(Invoices::class)->whereStatus('paid')->first();
	}

}
