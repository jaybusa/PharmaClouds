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



class SettingsController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function settings(Request $request) {

        $settings = AdminSetting::all();
        //pre($settings->toArray());
        $settings_data = [];
        foreach ($settings as $key => $value) {
            $settings_data[$value->key_name] = $value->key_value;
        }
        //pre($settings_data);
        return view('admin.settings',['settings_data'=>$settings_data]);
    }

    public function post_settings(Request $request) {

        //pre($request->all());
        AdminSetting::where('key_name','login_otp')->update(['key_value'=>$request->login_otp]);

        if($request->tax) {
            AdminSetting::where('key_name','tax')->update(['key_value'=>$request->tax]);
        }

        if($request->tax) {
            AdminSetting::where('key_name','commission')->update(['key_value'=>$request->commission]);
        }

        if($request->tax) {
            AdminSetting::where('key_name','support_email')->update(['key_value'=>$request->support_email]);
        }

        if($request->tax) {
            AdminSetting::where('key_name','support_phone')->update(['key_value'=>$request->support_phone]);
        }
        if($request->cash_limit) {
            AdminSetting::where('key_name','cash_limit')->update(['key_value'=>$request->cash_limit]);
        }
        if($request->min_cash_limit) {
            AdminSetting::where('key_name','min_cash_limit')->update(['key_value'=>$request->min_cash_limit]);
        }
        if($request->max_cash_limit) {
            AdminSetting::where('key_name','max_cash_limit')->update(['key_value'=>$request->max_cash_limit]);
        }
        if($request->app_fee) {
            AdminSetting::where('key_name','app_fee')->update(['key_value'=>$request->app_fee]);
        }
        if($request->terms_en) {
            AdminSetting::where('key_name','terms_en')->update(['key_value'=>$request->terms_en]);
        }
        if($request->terms_ar) {
            AdminSetting::where('key_name','terms_ar')->update(['key_value'=>$request->terms_ar]);
        }


        return redirect()->back()->with(['success'=>__('messages.setting_success')]);
    }

}
