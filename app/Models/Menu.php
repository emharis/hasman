<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';

    public function parentMenu(){
    	return $this->belongsTo('App\Models\Menu','menu_id');
    }

    public function childMenus(){
    	return $this->hasMany('App\Models\Menu','menu_id','id');
    }
}