<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeTraining extends Model
{
    protected $table = 'employee_training';

    public function employee()
	{
	    return $this->belongsTo('App\Employee');
	}
    public function training()
	{
	    return $this->belongsTo('App\Trainingschedules');
	}

    /* public function students()
	{
	    return $this->belongsToMany('App\Employee', 'employee_training', 'trainingschedule_id', 'employee_id' )
		->withTimestamps()
		->withPivot('email', 'fl_present','status_test','answers_json','token');
	} */
}
