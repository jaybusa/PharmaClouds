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



class CategoryController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    

    public function delete_category(Request $request) {
        Category::where('id',$request->id)->delete();
        return redirect()->back()->with(['success'=>__('messages.delete_categories_success')]);

    }

    public function category(Request $request) {
        $parent_category = ParentCategory::orderBy('id','desc')->get();

        $category = Category::whereHas('parent_category')->with('parent_category')->orderBy('id','desc')->get();
        //pre($category->toArray());
        return view('admin.category.list',['category'=>$category,'parent_category'=>$parent_category]);
    }
    
    public function check_category(Request $request) {
        $check_parent_category = new Category;
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

    public function post_category(Request $request) {
        $message = __('messages.categories_save_success');
        if($request->id && $request->id!=0) {
            $parent_category = Category::where('id',$request->id)->first();
        } else {
            $parent_category = new Category;
        }
        $parent_category->name = $request->name;
        $parent_category->ar_name = $request->ar_name;

        $parent_category->parent_category_id = $request->parent_category_id;
        $parent_category->save();
        return redirect()->back()->with(['success'=>$message]);
    }
}
