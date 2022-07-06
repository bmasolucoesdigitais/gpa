<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Serviceschedule extends Model
{
    public function company()
	{
	    return $this->belongsTo('App\Company', 'company_id');
	}
    
	public function apr()
	{
	    return $this->hasMany('App\Apr', 'service_id');
	}

    public function store()
	{
	    return $this->belongsTo('App\Company', 'store_id');
	}
    public function user()
	{
	    return $this->belongsTo('App\User', 'user_id');
	}
    public function file()
	{
	    return $this->belongsTo('App\File', 'file_id');
	}
    public function aprfile()
	{
	    return $this->belongsTo('App\File', 'aprsigned_id');
	}

	public function employees()
	{
	    return $this->belongsToMany('App\Employee', 'employee_serviceschedule', 'serviceschedule_id', 'employee_id' )
		->withTimestamps();
	}


	public static function boot()
    {
        parent::boot();

       
		
        self::created(function($model){
			$log = new Applog;
			$log->user_id = Auth::User()->id;
			$log->model =get_class($model);
			$log->model_id =  $model->getAttributes()['id'];
			$log->data =  json_encode($model->getAttributes());
			$log->save();
        });
		
        self::updated(function($model){
			$log = new Applog;
			if(isset(Auth::User()->id)){
				$log->user_id = Auth::User()->id;
			}else{
				$log->user_id = 1;

			}
			$log->model =get_class($model);
			$log->model_id =  $model->getAttributes()['id'];
			$log->data =  json_encode($model->getAttributes());
			$log->save();
        });

    }
}
