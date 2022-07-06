<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function company()
	{
	    return $this->belongsTo('App\Company');
	}

    public function services()
	{
	    return $this->belongsToMany('App\Service')
		->withTimestamps;
	}

	public function delivereds(){
		return $this->hasMany('App\Delivered');
	}

	public function docsettings(){
		return $this->hasMany('App\Settingsdoc');
	}


	


}
