<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Session;
use Illuminate\Support\Str;
use Crypt;
use App\Models\User;
use App\Models\ParentCategory;
use App\Models\Category;
use JWTAuth;

class CategoryController extends Controller
{

    public function __construct()
    {
    }

    public function category_list(Request $request) {
//        $return = veriftyAPITokenData();
//
//        //echo 'here';
//        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }
//
//        $user = JWTAuth::user();

        $category_list = ParentCategory::where('is_active',1)->whereHas('category',function($q){
            $q->where('is_active',1);
        })->with(['category'=>function($q){
            $q->where('is_active',1);
        }])->get();

        $message = __('messages.category_list_success');
        return SuccessResponse($message,200,$category_list);

    }


}
