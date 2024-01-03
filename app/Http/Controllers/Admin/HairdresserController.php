<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\ParentCategory;
use App\Models\Category;
use App\Models\AdminSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;
use App\Utils;
use Illuminate\Support\Str;
use Auth;



class PharmacloudsController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function hairdresser(Request $request) {
        $list = User::where('role_id',3)->get();
        return view('admin.hairdresser.list',['list'=>$list]);
    }
   
    public function withdrawal_requests(Request $request) {
        $list = User::where('role_id',3)->where('is_withdraw_request',1)->get();
        return view('admin.hairdresser.withdrawal_req',['list'=>$list]);
    }
   
    public function save_wallet_record(Request $request) {
        
        $wallet = new \App\Models\UserWalletHistory;
        $wallet->user_id = $request->user_id;
        $wallet->order_id = 0;
        $wallet->amount = -($request->amount);
        $wallet->type = 2;
        $wallet->description = 'Withdrawal From Admin : '.$request->reason;
        $wallet->arabic_description = 'سحب من قبل الادارة'.$request->reason;
        $wallet->save();
        
        User::where('id',$request->user_id)->update(['is_withdraw_request'=>NULL]);

        
        \App\Models\User::updateWallet($request->user_id);
        $message = __('messages.wallet_updated_success');
        return redirect()->back()->with(['success'=>$message]);
    }
    
    public function add_wallet_amount(Request $request) {
        
        $wallet = new \App\Models\UserWalletHistory;
        $wallet->user_id = $request->user_id;
        $wallet->order_id = 0;
        $wallet->amount = ($request->amount);
        $wallet->type = 1;
        $wallet->description = 'Deposite From Admin : '.$request->reason;
        $wallet->arabic_description = 'الإيداع من المسؤول'.$request->reason;
        $wallet->save();
        
        User::where('id',$request->user_id)->update(['is_withdraw_request'=>NULL]);

        
        \App\Models\User::updateWallet($request->user_id);
        $message = __('messages.wallet_updated_success');
        return redirect()->back()->with(['success'=>$message]);
    }

    public function change_status(Request $request) {
        $user = Auth::user();
        $user_id = $request->user_id;
        $is_active = $request->is_active;
        User::where('id',$user_id)->update(['is_active'=>$is_active]);
        if($is_active==1) {
            $title = 'Account actived';
            $message = 'Your account actived by admin';
            $type = USER_ACTIVE;
        } else {
            $title = 'Account deactived';
            $message = 'Your account deactived by admin';
            $type = USER_DEACTIVE;

        }


        // send notification to hairdressor

        $new_notifications = new \App\Models\UserNotification;
        $new_notifications->user_id = $user_id;
        $new_notifications->sender_id = $user->id;
        $new_notifications->order_id = null;
        $new_notifications->title = $title;
        $new_notifications->message = $message;
        $new_notifications->type = $type;
        $new_notifications->save();

        // send socket to hairdressor
        $socket_data = ['type' => $type, 'user_id' => $user_id, 'title' => $title, 'message' => $message];
        send_socket($socket_data, 'send_notification');

        // send push notification to hairdressor
        $message_data = array(
            'title' => $title,
            'body' => $message,
            'sound' => 'default'
        );
        $other_data = array(
            'user_id'=>$user_id,
        );
        commonPushNotification($user_id, $message_data, $other_data);

    }

    public function detail(Request $request) {
        $id = $request->id;
        $check_user = User::where('id',$id)->where('role_id',3)->first();
        if($check_user) {
            $check_user->orders = Order::where('hairdresser_id',$id)->get();
            $UserWalletHistory = \App\Models\UserWalletHistory::where('user_id',$check_user->id)->with('order_detail')->orderBy('id','desc')->get();
//            print_r($UserWalletHistory); exit();
            return view('admin.hairdresser.detail',['user'=>$check_user,'UserWalletHistory'=>$UserWalletHistory]);
        }
//        return redirect()->route('admin.hairdresser')->with(['danger'=>__('messages.hairdresser_not_found')]);
    }

}
