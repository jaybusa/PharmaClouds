<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\PromoCode;
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



class PromocodeController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function promocode(Request $request) {
        $promocode = PromoCode::orderBy('id','desc')->get();
        //pre($parent_category->toArray());
        return view('admin.promocode.list',['promocode'=>$promocode]);
    }

    public function delete_promocode(Request $request) {
        PromoCode::where('id',$request->id)->delete();
        return redirect()->back()->with(['success'=>__('messages.delete_promocode_success')]);

    }

    public function check_promocode(Request $request) {
        $check_parent_category = new PromoCode;
        $check_parent_category = $check_parent_category->where('code',$request->code);
        if($request->id && $request->id!=0) {
            $check_parent_category = $check_parent_category->where('id','!=',$request->id);
        }
        $check_parent_category = $check_parent_category->first();
        if($check_parent_category) {
            return "false";
        } else {
            return "true";
        }
    }

    
    

    public function post_promocode(Request $request) {
        $message = __('messages.promocode_save_success');
        if($request->id && $request->id!=0) {
            $parent_category = PromoCode::where('id',$request->id)->first();
        } else {
            $parent_category = new PromoCode;
        }
        $parent_category->name = $request->name;
        $parent_category->code = $request->code;
        $parent_category->percentage = $request->percentage;
        $parent_category->min_total = $request->min_total;

        $parent_category->total_user_limit = $request->total_user_limit;
        $parent_category->expired_date = $request->expired_date;
        $parent_category->save();
        return redirect()->back()->with(['success'=>$message]);
    }

    
}
