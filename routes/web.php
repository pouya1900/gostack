<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 |-----------------------------------
 | Index
 |--------- -------------------------
 */
Route::get('/', 'HomeController@index')->name('home');
Route::get('home', function() {
	return redirect('/');
});

/*
 |-----------------------------------
 | Images Sections
 |--------- -------------------------
 */
Route::get('latest', 'HomeController@latest');
Route::get('featured', 'HomeController@featured');
Route::get('popular', 'HomeController@popular');
Route::get('most/commented', 'HomeController@commented');
Route::get('most/viewed', 'HomeController@viewed');
Route::get('most/downloads', 'HomeController@downloads');
Route::get('photos/premium', 'HomeController@premium');

/*
 |-----------------------------------
 | Authentication
 |--------- -------------------------
 */
 // Authentication Routes.
 Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
 Route::post('login', 'Auth\LoginController@login');
 Route::get('logout', 'Auth\LoginController@logout');

 // Registration Routes.
 Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
 Route::post('register', 'Auth\RegisterController@register');

 // Password Reset Routes.
 Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
 Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
 Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
 Route::post('password/reset', 'Auth\ResetPasswordController@reset');

/*
 |-----------------------------------
 | Social Login
 |--------- -------------------------
 */
Route::group(['middleware' => 'guest'], function() {
	Route::get('oauth/{provider}', 'SocialAuthController@redirect')->where('provider', '(facebook|google|twitter)$');
	Route::get('oauth/{provider}/callback', 'SocialAuthController@callback')->where('provider', '(facebook|google|twitter)$');
});//<--- End Group guest

/*
 |
 |-----------------------------------
 | Default Sections
 |--------- -------------------------
 */

// Members
Route::get('members','HomeController@members');

 // Categories
 Route::get('categories','HomeController@categories');

 // Pricing
 Route::get('pricing','HomeController@pricing');

//<---- Categories List
Route::get('category/{slug}','HomeController@category');

//<---- Tags
Route::get('tags','HomeController@tags');
Route::get('tags/{tags}','HomeController@tagsShow');

// Collections
Route::get('collections','HomeController@collections' );

// Collections Detail
Route::get('{user}/collection/{id}/{slug?}','UserController@collectionDetail' );

// Cameras
Route::get('cameras/{cameras}','HomeController@cameras' );

// Colors
Route::get('colors/{colors}','HomeController@colors' );

// Search
Route::get('search', 'HomeController@getSearch');

// Photo Details
Route::get('photo/{id}/{slug?}','ImagesController@show');
Route::get('video/{id}/{slug?}','ImagesController@showVideo');

// Logout
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('contact','HomeController@contact');
Route::post('contact','HomeController@contactStore');

/*
 |
 |-----------------------------------
 | Verify Account
 |--------- -------------------------
 */
Route::get('verify/account/{confirmation_code}', 'HomeController@getVerifyAccount')->where('confirmation_code','[A-Za-z0-9]+');

/*
 |
 |------------------------
 | Pages Static Custom
 |--------- --------------
 */
 Route::get('page/{page}','PagesController@show')->where('page','[^/]*' );

 /*
 |
 |----------------------------
 | Sitemaps
 |--------- ------------------
 */
Route::get('sitemaps.xml', function() {
	return response()->view('default.sitemaps')->header('Content-Type', 'application/xml');
});

/*
 |
 |-----------------------------------
 | Ajax Request
 |--------- -------------------------
 */
Route::post('ajax/like', 'AjaxController@like');
Route::post('ajax/follow', 'AjaxController@follow');
Route::get('ajax/notifications', 'AjaxController@notifications');
Route::get('ajax/users', 'AjaxController@users');
Route::get('ajax/search', 'AjaxController@search');
Route::get('ajax/latest', 'AjaxController@latest');
Route::get('ajax/featured', 'AjaxController@featured');
Route::get('ajax/popular', 'AjaxController@popular');
Route::get('ajax/commented', 'AjaxController@commented');
Route::get('ajax/viewed', 'AjaxController@viewed');
Route::get('ajax/downloads', 'AjaxController@downloads');
Route::get('ajax/category', 'AjaxController@category');
Route::get('ajax/tags', 'AjaxController@tags');
Route::get('ajax/cameras', 'AjaxController@camera');
Route::get('ajax/colors', 'AjaxController@colors');
Route::get('ajax/user/images', 'AjaxController@userImages');
Route::get('ajax/comments', 'AjaxController@comments');
Route::get('ajax/premium', 'AjaxController@premium');

/*
 |
 |-----------------------------------
 | User Views LOGGED
 |--------- -------------------------
 */
Route::group(['middleware' => 'auth'], function() {

	//<---- Upload
	 Route::get('upload','ImagesController@showUpload');
	 Route::post('upload','ImagesController@create');

    Route::get('upload/video','ImagesController@showUploadVideo');
    Route::post('upload/video','ImagesController@createVideo');

	// Edit Photo
	Route::get('edit/photo/{id}','ImagesController@edit');
	Route::post('update/photo','ImagesController@update');

	// Delete Photo 6
	Route::post('delete/photo/{id}','ImagesController@destroy');

    // Edit video
    Route::get('edit/video/{id}','ImagesController@editVideo');
    Route::post('update/video','ImagesController@updateVideo');

    // Delete video 6
    Route::post('delete/video/{id}','ImagesController@destroyVideo');

	// Account Settings
	Route::get('account','UserController@account');
	Route::post('account','UserController@update_account');

	// Password
	Route::get('account/password','UserController@password');
	Route::post('account/password','UserController@update_password');

	// Delete Account
	Route::get('account/delete','UserController@delete');
	Route::post('account/delete','UserController@delete_account');

	// Upload Avatar
	Route::post('upload/avatar','UserController@upload_avatar');

	// Upload Cover
	Route::post('upload/cover','UserController@upload_cover');

	// Likes
	Route::get('likes','UserController@userLikes');

	// Feed
	Route::get('feed','UserController@followingFeed');

	// Photos Pending
	Route::get('photos/pending','UserController@photosPending');

	// Notifications
	Route::get('notifications','UserController@notifications');
	Route::get('notifications/delete','UserController@notificationsDelete');



	// Report Photo
	Route::post('report/photo','ImagesController@report');

	// Report User
	Route::post('report/user','UserController@report');

	// Collections
	Route::post('collection/store','CollectionController@store');

	// Collection Edit
	Route::post('collection/edit','CollectionController@edit');

	// Collectin Delete
	Route::get('collection/delete/{id}','CollectionController@destroy');

	// Add Image to Collection
	Route::get('collection/{id}/i/{image}', 'CollectionController@addImageCollection')->where(array( 'id' => '[0-9]+','image' => '[0-9]+'));

	// Comments
	Route::post('comment/store','CommentsController@store');

	// Comments Delete
	Route::post('comment/delete','CommentsController@destroy');

	// Comment Like
	Route::post('comment/like','CommentsController@like');

	//======= DASHBOARD ================//
	// Dashboard
	Route::get('user/dashboard','DashboardController@dashboard');

	// Photos
	Route::get('user/dashboard/photos','DashboardController@photos');

	// Sales
	Route::get('user/dashboard/sales','DashboardController@sales');

	// Purchases
	Route::get('user/dashboard/purchases','DashboardController@purchases');

	// Deposits
	Route::get('user/dashboard/deposits','DashboardController@deposits');

	// Add Funds
	Route::get('user/dashboard/add/funds','DashboardController@addFunds');
	Route::post('user/dashboard/add/funds','AddFundsController@send');

	// Withdrawals
	Route::get('user/dashboard/withdrawals','DashboardController@showWithdrawal');

	// Request withdrawal
	Route::post('request/withdrawal','DashboardController@withdrawal');

	Route::get('user/dashboard/withdrawals/configure','DashboardController@withdrawalsConfigureView');

	Route::post('user/withdrawals/configure/{type}','DashboardController@withdrawalConfigure');

	Route::post('delete/withdrawal/{id}','DashboardController@withdrawalDelete');

	// Download Photo by creator
	Route::post('purchase/stock/{token_id}','ImagesController@purchase');

	// Download Photo by Subscription
	Route::post('subscription/stock/{token_id}','ImagesController@subscriptionDownload');

	// Purchase Photo
	Route::post('buy/stock/{token}','CheckoutController@send');

	// Stripe Connect
	Route::get('stripe/connect', 'StripeConnectController@redirectToStripe')->name('redirect.stripe');
	Route::get('connect/{token}', 'StripeConnectController@saveStripeAccount')->name('save.stripe');

	// Subscription
	Route::post('buy/subscription','SubscriptionsController@buy');

	// Account Subscription
	Route::get('account/subscription','UserController@subscription');
	Route::get('buy/subscription/success','SubscriptionsController@success')->name('success.subscription');
	Route::post('account/subscription/cancel','SubscriptionsController@cancel');

});//<------ End User Views LOGGED

// See all Comments Likes
	Route::post('comments/likes','CommentsController@getLikes');

/*
 |
 |-----------------------------------
 | User Views
 |--------- -------------------------
 */

//<----------- USERS VIEWS ---------->>>

// Downloads Images
Route::group(['middleware' => 'downloads'], function() {
	Route::post('download/stock/{token_id}','ImagesController@download');
});


/*
 |
 |-----------------------------------
 | Profile User
 |-----------------------------------
 */

Route::get('{slug}', 'UserController@profile')->where('slug','[A-Za-z0-9\_-]+')->name('profile');
Route::get('{slug}/followers', 'UserController@followers')->where('slug','[A-Za-z0-9\_-]+')->name('profile');
Route::get('{slug}/following', 'UserController@following')->where('slug','[A-Za-z0-9\_-]+')->name('profile');
Route::get('{slug}/collections', 'UserController@collections')->where('slug','[A-Za-z0-9\_-]+')->name('profile');

/*
 |
 |-----------------------------------
 | Admin Panel
 |--------- -------------------------
 */
Route::group(['middleware' => 'role'], function() {


	// Upgrades
	Route::get('update/{version}','UpgradeController@update');

	// Dashboard
	Route::get('panel/admin','AdminController@dashboard')->name('dashboard');

	// Categories
	Route::get('panel/admin/categories','AdminController@categories')->name('categories');
	Route::get('panel/admin/categories/add','AdminController@addCategories')->name('categories');
	Route::post('panel/admin/categories/add','AdminController@storeCategories');
	Route::get('panel/admin/categories/edit/{id}','AdminController@editCategories')->name('categories');
	Route::post('panel/admin/categories/update','AdminController@updateCategories');
	Route::post('panel/admin/categories/delete/{id}','AdminController@deleteCategories');

	// Settings
	Route::get('panel/admin/settings','AdminController@settings')->name('general_settings');
	Route::post('panel/admin/settings','AdminController@saveSettings');


	// Limits
	Route::get('panel/admin/settings/limits','AdminController@settingsLimits')->name('general_settings');
	Route::post('panel/admin/settings/limits','AdminController@saveSettingsLimits');

	Route::view('panel/admin/announcements','admin.announcements')->name('announcements');
	Route::post('panel/admin/announcements', 'AdminController@storeAnnouncements');

	// Images
	Route::get('panel/admin/images','AdminController@images')->name('images');
	Route::post('panel/admin/images/delete','AdminController@delete_image');

	Route::get('panel/admin/images/{id}','AdminController@edit_image')->name('images');
	Route::post('panel/admin/images/update','AdminController@update_image');

	// Members
	Route::get('panel/admin/members','AdminUserController@index')->name('members');
	Route::get('panel/admin/members/edit/{id}','AdminUserController@edit')->name('members');
	Route::post('panel/admin/members/edit/{id}', 'AdminUserController@update');
	Route::post('panel/admin/members/{id}', 'AdminUserController@destroy')->name('user.destroy');

	// Members Reported
	Route::get('panel/admin/members-reported','AdminController@members_reported')->name('members_reported');
	Route::post('panel/admin/members-reported','AdminController@delete_members_reported');

	// Images Reported
	Route::get('panel/admin/images-reported','AdminController@images_reported')->name('images_reported');
	Route::post('panel/admin/images-reported','AdminController@delete_images_reported');

	// Pages
	Route::get('panel/admin/pages','PagesController@index')->name('pages');
	Route::get('panel/admin/pages/create','PagesController@create')->name('pages');
	Route::post('panel/admin/pages/create','PagesController@store');
	Route::get('panel/admin/pages/edit/{id}','PagesController@edit')->name('pages');
	Route::post('panel/admin/pages/edit/{id}', 'PagesController@update');
	Route::post('panel/admin/pages/{id}', 'PagesController@destroy')->name('pages.destroy');

	// Profiles Social
	Route::get('panel/admin/profiles-social','AdminController@profiles_social')->name('profiles_social');
	Route::post('panel/admin/profiles-social','AdminController@update_profiles_social');

	// Google
	Route::get('panel/admin/google','AdminController@google')->name('google');
	Route::post('panel/admin/google','AdminController@update_google');

	//***** Languages
	Route::get('panel/admin/languages','LangController@index')->name('languages');

	// ADD NEW LANG
	Route::get('panel/admin/languages/create','LangController@create')->name('languages');
	Route::post('panel/admin/languages/create','LangController@store');
	Route::get('panel/admin/languages/edit/{id}','LangController@edit')->name('languages');
	Route::post('panel/admin/languages/edit/{id}', 'LangController@update');
	Route::post('panel/admin/languages/{id}', 'LangController@destroy')->name('languages.destroy');

	// BULK UPLOAD
	Route::get('panel/admin/bulk-upload','BulkUploadController@bulkUpload')->name('bulk_upload');
	Route::post('panel/admin/bulk-upload','BulkUploadController@bulkUploadStore');
	Route::post('panel/admin/bulk/delete/media','BulkUploadController@destroy');

	// THEME
	Route::get('panel/admin/theme','AdminController@theme')->name('theme');
	Route::post('panel/admin/theme','AdminController@themeStore');

	// Custom CSS/JS
	Route::view('panel/admin/custom-css-js','admin.css-js')->name('custom_css_js');
	Route::post('panel/admin/custom-css-js','AdminController@customCssJs');

	// Payments
	Route::get('panel/admin/payments','AdminController@payments')->name('payment_settings');
	Route::post('panel/admin/payments','AdminController@savePayments');

	Route::get('panel/admin/payments/{id}','AdminController@paymentsGateways')->name('payment_settings');
	Route::post('panel/admin/payments/{id}','AdminController@savePaymentsGateways');

	// Purchases
	Route::get('panel/admin/purchases','AdminController@purchases')->name('purchases');

	// Deposits
	Route::get('panel/admin/deposits','AdminController@deposits')->name('deposits');

	//Withdrawals
	Route::get('panel/admin/withdrawals','AdminController@withdrawals')->name('withdrawals');
	Route::get('panel/admin/withdrawal/{id}','AdminController@withdrawalsView')->name('withdrawals');
	Route::post('panel/admin/withdrawals/paid/{id}','AdminController@withdrawalsPaid');

	// Maintenance mode
	Route::view('panel/admin/maintenance','admin.maintenance')->name('maintenance_mode');
	Route::post('panel/admin/maintenance','AdminController@maintenance');

	// Clear Cache
	Route::get('panel/admin/clear-cache', 'AdminController@clearCache')->name('maintenance_mode');

	// BILLING
	Route::view('panel/admin/billing','admin.billing')->name('billing');
	Route::post('panel/admin/billing','AdminController@billingStore');

	// Tax Rates
	Route::get('panel/admin/tax-rates','TaxRatesController@show')->name('tax_rates');
	Route::view('panel/admin/tax-rates/add', 'admin.add-tax')->name('tax_rates');
	Route::post('panel/admin/tax-rates/add', 'TaxRatesController@store');
	Route::get('panel/admin/tax-rates/edit/{id}', 'TaxRatesController@edit')->name('tax_rates');
	Route::post('panel/admin/tax-rates/update', 'TaxRatesController@update');
	Route::post('panel/admin/ajax/states', 'TaxRatesController@getStates');

	// Plans
	Route::get('panel/admin/plans','PlansController@show')->name('plans');
	Route::view('panel/admin/plans/add', 'admin.add-plan')->name('plans');
	Route::post('panel/admin/plans/add', 'PlansController@store');
	Route::get('panel/admin/plans/edit/{id}', 'PlansController@edit')->name('plans');
	Route::post('panel/admin/plans/update', 'PlansController@update');

	// Subscriptions
	Route::get('panel/admin/subscriptions','AdminController@subscriptions')->name('subscriptions');

	// Countries
	Route::get('panel/admin/countries', 'CountriesStatesController@countries')->name('countries');
	Route::view('panel/admin/countries/add', 'admin.add-country')->name('countries');
	Route::post('panel/admin/countries/add', 'CountriesStatesController@addCountry');
	Route::get('panel/admin/countries/edit/{id}', 'CountriesStatesController@editCountry')->name('countries');
	Route::post('panel/admin/countries/update', 'CountriesStatesController@updateCountry');
	Route::post('panel/admin/countries/delete/{id}', 'CountriesStatesController@deleteCountry');

	// States
	Route::get('panel/admin/states', 'CountriesStatesController@states')->name('states');
	Route::view('panel/admin/states/add', 'admin.add-state')->name('states');
	Route::post('panel/admin/states/add', 'CountriesStatesController@addState');
	Route::get('panel/admin/states/edit/{id}', 'CountriesStatesController@editState')->name('states');
	Route::post('panel/admin/states/update', 'CountriesStatesController@updateState');
	Route::post('panel/admin/states/delete/{id}', 'CountriesStatesController@deleteState');

	// EMAIL SETTINGS
	Route::view('panel/admin/settings/email','admin.email-settings')->name('email_settings');
	Route::post('panel/admin/settings/email','AdminController@emailSettings');

	// STORAGE
	Route::view('panel/admin/storage','admin.storage')->name('storage');
	Route::post('panel/admin/storage','AdminController@storage');

	// Social Login
	Route::view('panel/admin/social-login','admin.social-login')->name('social_login');
	Route::post('panel/admin/social-login','AdminController@updateSocialLogin');

	// PWA
	Route::view('panel/admin/pwa','admin.pwa')->name('pwa');
	Route::post('panel/admin/pwa','AdminController@pwa');

	// Role and permissions
	Route::get('panel/admin/roles-and-permissions', 'RolesAndPermissionsController@index')->name('role_and_permissions');
	Route::view('panel/admin/roles-and-permissions/create', 'admin.add-role')->name('role_and_permissions');
	Route::post('panel/admin/roles-and-permissions/create', 'RolesAndPermissionsController@store');
	Route::get('panel/admin/roles-and-permissions/edit/{id}', 'RolesAndPermissionsController@edit')->name('role_and_permissions');
	Route::post('panel/admin/roles-and-permissions/update', 'RolesAndPermissionsController@update');
	Route::post('panel/admin/roles-and-permissions/delete/{id}', 'RolesAndPermissionsController@destroy');

});//<--- End Group Role

Route::get('lang/{id}','AdminController@language')->where(['id' => '[a-z]+']);

// Version 3.2
Route::get('install/{addon}','InstallController@install');

// PayPal Add funds
Route::get('payment/paypal','PayPalController@show')->name('paypal');
Route::get('paypal/success','PayPalController@success')->name('paypal.success');

// PayPal Buy
Route::get('paypal/buy','PayPalController@buy')->name('paypal.buy');
Route::get('paypal/buy/success', 'PayPalController@successBuy')->name('buy.success');

// PayPal Cancel
Route::get('paypal/cancel', 'PayPalController@cancel')->name('paypal.cancel');

// Stripe Add funds
Route::get('payment/stripe', 'StripeController@show')->name('stripe');
Route::post('payment/stripe/charge', 'StripeController@charge');

// Stripe Buy
Route::get('payment/stripe/buy', 'StripeController@buy')->name('stripe.buy');

// Stripe Subscription
Route::get('payment/stripe/subscription', 'StripeController@subscription')->name('stripe.subscription');

// Stripe Webhook
Route::post('stripe/webhook', 'StripeWebHookController@handleWebhook');

Route::get('user/dashboard/downloads','DashboardController@downloads')->middleware('auth');
Route::get('files/preview/{size}/{path}', 'ImagesController@image')->where('path', '.*');
Route::get('assets/preview/{path}.{ext}', 'ImagesController@preview');

Route::get('files/video/preview/{size}/{path}', 'ImagesController@video')->where('path', '.*');


Route::get('invoice/{id}','UserController@invoice');
Route::get('my/referrals','UserController@myReferrals')->middleware('auth');
Route::post('verify/2fa', 'TwoFactorAuthController@verify');
Route::post('2fa/resend', 'TwoFactorAuthController@resend');

Route::get('installer/script','InstallScriptController@wizard');
Route::post('installer/script/database','InstallScriptController@database');
Route::post('installer/script/user','InstallScriptController@user');

Route::get('payment/paystack', 'PaystackController@show')->name('paystack');
Route::get('payment/paystack/buy', 'PaystackController@buy')->name('paystack.buy');

Route::get('payment/mollie', 'MollieController@show')->name('mollie');

Route::get('payment/mollie/buy', 'MollieController@buy')->name('mollie.buy');
Route::post('webhook/mollie', 'MollieController@webhook');

Route::get('payment/razorpay', 'RazorpayController@show')->name('razorpay');
Route::get('payment/razorpay/buy', 'RazorpayController@buy')->name('razorpay.buy');

Route::get('payment/instamojo', 'InstamojoController@show')->name('instamojo');

Route::get('payment/instamojo/buy', 'InstamojoController@buy')->name('instamojo.buy');
Route::get('webhook/instamojo', 'InstamojoController@webhook')->name('webhook.instamojo');

Route::get('panel/admin/collections','AdminController@collections')->name('collections')->middleware('role');
Route::post('panel/admin/collections','AdminController@deleteCollection')->middleware('role');

Route::get('payment/coinpayments', 'CoinpaymentsController@show')->name('coinpayments');
Route::get('payment/coinpayments/buy', 'CoinpaymentsController@buy')->name('coinpayments.buy');
Route::post('webhook/coinpayments', 'CoinpaymentsController@webhook')->name('coinpaymentsIPN');

Route::get('payment/flutterwave', 'FlutterwaveController@show')->name('flutterwave');
Route::get('payment/flutterwave/buy', 'FlutterwaveController@buy')->name('flutterwave.buy');
Route::get('callback/flutterwave', 'FlutterwaveController@callback')->name('flutterwaveCallback');



// shetab Add funds
//Route::get('payment/shetab','ZarinpalController@payRequest')->name('zarinpal');
//Route::get('shetab/success','ZarinpalController@payVerify')->name('zarinpal.verify');

//// shetab Buy
Route::get('shetab/buy','ZarinpalController@buy')->name('shetab.buy');
Route::get('shetab/buy/success', 'ZarinpalController@successBuy')->name('shetab.buy.success');
//
//// shetab Cancel
//Route::get('paypal/cancel', 'PayPalController@cancel')->name('paypal.cancel');
