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



class ParentCategoryController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function parent_category(Request $request) {
        $parent_category = ParentCategory::orderBy('id','desc')->get();
        //pre($parent_category->toArray());
        return view('admin.parent_category.list',['parent_category'=>$parent_category]);
    }

    public function delete_parent_category(Request $request) {
        ParentCategory::where('id',$request->id)->delete();
        return redirect()->back()->with(['success'=>__('messages.delete_parent_categories_success')]);

    }

    public function check_parent_category(Request $request) {
        $check_parent_category = new ParentCategory;
        $check_parent_category = $check_parent_category->where('name',$request->name);
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

    public function post_parent_category(Request $request) {
        $message = __('messages.parent_categories_save_success');
        if($request->id && $request->id!=0) {
            $parent_category = ParentCategory::where('id',$request->id)->first();
        } else {
            $parent_category = new ParentCategory;
        }
        $parent_category->name = $request->name;
        $parent_category->name_en = $request->name_en;
        $parent_category->code = $request->code;
        if($request->image) {
            $file = $request->file('image');
            $file_name = 'parent_category_'.Str::random(10).'_'.time();
            $full_file_name = $file_name.'.'.$file->getClientOriginalExtension();
            $destinationPath = 'parent_category';
            $parent_category->image = $request->file('image')->store('parent_category');
        }
        $parent_category->save();
        return redirect()->back()->with(['success'=>$message]);
    }

}
