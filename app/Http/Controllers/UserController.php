<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Query;
use App\Models\AdminSettings;
use App\Models\UsersReported;
use App\Models\ImagesReported;
use App\Models\Images;
use App\Models\Followers;
use App\Models\Stock;
use App\Models\TaxRates;
use App\Models\Deposits;
use App\Models\Invoices;
use App\Models\Notifications;
use App\Models\Collections;
use App\Models\CollectionsImages;
use App\Models\ReferralTransactions;
use App\Models\Subscriptions;
use App\Models\PaymentGateways;
use App\Helper;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Image;
use DB;

class UserController extends Controller {

	use Traits\UserTrait;

	public function __construct (AdminSettings $settings) {
		$this->settings = $settings::first();
	}

	protected function validator(array $data, $id = null) {

    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		// Validate if have one letter
	Validator::extend('letters', function($attribute, $value, $parameters){
    	return preg_match('/[a-zA-Z0-9]/', $value);
	});

			return Validator::make($data, [
	    'full_name' => 'required|min:3|max:25',
			'username'  => 'required|min:3|max:15|ascii_only|alpha_dash|letters|unique:pages,slug|unique:reserved,name|unique:users,username,'.$id,
			'email'     => 'required|email|unique:users,email,'.$id,
			'countries_id' => 'required',
			'paypal_account' => 'email',
      'website'   => 'url',
      'facebook'   => 'url',
      'twitter'   => 'url',
			'instagram'   => 'url',
      'description' => 'max:200',
	        ]);

    }//<--- End Method

    public function profile($slug, Request $request)
		{
			$user  = User::where('username','=', $slug)->whereStatus('active')->firstOrFail();
			$title = $user->name ?: $user->username;

	   $images = Query::userImages($user->id);

		if ($request->input('page') > $images->lastPage()) {
			abort('404');
		}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username;

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
		 	$followActive = Followers::whereFollower(auth()->id())
		 	->where('following', $user->id)
			->where('status', '1')
		 	->first();

	     if ($followActive) {
	     	  $textFollow   = trans('users.following');
				  $icoFollow    = '-person-check';
				  $activeFollow = 'btnFollowActive';
	     } else {
	     		$textFollow   = trans('users.follow');
		    	$icoFollow    = '-person-plus';
					$activeFollow = '';
	     }
		}

		if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

 		return view('users.profile', [
					'title' => $title,
					'user' => $user ,
					'images' => $images,
					'textFollow' => $textFollow ?? null,
					'icoFollow' => $icoFollow ?? null,
					'activeFollow' => $activeFollow ?? null,
				]);

    }//<--- End Method

    public function followers($slug, Request $request) {

		$user  = User::where ('username','=', $slug)->firstOrFail();
		$_title = $user->name ?: $user->username;
		$title  = $_title.' - '.trans('users.followers');

		if ($user->status == 'suspended') {
			return view('errors.user_suspended');
		}

	   $followers = User::where('users.status','active')
			->leftjoin('followers', 'users.id', '=', \DB::raw('followers.follower AND followers.status = "1"'))
			->leftjoin('images', 'users.id', '=', \DB::raw('images.user_id AND images.status = "active"'))
			->where('users.status', '=', 'active')
			->where ('followers.following', $user->id)
			->groupBy('users.id')
			->orderBy('followers.id', 'DESC')
			->select('users.*')
			->paginate(10);

		if ($request->input('page') > $followers->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
						return view('includes.users',['users' => $followers])->render();
				}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username.'/followers';

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
		 	$followActive = Followers::whereFollower(auth()->id())
		 	->where('following', $user->id)
			->where('status', '1')
		 	->first();

	     if ($followActive) {
	     	  $textFollow   = trans('users.following');
				  $icoFollow    = '-person-check';
				  $activeFollow = 'btnFollowActive';
	     } else {
	     		$textFollow   = trans('users.follow');
		    	$icoFollow    = '-person-plus';
					$activeFollow = '';
	     }
		}

	 		return view('users.profile', [
				'title' => $title,
				'followers' => $followers,
				'user' => $user,
				'textFollow' => $textFollow ?? null,
				'icoFollow' => $icoFollow ?? null,
				'activeFollow' => $activeFollow ?? null,

			]);
    }//<--- End Method

    public function following($slug, Request $request) {

		$user  = User::where ('username','=', $slug)->firstOrFail();
		$_title = $user->name ?: $user->username;
		$title  = $_title.' - '.trans('users.following');

		if ($user->status == 'suspended') {
			return view('errors.user_suspended');
		}

	   $following = User::where('users.status','active')
			->leftjoin('followers', 'users.id', '=', \DB::raw('followers.following AND followers.status = "1"'))
			->leftjoin('images', 'users.id', '=', \DB::raw('images.user_id AND images.status = "active"'))
			->where('users.status', '=', 'active')
			->where ('followers.follower', $user->id)
			->groupBy('users.id')
			->orderBy('followers.id', 'DESC')
			->select('users.*')
			->paginate(10);

		if ($request->input('page') > $following->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
						return view('includes.users',['users' => $following])->render();
				}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username.'/following';

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
		 	$followActive = Followers::whereFollower(auth()->id())
		 	->where('following', $user->id)
			->where('status', '1')
		 	->first();

	     if ($followActive) {
	     	  $textFollow   = trans('users.following');
				  $icoFollow    = '-person-check';
				  $activeFollow = 'btnFollowActive';
	     } else {
	     		$textFollow   = trans('users.follow');
		    	$icoFollow    = '-person-plus';
					$activeFollow = '';
	     }
		}

 		return view('users.profile', [
					'title' => $title,
					'following' => $following,
					'user' => $user,
					'textFollow' => $textFollow ?? null,
					'icoFollow' => $icoFollow ?? null,
					'activeFollow' => $activeFollow ?? null,
				]);
    }//<--- End Method

    public function account()
    {
		return view('users.account');
    }//<--- End Method

	public function update_account(Request $request)
    {

	   $input = $request->all();
	   $id    = auth()->user()->id;

	   $validator = $this->validator($input, $id);

		 if ($validator->fails()) {
        return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
    }

	   $user = User::find($id);
	   $user->name        = $input['full_name'];
	   $user->email        = trim($input['email']);
	   $user->username = $input['username'];
	   $user->countries_id    = $input['countries_id'];
		 $user->author_exclusive = $input['author_exclusive'] ?? auth()->user()->author_exclusive;
	   $user->paypal_account = trim($input['paypal_account']);
	   $user->website     = trim(strtolower($input['website']));
	   $user->facebook  = trim(strtolower($input['facebook']));
	   $user->twitter       = trim(strtolower($input['twitter']));
		 $user->instagram  = trim(strtolower($input['instagram']));
	   $user->bio = $input['description'];
		 $user->two_factor_auth = $input['two_factor_auth'] ?? 'no';
	   $user->save();

	   \Session::flash('notification',trans('auth.success_update'));

	   return redirect('account');

	}//<--- End Method

	public function password()
    {
		return view('users.password');
    }//<--- End Method

    public function update_password(Request $request)
    {

	   $input = $request->all();
	   $id = auth()->user()->id;

		   $validator = Validator::make($input, [
			'old_password' => 'required|min:6',
			'password'     => 'required|min:8',
    	]);

			if ($validator->fails()) {
         return redirect()->back()
						 ->withErrors($validator)
						 ->withInput();
					 }

	   if (! \Hash::check($input['old_password'], auth()->user()->password)) {
		    return redirect('account/password')->with (array ('incorrect_pass' => trans('misc.password_incorrect')));
		}

	   $user = User::find($id);
	   $user->password  = \Hash::make($input["password"]);
	   $user->save();

	   \Session::flash('notification',trans('auth.success_update_password'));

	   return redirect('account/password');

	}//<--- End Method

	public function delete()
    {
    	if (auth()->user()->id == 1) {
    		return redirect('account');
    	}
		return view('users.delete');
    }//<--- End Method

    public function delete_account()
		{

		$id = auth()->user()->id;
		$user = User::findOrFail($id);

		 if ($user->id == 1) {
		 	return redirect('account');
			exit;
		 }

		 $this->deleteUser($id);

      return redirect('account');

    }//<--- End Method

    public function notifications() {

		$sql = DB::table('notifications')
			 ->select(DB::raw('
			notifications.id id_noty,
			notifications.type,
			notifications.created_at,
			users.id userId,
			users.username,
			users.name,
			users.avatar,
			images.id,
			images.title
			'))
			->leftjoin('users', 'users.id', '=', DB::raw('notifications.author'))
			->leftjoin('images', 'images.id', '=', DB::raw('notifications.target AND images.status = "active"'))
			->leftjoin('comments', 'comments.images_id', '=', DB::raw('notifications.target
			AND comments.user_id = users.id
			AND comments.images_id = images.id
			AND comments.status = "1"
			'))
			->where('notifications.destination', '=',  auth()->user()->id)
			->where('notifications.trash', '=',  '0')
			->where('users.status', '=',  'active')
			->groupBy('notifications.id')
			->orderBy('notifications.id', 'DESC')
			->paginate (10);

			// Mark seen Notification
			Notifications::where('destination', auth()->user()->id)
			->update(array('status' => '1'));

			return view('users.notifications')->withSql($sql);

    }//<--- End Method

    public function notificationsDelete(){

		$notifications = Notifications::where('destination', auth()->user()->id)->get();

		if (isset ($notifications)){
			foreach($notifications as $notification){
				$notification->delete();
			}
		}

		return redirect('notifications');

    }//<--- End Method

    public function upload_avatar(Request $request)
		{
	   $id = auth()->user()->id;

		$validator = Validator::make($request->all(), [
		'photo' => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=180,min_height=180|max:'.$this->settings->file_size_allowed.'',
	]);

		   if ($validator->fails()) {
		        return response()->json([
				        'success' => false,
				        'errors' => $validator->getMessageBag()->toArray(),
				    ]);
		    }

		// PATHS
	  $path = config('path.avatar');

		 //<--- HASFILE PHOTO
	    if ($request->hasFile('photo'))	{

				$photo     = $request->file('photo');
				$extension = $request->file('photo')->getClientOriginalExtension();
				$avatar    = strtolower(auth()->user()->username.'-'.auth()->user()->id.time().str_random(10).'.'.$extension);

				$imgAvatar  = Image::make($photo)->orientate()->fit(180, 180, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				})->encode($extension);

				// Copy folder
				Storage::put($path.$avatar, $imgAvatar, 'public');

				//<<<-- Delete old image -->>>/
				if (auth()->user()->avatar != $this->settings->avatar) {
					Storage::delete(config('path.avatar').auth()->user()->avatar);
				}

				// Update Database
				User::where('id', auth()->user()->id)->update(['avatar' => $avatar]);

				return response()->json([
				        'success' => true,
				        'avatar' => Storage::url($path.$avatar),
				    ]);
	    }//<--- HASFILE PHOTO
    }//<--- End Method Avatar

    public function upload_cover(Request $request)
		{
	   $settings  = AdminSettings::first();
	   $id = auth()->user()->id;

		$validator = Validator::make($request->all(), [
		'photo' => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width=800,min_height=600|max:'.$settings->file_size_allowed.'',
	]);

		   if ($validator->fails()) {
		        return response()->json([
				        'success' => false,
				        'errors' => $validator->getMessageBag()->toArray(),
				    ]);
		    }

		// PATHS
	  $path = config('path.cover');

		 //<--- HASFILE PHOTO
	    if ($request->hasFile('photo'))	{

				$photo       = $request->file('photo');
				$widthHeight = getimagesize($photo);
				$extension   = $photo->getClientOriginalExtension();
				$cover       = strtolower(auth()->user()->username.'-'.auth()->user()->id.time().str_random(10).'.'.$extension);

				//=============== Image Large =================//
				$width     = $widthHeight[0];
				$height    = $widthHeight[1];
				$max_width = '1500';

				if ($width < $height) {
					$max_width = '800';
				}

				if ($width > $max_width) {
					$coverScale = $max_width / $width;
				} else {
					$coverScale = 1;
				}

				$scale    = $coverScale;
				$widthCover = ceil($width * $scale);

				$imgCover = Image::make($photo)->orientate()->resize($widthCover, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				})->encode($extension);

				// Copy folder
				Storage::put($path.$cover, $imgCover, 'public');

				//<<<-- Delete old image -->>>/
				if (auth()->user()->cover != $this->settings->cover) {
					Storage::delete(config('path.cover').auth()->user()->cover);
				}//<--- IF FILE EXISTS #1

				// Update Database
				User::where ('id', auth()->user()->id)->update(['cover' => $cover]);

				return response()->json([
				        'success' => true,
				        'cover' => Storage::url($path.$cover),
				    ]);

	    }//<--- HASFILE PHOTO
    }//<--- End Method Cover

    public function userLikes(Request $request) {

		$title       = trans('users.likes').' - ';

	   $images = Images::where('images.status','active')
			->leftjoin('likes', 'images.id', '=', \DB::raw('likes.images_id AND likes.status = "1"'))
			->where ('likes.user_id', auth()->user()->id)
			->groupBy('images.id')
			->orderBy('likes.id', 'DESC')
			->select('images.*')
			->paginate($this->settings->result_request);

		if ($request->input('page') > $images->lastPage()) {
			abort('404');
		}

 		return view('users.likes', [ 'title' => $title, 'images' => $images]);
    }//<--- End Method

    public function followingFeed(Request $request) {

		$title = trans('misc.feed').' - ';

	   $images = Images::leftjoin('followers', 'images.user_id', '=', \DB::raw('followers.following AND followers.status = "1"'))
			->where('images.status', 'active')
			->where('followers.follower', '=', auth()->user()->id)
			->groupBy('images.id')
			->orderBy ('images.id', 'desc')
			->select('images.*')
			->paginate ($this->settings->result_request);

		if ($request->input('page') > $images->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
						return view('includes.images',['images' => $images])->render();
				}

 		return view('users.feed', [ 'title' => $title, 'images' => $images]);
    }//<--- End Method

    public function collections($slug, Request $request) {

		$user  = User::where ('username','=', $slug)->firstOrFail();
		$_title = $user->name ?: $user->username;
		$title  = $_title.' - '.trans('misc.collections');

		if ($user->status == 'suspended') {
			return view('errors.user_suspended');
		}

		if (auth()->check()) {
			$AuthId = auth()->user()->id;
		} else {
			$AuthId = 0;
		}

	   $collections = $user->collections()->where('user_id', $user->id)
	   ->where('type','public')
		->orWhere('user_id', $AuthId)
		->where ('user_id', $user->id)
		->where('type','private')
		->orderBy('id','desc')
		->paginate ($this->settings->result_request);

		if ($request->input('page') > $collections->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
						return view('includes.collections-grid', ['data' => $collections])->render();
				}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username.'/collections';

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

		if (auth()->check()) {
			// Follow Active
		 	$followActive = Followers::whereFollower(auth()->id())
		 	->where('following', $user->id)
			->where('status', '1')
		 	->first();

	     if ($followActive) {
	     	  $textFollow   = trans('users.following');
				  $icoFollow    = '-person-check';
				  $activeFollow = 'btnFollowActive';
	     } else {
	     		$textFollow   = trans('users.follow');
		    	$icoFollow    = '-person-plus';
					$activeFollow = '';
	     }
		}

 		return view('users.profile', [
					'title' => $title,
					'collections' => $collections,
					'user' => $user,
					'textFollow' => $textFollow ?? null,
					'icoFollow' => $icoFollow ?? null,
					'activeFollow' => $activeFollow ?? null,
				]);
    }//<--- End Method

    public function collectionDetail(Request $request) {

	   $collectionData = Collections::where('id', $request->id)->firstOrFail();

	   $user = User::find($collectionData->user_id);

	   $images = CollectionsImages::where('collections_images.collections_id',$request->id)
		->join('images', 'images.id', '=', 'collections_images.images_id')
		->join('collections', 'collections.id', '=', 'collections_images.collections_id')
		->join('users', 'users.id', '=', 'collections.user_id')
		->where('images.status','active')
		->where('users.status','active')
		->orderBy('collections_images.id','desc')
		->select('images.*')
		->paginate ($this->settings->result_request);

		$title = trans('misc.collection').' - '.$collectionData->title.' -';

		if ($request->input('page') > $images->lastPage()) {
			abort('404');
		}

		if (request()->ajax()) {
						return view('includes.images',['images' => $images])->render();
				}

		if($collectionData->type == 'private' && auth()->check() && auth()->user()->id != $collectionData->user_id
				|| $collectionData->type == 'private' && auth()->guest()) {
			abort('404');
		}

		$slugUrl = str_slug ($collectionData->title);

		if ($slugUrl  == '') {
				$slugUrl  = '';
			} else {
				$slugUrl  = '/'.$slugUrl;
			}

		//<<<-- * Redirect the user real name * -->>>
		$uri = request()->path();
		$uriCanonical = $user->username.'/collection/'.$collectionData->id.$slugUrl;

		if ($uri != $uriCanonical) {
			return redirect($uriCanonical);
		}

 		return view('users.collection-detail', [ 'title' => $title, 'images' => $images, 'collectionData' => $collectionData, 'user' => $user]);
    }//<--- End Method

    public function report(Request $request){

		$data = UsersReported::firstOrNew(['user_id' => auth()->user()->id, 'id_reported' => $request->id]);

		if ($data->exists) {
			\Session::flash('noty_error','error');
			return redirect()->back();
		} else {

			$data->reason = $request->reason;
			$data->save();
			\Session::flash('noty_success','success');
			return redirect()->back();
		}

	}//<--- End Method

	public function photosPending(Request $request) {

		$images = Images::where('user_id',auth()->user()->id)->where('status','pending')->paginate ($this->settings->result_request);

		if ($request->input('page') > $images->lastPage()) {
			abort('404');
		}

 		return view('users.photos-pending', [ 'images' => $images]);
    }//<--- End Method

		public function invoice($id)
    {
      $data = Invoices::whereId($id)
					->whereUserId(auth()->id())
					->whereStatus('paid')
					->firstOrFail();

      $taxes = TaxRates::whereIn('id', collect(explode('_', $data->taxes)))->get();
      $totalTaxes = ($data->amount * $taxes->sum('percentage') / 100);

      $totalAmount = ($data->amount + $data->transaction_fee + $totalTaxes);

     return view('users.invoice', [
       'data' => $data,
       'amount' => $data->amount,
       'percentageApplied' => $data->percentage_applied,
       'transactionFee' => $data->transaction_fee,
       'totalAmount' => $totalAmount,
       'taxes' => $taxes
     ]);
    }

		public function myReferrals()
    {
      $transactions = ReferralTransactions::whereReferredBy(auth()->id())
      ->orderBy('id', 'desc')
      ->paginate(20);

       return view('users.referrals', ['transactions' => $transactions]);

    }//<--- End Method

		public function subscription()
		{
			$subscription  = auth()->user()->mySubscription()->latest()->first();
			$subscriptions = auth()->user()->mySubscription()->paginate(10);

			return view('users.subscription')->with([
				'subscription' => $subscription,
				'subscriptions' => $subscriptions
			]);
		}
}
