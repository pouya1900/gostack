<?php

namespace App;

use Cookie;
use App\Models\AdminSettings;
use App\Models\Countries;
use App\Models\Referrals;
use App\Models\User;
use App\Helper;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    public function createOrGetUser(ProviderUser $providerUser, $provider )
    {
      $settings = AdminSettings::first();

      $user = User::whereOauthProvider($provider)
          ->whereOauthUid($providerUser->getId())
          ->first();

      if (! $user) {
        //return 'Error! Your email is required, Go to app settings and delete our app and try again';
        if (! $providerUser->getEmail()) {
          return redirect("login")->with(['login_required' => trans('error.error_required_mail')]);
          exit;
        }

        //Verify Email user
        $userEmail = User::whereEmail($providerUser->getEmail())->first();

        if ($userEmail) {
          return redirect("login")->with(['login_required' => trans('error.mail_exists')]);
          exit;
        }

        $token = str_random(75);
        $avatar = 'default.jpg';
        $nameAvatar = time().$providerUser->getId();
        $path = config('path.avatar');

        if (! empty($providerUser->getAvatar())) {

          // Get Avatar Large Facebook
          if ($provider == 'facebook') {
            $avatarUser = str_replace('?type=normal', '?type=large', $providerUser->getAvatar());
          }

          // Get Avatar Large Twitter
          if ($provider == 'twitter' ) {
            $avatarUser = str_replace('_normal', '_200x200', $providerUser->getAvatar());
          }

          // Get Avatar Google
          if ($provider == 'google') {
            $avatarUser = $providerUser->getAvatar();
          }

          $fileContents = file_get_contents($avatarUser);

          // Storage avatar user
          \Storage::put($path.$nameAvatar.'.jpg', $fileContents, 'public');

          $avatar = $nameAvatar.'.jpg';

        }// empty getAvatar

        // Get user country
        $country = Countries::whereCountryCode(Helper::userCountry())->first();

				$user = User::create([
					'username'        => Helper::strRandom(),
					'name'            => $providerUser->getName(),
          'countries_id'    => $country->id ?? '',
					'password'        => '',
					'email'           => strtolower($providerUser->getEmail()),
					'avatar'          => $avatar,
					'cover'           => 'cover.jpg',
					'status'          => 'active',
					'type_account'    => '1',
          'website'         => '',
					'activation_code' => '',
          'oauth_uid'       => $providerUser->getId(),
          'oauth_provider'  => $provider,
					'token'           => $token,
          'ip'              => request()->ip(),
			]);

      // Check Referral
      if ($settings->referral_system == 'on') {

        $referredBy = User::find(Cookie::get('referred'));

        if ($referredBy) {
          Referrals::create([
            'user_id' => $user->id,
            'referred_by' => $referredBy->id,
          ]);
        }
      }

    }// !$user
        return $user;
    }
}
