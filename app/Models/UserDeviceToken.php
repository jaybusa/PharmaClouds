<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDeviceToken extends Model
{
	use SoftDeletes;
	protected $table  = 'user_device_token'; 
    protected $dates = ['deleted_at'];
}
