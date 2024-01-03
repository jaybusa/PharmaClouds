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
use App\Models\Order;
use App\Models\PharmacloudsService;
use App\Models\Category;
use App\Models\ParentCategory;
use JWTAuth;
use DB;

class ServiceController extends Controller
{

    public function __construct()
    {
    }

    public function add_edit_service(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            'category_id'=>'required',
            'price'=>'required',
        ];

        $messages = [
            'category_id.required'=>trans('messages.category_id_required'),
            'price.required'=>trans('messages.price_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();

        $check_added = PharmacloudsService::where('hairdresser_id',$user->id)->where('category_id',$request->category_id)->first();
        if($check_added) {
            $check_added->price = $request->price;
            $check_added->save();
        } else {
            $new = new PharmacloudsService;
            $new->hairdresser_id = $user->id;
            $new->category_id = $request->category_id;
            $new->price = $request->price;
            $new->save();
        }

        $message = __('messages.save_service_success');
        return SuccessResponse($message,200,[]);

    }

    public function delete_service(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $rules = [
            'service_id'=>'required',
        ];

        $messages = [
            'category_id.required'=>trans('messages.service_id_required'),
        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        $user = JWTAuth::user();

        $check_added = PharmacloudsService::where('hairdresser_id',$user->id)->where('id',$request->service_id)->delete();

        $message = __('messages.delete_service_success');
        return SuccessResponse($message,200,[]);

    }

    public function service_list(Request $request) {
        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
        $success = $return->original['success'];

        //if response false return
        if (!$success) {
            return $return;
        }

        $user = JWTAuth::user();

        //echo $user->id;exit;

        $category_list = PharmacloudsService::where('hairdresser_id',$user->id)->get()->pluck('category_id')->toArray();

        $parent_category_list = Category::whereIn('id',$category_list)->get()->pluck('parent_category_id')->toArray();

        $list = ParentCategory::where('is_active',1)->whereIn('id',$parent_category_list)->whereHas('category',function($q) use($category_list){
            $q->where('is_active',1)->whereIn('id',$category_list);
        })->with(['category'=>function($q) use($category_list,$user){
            $q = $q->where('is_active',1)->whereIn('id',$category_list);
            $q = $q->with(['service'=>function($q1) use($user) {
                $q1->where('hairdresser_id',$user->id);
            }]);
        }])->get();

        $message = __('messages.service_list_success');
        return SuccessResponse($message,200,$list);

    }

    public function near_by_hairdressor_list(Request $request) {
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

        $rules = [
            'service_ids'=>'required|json',
            'latitude'=>'required',
            'longitude'=>'required',
        ];

        $messages = [
            'service_ids.required'=>trans('messages.service_ids_required'),
            'service_ids.json'=>trans('messages.service_ids_json_required'),
            'latitude.required'=>trans('messages.latitude_required'),
            'longitude.required'=>trans('messages.longitude_required'),

        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }

        \Log::info('request : ', ['near_by_hairdressor_list' => json_encode($request->all())]);

        $user = JWTAuth::user();

        $lat = $request->latitude;
        $lng = $request->longitude;

        $order_hairdresser_ids = Order::whereIn('order_status',[ORDER_ACCEPT,ORDER_ON_THE_WAY,ORDER_PROCESSING])->pluck('hairdresser_id')->toArray();

        //pre($request->all());
        $service_ids = json_decode($request->service_ids);
        //pre($service_ids);
        $hairdresser_ids = PharmacloudsService::groupBy('hairdresser_id')
            ->having(DB::raw('count(DISTINCT CASE WHEN category_id IN ('.implode(',',$service_ids).') THEN category_id END)'),'=',count($service_ids))->select('hairdresser_id')->get()->pluck('hairdresser_id')->toArray();
        //pre($category_list);
        //$data_record = DB::select("SELECT hairdresser_id FROM hairdresser_services GROUP BY hairdresser_id HAVING COUNT(DISTINCT CASE WHEN category_id IN (1,2) THEN category_id END) = 2");

        //$hairdresser_ids = $data_record->get()->pluck('hairdresser_id');

        $radius = env('SEARCH_MILE');
        if (empty($lat) && empty($lng)) {
            $string = "(-1) as distance";
        } else {
            $string = "(3959 * ACOS(COS(RADIANS($lat))
                                * COS(RADIANS(latitude))
                                * COS(RADIANS($lng) - RADIANS(longitude))
                                + SIN(RADIANS($lat))
                            * SIN(RADIANS(latitude)))) AS distance";
        }

        $hairdresser_list = new User;
        $hairdresser_list = $hairdresser_list->where('role_id',3);
        $hairdresser_list = $hairdresser_list->whereNotIn('id',$order_hairdresser_ids);
        $hairdresser_list = $hairdresser_list->whereIn('id',$hairdresser_ids);
        $hairdresser_list = $hairdresser_list->where('is_active',1);
        $hairdresser_list = $hairdresser_list->where('is_online',1);

        $hairdresser_list = $hairdresser_list->select('*', DB::raw($string));
        if(!empty($user)){
            $hairdresser_list = $hairdresser_list->withCount(['favorite'=>function($q) use($user){
                $q->where('user_id',$user->id);
            }]);
        }

        // if($request->has('page')) {
        //     $page = $request->has('page') ? $request->page : 1;
        //     $page = $page - 1;
        //     $hairdresser_list = $hairdresser_list->skip($page * self::ITEMS_PER_PAGE)->take(self::ITEMS_PER_PAGE);
        // }
        $hairdresser_list = $hairdresser_list->with(['avgRating','totalEarning','reviews'=>function($q){
            $q->with(['sender']);
        },'service'=>function($q){
            $q->with(['category_detail'=>function($q1){
                $q1->with(['parent_category']);
            }]);
        },'review_list'=>function($q){
            $q = $q->with(['sender']);
            $q = $q->orderBy('created_at','desc');
            $q = $q->limit(5);
        }])->orderBy('distance', 'asc');
        $hairdresser_list = $hairdresser_list->having('distance', '<=', $radius);
        $hairdresser_list = $hairdresser_list->get();



        // $parent_category_list = Category::whereIn('id',$category_list)->get()->pluck('parent_category_id')->toArray();



        // $list = ParentCategory::where('is_active',1)->whereIn('id',$parent_category_list)->whereHas('category',function($q) use($category_list){
        //     $q->where('is_active',1)->whereIn('id',$category_list);
        // })->with(['category'=>function($q) use($category_list,$user){
        //     $q = $q->where('is_active',1)->whereIn('id',$category_list);
        //     $q = $q->with(['service'=>function($q1) use($user) {
        //         $q1->where('hairdresser_id',$user->id);
        //     }]);
        // }])->get();

        $message = __('messages.service_list_success');
        return SuccessResponse($message,200,$hairdresser_list);

    }

    public function near_by_hairdressor_list_dashboard(Request $request) {
//        $return = veriftyAPITokenData();
//
//        echo 'here';
////        get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'latitude'=>'required',
            'longitude'=>'required',
        ];

        $messages = [
            'service_ids.required'=>trans('messages.service_ids_required'),
            'service_ids.json'=>trans('messages.service_ids_json_required'),
            'latitude.required'=>trans('messages.latitude_required'),
            'longitude.required'=>trans('messages.longitude_required'),

        ];


        $validator = Validator::make($request->all(), $rules,$messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error,101);
        }


//        $user = JWTAuth::user();

        \Log::info('request : ', ['near_by_hairdressor_list_dashboard' => json_encode($request->all())]);

        $lat = $request->latitude;
        $lng = $request->longitude;

//        pre($request->all()); exit();
        //pre($service_ids);
        //pre($category_list);
        //$data_record = DB::select("SELECT hairdresser_id FROM hairdresser_services GROUP BY hairdresser_id HAVING COUNT(DISTINCT CASE WHEN category_id IN (1,2) THEN category_id END) = 2");

        //$hairdresser_ids = $data_record->get()->pluck('hairdresser_id');

        $radius = env('SEARCH_MILE');
        if (empty($lat) && empty($lng)) {
            $string = "(-1) as distance";
        } else {
            $string = "(3959 * ACOS(COS(RADIANS($lat))
                                * COS(RADIANS(latitude))
                                * COS(RADIANS($lng) - RADIANS(longitude))
                                + SIN(RADIANS($lat))
                            * SIN(RADIANS(latitude)))) AS distance";
        }

        $order_hairdresser_ids = Order::whereIn('order_status',[ORDER_ACCEPT,ORDER_ON_THE_WAY,ORDER_PROCESSING])->pluck('hairdresser_id')->toArray();
//        $hair_dresser_service = PharmacloudsService::distinct('hairdresser_id')->pluck('hairdresser_id')->toArray();

        $hairdresser_list = new User;
        $hairdresser_list = $hairdresser_list->where('role_id',3);
        $hairdresser_list = $hairdresser_list->whereNotIn('id',$order_hairdresser_ids);
//        $hairdresser_list = $hairdresser_list->whereIn('id',$hair_dresser_service);
        $hairdresser_list = $hairdresser_list->where('is_online',1);
        $hairdresser_list = $hairdresser_list->where('is_active',1);
        $hairdresser_list = $hairdresser_list->select('*', DB::raw($string));
        $hairdresser_list = $hairdresser_list->whereHas('service');
//        if(!empty($user)){
//            $hairdresser_list = $hairdresser_list->withCount(['favorite'=>function($q) use($user){
//                $q->where('user_id',$user->id);
//            }]);
//        }

        $hairdresser_list = $hairdresser_list->with(['avgRating','totalEarning','reviews'=>function($q){
            $q->with(['sender']);
        },'service'=>function($q){
            $q->with(['category_detail'=>function($q1){
                $q1->with(['parent_category']);
            }]);
        },'review_list'=>function($q){
            $q = $q->with(['sender']);
            $q = $q->orderBy('created_at','desc');
            $q = $q->limit(5);
        }])->orderBy('distance', 'asc');
        $hairdresser_list = $hairdresser_list->having('distance', '<=', $radius);
        $hairdresser_list = $hairdresser_list->get();

        $message = __('messages.service_list_success');
        return SuccessResponse($message,200,$hairdresser_list);

    }

    public function hairdressor_detail(Request $request)
    {
//        $return = veriftyAPITokenData();

        //echo 'here';
        //get success of response
//        $success = $return->original['success'];
//
//        //if response false return
//        if (!$success) {
//            return $return;
//        }

        $rules = [
            'hairdresser_id' => 'required',
        ];

        $messages = [
            'hairdresser_id.required' => trans('messages.hairdresser_id_required'),
        ];


        $validator = Validator::make($request->all(), $rules, $messages);

        //if validation false
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return InvalidResponse($error, 101);
        }


        $user = JWTAuth::user();

        $lat = $request->latitude;
        $lng = $request->longitude;

        //pre($request->all());
        //$service_ids = json_decode($request->service_ids);
        //pre($service_ids);
        //$hairdresser_ids = PharmacloudsService::groupBy('hairdresser_id')->having(DB::raw('count(DISTINCT CASE WHEN category_id IN ('.implode(',',$service_ids).') THEN category_id END)'),'=',count($service_ids))->select('hairdresser_id')->get()->pluck('hairdresser_id')->toArray();
        //pre($category_list);
        //$data_record = DB::select("SELECT hairdresser_id FROM hairdresser_services GROUP BY hairdresser_id HAVING COUNT(DISTINCT CASE WHEN category_id IN (1,2) THEN category_id END) = 2");

        //$hairdresser_ids = $data_record->get()->pluck('hairdresser_id');
        //echo 'dsd';exit;

        $hairdresser_list = new User;
        $hairdresser_list = $hairdresser_list->where('id', $request->hairdresser_id);
        //$hairdresser_list = $hairdresser_list->where('is_online',1);
        $hairdresser_list = $hairdresser_list->where('role_id', 3);
        if (!empty($user)){
            $hairdresser_list = $hairdresser_list->withCount(['favorite' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);
        }

        //$hairdresser_list = $hairdresser_list->where('is_active',1);
        $hairdresser_list = $hairdresser_list->with(['avgRating','totalEarning','reviews'=>function($q){
            $q->with(['sender']);
        },'service'=>function($q){
            $q->with(['category_detail'=>function($q1){
                $q1->with(['parent_category']);
            }]);
        },'review_list'=>function($q){
            $q = $q->with(['sender']);
            $q = $q->orderBy('created_at','desc');
            $q = $q->limit(5);
        }]);
        $hairdresser_list = $hairdresser_list->first();

        $message = __('messages.hairdresser_detail_success');
        return SuccessResponse($message,200,$hairdresser_list);

    }




}
