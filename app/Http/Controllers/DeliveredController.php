<?php

namespace App\Http\Controllers;

use App\Delivered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Employee;
use App\Document;

use App\Company;

class DeliveredController extends Controller
{


    public function index (Delivered $delivereds){

	$delivereds = $delivereds->all()->where('fl_deleted', 0);

	return view('app.g3.delivereds.index', compact('delivereds'));

    }

    public function insert()
    {
        $data = Array();
        $data['employees'] = Employee::all()->where('fl_deleted',0);
        $data['documents'] = Document::all()->where('fl_deleted',0);
        $data['companies'] = Company::all()->where('fl_deleted',0);
        //dd($data);
        return view('app.g3.delivereds.insert', compact('data'));
    }

    public function create(Request $request)
    {
    	$delivered = new Delivered;
		//$delivered->description = $request->input('description');
		$delivered->description = date("d/m/Y");
        $expiration = $request->input('expiration');
        $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
        $delivered->expiration = $expiration;
    	$delivered->document_id = $request->input('document_id');
		$delivered->employee_id = $request->input('employee_id');
		$delivered->company_id = $request->input('company_id');
        $delivered->save();

        dd($delivered->toArray());


        return redirect()->route('delivereds.index')->with('message', 'Delivered created successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
       $data = Array();
        $data['employees'] = Employee::all()->where('fl_deleted',0);
        $data['documents'] = Document::all()->where('fl_deleted',0);
        $data['companies'] = Company::all()->where('fl_deleted',0);

        $delivered = Delivered::findOrFail($id);

        return view('app.g3.delivereds.edit',compact('data','delivered'));
    }

    public function update(Request $request, $id)
    {

    	//dd($request->input());
      	$delivered = Delivered::findOrFail($id);
    	$delivered->description = $request->input('description');
        $expiration = $request->input('expiration');
        $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
    	$delivered->expiration = $expiration;
    	$delivered->document_id = $request->input('document_id');
		$delivered->employee_id = $request->input('employee_id');
		$delivered->company_id = $request->input('company_id');
        $delivered->save();

        dd($delivered->toArray());
        return redirect()->route('delivereds.index')->with('message', __('general.Delivered').' '. __('general.updated successfully!'));
    }

     public function delete($id)
    {

        $delivered = Delivered::findOrFail($id);

        return view('app.g3.delivereds.delete',compact('delivered'));
    }

    public function destroy(Request $request)
    {
        $delivered = Delivered::findOrFail($request->input('id'));
         //$delivereds = Delivered::findOrFail($id);
        $delivered->fl_deleted = 1;
        $delivered->save();
        return redirect()->route('delivereds.index')->with('alert-success','Delivered has been deleted!');
    }
}
