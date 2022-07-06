<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Company;

class Employee extends Model
{
	public function companies()
	{
		return $this->belongsToMany('App\Company')
		->withTimestamps();
	}

    public function outsourceds()
	{
		return $this->belongsToMany('App\Company', 'company_outsourced', 'employee_id', 'company_id')
		->withPivot('fl_ready', 'dt_ready_sent', 'file_auth')
		->withTimestamps();
	}

    public function outs()
	{
		return $this->belongsToMany('App\Company', 'company_employee', 'employee_id', 'company_id')
		->withPivot('fl_ready', 'dt_ready_sent', 'file_auth')
		->withTimestamps();
	}

	public function delivereds()
	{
		return $this->hasMany('App\Delivered');
	}

	public function services()
	{
		return $this->belongsToMany('App\Service')
		->withTimestamps();
	}

public function files()
	{
		return $this->hasMany('App\File');
	}

public function documents()
	{
	    return $this->belongsToMany('App\Document')
		->withTimestamps();
	}

	public function allower()
	{
	    return $this->belongsTo('App\User', 'manual_user_id', 'id' );
	}



}
