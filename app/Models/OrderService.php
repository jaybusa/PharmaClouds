<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderService extends Model
{
	use SoftDeletes;
	protected $table  = 'order_services'; 
    protected $dates = ['deleted_at'];

    public function customer(){
    	return $this->hasMany('\App\Models\User','id','customer_id');
    }

    public function hairdresser(){
    	return $this->hasMany('\App\Models\User','id','hairdresser_id');
    }

    public function category(){
    	return $this->hasOne('\App\Models\Category','id','service_id');
    }
}
