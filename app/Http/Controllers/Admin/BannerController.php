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



class BannerController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function banner(Request $request) {
        $Banner = Banner::orderBy('id','desc')->get();
        //pre($parent_category->toArray());
        return view('admin.banner.list',['Banner'=>$Banner]);
    }

    public function delete_banner(Request $request) {
        Banner::where('id',$request->id)->delete();
        return redirect()->back()->with(['success'=>__('messages.delete_banner_success')]);

    }
    
    

    public function post_banner(Request $request) {
        $message = __('messages.banner_save_success');
        if($request->id && $request->id!=0) {
            $parent_category = Banner::where('id',$request->id)->first();
        } else {
            $parent_category = new Banner;
        }
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|file|image',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(['error'=>"Invalid File Type."]);
        }

        $parent_category->title = $request->title;
        $parent_category->description = $request->description;

        if($request->image) {
            $file = $request->file('image');
            //echo 'here';exit;
            $file_name = 'parent_category_'.Str::random(10).'_'.time();
            $full_file_name = $file_name.'.'.$file->getClientOriginalExtension();
            //Move Uploaded File
            $destinationPath = 'banner';
            $file->move($destinationPath,$full_file_name);
            $parent_category->image = $full_file_name;
        }
        $parent_category->save();
        return redirect()->back()->with(['success'=>$message]);
    }

    
}
