<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
	protected $guarded = [];
	public $timestamps = false;

	protected $fillable = [ 'name', 'abbreviation' ];
}
