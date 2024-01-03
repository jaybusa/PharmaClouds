<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;
use App\Utils;

class LoginController extends Controller {

    const ITEMS_PER_PAGE = 15;
    const TIME_FORMAT = 'Y-m-d H:i:s';

    public function index(Request $request) {
    	    //$locate = \App::getLocale();
    	    //echo $locate;exit;
       return view('admin.login');
    }

    public function post_login(Request $request) {
    	pre($request->all());
    }

}
