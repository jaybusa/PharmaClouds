<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/query_build', function (){
    $socket_data = ["type"=>"7","user_id"=>"88","user_id_2"=>"105","title"=>"Order completed","message"=>"Order 42 has been completed","order_id"=>"42"];
    print_r(send_socket($socket_data, 'send_notification')); exit();
});

Route::group(['middleware' => ['Localization'],'prefix' => '/v1','namespace' => 'Api\v1'], function () {
	Route::post('/register', 'UserController@register');
	Route::post('/check_email', 'UserController@check_email');
	Route::post('/forgot_password_otp', 'UserController@forgot_password_otp');

	Route::post('/resend_otp', 'UserController@resend_otp');


	Route::post('/login', 'UserController@login');
	Route::post('/settings', 'UserController@admin_settings');
	Route::post('/forgot_password', 'UserController@forgot_password');

	Route::post('/banner_list', 'UserController@banner_list');

    Route::post('/category_list', 'CategoryController@category_list');
    Route::post('/near_by_hairdressor_list_dashboard', 'ServiceController@near_by_hairdressor_list_dashboard');
    Route::post('/near_by_hairdressor_list', 'ServiceController@near_by_hairdressor_list');
    Route::post('/hairdressor_detail', 'ServiceController@hairdressor_detail');
});

Route::group(['middleware' => ['Localization','jwt.auth'],'prefix' => '/v1','namespace' => 'Api\v1'], function () {
		Route::post('/send_otp', 'UserController@send_otp');
		Route::post('/change_password', 'UserController@change_password');
		Route::post('/profile', 'UserController@profile');
		Route::post('/update_profile', 'UserController@update_profile');
		Route::post('/delete_profile/{id}', 'UserController@destroy');
		Route::post('/logout', 'UserController@logout');

		Route::post('/user_favorite', 'UserController@user_favorite');
		Route::post('/user_favorite_list', 'UserController@user_favorite_list');

		Route::post('/user_withdraw_request', 'UserController@user_withdraw_request');
		Route::post('/add_money', 'UserController@add_money');

		Route::post('/order_otp', 'OrderController@order_otp');
		Route::post('/check_ongoing_order', 'OrderController@check_ongoing_order');




		Route::post('/add_edit_service', 'ServiceController@add_edit_service');
		Route::post('/delete_service', 'ServiceController@delete_service');
		Route::post('/service_list', 'ServiceController@service_list');

		Route::post('/wallet_list', 'UserController@wallet_list');



		Route::post('/notification_list', 'UserController@notification_list');
		Route::post('/clear_notification_list', 'UserController@clear_notification_list');







		Route::post('/order_request', 'OrderController@order_request');

		Route::post('/order_timeout', 'OrderController@order_timeout');

		Route::post('/order_cancel', 'OrderController@order_cancel');

		Route::post('/order_accept', 'OrderController@order_accept');

		Route::post('/order_on_the_way', 'OrderController@order_on_the_way');

		Route::post('/order_processing', 'OrderController@order_processing');

		Route::post('/order_complete', 'OrderController@order_complete');

		Route::post('/order_payment', 'OrderController@order_payment');

		Route::post('/my_order_list', 'OrderController@my_order_list');

		Route::post('/order_detail', 'OrderController@order_detail');

		Route::post('/apply_promocode', 'OrderController@apply_promocode');

		Route::post('/order_review', 'OrderController@order_review');

		Route::post('/request_order_list', 'OrderController@request_order_list');

		Route::post('/order_retry', 'OrderController@order_retry');

		Route::post('/order_reject', 'OrderController@order_reject');

		Route::post('/order_dispute', 'OrderController@order_dispute');


		Route::post('/previously_hired_hairdresser_list', 'OrderController@previously_hired_hairdresser_list');



});
