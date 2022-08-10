<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchases;
use App\Models\Deposits;
use App\Models\Downloads;
use App\Models\Withdrawals;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Helper;
use App\Models\PaymentGateways;
use Carbon\Carbon;

class DashboardController extends Controller
{

  public function __construct(AdminSettings $settings, Request $request) {

    $this->middleware('SellOption');
    $this->settings = $settings::first();
    $this->request = $request;
  }

  // Dashboard
	public function dashboard()
	{
    $earningNetUser = auth()->user()->mySales()->sum('earning_net_seller');

    //  Calcule Chart Earnings last 20 days
    for ($i=0; $i <= 30; ++$i) {

      $date = date('Y-m-d', strtotime('-'.$i.' day'));

      // Earnings last 20 days
      $sales = auth()->user()->mySales()->whereDate('purchases.date', '=', $date)->sum('earning_net_seller');

      // Sales last 20 days
      $salesLast20 = auth()->user()->mySales()->whereDate('purchases.date', '=', $date)->count();

      // Format Date on Chart
      $formatDate = Helper::formatDateChart($date);
      $monthsData[] =  "'$formatDate'";

      // Earnings last 20 days
      $earningNetUserSum[] = $sales;

      // Earnings last 20 days
      $lastSales[] = $salesLast20;
    }

		// Today
		$stat_revenue_today = auth()->user()->mySales()
      ->where('purchases.date', '>=', Carbon::today())
		 ->sum('earning_net_seller');

     // Yesterday
 		$stat_revenue_yesterday = auth()->user()->mySales()
    ->where('purchases.date', '>=', Carbon::yesterday())
    ->where('purchases.date', '<', Carbon::today())
 		 ->sum('earning_net_seller');

		 // Week
	 	$stat_revenue_week = auth()->user()->mySales()
        ->whereBetween('purchases.date', [
	        Carbon::parse()->startOfWeek(),
	        Carbon::parse()->endOfWeek(),
	    ])->sum('earning_net_seller');

     // Last Week
	 	$stat_revenue_last_week = auth()->user()->mySales()
        ->whereBetween('purchases.date', [
	        Carbon::now()->startOfWeek()->subWeek(),
	        Carbon::now()->subWeek()->endOfWeek(),
	    ])->sum('earning_net_seller');

		 // Month
	 	$stat_revenue_month = auth()->user()->mySales()
        ->whereBetween('purchases.date', [
	        Carbon::parse()->startOfMonth(),
	        Carbon::parse()->endOfMonth(),
	    ])->sum('earning_net_seller');

      // Last Month
 	 	$stat_revenue_last_month = auth()->user()->mySales()
        ->whereBetween('purchases.date', [
 	        Carbon::now()->startOfMonth()->subMonth(),
 	        Carbon::now()->subMonth()->endOfMonth(),
 	    ])->sum('earning_net_seller');

    $label = implode(',', array_reverse($monthsData));
    $data = implode(',', array_reverse($earningNetUserSum));

    $datalastSales = implode(',', array_reverse($lastSales));

    $photosPending = auth()->user()->images_pending()->count();
    $totalImages = auth()->user()->allImages()->count();
    $totalSales = auth()->user()->mySales()->count();

    return view('dashboard.dashboard', [
          'earningNetUser' => $earningNetUser,
          'label' => $label,
          'data' => $data,
          'datalastSales' => $datalastSales,
          'photosPending' => $photosPending,
          'totalImages' => $totalImages,
          'totalSales' => $totalSales,
          'stat_revenue_today' => $stat_revenue_today,
          'stat_revenue_yesterday' => $stat_revenue_yesterday,
    			'stat_revenue_week' => $stat_revenue_week,
          'stat_revenue_last_week' => $stat_revenue_last_week,
    			'stat_revenue_month' => $stat_revenue_month,
          'stat_revenue_last_month' => $stat_revenue_last_month
        ]);

	}//<--- End Method

  public function photos()
  {
		$query = request()->get('q');
		$sort = request()->get('sort');
		$pagination = 15;

		$data = auth()->user()->allImages()->orderBy('id','desc')->paginate($pagination);

		// Search
		if (isset( $query ) ) {
		 	$data = Images::where('title', 'LIKE', '%'.$query.'%')
      ->whereUserId(auth()->id())
			->orWhere('tags', 'LIKE', '%'.$query.'%')
      ->whereUserId(auth()->id())
		 	->orderBy('id','desc')->paginate($pagination);
		 }

		// Sort
		if( isset( $sort ) && $sort == 'title' ) {
			$data = Images::whereUserId(auth()->id())->orderBy('title','asc')->paginate($pagination);
		}

		if( isset( $sort ) && $sort == 'pending' ) {
			$data = Images::whereUserId(auth()->id())->where('status','pending')->paginate($pagination);
		}

		if( isset( $sort ) && $sort == 'downloads' ) {
			$data = Images::join('downloads', 'images.id', '=', 'downloads.images_id')
          ->where('images.user_id', auth()->id())
					->groupBy('downloads.images_id')
					->orderBy( \DB::raw('COUNT(downloads.images_id)'), 'desc' )
					->select('images.*')
					->paginate( $pagination );
		}

		if( isset( $sort ) && $sort == 'likes' ) {
			$data = Images::join('likes', function($join){
				$join->on('likes.images_id', '=', 'images.id')
        ->where('images.user_id', auth()->id())
        ->where('likes.status', '=', '1' );
			})
					->groupBy('likes.images_id')
					->orderBy( \DB::raw('COUNT(likes.images_id)'), 'desc' )
					->select('images.*')
					->paginate( $pagination );
		}

		return view('dashboard.photos', ['data' => $data, 'query' => $query, 'sort' => $sort ]);
	}//<--- End Method

  public function sales()
  {
    $data = Purchases::leftJoin('images', function($join) {
  		 $join->on('purchases.images_id', '=', 'images.id');
  	 })
  	 ->where('images.user_id',auth()->id())
     ->where('purchases.approved', '1')
  	 ->select('purchases.*')
  	 ->orderBy('purchases.id','DESC')
     ->paginate(20);

		return view('dashboard.sales')->withData($data);
	}//<--- End Method

  public function purchases()
  {
    $data = Purchases::whereUserId(auth()->id())
  	 ->orderBy('id','DESC')
     ->whereApproved('1')
     ->whereMode('normal')
     ->paginate(20);

		return view('dashboard.purchases')->withData($data);
	}//<--- End Method

  public function deposits() {

    $data = Deposits::whereUserId(auth()->id())->orderBy('id', 'desc')->paginate(20);

		return view('dashboard.deposits-history')->withData($data);
	}//<--- End Method

  // Add Funds
	public function addFunds()
	{
    // Get Deposits History
    $data = Deposits::whereUserId(auth()->id())->orderBy('id', 'desc')->paginate(20);

    // Stripe Key
    $_stripe = PaymentGateways::where('id', 2)->where('enabled', '1')->select('key')->first();

		return view('dashboard.add-funds')->with([
      '_stripe' => $_stripe,
      'data' => $data
    ]);
	}//<--- End Method

  public function showWithdrawal()
  {

    $withdrawals = Withdrawals::whereUserId(auth()->id())->paginate(20);
    return view('dashboard.withdrawals')->withWithdrawals($withdrawals);

  }//<--- End Method

  public function withdrawal()
  {
    if( auth()->user()->payment_gateway == 'PayPal'
		&& empty(auth()->user()->paypal_account)

		|| auth()->user()->payment_gateway == 'Bank'
		&& empty(  auth()->user()->bank  )

		|| empty(auth()->user()->payment_gateway)

		) {
			\Session::flash('error',trans('misc.configure_withdrawal_method'));
			return redirect('user/dashboard/withdrawals');
		}

    // Verify amount validate
    if(auth()->user()->balance < $this->settings->amount_min_withdrawal) {
      \Session::flash('error',trans('misc.withdraw_not_valid'));
			return redirect('user/dashboard/withdrawals');
    }

      if( auth()->user()->payment_gateway == 'PayPal' ) {
       $_account = auth()->user()->paypal_account;
      } else {
       $_account = auth()->user()->bank;
      }

      $sql               = new Withdrawals;
			$sql->user_id      = auth()->id();
			$sql->amount       = auth()->user()->balance;
			$sql->gateway      = auth()->user()->payment_gateway;
			$sql->account      = $_account;
			$sql->save();

      // Remove Balance the User
      $userBalance = User::find(auth()->id());
      $userBalance->balance = 0;
      $userBalance->save();

			return redirect('user/dashboard/withdrawals');

  }//<--- End Method

    public function withdrawalConfigure()
    {
      if ($this->request->type != 'paypal' && $this->request->type != 'bank') {
        return redirect('user/dashboard/withdrawals/configure')->withError(__('misc.error'));
      }

    // Validate Email Paypal
    if ($this->request->type == 'paypal') {
      $rules = [
          'email_paypal'  => 'required|email|confirmed',
        ];

    $this->validate($this->request, $rules);

    $user = User::find(auth()->id());
    $user->paypal_account = $this->request->email_paypal;
    $user->payment_gateway = 'PayPal';
    $user->save();

    return redirect('user/dashboard/withdrawals/configure')->withSuccess(__('admin.success_update'));

    } else if ($this->request->type == 'bank') {

      $rules = [
          'bank' => 'required',
        ];

      $this->validate($this->request, $rules);

       $user = User::find(auth()->id());
       $user->bank = $this->request->bank;
       $user->payment_gateway = 'Bank';
       $user->save();

      return redirect('user/dashboard/withdrawals/configure')->withSuccess(__('admin.success_update'));
    }

    }//<--- End Method

    public function withdrawalDelete()
    {

      $withdrawal = Withdrawals::whereId($this->request->id)
      ->whereUserId(auth()->id())
      ->whereStatus('pending')
      ->firstOrFail();

        $withdrawal->delete();

        // Add Balance the User again
        auth()->user()->increment('balance', $withdrawal->amount);

        return redirect('user/dashboard/withdrawals');

    }//<--- End Method

    // withdrawals configure view
    public function withdrawalsConfigureView()
    {
      $stripeConnectCountries = explode(',', $this->settings->stripe_connect_countries);
      return view('dashboard.withdrawals-configure')->withStripeConnectCountries($stripeConnectCountries);
    }//<--- End Method

    public function downloads()
    {
      $data = auth()->user()->downloads()
      ->join('images', 'images.id', '=', 'downloads.images_id')
      ->where('downloads.type', '<>', 'sale')
      ->select('images.id', 'images.title', 'images.token_id', 'images.thumbnail')
      ->addSelect('downloads.date AS dateDownload', 'downloads.type', 'downloads.size')
      ->orderBy('downloads.id','DESC')
      ->paginate(20);

  		return view('dashboard.downloads')->withData($data);
  	}//<--- End Method

}
