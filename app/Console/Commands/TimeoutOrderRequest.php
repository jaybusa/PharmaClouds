<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use Carbon\Carbon;
use App\Models\User;
use DB;

class TimeoutOrderRequest extends Command
{

    protected $signature = 'time_out_order_request:cron';
    protected $description = 'time out order request cron';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::now()->addMinute(-6)->format("Y-m-d H:i:s");

        $order_list = \App\Models\Order::where('order_status',1)->where('request_date','<=',$date)->get();

        foreach ($order_list as $key => $value) {
            $order = \App\Models\Order::where('id',$value->id)->first();
            if($order->order_status!=ORDER_TIMEOUT) {
                $user_detail = User::where('id',$order->hairdresser_id)->first();
                    $order_timeout_count = $user_detail->order_timeout_count;
                    $order_timeout_count+=1;
                    if($order_timeout_count==3) {
                        $user_detail->is_online=2;
                        $user_detail->offline_date=date('Y-m-d H:i:s');

                        $title = 'You are offline';
                        $message = __('messages.user_inactive_by_admin_24_hours');
                        $new_notifications = new \App\Models\UserNotification;
                        $new_notifications->user_id = $user_detail->id;
                        $new_notifications->sender_id = $user_detail->id;
                        $new_notifications->order_id = 0;
                        $new_notifications->title = $title;
                        $new_notifications->message = $message;
                        $new_notifications->type = USER_OFFLINE;
                        $new_notifications->save();

                        $message_data = array(
                            'title' => $title,
                            'body' => $message,
                            'sound' => 'default'
                        );
                        $other_data = array(
                            'order_id'=>0,
                        );

                        commonPushNotification($user_detail->id, $message_data, $other_data);

                    }
                    $user_detail->order_timeout_count = $order_timeout_count;
                    $user_detail->save();
                }

            $order->order_status = ORDER_TIMEOUT;
            $order->save();
            $order_code = $order->id;
            
            $title = 'Order timeout';
            $message = 'Order '.$order_code.' has been timeout';
            // send notification

            $new_notifications = new \App\Models\UserNotification;
            $new_notifications->user_id = $order->customer_id;
            $new_notifications->sender_id = $order->hairdresser_id;
            $new_notifications->order_id = $order->id;
            $new_notifications->title = $title;
            $new_notifications->message = $message;
            $new_notifications->type = ORDER_TIMEOUT;
            $new_notifications->save();

            // send socket 
            $socket_data = ['type' => ORDER_TIMEOUT, 'user_id' => $order->customer_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
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

            commonPushNotification($order->customer_id, $message_data, $other_data);

            // send hairdresser

            $new_notifications = new \App\Models\UserNotification;
            $new_notifications->user_id = $order->hairdresser_id;
            $new_notifications->sender_id = $order->customer_id;
            $new_notifications->order_id = $order->id;
            $new_notifications->title = $title;
            $new_notifications->message = $message;
            $new_notifications->type = ORDER_TIMEOUT;
            $new_notifications->save();

            // send socket 
            $socket_data = ['type' => ORDER_TIMEOUT, 'user_id' => $order->hairdresser_id, 'title' => $title, 'message' => $message, 'order_id' => $order->id];
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

            commonPushNotification($order->hairdresser_id, $message_data, $other_data);


            \Log::info('Order timeout cron', ['Date' => date('Y-m-d H:i:s'), 'order' => $order]);

        }
        \Log::info('timeout cron job', ['Date' => date('Y-m-d H:i:s')]);

        //echo 'Date :'.$date;
    }
}