<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;
use App\Document;
use App\User;
use App\Company;
use Auth;
use JsValidator;

class ServiceController extends Controller
{

    protected $validationRules = [
            'name'=>'required|max:120',
            'description'=>'required|max:120',
            'initials'=>'required|max:10',
            
        ];

    public function index (Service $service){
    
    if(Auth::User()->can('master')){
        $services = $service->all()->where('fl_deleted', 0);
    }else{
	   $services = $service->all()->whereIn('company_id', [1,Auth::User()->company_id])->where('fl_deleted', 0);
    }
	//dd(Auth::User()->can('master'));
	return view('app.g3.services.index', compact('services'));
	
    }

    public function insert()
    {
    	//$users = User::all();
        $companies = Company::all();
        $validator = JsValidator::make($this->validationRules); 
        return view('app.g3.services.insert', compact('companies', 'validator'));
    }
  
    public function create(Request $request)
    {	
    	$service = new Service;
    	$service->name = $request->input('name');
		$service->description = $request->input('description');
		$service->initials = $request->input('initials');
		$service->company_id = $request->input('company_id');
        $service->save();


        return redirect()->route('services.index')->with('message', 'Service created successfully!');
    }
  
    public function show($id)
    {
        //
    }
  
    public function edit($id)
    {
        //dd(Auth::User());
    if(Service::find($id)->company_id == Auth::User()->company_id || Auth::user()->can('master')){
        //die('simm');
        $companies = Company::all();
        $service = Service::findOrFail($id);
        $validator = JsValidator::make($this->validationRules); 
        return view('app.g3.services.edit',compact('service', 'companies','validator'));
    }else{
        return redirect()->route('services.index')->withErrors(__('general.Permission denied'));; 
    }
    }
  
    public function update(Request $request, $id)
    {
     
    	//dd($request->input());
      	$service = Service::findOrFail($id);
    	$service->name = $request->input('name');
		$service->description = $request->input('description');
		$service->initials = $request->input('initials');
		$service->company_id = $request->input('company_id');
        $service->save();
        return redirect()->route('services.index')->with('message', 'Service updated successfully!');
    }

     public function delete($id)
    {
        
        $service = Service::findOrFail($id);

        return view('app.g3.services.delete',compact('service'));
    }
  
    public function destroy(Request $request)
    {
        $service = Service::findOrFail($request->input('id'));
         //$service = Service::findOrFail($id);
        $service->fl_deleted   = 1;
        $service->save();
        return redirect()->route('services.index')->with('alert-success','Service has been deleted!');
    }

       public function documents($id)
    {
    
        //$se = Service::find(1);
        //$ep = Employee::find(1);
        //dd($cp->Employees());
        //$ep->Companies()->save($cp);
        $service = Service::find($id);
       return view('app.g3.services.documents', compact('service'));
    
    }

    public function documentsAdd(Request $request, $id){

         if ($request->input()) {
            
            $documents = $request->input('documents'); 
            $se = Service::find($id);
            //$se = Service::find($services);
            //dd($se);
            $se->documents()->sync($documents);
             return redirect()->route('services.documents', ['id'=>$id])->with('alert-success','Services has added successfully!');
            //dd($request->input('services'));
        }
        $service = Service::find($id);
        $documents = Document::where('fl_deleted', 0)->get();
       return view('app.g3.services.documents_add', compact('service', 'documents'));

    }
}
