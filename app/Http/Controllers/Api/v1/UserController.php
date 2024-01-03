<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PharmacloudsService;
use App\Models\Order;
use App\Models\UserFavorite;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Session;
use Illuminate\Support\Str;
use Crypt;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Models\UserDeviceLoginToken;
use App\Models\AdminSetting;
use Carbon\Carbon;
use JWTAuth;

class UserController extends Controller
{
    const ITEMS_PER_PAGE = 10;
    public function __construct()
    {
    }
    public function register(Request $request) {
//    	$return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'name'=>'required',
            'email'=>'required|email',
            'phone_number'=>'required',
            'password'=>'required|min:6',
            'role_id'=>'required|integer',
            'id_card' => 'file|required_if:role_id,3',
            'iban_card' => 'file|required_if:role_id,3',
        ];

        $messages = [
            'name.required'=>trans('messages.name_required'),
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
            'phone_number.required'=>trans('messages.phone_number_required'),
            'password.required'=>trans('messages.password_required'),
            'password.min'=>trans('messages.password_min'),
            'role_id.required'=>trans('messages.role_id_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }


        $check_user_by_phone_number = User::where('phone_number',$request->phone_number)->first();
        if($check_user_by_phone_number){
            $message = __('messages.phone_already_register');
            return InvalidResponse($message,101,[]);    
        }
        $check_user = User::where('email',$request->email)->first();
        
        if(!$check_user) {
            if($request->role_id == 3) {
                $id_card = $request->file('id_card');
                $iban_card = $request->file('iban_card');
                
                $id_cardpath =  uniqid()."_".time()."_".$id_card->getClientOriginalName();
                $iban_cardpath = uniqid()."_".time()."_".$iban_card->getClientOriginalName();
        
                $idDstinationPathAbsolute = '/home/quaferec/public_html/api.quafere.com.sa/public/ids';
                $ibanDstinationPathAbsolute = '/home/quaferec/public_html/api.quafere.com.sa/public/iban';
            
        
                $id_card->move($idDstinationPathAbsolute,$id_cardpath);
                $iban_card->move($ibanDstinationPathAbsolute,$iban_cardpath);
            }
        	$check_user = new User;
            $check_user->name = $request->name;
            $check_user->email = $request->email;
            $check_user->password = Hash::make($request->password);
        	$check_user->phone_number = $request->phone_number;
            $check_user->role_id = $request->role_id;
            $check_user->id_card = $request->role_id == 3 ? "public/ids/". $id_cardpath : NULL;
            $check_user->iban_card = $request->role_id == 3 ? "public/iban/". $iban_cardpath : NULL;
            
            if($request->payment_type) {
                $check_user->payment_type = $request->payment_type;
            }
            if($request->refrel_phone_number) {
                $check_user->refrel_phone_number = $request->refrel_phone_number;
            }
            if($request->role_id == 3) {
                $check_user->is_active = 0;
            }
        	$check_user->save();

            if($request->device_type && $request->token) {
                $check_user_device_token = UserDeviceToken::where('user_id',$check_user->id)->where('device_type',$request->device_type)->where('token',$request->token)->first();
                if(!$check_user_device_token) {
                    $new_user_device_token = new UserDeviceToken;
                    $new_user_device_token->user_id = $check_user->id;
                    $new_user_device_token->device_type = $request->device_type;
                    $new_user_device_token->token = $request->token;
                    $new_user_device_token->save();
                }
            }
            $token = JWTAuth::fromUser($check_user);
            
            $new_user__login_device_token = new UserDeviceLoginToken;
            $new_user__login_device_token->user_id = $check_user->id;
            $new_user__login_device_token->token = $token;
            $new_user__login_device_token->save();
            
            $check_user->token = $token;
            if($request->role_id == 3) {
                $message = __('messages.dresser_register_success');
                return SuccessResponse($message,102,$check_user);
            }else{
                $message = __('messages.user_register_success');
                return SuccessResponse($message,200,$check_user);    
            }
            

        }

        $message = __('messages.email_already_register');
        return InvalidResponse($message,101,[]);
    }

    public function forgot_password_otp(Request $request) {
//        $return = veriftyAPITokenData();
//
//        //echo 'here';
//        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'email'=>'required|email',
            'otp'=>'required',
        ];

        $messages = [
            'otp.required'=>trans('messages.otp_required'),
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }


        $check_user = User::where('email',$request->email)->first();
        if($check_user) {

            if($request->otp) {
                $emaildata = [
                    'name'=>$request->name,
                    'otp'=>$request->otp
                ];
                $email = $request->email;
                $subject = __('messages.otp_subject');
                $path = 'emails.otp_mail';
                sendMail($path, $emaildata, $email, $subject);
                send_otp_sms($check_user->phone_number,$request->otp);
            }
            \Log::info('request : ', ['request' => json_encode($request->all())]);

            if($request->is_notification && $request->is_notification=='1') {
                $order_id = 0;
                if($request->order_id) {
                    $order_id = $request->order_id;
                }
                $title = 'Order otp';
                $message = 'Order '.$order_id.' otp '.$request->otp;
                $message_data = array(
                    'title' => $title,
                    'body' => $message,
                    'sound' => 'default'
                );
                $other_data = array(
                    'order_id'=>$order_id,
                );

                commonPushNotification($check_user->id, $message_data, $other_data);
            }
            $message = __('messages.otp_send_success');
            return SuccessResponse($message,200,[]);

        }
        $message = __('messages.user_email_not_found');
        return InvalidResponse($message,101,[]);
    }



    public function check_email(Request $request) {
//        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'name'=>'required',
            'email'=>'required|email',
            'phone_number'=>'required',
        ];

        $messages = [
            'name.required'=>trans('messages.name_required'),
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }


        $check_user = User::where('email',$request->email)->first();
        if(!$check_user) {

            if($request->otp) {
                $emaildata = [
                    'name'=>$request->name,
                    'otp'=>$request->otp
                ];
                $email = $request->email;
                $subject = __('messages.otp_subject');
                $path = 'emails.otp_mail';
                sendMail($path, $emaildata, $email, $subject);
                send_otp_sms($request->phone_number,$request->otp);
            }
            $message = __('messages.user_email_not_found');
            return SuccessResponse($message,200,[]);

        }

        $message = __('messages.email_already_register');
        return InvalidResponse($message,101,[]);
    }



    public function login(Request $request) {
//        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'email'=>'required|email',
            'password'=>'required|min:6',
            'role_id'=>'required|integer',
        ];

        $messages = [
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
            'password.required'=>trans('messages.password_required'),
            'password.min'=>trans('messages.password_min'),
            'role_id.required'=>trans('messages.role_id_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }


        $check_user_email = User::where('email',$request->email)->first();
        if(!empty($check_user_email)){
            if($check_user_email->role_id != $request->role_id){
                $message = __('messages.user_role_not_matched');
                return InvalidResponse($message,101);
            }
        }
        
        if(!empty($check_user_email)){
            if(!$check_user_email->is_active){
                $message = __('messages.not_active_account');
                return InvalidResponse($message,101);
            }
        }

        $check_user = User::where('email',$request->email)->where('role_id',$request->role_id)->with(['avgRating','totalEarning','review_list'=>function($q){
            $q = $q->with(['sender']);
        },'service'=>function($q){
            $q->with(['category_detail'=>function($q1){
                $q1->with(['parent_category']);
                }]);
            }])->first();
        if($check_user) {
            $current_date = date('Y-m-d H:i:s');


            if(Hash::check($request->password,$check_user->password)) {

                if($check_user->is_active==0) {
                    $message = __('messages.user_inactive_by_admin');
                    return InvalidResponse($message,101);
                }

                if($check_user->role_id==3) {
                    //$check_user->is_online = 1;
                    //$check_user->save();
                }

                // delete all token
                $deviceToken = UserDeviceLoginToken::where('user_id',$check_user->id)->get();
                // dd($deviceToken);
                foreach($deviceToken as $_token){
                    if($_token->token){
                         JWTAuth::setToken($_token->token);
                         JWTAuth::invalidate();
                    }
                    
                }
                
                UserDeviceLoginToken::where('user_id',$check_user->id)->delete();
                //
                $token = JWTAuth::fromUser($check_user);
                
                $new_user__login_device_token = new UserDeviceLoginToken;
                $new_user__login_device_token->user_id = $check_user->id;
                $new_user__login_device_token->token = $token;
                $new_user__login_device_token->save();
                
                $check_user->order_count = \App\Models\Order::where('hairdresser_id',$check_user->id)->whereIn('order_status',[7])->count();

                $check_user->token = $token;
                if($request->otp) {
                    $emaildata = [
                        'name'=>$request->name,
                        'otp'=>$request->otp
                    ];
                    $email = $request->email;
                    $subject = __('messages.otp_subject');
                    $path = 'emails.otp_mail';
                    sendMail($path, $emaildata, $email, $subject);
                }



                if($request->device_type && $request->token) {
                    $check_user_device_token = UserDeviceToken::where('user_id',$check_user->id)->where('device_type',$request->device_type)->where('token',$request->token)->first();
                    if(!$check_user_device_token) {
                        UserDeviceToken::where('user_id',$check_user->id)->where('device_type',$request->device_type)->delete();
                        $new_user_device_token = new UserDeviceToken;
                        $new_user_device_token->user_id = $check_user->id;
                        $new_user_device_token->device_type = $request->device_type;
                        $new_user_device_token->token = $request->token;
                        $new_user_device_token->save();
                    }
                }

                $message = __('messages.user_login_success');
                return SuccessResponse($message,200,$check_user);
            } else {
                $message = __('messages.login_invalid_credential');
                return InvalidResponse($message,101,[]);
            }

        }

        $message = __('messages.login_invalid_credential');
        return InvalidResponse($message,101,[]);
    }

    public function send_otp(Request $request) {
//        $return = veriftyAPITokenData();
//
//        //echo 'here';
//        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'otp'=>'required',
        ];

        $messages = [
            'otp.required'=>trans('messages.otp_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();

        $message = __('messages.otp_send_success');
        return SuccessResponse($message,200,[]);
    }

    public function resend_otp(Request $request) {
//        $return = veriftyAPITokenData();
//
//        //echo 'here';
//        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }
//
//        $rules = [
//            'name'=>'required',
//            'email'=>'required|email',
//            'otp'=>'required',
//
//        ];

        $messages = [
            'name.required'=>trans('messages.name_required'),
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
            'otp.required'=>trans('messages.otp_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }


        $check_user = User::where('email',$request->email)->first();
        if($check_user) {
            if($request->otp) {
                $emaildata = [
                    'name'=>$request->name,
                    'otp'=>$request->otp
                ];
                $email = $request->email;
                $subject = __('messages.otp_subject');
                $path = 'emails.otp_mail';
                sendMail($path, $emaildata, $email, $subject);
            }

            $message = __('messages.otp_send_success');
            return SuccessResponse($message,200,$check_user);
        }

        $message = __('messages.login_invalid_credential');
        return InvalidResponse($message,101,[]);
    }



    public function change_password(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            'password'=>'required|min:6',
            'new_password'=>'required|min:6',
        ];

        $messages = [
            'password.required'=>trans('messages.password_required'),
            'password.min'=>trans('messages.password_min'),
            'new_password.required'=>trans('messages.new_password_required'),
            'new_password.min'=>trans('messages.new_password_min'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();
        if(Hash::check($request->password,$user->password)) {
            $password = Hash::make($request->new_password);
            User::where('id',$user->id)->update(['password'=>$password]);
            $message = __('messages.change_password_success');
            return SuccessResponse($message,200,[]);
        }

        $message = __('messages.invalid_current_password');
        return InvalidResponse($message,101,[]);

    }

    public function forgot_password(Request $request) {
//        $return = veriftyAPITokenData();
//
//        //echo 'here';
//        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'email'=>'required|email',
            //'password'=>'required|min:6',
            'new_password'=>'required|min:6',
        ];

        $messages = [
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
            'password.required'=>trans('messages.password_required'),
            'password.min'=>trans('messages.password_min'),
            'new_password.required'=>trans('messages.new_password_required'),
            'new_password.min'=>trans('messages.new_password_min'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $check_user = User::where('email',$request->email)->first();
        if($check_user) {
            $password = Hash::make($request->new_password);
            User::where('id',$check_user->id)->update(['password'=>$password]);
            $message = __('messages.change_password_success');
            return SuccessResponse($message,200,[]);
        }

        $message = __('messages.user_email_not_found');
        return InvalidResponse($message,101,[]);

    }

    public function admin_settings(Request $request) {
//        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }



        $admin_setting = AdminSetting::all();
        $message = __('messages.admin_setting_success');
        return SuccessResponse($message,200,$admin_setting);
    }

    public function profile(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();
        $user = User::where('id',$user->id)->with(['avgRating','totalEarning','review_list'=>function($q){
            $q = $q->with(['sender']);
        },'service'=>function($q){
            $q->with(['category_detail'=>function($q1){
                $q1->with(['parent_category']);
            }]);
        }])->first();
        $user->order_count = \App\Models\Order::where('hairdresser_id',$user->id)->whereIn('order_status',[7])->count();
        $user->tax_amount = \App\Models\Order::where('hairdresser_id',$user->id)->whereIn('order_status',[7])->sum('tax_amount');

        $message = __('messages.profile_success');
        return SuccessResponse($message,200,$user);
    }

    public function banner_list(Request $request) {
//        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $list = \App\Models\Banner::all();

        $message = __('messages.banner_list_success');
        return SuccessResponse($message,200,$list);
    }



    public function update_profile(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            //'name'=>'required',
        ];

        $messages = [
            'name.required'=>trans('messages.name_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();
        $check_user = User::where('id',$user->id)->first();
        if($check_user) {
            if($request->name) {
                $check_user->name = $request->name;
            }

            if($request->phone_number) {
                $check_user->phone_number = $request->phone_number;
            }

            if($request->image) {
                $file = $request->file('image');
                //echo 'here';exit;
                $file_name = 'user_'.Str::random(10).'_'.time();
                $full_file_name = $file_name.'.'.$file->getClientOriginalExtension();
                //Move Uploaded File
                $destinationPath = 'user';
                $file->move($destinationPath,$full_file_name);
                $check_user->image = $full_file_name;
            }

            if($request->about) {
                $check_user->about = $request->about;
            }

            if($request->address) {
                $check_user->address = $request->address;
            }

            if($request->latitude) {
                $check_user->latitude = $request->latitude;
            }
            
            if($request->bank_account_number) {
                $check_user->bank_account_number = $request->bank_account_number;
            }
            if($request->name_in_bank_account) {
                $check_user->name_in_bank_account = $request->name_in_bank_account;
            }

            if($request->longitude) {
                $check_user->longitude = $request->longitude;
            }
            if($request->is_online) {
                if($request->is_online==1) {
                    //$date = Carbon::now()->addHours(-24)->format("Y-m-d H:i:s");
                    $date = Carbon::now()->addMinutes(-5)->format("Y-m-d H:i:s");
                    if($check_user->offline_date>$date) {
                        $message = __('messages.user_inactive_by_admin_24_hours');
                        return InvalidResponse($message,101);
                    }
                    if($check_user->reject_count==3 || $check_user->reject_count > 3){
                        $check_user->reject_count=0;
                    }
                }
                $check_user->is_online = $request->is_online;
                $check_user->is_online_date = date('Y-m-d H:i:s');
            }
            if($request->payment_type) {
                $check_user->payment_type = $request->payment_type;
            }
            $check_user->save();
            $message = __('messages.profile_update_success');
            return SuccessResponse($message,200,$check_user);
        }
        $message = __('messages.user_email_not_found');
        return InvalidResponse($message,101,[]);

    }

    public function logout(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();
        // dd($request->token);
        if($request->device_type && $request->token) {
            JWTAuth::invalidate($request->token);
            $check_user_device_token = UserDeviceToken::where('user_id',$user->id)->where('device_type',$request->device_type)->where('token',$request->token)->delete();

        }


        $message = __('messages.logout');
        return SuccessResponse($message,200,[]);

    }



    public function clear_notification_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();

        $list = new \App\Models\UserNotification;
        $list = $list->where('user_id',$user->id)->delete();
        $message = __('messages.notification_list_success');
        return SuccessResponse($message,200,array());

    }

    public function notification_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();

        $list = new \App\Models\UserNotification;
        $list = $list->where('user_id',$user->id);
        if($request->has('page')) {
            $page = $request->has('page') ? $request->page : 1;
            $page = $page - 1;
            $list = $list->skip($page * self::ITEMS_PER_PAGE)->take(self::ITEMS_PER_PAGE);
        }
        $list = $list->with('order_detail')->orderBy('id','desc')->get();
        $message = __('messages.notification_list_success');
        return SuccessResponse($message,200,$list);

    }

    public function wallet_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();
        //print_r($user->toArray());exit;
        $UserWalletHistory = new \App\Models\UserWalletHistory;
        $UserWalletHistory = $UserWalletHistory->where('user_id',$user->id);
        if($request->has('page')) {
            $page = $request->has('page') ? $request->page : 1;
            $page = $page - 1;
            $UserWalletHistory = $UserWalletHistory->skip($page * self::ITEMS_PER_PAGE)->take(self::ITEMS_PER_PAGE);
        }
        $UserWalletHistory = $UserWalletHistory->with('order_detail')->orderBy('id','desc')->get();
        $message = __('messages.wallet_history_success');
        return SuccessResponse($message,200,$UserWalletHistory);

    }

    public function user_withdraw_request(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
        ];

        $messages = [
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();


        $user_id = $user->id;
        $user = User::where('id',$user->id)->first();
        $user->is_withdraw_request = 1;
        $user->save();

        //pre($request->all());
        $message = __('messages.withdraw_request_success');
        return SuccessResponse($message,200,$user);

    }

    public function user_favorite(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            'hairdressor_id'=>'required',
            'type'=>'required',
        ];

        $messages = [
            'hairdressor_id.required'=>trans('messages.hairdressor_id_required'),
            'type.required'=>trans('messages.type_required'),

        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();

        if($request->type==1) {
            $new_favorite = new \App\Models\UserFavorite;
            $new_favorite->user_id = $user->id;
            $new_favorite->hairdressor_id = $request->hairdressor_id;
            $new_favorite->save();
            $message = __('messages.favorite_success');
        } else {
            \App\Models\UserFavorite::where('user_id',$user->id)->where('hairdressor_id',$request->hairdressor_id)->delete();
            $message = __('messages.unfavorite_success');

        }
        return SuccessResponse($message,200,[]);

    }

    public function user_favorite_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            //'hairdressor_id'=>'required',
            //'type'=>'required',
        ];

        $messages = [
            //'hairdressor_id.required'=>trans('messages.hairdressor_id_required'),
            //'type.required'=>trans('messages.type_required'),

        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $radius = env('SEARCH_MILE');
        $lat = $request->latitude;
        $lng = $request->longitude;

        if (empty($lat) && empty($lng)) {
            $string = "(-1) as distance";
        } else {
            $string = "(3959 * ACOS(COS(RADIANS($lat))
                                * COS(RADIANS(latitude))
                                * COS(RADIANS($lng) - RADIANS(longitude))
                                + SIN(RADIANS($lat))
                            * SIN(RADIANS(latitude)))) AS distance";
        }


        $user = JWTAuth::user();
        $list = \App\Models\UserFavorite::where('user_id',$user->id)->pluck('hairdressor_id')->toArray();
        $list = \App\Models\User::whereIn('id',$list)->with(['avgRating','totalEarning','reviews'=>function($q){
            $q->with(['sender']);
        }])->select('*',\DB::raw($string))->get();
        $message = __('messages.favorite_list_success');
        return SuccessResponse($message,200,$list);

    }


    public function destroy(Request $request,$id){
        try{
            $user_data = JWTAuth::parseToken()->authenticate();
            if($id != $user_data->id ){
                $message = __('messages.user_not_match');
                return InvalidResponse($message,505);
            }
            $user = User::find($id);
            if(!empty($user)){
                UserDeviceToken::where('user_id',$id)->delete();
                UserFavorite::where('user_id',$id)->delete();
                UserNotification::where('user_id',$id)->delete();
                Order::where('customer_id',$id)->delete();
                Order::where('hairdresser_id',$id)->delete();
                PharmacloudsService::where('hairdresser_id',$id)->delete();
                User::find($id)->delete();
                $message = __('messages.user_deleted');
                return SuccessResponse($message,200,[]);
            }
        }
        catch (\Exception $exception){
            $message = __('messages.user_not_deleted');
            return InvalidResponse($message,505);
        }
    }
    
    public function add_money(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            'receipt'=>'required',
            'amount'=>'required',
        ];

        $messages = [
            //'payment_method.required'=>trans('messages.payment_method_required'),
            'amount.required'=>trans('messages.amount_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();


        $user_id = $user->id;
        $amount = $request->amount;
        $receipt = $request->receipt;
        $order_id = 0; //$request->order_id;


        $payment_method = 2;

       
        $card_id = null;
        $transaction_id = $receipt;
        $order_transaction = new \App\Models\OrderTransaction;
        $order_transaction->order_id = $order_id;
        $order_transaction->transaction_id = $transaction_id;
        $order_transaction->card_id = $card_id;
        $order_transaction->total = $amount;
        $order_transaction->due_amount = 0;
        $order_transaction->final_amount = $amount;
        $order_transaction->payment_method = $payment_method;
        $order_transaction->save();


        $description = 'Money Added Successfully.';
        $arabic_description = 'تمت إضافة الأموال بنجاح.';

        $wallet = new \App\Models\UserWalletHistory;
        $wallet->user_id = $user_id;
        $wallet->order_id = $order_id;
        $wallet->user_paid_amount = $amount;
        $wallet->amount = $amount;
        $wallet->type = 1;
        $wallet->description = $description;
        $wallet->arabic_description = $arabic_description;
        $wallet->save();

        \App\Models\User::updateWallet($user_id);
        $user = User::where('id',$user_id)->first();
        
        $message = __('messages.order_payment_success');
        return SuccessResponse($message,200,$user);

    }
    

}
