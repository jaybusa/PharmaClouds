<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotification extends Model
{
	use SoftDeletes;
	protected $table  = 'user_notifications'; 
    protected $dates = ['deleted_at'];

    public function order_detail() {
    	return $this->hasOne('\App\Models\Order','id','order_id');
    }

}
