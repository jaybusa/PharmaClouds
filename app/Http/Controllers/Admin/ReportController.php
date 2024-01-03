<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Banner;
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



class ReportController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    // public function index(Request $request) {
    //     $Dressers = User::where('role_id','3')->get();
    //     $dresserIds = array();
    //     foreach($Dressers as $_dresser){
    //         $dresserIds[] = $_dresser->id;
    //     }
    //     $fromDate = date('Y-m-01');
    //     $toDate = date('Y-m-d');
    //     $userId = -1;
    //     $UserWalletHistory = \App\Models\UserWalletHistory::whereIn('user_id',$dresserIds)->where('created_at','>=',$fromDate)->where('created_at','<',$toDate)->with('users')->with('order_detail')->orderBy('id','desc')->get();    
    //     return view('admin.report.index',['Dressers'=>$Dressers,'UserWalletHistory'=>$UserWalletHistory,'fromDate'=>$fromDate,'toDate'=>$toDate,'userId' =>$userId]);
    // }

    public function index(Request $request) {
        
        $userId = $request->users;
        $fromDate = $request->from_date?$request->from_date:date('Y-m-01');
        $toDate = $request->to_date?$request->to_date:date('Y-m-d');
        // $toDate = $request->to_date;
        $Dressers = User::where('role_id','3')->get();
        if($userId >= 1 ){
            $UserWalletHistory = \App\Models\UserWalletHistory::where('user_id',$userId)->where('created_at','>=',$fromDate)->where('created_at','<=',$toDate." 23:59:59")->with('users')->with('order_detail')->orderBy('id','desc')->get();    
        }else{
            $userId = -1;
            $dresserIds = array();
            foreach($Dressers as $_dresser){
                $dresserIds[] = $_dresser->id;
            }
            $UserWalletHistory = \App\Models\UserWalletHistory::whereIn('user_id',$dresserIds)->where('created_at','>=',$fromDate)->where('created_at','<',$toDate." 23:59:59")->with('users')->with('order_detail')->orderBy('id','desc')->get();    
        }
        
        return view('admin.report.index',['Dressers'=>$Dressers,'UserWalletHistory'=>$UserWalletHistory,'fromDate'=>$fromDate,'toDate'=>$toDate,'userId' =>$userId]);

    }
    
     public function amount_report(Request $request) {
        $Dressers = User::where('role_id','3')->get();
        return view('admin.report.amount_report',['Dressers'=>$Dressers]);
    }

    
   
    
}
