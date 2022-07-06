<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Company;
use Auth;
use JsValidator;

class DocumentController extends Controller
{

      protected $validationRules = [
            'name'=>'required|max:120',
            'description'=>'required|max:120',
            
            
        ];

    public function index (Document $documents){
    if(Auth::User()->can('master')){
        $documents = $documents->all()->where('fl_deleted', 0);
    }else{
        $documents = $documents->all()->whereIn('company_id', [1, Auth::User()->company_id])->where('fl_deleted', 0);
    }
	
	
	return view('app.g3.documents.index', compact('documents'));
	
    }

    public function insert()
    {
    	$users = User::all();
        $companies = Company::all();
        $validator = JsValidator::make($this->validationRules); 
        return view('app.g3.documents.insert', compact('users', 'companies', 'validator'));
    }
  
    public function create(Request $request)
    {	
        if( $request->input('fl_criteria')){
            $fl_criteria =   $request->input('fl_criteria');
        }else{
            $fl_criteria = 0;
        }
        if( $request->input('fl_print')){
            $fl_print =   $request->input('fl_print');
        }else{
            $fl_print = 0;
        }
    	$document = new Document;
    	$document->name = $request->input('name');
    	$document->type = $request->input('type');
		$document->description = $request->input('description');
		$document->fl_criteria = $fl_criteria;
		$document->fl_print = $fl_print;
		$document->company_id = $request->input('company_id');
        $document->save();


        return redirect()->route('documents.index')->with('message', 'Document created successfully!');
    }
  
    public function show($id)
    {
        //
    }
  
    public function edit($id)
    {

        $users = User::all();
        $document = Document::findOrFail($id);
        $companies = Company::all();
        $validator = JsValidator::make($this->validationRules); 
 
        return view('app.g3.documents.edit',compact('document', 'users', 'companies', 'validator'));
    }
  
    public function update(Request $request, $id)
    {
     
    	if( $request->input('fl_criteria')){
            $fl_criteria =   $request->input('fl_criteria');
        }else{
            $fl_criteria = 0;
        }
        if( $request->input('fl_print')){
            $fl_print =   $request->input('fl_print');
        }else{
            $fl_print = 0;
        }
      	$document = Document::findOrFail($id);
    	$document->name = $request->input('name');
    	$document->type = $request->input('type');
		$document->description = $request->input('description');
		$document->fl_criteria =  $fl_criteria;
		$document->fl_print =  $fl_print;
		$document->company_id = $request->input('company_id');
        $document->save();
        return redirect()->route('documents.index')->with('message', 'Document updated successfully!');
    }

     public function delete($id)
    {
        
        $document = Document::findOrFail($id);

        return view('app.g3.documents.delete',compact('document'));
    }
  
    public function destroy(Request $request)
    {
        $document = Document::findOrFail($request->input('id'));
         //$documents = Document::findOrFail($id);
        $document->fl_deleted   = 1;
        $document->save();
        return redirect()->route('documents.index')->with('alert-success','Document has been deleted!');
    }
}
