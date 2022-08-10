<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TwoFactorCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TwoFactorAuthController extends Controller
{
	use Traits\FunctionsTrait;

    /**
     * Check if the code is correct, and log in
     */
    public function verify(Request $request)
    {
			$messages = [
				'code.required' => trans('misc.please_enter_code')
			];

        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ], $messages);

				if ($validator->fails()) {
						return response()->json([
								'success' => false,
								'errors' => $validator->getMessageBag()->toArray()
						]);
				}

				$verifyCode = TwoFactorCodes::whereUserId(session('user:id'))
                        ->where('code', $request->code)
                        ->where('updated_at', '>=', now()->subMinutes(2))
                        ->first();

        if ($verifyCode) {

					// Delete old code
					TwoFactorCodes::whereUserId(session('user:id'))->delete();

					// Login user
					auth()->loginUsingId(session()->pull('user:id'), true);

					return response()->json([
			        'success' => true,
			        'redirect' => url('/')
			    ]);
        }

				return response()->json([
		        'success' => false,
		        'errors' => ['error' => trans('misc.code_2fa_invalid')]
		    ]);
    }// End method

		/**
     * Resend code
     */
		public function resend()
    {
			// Delete old code
			TwoFactorCodes::whereUserId(session('user:id'))->delete();

			// Get User details
			$user = User::findOrFail(session('user:id'));

      $this->generateTwofaCode($user);

				return response()->json([
						'success' => true,
						'text' => trans('misc.resend_code_success')
				]);
    }
}
