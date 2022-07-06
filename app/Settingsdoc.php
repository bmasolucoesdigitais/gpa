<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settingsdoc extends Model
{
    public function document()
	{
	    return $this->belongsTo('App\Document');
    }
    public function company()
	{
	    return $this->belongsTo('App\Company');
	}
}
