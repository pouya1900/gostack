<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionsImages extends Model
{
	protected $guarded = [];
	public $timestamps = false;

	public function user()
	{
        return $this->belongsTo(User::class)->first();
    }

	public function images()
	{
		return $this->belongsTo(Images::class)->whereStatus('active')->orderBy('id','desc')->first();
	}

	public function stock()
	{
		return $this->hasManyThrough(
			Stock::class,
			Images::class,
			'id',
			'images_id',
			'images_id',
			'id'
			)->whereType('medium')
			->whereStatus('active')
			->orderBy('id','desc')
			->first();
	}

	public function collection()
	{
        return $this->belongsTo(Collections::class)->first();
    }

	public function belongsCollection()
	{
        return $this->belongsTo(Collections::class, 'collections_id')->orderBy('id','desc');
    }

	public function collections()
	{
        return $this->hasMany(Collections::class)->orderBy('id','desc');
    }

	public function likes()
	{
		return $this->hasMany(Like::class, 'images_id','images_id')->where('status', '1');
	}

	public function downloads()
	{
		return $this->hasMany(Downloads::class,'images_id','images_id');
	}

}
