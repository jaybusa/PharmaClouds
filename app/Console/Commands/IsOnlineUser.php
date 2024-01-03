<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use Carbon\Carbon;
use App\Models\User;
use DB;

class IsOnlineUser extends Command
{

    protected $signature = 'is_online_user:cron';
    protected $description = 'is online user cron';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::now()->addMinute(-5)->format("Y-m-d H:i:s");

        $user_list = \App\Models\User::where('is_online_date','<=',$date)->get();

        foreach ($user_list as $key => $value) {
            $check_user = \App\Models\User::where('id',$value)->first();
            $check_user->is_online = 0;
            $check_user->offline_date=null;
            $check_user->is_online_date = date('Y-m-d H:i:s');
            $check_user->save();
            \Log::info('User Offline cron', ['Date' => date('Y-m-d H:i:s'), 'User' => $check_user]);

        }
        //echo 'Date :'.$date;
    }
}