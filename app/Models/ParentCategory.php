<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentCategory extends Model
{
	// use SoftDeletes;
	protected $table  = 'category'; 
    // protected $dates = ['deleted_at'];

    public function category(){
    	return $this->hasMany('\App\Models\Category','parent_category_id','id');
    }
}
