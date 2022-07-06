<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apr extends Model
{
    public function items()
	{
	    return $this->hasMany('App\AprItem', 'apr_id');
	}
}
