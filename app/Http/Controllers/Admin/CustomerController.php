<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
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
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';


    public function userList(Request $request) {
        $list = User::where('role_id',1)->get();
        return view('admin.customer.list',['list'=>$list]);
    }

    function userAdd() {
        return view('admin.customer.create');
    }

    function userEdit(User $user) {
        return view('admin.customer.edit',compact('user'));
    }

    function userStore(Request $request) {

        $user = new User();
        $user->first_name = $request->name;
        $user->email = $request->email;
        $user->role_id = 1;
        $user->name = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.customer');
    }

    function userUpdate(Request $request , User $user) {
        $user->first_name = $request->name;
        $user->email = $request->email;
        $user->role_id = 1;
        $user->name = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('admin.customer');
    }

    // function updateProfile(Request $request , User $user) {
    //     $user->first_name = $request->name;
    //     $user->email = $request->email;
    //     $user->role_id = 1;
    //     $user->name = $request->username;
    //     $user->password = Hash::make($request->password);
    //     $user->save();
    //     return redirect()->back();

    // }

    function deleteUser(Request $request , User $user) {
        $user->delete();
        return redirect()->route('admin.customer');

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
        $check_user = User::where('id',$id)->where('role_id',2)->with(['orders'=>function($q){
            $q->with(['customer','hairdresser']);
        }])->first();
        if($check_user) {
            $UserWalletHistory = \App\Models\UserWalletHistory::where('user_id',$check_user->id)->with('order_detail')->orderBy('id','desc')->get();
            //pre($UserWalletHistory->toArray());exit();
            return view('admin.customer.detail',['user'=>$check_user,'UserWalletHistory'=>$UserWalletHistory]);
        }
        return redirect()->route('admin.customer')->with(['danger'=>__('messages.customer_not_found')]);
    }
}
