<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReview extends Model
{
	use SoftDeletes;
	protected $table  = 'order_review'; 
    protected $dates = ['deleted_at'];

    public function sender() {
    	return $this->hasOne('\App\Models\User','id','sender_id');
    }
}
