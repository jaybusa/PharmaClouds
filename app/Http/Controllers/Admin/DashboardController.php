<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Products,Order,User,Clients,Category,AdminSetting};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;
use App\Utils;
use Illuminate\Support\Str;



class DashboardController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function index(Request $request) {
        $data = array();
        $today = '2022-06-13'; //date('Y-m-d')
        $data['clientCount'] = Clients::where('is_active', '1')->count();
        $data['productCount'] = Products::where('is_active', '1')->where('is_delete', '0')->count();
        $data['todayOrderCount'] = Order::where('order_payment_status', '1')->where('createdate', $today)->count();
        $data['todayRevenue'] = (float) Order::selectRaw(\DB::raw('sum(total) as revenue'))->where('order_payment_status', '1')->where('createdate', $today)->first()->revenue;
        $data['weekRevenue'] = (float) Order::selectRaw(\DB::raw('sum(total) as revenue'))->where('order_payment_status', '1')->whereBetween('createdate', [date('Y-m-d', strtotime(' - 7 days')),$today])->first()->revenue;
        
        $data['todayOrder'] = Order::with(['client','coupon'])->where('order_payment_status', '1')->where('createdate', $today)->get();

        return view('admin.dashboard',$data);
    }
}
