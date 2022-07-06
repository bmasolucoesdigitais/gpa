<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	public function employees()
	{
		return $this->belongsToMany('App\Employee')
		->withTimestamps();
	}

	public function clients()
	{
        return $this->belongsToMany('App\Company', 'company_client', 'company_id', 'client_id')
        ->withPivot('mail_company', 'mail_client')
		->withTimestamps();
	}


    public function providers()
	{
        return $this->belongsToMany('App\Company', 'company_client', 'client_id', 'company_id')
        ->withPivot('mail_company', 'mail_client')
		->withTimestamps();
	}

	public function outsourceds()
	{
		return $this->belongsToMany('App\Employee', 'company_outsourced', 'company_id', 'employee_id')
		->withPivot('fl_ready', 'dt_ready_sent', 'file_auth', 'fl_active')
		->withTimestamps();
	}

	public function documents()
	{
	    return $this->belongsToMany('App\Document', 'company_document', 'company_id', 'document_id' )
		->withTimestamps();
	}

	public function branches()
	{
	    return $this->hasMany('App\Company', 'headquarter');
	}

    public function services()
	{
	    return $this->hasMany('App\Serviceschedule', 'company_id');
	}

	public function headquarter()
	{
	    return $this->belongsTo('App\Company', 'headquarter');
	}

	public function docsettings(){
		return $this->hasMany('App\Settingsdoc');
	}


	//protected $table = 'companies';
}
