<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Session;
use Illuminate\Support\Str;
use Crypt;
use App\Models\User;
use App\Models\PharmacloudsService;
use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\UserNotification;
use JWTAuth;
use DB;

class OrderControllerBK extends Controller
{
    const ITEMS_PER_PAGE = 10;

    public function __construct()
    {
    }
    
    public function order_request(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'hairdresser_id'=>'required',
            'services'=>'required|json',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'tax_percentage'=>'required',
            'tax_amount'=>'required',
            'commision_percentage'=>'required',
            'commision_amount'=>'required',
            'payment_method'=>'required',
        ];

        $messages = [
            'hairdresser_id.required'=>trans('messages.hairdresser_id_required'),
            'services.required'=>trans('messages.services_required'),
            'services.json'=>trans('messages.services_json_required'),
            'address.required'=>trans('messages.address_required'),
            'latitude.required'=>trans('messages.latitude_required'),
            'longitude.required'=>trans('messages.longitude_required'),
            'tax_percentage.required'=>trans('messages.tax_percentage_required'),
            'tax_amount.required'=>trans('messages.tax_amount_required'),
            'commision_percentage.required'=>trans('messages.commision_percentage_required'),
            'commision_amount.required'=>trans('messages.commision_amount_required'),
            'payment_method.required'=>trans('messages.payment_method_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        $order_code = strtoupper(Str::random(3).Str::random(3));
        //echo $order_code;exit;

        $services = json_decode($request->services,true);
        //pre($services);

        \Log::info('Order Services', ['Date' => date('Y-m-d H:i:s'), 'Services' => $services,'order_code'=>$order_code]);



        $final_total = 0;
        foreach ($services as $key => $value) {
            //echo $value['price'];
            $service_total = $value['price']*$value['quantity'];
            $final_total+=$service_total;
        }
        $hairdressor_amount = $final_total;

        $final_total+=$request->tax_amount;
        $final_total+=$request->commision_amount;

        $hairdresser_id = (int)$request->hairdresser_id;
        $order = new Order;
        $order->customer_id = $user->id;
        $order->hairdresser_id = $hairdresser_id;
        $order->order_code = $order_code;
        $order->address = $request->address;
        $order->latitude = $request->latitude;
        $order->longitude = $request->longitude;
        $order->order_status = ORDER_REQUEST;
        $order->request_date = Date('Y-m-d H:i:s');
        $order->tax_percentage = $request->tax_percentage;
        $order->tax_amount = $request->tax_amount;
        $order->commision_percentage = $request->commision_percentage;
        $order->commision_amount = $request->commision_amount;
        $order->payment_method = $request->payment_method;

        if($request->promo_code_id) {
            $order->promo_code_id = $request->promo_code_id;
            $order->promo_code_amount = $request->promo_code_amount;
            $final_total-=$request->promo_code_amount;
        }

        $admin_amount = $final_total-$hairdressor_amount;
        $order->hairdressor_amount = $hairdressor_amount;
        $order->admin_amount = $admin_amount;
        $order->final_total = $final_total;

        $order->save();

        foreach ($services as $key => $value) {
            $order_service = new OrderService;
            $order_service->order_id = $order->id;
            $order_service->service_id = $value['id'];
            $order_service->quantity = $value['quantity'];
            $order_service->price = $value['price'];
            $order_service->total = $value['price']*$value['quantity'];
            $order_service->save();
        }

        $title = 'You have a new order';
        $message = $user->name.' has requested order '.$order->id;

        // send notification to hairdressor

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $hairdresser_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_REQUEST;
        $new_notifications->save();

        // send socket to hairdressor
        $socket_data = ['type' => ORDER_REQUEST, 'user_id' => $hairdresser_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($hairdresser_id, $message_data, $other_data);

        $order = Order::detail($order->id);
        \Log::info('Order Request', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_request_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_retry(Request $request) {

        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        $order->order_status = ORDER_REQUEST;
        $order->request_date = Date('Y-m-d H:i:s');
        $order->save();
        $order_code = $order->order_code;

        $hairdresser_id = $order->hairdresser_id;


        $title = 'You have a new order';
        $message = $user->name.' has requested order '.$order_code;

        // send notification to hairdressor

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $hairdresser_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_REQUEST;
        $new_notifications->save();

        // send socket to hairdressor
        $socket_data = ['type' => ORDER_REQUEST, 'user_id' => $hairdresser_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($hairdresser_id, $message_data, $other_data);

        $order = Order::detail($order->id);
        \Log::info('Order Retry Request', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_request_success');
        return SuccessResponse($message,200,$order);
           
    }

    public function check_ongoing_order(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();
        $user_id = $user->id;

        $order = new Order;
        if($user->role_id==2) {
            $order = $order->where('customer_id',$user_id);
        } else {
            $order = $order->where('hairdresser_id',$user_id);
        }

        $order = $order->whereIn('order_status',[ORDER_ACCEPT,ORDER_ON_THE_WAY,ORDER_PROCESSING]);
        $order = $order->orderBy('id','desc');
        $order = $order->first();        

        //pre($request->all());
        $message = 'success';
        return SuccessResponse($message,200,$order);
    }

    public function order_review(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
            'sender_id'=>'required',
            'receiver_id'=>'required',
            'rating'=>'required',
            'comment'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
            'sender_id.required'=>trans('messages.sender_id_required'),
            'receiver_id.required'=>trans('messages.receiver_id_required'),
            'rating.required'=>trans('messages.rating_required'),
            'comment.required'=>trans('messages.comment_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }

        $OrderReview = new \App\Models\OrderReview;
        $OrderReview->order_id = $request->order_id;
        $OrderReview->sender_id = $request->sender_id;
        $OrderReview->receiver_id = $request->receiver_id;
        $OrderReview->rating = $request->rating;
        $OrderReview->comment = $request->comment;
        $OrderReview->save();

        $title = 'Order '.$order->id.' review';
        $message = 'Order '.$order->id.' review added with rating '.$request->rating;
        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($request->receiver_id, $message_data, $other_data);

        //pre($request->all());
        $message = __('messages.order_review_save');
        return InvalidResponse($message,200,$OrderReview);
        
    }

    public function apply_promocode(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'promo_code'=>'required',
            'amount'=>'required',
        ];

        $messages = [
            'promo_code.required'=>trans('messages.promo_code_required'),
            'amount.required'=>trans('messages.amount_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        $check_promocode = \App\Models\PromoCode::where('code',$request->promo_code)->first();
        if($check_promocode) {
            $check_already_used_in_order = Order::where('customer_id',$user->id)->where('promo_code_id',$check_promocode->id)->first();
            if($check_already_used_in_order) {
                $message = __('messages.promocode_already_used');
                return InvalidResponse($message,101,[]);
            }

            if($request->amount<$check_promocode->min_total) {
                $message = __('messages.promocode_amount_min_required').' '.$check_promocode->min_total;
                return InvalidResponse($message,101,[]);   
            }

            if($check_promocode->expired_date) {
                if(date('Y-m-d')>date('Y-m-d',strtotime($check_promocode->expired_date))) {
                     $message = __('messages.promocode_expired');
                    return InvalidResponse($message,101,[]);     
                } 
            }

            if($check_promocode->total_user_limit) {
                $check_promocode_use_count = Order::where('customer_id',$user->id)->where('promo_code_id',$check_promocode->id)->count();
                if($check_promocode_use_count>=$check_promocode->total_user_limit) {
                    $message = __('messages.promocode_use_limit_over');
                    return InvalidResponse($message,101,[]);     
                }

            }



                

            $message = __('messages.promocode_apply_sucess');
            return SuccessResponse($message,200,$check_promocode);   
        }
        
        //pre($request->all());
        $message = __('messages.invalid_promocode');
        return InvalidResponse($message,101,[]);
        
    }

    public function order_timeout(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;

        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }

        if($order->order_status!=ORDER_TIMEOUT) {
            $user_detail = User::where('id',$order->hairdresser_id)->first();
            $current_date = date('Y-m-d H:i:s',strtotime("-30 seconds"));
            if($user_detail->updated_at<=$current_date) {
                $order_timeout_count = $user_detail->order_timeout_count;
                $order_timeout_count+=1;
                if($order_timeout_count==3) {
                    $user_detail->is_online=2;
                }
                $user_detail->order_timeout_count = $order_timeout_count;
                $user_detail->save();
            }
        }
        $order->order_status = ORDER_TIMEOUT;
        $order->save();
        $order_code = $order->id;
        
        $title = 'Order timeout';
        $message = 'Order '.$order_code.' has been timeout';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_TIMEOUT;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_TIMEOUT, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order timeout', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_timeout_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_cancel(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        
        $order->order_status = ORDER_CANCEL;
        $order->save();
        $order_code = $order->id;
        
        $title = 'Order canceled';
        $message = 'Order '.$order_code.' has been canceled';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }



        $wallet = new \App\Models\UserWalletHistory;
        $wallet->user_id = $order->customer_id;
        $wallet->order_id = $order->id;
        $wallet->amount = -$order->final_total;
        $wallet->type = 1;
        $wallet->description = "#".$order->order_code.' canceled. so refunded order amount.';
        $wallet->save();

        \App\Models\User::updateWallet($order->customer_id);


        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_CANCEL;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_CANCEL, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order cancel', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_cancel_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_accept(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        $order->order_status = ORDER_ACCEPT;
        $order->save();
        $order_code = $order->id;
        
        $title = 'Order accepted';
        $message = 'Order '.$order_code.' has been accepted';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_ACCEPT;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_ACCEPT, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order accept', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_accept_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_reject(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        if($request->reason) {
            $order->reject_reason = $request->reason;
        }
        $order->order_status = ORDER_REJECT;
        $order->save();
        $order_code = $order->id;
        
        $title = 'Order rejected';
        $message = 'Order '.$order_code.' has been rejected';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_REJECT;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_REJECT, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order reject', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_reject_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_dispute(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
            'dispute_reason'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
            'dispute_reason.required'=>trans('messages.dispute_reason_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        $order->dispute_reason = $request->dispute_reason;
        $order->is_dispute = 1;
        $order->save();
        
        //pre($request->all());
        $message = __('messages.order_dispute_success');
        return SuccessResponse($message,200,$order);
        
    }


    public function order_on_the_way(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        $order->order_status = ORDER_ON_THE_WAY;
        $order->save();
        $order_code = $order->id;
        
        $title = 'Order on the way';
        $message = 'Order '.$order_code.' has been on the way';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_ON_THE_WAY;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_ON_THE_WAY, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order on the way', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_on_the_way_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_processing(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        $order->start_date = date('Y-m-d H:i:s');
        $order->order_status = ORDER_PROCESSING;
        $order->save();
        $order_code = $order->id;
        
        $title = 'Order processing';
        $message = 'Order '.$order_code.' has been processing';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_PROCESSING;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_PROCESSING, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order processing', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_processing_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_complete(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
            'payment_method'=>'required',
            'due_amount'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
            'payment_method.required'=>trans('messages.payment_method_required'),
            'due_amount.required'=>trans('messages.due_amount_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $payment_method = $request->payment_method;


        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        if($payment_method==2 || $payment_method==1) {
            $order->is_payment = 1;
        }
        $order->complete_date = date('Y-m-d H:i:s');
        $order->payment_method = $payment_method;
        $order->order_status = ORDER_COMPLETE;
        $order->save();

        $final_total = $order->final_total;
        $hairdressor_amount = $order->hairdressor_amount;
        $admin_amount = $order->admin_amount;
        $commision_amount = $order->commision_amount;
        $tax_amount = $order->tax_amount;
        $due_amount = $request->due_amount;
        $order_code = $order->id;
        $final_amount = $final_total+$due_amount;

        //$transaction_id = \Str::random(20);
        
        $card_id = null;
        $transaction_id = null;

        if($payment_method!=2) {
            $order_transaction = new \App\Models\OrderTransaction;
            $order_transaction->order_id = $order->id;
            $order_transaction->transaction_id = $transaction_id;
            $order_transaction->card_id = $card_id;
            $order_transaction->total = $final_total;
            $order_transaction->due_amount = $due_amount;
            $order_transaction->final_amount = $final_amount;
            $order_transaction->payment_method = $payment_method;
            $order_transaction->save();
        }
        

        if($payment_method==1) {
            // cash on delivery
            // hairdressor wallet update
            // $description = '';
            // if($due_amount>0) {
            //     $description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount;
            // } else {
            //     $description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount;
            // }

            $order_amount = $due_amount;

            $description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Order amount='.$order_amount.', Payment Type=Cash Payment';

            if($order_amount==$final_total) {
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->hairdresser_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($admin_amount);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();
            } else if($order_amount==$hairdressor_amount) {
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->customer_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($admin_amount);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();

                // $wallet = new \App\Models\UserWalletHistory;
                // $wallet->user_id = $order->customer_id;
                // $wallet->order_id = $order->id;
                // $wallet->amount = -($final_total-$order_amount);
                // $wallet->type = 2;
                // $wallet->description = $description;
                // $wallet->save();
            } else if($order_amount>$hairdressor_amount) {
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->hairdresser_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($order_amount-$hairdressor_amount);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();

                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->customer_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($final_total-$order_amount);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();
            } else if($order_amount<$hairdressor_amount) {
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->hairdresser_id;
                $wallet->order_id = $order->id;
                $wallet->amount = ($hairdressor_amount-$order_amount);
                $wallet->type = 1;
                $wallet->description = $description;
                $wallet->save();


                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->customer_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($final_total-$order_amount);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();
            } else if($order_amount>$final_total) {
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->customer_id;
                $wallet->order_id = $order->id;
                $wallet->amount = $order_amount-$final_total;
                $wallet->type = 1;
                $wallet->description = $description;
                $wallet->save();

                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->hairdresser_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($order_amount-$hairdressor_amount);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();
            }

            
            // customer wallet update
            // if($due_amount>0) {
            //     // customer pay due amount so we need credit there overdue amount in that account
            //     $wallet = new \App\Models\UserWalletHistory;
            //     $wallet->user_id = $order->customer_id;
            //     $wallet->order_id = $order->id;
            //     $wallet->amount = $due_amount;
            //     $wallet->type = 1;
            //     $wallet->description = $description;
            //     $wallet->save();
            // }

            \App\Models\User::updateWallet($order->customer_id);
            \App\Models\User::updateWallet($order->hairdresser_id);

        } 
        else if($payment_method==3) {
            // no payment
            $description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount.', Payment Type=No Payment';

            // hairdressor wallet update
            //$description = 'Pharmaclouds amount='.$hairdressor_amount;
            //$description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount;

            $wallet = new \App\Models\UserWalletHistory;
            $wallet->user_id = $order->hairdresser_id;
            $wallet->order_id = $order->id;
            $wallet->amount = $hairdressor_amount;
            $wallet->type = 1;
            $wallet->description = $description;
            $wallet->save();

            // customer wallet update
            
                //$description = 'Pharmaclouds amount='.$hairdressor_amount.',Admin amount='.$admin_amount;
                //$description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount;


                // customer not pay due amount so we need debit there overdue amount in that account
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->customer_id;
                $wallet->order_id = $order->id;
                $wallet->amount = -($final_total);
                $wallet->type = 2;
                $wallet->description = $description;
                $wallet->save();

            \App\Models\User::updateWallet($order->customer_id);
            \App\Models\User::updateWallet($order->hairdresser_id);

        } 
        else {

            // card payment
            /*$description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount.', Payment Type=Card Payment';
            // hairdressor wallet update
            //$description = 'Pharmaclouds amount='.$hairdressor_amount;
            //$description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount;

            $wallet = new \App\Models\UserWalletHistory;
            $wallet->user_id = $order->hairdresser_id;
            $wallet->order_id = $order->id;
            $wallet->amount = $hairdressor_amount;
            $wallet->type = 1;
            $wallet->description = $description;
            $wallet->save();

            // customer wallet update
            if($due_amount>0) {
                // customer pay due amount so we need credit there overdue amount in that account
                $wallet = new \App\Models\UserWalletHistory;
                $wallet->user_id = $order->customer_id;
                $wallet->order_id = $order->id;
                $wallet->amount = $due_amount;
                $wallet->type = 1;
                $wallet->description = $description;
                $wallet->save();
            }

            \App\Models\User::updateWallet($order->customer_id);
            \App\Models\User::updateWallet($order->hairdresser_id);


            */
        }
        
        $title = 'Order completed';
        $message = 'Order '.$order_code.' has been completed';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_COMPLETE;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_COMPLETE, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order complete', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_complete_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_payment(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
            'receipt'=>'required',
            'amount'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
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
        $order_id = $request->order_id;


        $order = Order::detail($order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }

        $order->is_payment = 1;
        $order->save();
        $payment_method = 2;
        $final_total = $order->final_total;
        $hairdressor_amount = $order->hairdressor_amount;
        $admin_amount = $order->admin_amount;
        $commision_amount = $order->commision_amount;
        $tax_amount = $order->tax_amount;
        $amount = $request->amount;
        $order_code = $order->id;

        Logger()->info("order Payment request =" . json_encode($request->all()));

        Logger()->info("order Payment Order =" . json_encode($order));


        $final_amount = $amount;
        $due_amount = $amount-$final_total;

        Logger()->info("order Payment final_amount =" . $final_amount);
        Logger()->info("order Payment due_amount =" . $due_amount);

        //$final_amount = $final_total+$amount;

        //$transaction_id = \Str::random(20);
        
        $card_id = null;
        $transaction_id = $receipt;
        $order_transaction = new \App\Models\OrderTransaction;
        $order_transaction->order_id = $order->id;
        $order_transaction->transaction_id = $transaction_id;
        $order_transaction->card_id = $card_id;
        $order_transaction->total = $final_total;
        $order_transaction->due_amount = $due_amount;
        $order_transaction->final_amount = $final_amount;
        $order_transaction->payment_method = $payment_method;
        $order_transaction->save();

        $description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount.', amount='.$amount.', Payment Type=Online Payment';

        // hairdressor wallet update
        //$description = 'Pharmaclouds amount='.$hairdressor_amount;
        //$description = 'Admin amount='.$admin_amount.', Pharmaclouds amount='.$hairdressor_amount.', Commision Amount='.$commision_amount.', Tax amount='.$tax_amount.', Due amount='.$due_amount;

        $wallet = new \App\Models\UserWalletHistory;
        $wallet->user_id = $order->hairdresser_id;
        $wallet->order_id = $order->id;
        $wallet->amount = $hairdressor_amount;
        $wallet->type = 1;
        $wallet->description = $description;
        $wallet->save();

        $wallet = new \App\Models\UserWalletHistory;
        $wallet->user_id = $order->customer_id;
        $wallet->order_id = $order->id;
        $wallet->amount = $due_amount;
        $wallet->type = 1;
        $wallet->description = $description;
        $wallet->save();

        \App\Models\User::updateWallet($order->customer_id);
        \App\Models\User::updateWallet($order->hairdresser_id);

        $title = 'Order paid';
        $message = 'Order '.$order_code.' has been paid';
        $sender_id = 0;
        if($user_id==$order->customer_id) {
            $sender_id = $order->hairdresser_id;
        } else {
            $sender_id = $order->customer_id;
        }
        // send notification

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $sender_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = $order->id;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = ORDER_PAID;
        $new_notifications->save();

        // send socket 
        $socket_data = ['type' => ORDER_PAID, 'user_id' => $sender_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'order_id'=>$order->id,
        );

        commonPushNotification($sender_id, $message_data, $other_data);

        $order = Order::detail($order->id);

        \Log::info('Order payment', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);


        //pre($request->all());
        $message = __('messages.order_payment_success');
        return SuccessResponse($message,200,$order);
        
    }


    public function my_order_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        
        $order = new Order;
        if($user->role_id==2) {
            $order = $order->where('customer_id',$user_id);
        } else {
            $order = $order->where('hairdresser_id',$user_id);
        }

        if($request->has('page')) {
            $page = $request->has('page') ? $request->page : 1;
            $page = $page - 1;  
            $order = $order->skip($page * self::ITEMS_PER_PAGE)->take(self::ITEMS_PER_PAGE);  
        }

        $order = $order->with(['order_service'=>function($q){
            $q->with('category');
        },'customer','hairdresser','promocode_detail','transaction_detail']);
        $order = $order->orderBy('id','desc');
        $order = $order->get();




        //pre($request->all());
        $message = __('messages.order_list_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function order_detail(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'order_id'=>'required',
        ];

        $messages = [
            'order_id.required'=>trans('messages.order_id_required'),
        ];

       
        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);        
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        $order = Order::detail($request->order_id);
        if(!$order) {
            $message = __('messages.order_detail_not_found');
            return InvalidResponse($message,101);
        }
        

        
        $message = __('messages.order_complete_success');
        return SuccessResponse($message,200,$order);
        
    }

    public function request_order_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        
        $order = new Order;
        $order = $order->where('order_status',1);
        if($user->role_id==2) {
            $order = $order->where('customer_id',$user_id);
        } else {
            $order = $order->where('hairdresser_id',$user_id);
        }

        if($request->has('page')) {
            $page = $request->has('page') ? $request->page : 1;
            $page = $page - 1;  
            $order = $order->skip($page * self::ITEMS_PER_PAGE)->take(self::ITEMS_PER_PAGE);  
        }

        $order = $order->with(['order_service'=>function($q){
            $q->with('category');
        },'customer','hairdresser','promocode_detail','transaction_detail']);
        $order = $order->orderBy('id','desc');
        $order = $order->get();




        //pre($request->all());
        $message = __('messages.order_list_success');
        return SuccessResponse($message,200,$order);

    }

    public function previously_hired_hairdresser_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();

        
        $user_id = $user->id;
        


        $order = new Order;
        $order = $order->where('customer_id',$user_id);
        $order = $order->where('order_status',7);
        $order = $order->join(DB::raw('(Select max(id) as id from orders group by hairdresser_id) new_order_object'), function($join) {
            $join->on('orders.id', '=', 'new_order_object.id');
            });
         $order = $order->orderBy('created_at', 'desc');

        if($request->has('page')) {
            $page = $request->has('page') ? $request->page : 1;
            $page = $page - 1;  
            $order = $order->skip($page * self::ITEMS_PER_PAGE)->take(self::ITEMS_PER_PAGE);  
        }

        $order = $order->with(['order_service'=>function($q){
            $q->with('category');
        },'customer','hairdresser','promocode_detail','transaction_detail']);
        $order = $order->get();




        //pre($request->all());
        $message = __('messages.order_list_success');
        return SuccessResponse($message,200,$order);

    }

    public function order_otp(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];
        
        //if response false return  
        if (!$success) {
            return $return;
        }

        $rules = [
            'email'=>'required|email',
            'otp'=>'required',
            'order_id'=>'required',
        ];

        $messages = [
            'otp.required'=>trans('messages.otp_required'),
            'email.required'=>trans('messages.email_required'),
            'email.email'=>trans('messages.email_valid'),
            'order_id.required'=>trans('messages.order_id_required'),


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
                $order = \App\Models\Order::detail($request->order_id);
                if(!$order) {
                    $message = __('messages.order_detail_not_found');
                    return InvalidResponse($message,101);
                }


                $emaildata = [
                    'name'=>$check_user->name,
                    'otp'=>$request->otp,
                    'order_code'=>$order->id
                ];
                $email = $request->email;
                $subject = __('messages.otp_subject');
                $path = 'emails.otp_order_mail';
                sendMail($path, $emaildata, $email, $subject);

                // send push notification to hairdressor
                $title = 'Order otp';
                $message = 'Order '.$order->id.' otp '.$request->otp;
                $message_data = array(
                    'title' => $title,
                    'body' => $message,
                    'sound' => 'default'
                );
                $other_data = array(
                    'order_id'=>$order->id,
                );

                commonPushNotification($check_user->id, $message_data, $other_data);

            }
            $message = __('messages.otp_send_success');
            return SuccessResponse($message,200,[]);
            
        } 
        $message = __('messages.user_email_not_found');
        return InvalidResponse($message,101,[]);
    }
}