<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	// use SoftDeletes;
	protected $table  = 'orders'; 
    protected $dates = [];

    public function client(){
    	return $this->hasOne('\App\Models\Clients','id','client_id');
    }

    public function coupon(){
    	return $this->hasOne('\App\Models\Coupon','id','coupon_id');
    }
}
