<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Company;
use App\Applog;
Use Auth;
class Delivered extends Model
{
    public function company()
	{
	    return $this->belongsTo('App\Company');
	}
	 public function document()
	{
	    return $this->belongsTo('App\Document');
	}
	 public function employee()
	{
	    return $this->belongsTo('App\Employee');
	}
	public function files()
	{
	    return $this->belongsToMany('App\File')
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
			$log->user_id = Auth::User()->id;
			$log->model =get_class($model);
			$log->model_id =  $model->getAttributes()['id'];
			$log->data =  json_encode($model->getAttributes());
			$log->save();
        });

    }
}
