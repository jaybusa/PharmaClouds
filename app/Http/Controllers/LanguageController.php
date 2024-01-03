<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use DB;
use Session;
use Cookie;
use Auth;
use Hash;
use Mail;
use App;

class LanguageController extends Controller 
{
    public function language($lang)
    {
    	
        if (array_key_exists($lang, config('locale.languages'))) 
        {
            //echo $lang;exit;
            \App::setLocale($lang);
            $locate = \App::getLocale();
            //echo $locate;
            Session::put('locale', $lang);
        }
        return redirect()->back();
    }
}   
