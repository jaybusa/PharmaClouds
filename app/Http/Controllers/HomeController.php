<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use DataTables;
use Auth;
use DB;
use Log;
use Session;
use App\User;

class HomeController extends Controller {
  
    public function send_socket() { 
    	return view('send_socket');
    }

    public function receive_socket() { 
    	
        return view('receive_socket');
    } 

    public function clear_jay_db() { 
    	
        DB::table('hairdresser_services')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_review')->truncate();
        DB::table('order_services')->truncate();
        DB::table('order_transactions')->truncate();
        DB::table('user_device_login_token')->truncate();
        DB::table('user_device_token')->truncate();
        DB::table('user_favorite')->truncate();
        DB::table('user_notifications')->truncate();
        DB::table('user_wallet_history')->truncate();
        DB::table('users')->where('id','>','104')->delete();
        DB::table('users')->update(['wallet_total' => 0.0]);
    } 

    public function post_send_socket(Request $request) {
    	$data = ['user_id'=>1,'message'=>$request->message];
 		User::send_socket($data,'send_notification');
	
    }

    
}
