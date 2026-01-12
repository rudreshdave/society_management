<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\CommonController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\User;
use App\Models\UserRole;
use App\Traits\ApiResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use DB,
    Auth,
    Log;

class AuthController extends CommonController
{
    use ApiResponse;
    /**
     * @var \App\Helpers\Helper
     */
    private $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Summary of login
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $loginType = $request->input('logintype'); // Expecting 1 for email/password and 2 for mobile/OTP

            if ($loginType == 1) {
                // Email/Password Login
                if (Auth::attempt($request->only('mobile', 'password'))) {

                    $user = Auth::user();

                    // Check if user is blocked
                    if ($user->status == 3) {
                        return $this->customResponse(4, trans('translate.user_blocked'));
                    }


                    $societies = $user->societies;
                    if ($societies->count() === 1) {
                        $user->token = $user->createToken('DWARX', ["society_id" => $user->societies[0]->id])->plainTextToken;
                        if ($user->usertype == 2) {
                            $society_id = $user->societies[0]->id;
                            $society = null;
                            foreach (Auth::user()->societies as $society_details) {
                                if ($society_details['id'] == $society_id) {
                                    $society = $society_details;
                                }
                            }
                            if (isset($society->database_name) && !empty($society->database_name) && isset($society->database_username) && !empty($society->database_username) && isset($society->database_password) && !empty($society->database_password)) {
                                $this->helper->set_society_wise_database($society->database_name);
                            }
                        }
                        return $this->customResponse(1, trans('translate.user_logged_in'), $user);
                    } elseif ($user->usertype == 0) {
                        $user->token = $user->createToken('DWARX')->plainTextToken;
                        return $this->customResponse(1, trans('translate.user_logged_in'), $user);
                    } else {
                        $user->token = null;
                        return $this->customResponse(1, trans('translate.user_logged_in'), $user);
                    }
                } else {
                    return $this->customResponse(4, trans('translate.invalid_credentials'));
                }
            } elseif ($loginType == 2) {
                // Mobile/OTP Login
                $user = User::where('mobile', $request->mobile)->first();

                if ($user) {
                    // Check if user is blocked
                    if ($user->status == 3) {
                        return $this->customResponse(4, trans('translate.user_blocked'));
                    }
                    // Generate and send OTP to the user's registered email
                    $otp = rand(100000, 999999);
                    $user->user_otp = $otp;
                    $user->otp_expiration = now()->addMinutes(5);
                    $user->save();
                    $message = "Dear User, Your OTP for login to MBT is $otp. Please do not share this OTP.Regards, WLG Team";
                    // $this->helper->send_sms($request->mobile, $message);
                    //                    Mail::to($user->email)->send(new SendOtpMail($otp));
                    //mail
                    try {
                        $TemplateData = array(
                            'EMAIL' => $user->email,
                            'USER_NAME' => $user->name,
                            'OTP' => $user->user_otp
                        );
                        // MailHelper::sendMail('OTP_LOGIN', $TemplateData);
                        // $user->otp = $user->forgot_password_token;
                        return $this->customResponse(1, trans("translate.otp_login"), $user);
                    } catch (\Exception $ex) {
                        return $this->customResponse(-1, null, $ex->getMessage()->all());
                    }

                    return $this->customResponse(1, trans('translate.otp_sent'), $user);
                } else {
                    return $this->customResponse(4, trans('translate.phone_number_not_registered'));
                }
            } else {
                return $this->customResponse(4, trans('translate.invalid_login_type'));
            }
        } catch (\Throwable $th) {
            dd($th->getMessage() . ":" . $th->getLine() . ":" . $th->getFile());
            return $this->customResponse(-1, null, $th->getMessage());
        }
    }

    /**
     * Summary of logout
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {

            if (Auth::check()) {
                if (Auth::user()->usertype !== '0') {
                    $society_id = Auth::user()->currentAccessToken()->abilities['society_id'];
                    $society = null;
                    foreach (Auth::user()->societies as $society_details) {
                        if ($society_details['id'] == $society_id) {
                            $society = $society_details;
                        }
                    }
                    // $database_change = $this->helper->configure_database_connection($society->database_name);

                    // $devices = UserPushDevice::where('user_id', $society->company_user_id)->get();
                    // foreach ($devices as $device) {
                    //     $device->forceDelete();
                    // }

                    $database_change = $this->helper->configure_database_connection(env('DB_DATABASE'));
                }

                $token = Auth::user()->currentAccessToken();
                $token->delete();
                return $this->customResponse(1, trans("translate.logged_out"));
            } else {
                return $this->customResponse(1, trans("translate.not_logged_in"));
            }
        } catch (\Exception $ex) {
            return $this->customResponse(-1, null, $ex->getMessage());
        }
    }
}
