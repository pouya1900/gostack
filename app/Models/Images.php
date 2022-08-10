<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Traits\SearchTrait;

class Images extends Model
{
	use SearchTrait;

	protected $guarded = [];
	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	protected $fillable = [
    'title',
		'description',
		'categories_id',
		'tags',
		'camera',
		'exif',
		'how_use_image',
		'attribution_required',
		'price',
		'item_for_sale'
    ];

		protected $searchable = [
	        'title',
	        'tags'
	    ];

	public function user()
	{
        return $this->belongsTo(User::class)->first();
    }

	public function likes()
	{
		return $this->hasMany(Like::class)->where('status', '1');
	}

	public function downloads()
	{
		return $this->hasMany(Downloads::class);
	}

	public function stock()
	{
		return $this->hasMany(Stock::class)->orderBy('type','asc');
	}

	 public function comments()
	 {
		return $this->hasMany(Comments::class);
	}

	 public function visits()
	 {
		return $this->hasMany(Visits::class);
	}

	 public function category()
	 {
	 	 return $this->belongsTo(Categories::class, 'categories_id');
	 }

	 public function collections()
	 {
	 	 return $this->belongsTo(Collections::class);
	 }

	 public function collectionsImages()
	 {
	 	 return $this->belongsTo(CollectionsImages::class);
	 }

	  public function tags()
		{
	 	 return $this->hasMany(Images::class, 'tags');
	 }

	 public function purchases()
	 {
 		return $this->hasMany(Purchases::class);
 	}
}
