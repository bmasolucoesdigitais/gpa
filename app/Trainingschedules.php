<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainingschedules extends Model
{
    public function company()
	{
	    return $this->belongsTo('App\Company');
	}
    public function test()
	{
	    return $this->belongsTo('App\Test');
	}

    public function students()
	{
	    return $this->belongsToMany('App\Employee', 'employee_training', 'trainingschedule_id', 'employee_id' )
		->withTimestamps()
		->withPivot('email', 'fl_present','status_test','answers_json','token');
	}
}
