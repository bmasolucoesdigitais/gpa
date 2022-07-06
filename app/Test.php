<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public function company()
	{
	    return $this->belongsTo('App\Company', 'company_id', 'id' );
	}

    public function quests()
	{
	    return $this->hasMany('App\Question', 'test_id');
	}
}
