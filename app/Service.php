<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class Service extends Model
{


	public function company()
	{
	    return $this->belongsTo('App\Company');
	}

	public function employees()
	{
		return $this->belongsToMany('App\Employee')
		->withTimestamps();
	}

	

	public function documents()
	{
	    return $this->belongsToMany('App\Document')
		->withTimestamps();
	}

    
}