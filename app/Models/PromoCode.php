<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
	use SoftDeletes;
	protected $table  = 'promocodes'; 
    protected $dates = ['deleted_at'];
}
