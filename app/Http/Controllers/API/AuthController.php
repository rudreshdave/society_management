<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\CommonController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\User;
use App\Models\Society;
use App\Models\UserRole;
use App\Traits\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ChangePasswordRequest;
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

    /**
     * Summary of profile
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $profile = User::select(['id', 'name', 'email', 'mobile', 'status'])->with(['societies'])->where(['id' => Auth::id()])->first();
            if (isset($profile) && !empty($profile)) {
                return $this->customResponse(1, trans("translate.profile_found_successfully!"), $profile);
            }
        } catch (\Exception $ex) {
            return $this->customResponse(-1, null, $ex->getMessage());
        }
    }

    /**
     * Summary of profile_update
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile_update(Request $request)
    {
        try {
            $user = Auth::User();

            if (isset($user) && !empty($user)) {
                $user->name = isset($request->name) && !empty($request->name) ? $request->name : $user->name;
                $user->email = isset($request->email) && !empty($request->email) ? $request->email : $user->email;
                $user->mobile = isset($request->mobile) && !empty($request->mobile) ? $request->mobile : $user->mobile;
                $user->status = isset($request->status) && !empty($request->status) ? $request->status : $user->status;

                // Handle Profile Image Upload
                if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                    // Delete the old image if exists
                    if ($user->profile_image && Storage::disk('public')->exists($user->getRawOriginal('profile_image'))) {
                        Storage::disk('public')->delete($user->getRawOriginal('profile_image'));
                    }
                    // Store the new image
                    $path = $request->file('profile_image')->store('profile_images/' . $user->id, 'public');
                    $user->profile_image = $path;
                }
                $user->save();
                if (isset($user) && !empty($user) && $user->roles[0]['id'] == 2) {
                    $user_role = UserRole::where(['user_id' => $user->id])->first();
                    if (isset($user_role) && !empty($user_role)) {
                        $society = Society::find($user_role->society_id);
                        $society->society_name = isset($request->society_name) && !empty($request->society_name) ? $request->society_name : $society->mobile;
                        $society->registration_no = isset($request->registration_no) && !empty($request->registration_no) ? $request->registration_no : $society->registration_no;
                        $society->address_line_1 = isset($request->address_line_1) && !empty($request->address_line_1) ? $request->address_line_1 : $society->address_line_1;
                        $society->address_line_2 = isset($request->address_line_2) && !empty($request->address_line_2) ? $request->address_line_2 : $society->address_line_2;
                        $society->city_id = isset($request->city_id) && !empty($request->city_id) ? $request->city_id : $society->city_id;
                        $society->state_id = isset($request->state_id) && !empty($request->state_id) ? $request->state_id : $society->state_id;
                        $society->pincode = isset($request->pincode) && !empty($request->pincode) ? $request->pincode : $society->pincode;
                        $society->contact_email = isset($request->email) && !empty($request->email) ? $request->email : $society->email;
                        $society->contact_mobile = isset($request->mobile) && !empty($request->mobile) ? $request->mobile : $society->mobile;
                        $society->total_wings = isset($request->total_wings) && !empty($request->total_wings) ? $request->total_wings : $society->total_wings;
                        $society->total_flats = isset($request->total_flats) && !empty($request->total_flats) ? $request->total_flats : $society->total_flats;
                        $society->status = isset($request->status) && !empty($request->status) ? $request->status : $society->status;


                        $society->save();
                    }
                }
                return $this->customResponse(1, trans("translate.profile_updated_successfully!"), $user);
            } else {
                return $this->customResponse(8);
            }
        } catch (\Exception $ex) {
            return $this->customResponse(-1, null, $ex->getMessage());
        }
    }

    /**
     * Summary of change_password
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function change_password(ChangePasswordRequest $request)
    {
        try {
            $society = Auth::User();
            if (isset($society) && !empty($society)) {
                $society->password = Hash::make($request->new_password);
                $society->save();
                return $this->customResponse(1, trans("translate.change_password"));
            }
        } catch (\Exception $ex) {
            return $this->customResponse(-1, null, $ex->getMessage());
        }
    }
}
