<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    
	public function upload(Request $request){
			if($request->image){

				$file = $request->image;
				$upload = $file->store('public/'.$request->path);
				dd($upload);
			}else{
    			return view('app.upload');
			}



	}
}
