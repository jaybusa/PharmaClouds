<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PharmacloudsService extends Model
{
	use SoftDeletes;
	protected $table  = 'hairdresser_services'; 
    protected $dates = ['deleted_at'];

    public function category(){
    	return $this->hasMany('\App\Models\Category','parent_category_id','id');
    }

    public function category_detail(){
    	return $this->hasOne('\App\Models\Category','id','category_id');
    }
}
