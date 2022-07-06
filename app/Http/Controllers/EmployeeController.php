<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Service;
use App\Serviceschedule;
use App\Company;
use App\Delivered;
use App\Document;
use App\File;
use Auth;
use Illuminate\Support\Facades\Redirect;
use JsValidator;



class EmployeeController extends Controller
{

    protected $validationRules = [
        'name'=>'required|max:120',
        'cpf'=>'required|min:14',
        'rg'=>'required',
        'borndate'=>'required',


    ];


    public function index (Request $request, Employee $employee, $brc = null){
        /*
        $company = Company::findOrFail(2);
        $company->outsourceds()->syncWithoutDetaching(3);
        $employees = Employee::with('companies')->get();
        dd($employees);
        */

        $branch = Company::find($brc);
        if (Auth::user()->can('G3 Admin')) {


            $employees = $employee->all()->where('fl_deleted', 0);

            return view('app.g3.employees.index', compact('employees'));

        }
        else if (Auth::user()->can('fornecedor'))
        {


               $employees = Company::findOrFail(Auth::User()->company_id)->employees->where('fl_deleted', 0);


           return view('app.g3.employees.index', compact('employees'));
        }
        else
        {
            if($request->is('g3/branches*')){
                $employees = Company::findOrFail($brc)->outsourceds->where('fl_deleted', 0);
            }else{

               $employees = Company::findOrFail(Auth::User()->company_id)->outsourceds->where('fl_deleted', 0);
              //dd(Auth::User()->company_id);
            }
           return view('app.g3.employees.index', compact('employees', 'branch'));
       }
   }

   public function outsourceds ($id = null){

        /*
        $company = Company::findOrFail(2);
        $company->outsourceds()->syncWithoutDetaching(3);
        $employees = Employee::with('companies')->get();
        dd($employees);
        */

        if (Auth::user()->can('master')) {
            $employees = Company::find($id)->outsourceds->where('fl_deleted', 0);
            //dd($employees);
            return view('app.g3.employees.outsourceds', compact('employees'));
        }
        else
        {
           $employees = Company::findOrFail(Auth::User()->company_id)->outsourceds->where('fl_deleted', 0);
          //dd(Auth::User()->company_id);
           return view('app.g3.employees.outsourceds', compact('employees'));
       }
   }

   public function insert()
   {
       $users = User::all();
       $validator = JsValidator::make($this->validationRules);
       return view('app.g3.employees.insert', compact('users','validator'));
   }

    public function create(Request $request){

        if ($request->input('allowed')) {
            $allowed = 1;
        }else{
            $allowed = 0;
        }
        $employee = new Employee;
        $employee->name = strtoupper($request->input('name'));
        $employee->cpf = $request->input('cpf');
        $employee->rg = $request->input('rg');
        $borndate = $request->input('borndate');
        $borndate = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $borndate);
        $employee->borndate = $borndate;
        $employee->allowed = 0;
        $employee->save();

        $employee->documents()->syncWithoutDetaching([3,4,5,6]);

        if (Auth::user()->can('fornecedor'))
        {
            $company = Company::find(Auth::User()->company_id);
            $company->employees()->attach($employee->id);

        }

        if ($request->input('brc')) {
            $company = Company::findOrFail($request->input('brc'));
        }else{
            $company = Company::findOrFail(Auth::User()->company_id);
        }
        if (!Auth::User()->can('master')) {
            $company->outsourceds()->attach($employee->id);
        }
        if ($request->input('brc')) {
            return redirect()->route('branches.outsourceds', $request->input('brc'))->with('message', 'Employee created successfully!');
        }else{
            return redirect()->route('employees.index')->with('message', 'Employee created successfully!');

        }
    }

    public function show($id){
        //
    }

    public function edit($id){
        $users = User::all();
        $employee = Employee::findOrFail($id);
        $validator = JsValidator::make($this->validationRules);
        return view('app.g3.employees.edit',compact('employee', 'users','validator'));
    }

    public function update(Request $request, $id){

        if ($request->input('allowed')) {
            $allowed = 1;
        }else{
            $allowed = 0;
        }
        $employee = Employee::findOrFail($id);
        $employee->name = $request->input('name');
        $employee->cpf = $request->input('cpf');
        $employee->rg = $request->input('rg');
        $borndate = $request->input('borndate');
        $borndate = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $borndate);
        $employee->borndate = $borndate;
        $employee->allowed = $allowed;
        $employee->save();
        return redirect()->route('employees.index')->with('message', 'Employee updated successfully!');
    }


    public function allow(Request $request, $id){
        if($request->input()){
            $company = Company::findOrFail(Auth::user()->company_id);
            $employee = $company->outsourceds()->findOrFail($id);
            if($request->input('allow') == 1){
                $allow = 1;
            }else{
                $allow = 0;
            }
            $employee->manual = $allow;
            $dt_end = $request->input('dt_end');
            $dt_end = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $dt_end);
            $employee->dt_manual = $dt_end;
            $employee->manual_user_id = Auth::User()->id;
            $employee->save();
            if ($request->hasFile('file')) {
                $name=$request->file->getClientOriginalName();
                $fileUpload = $request->file;
                $fileName=$request->file->getClientOriginalName();
                $upload = $fileUpload->store('public/uploads');


                $file = new File;
                $file->name = $name;
                $file->file = str_replace('public/uploads/', "", $upload);
                $file->save();

                $company->outsourceds()->updateExistingPivot($employee, ['file_auth' => $file]);
            }
            return redirect()->route('employees.index')->with('message', 'Autorização manual editada com sucesso!');

        }else{
            $company = Company::findOrFail(Auth::user()->company_id);
            $employee = $company->outsourceds()->findOrFail($id);
            return view('app.g3.employees.allow',compact('employee'));
        }
    }

    public function delete($id){

        $employee = Employee::findOrFail($id);

        return view('app.g3.employees.delete',compact('employee'));
    }

    public function destroy(Request $request)
    {
        if(Auth::User()->can('master')){
            $employee = Employee::findOrFail($request->input('id'));
             //$employee = Employee::findOrFail($id);
            $employee->fl_deleted   = 1;
            $employee->save();
            return redirect()->route('employees.index')->with('alert-success','Employee has been deleted!');
        }else{
            return redirect()->route('employees.index')->withError('general.Permission denied');
        }

    }

    public function test()
    {
        $ep = Serviceschedule::find(11)->employees[0]->services;
        dd($ep);


        //delivereds()->where('fl_deleted', 0)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'))->count());

        $se = Service::find(1);
        $ep = Employee::find(1);
            //dd($cp->Employees());
            //$ep->Companies()->save($cp);
        $employees = Employee::all();
        return view('app.g3.employees.test', compact('employees'));

    }

    public function services($id, $brc = null)
    {

            //$se = Service::find(1);
            //$ep = Employee::find(1);
            //dd($cp->Employees());
            //$ep->Companies()->save($cp);
        $branch=Company::find($brc);
        $data = Array();
        $data['employee'] = Employee::find($id);
        if(Auth::User()->can('master')){
            $data['services'] = Employee::find($id)->services->where('fl_deleted', 0);
        }else{
            $data['services'] = Employee::find($id)->services->where('fl_deleted', 0)->whereIn('company_id', [1,Auth::User()->company_id]);

        }
        return view('app.g3.employees.services', compact('data', 'branch'));

    }

    public function servicesAdd(Request $request, $id, $brc=null)
    {

        if ($request->input()) {



            $services = $request->input('services');
            $ep = Employee::find($id);
                //$se = Service::find($services);
                //dd($se);
            $ep->services()->syncWithoutDetaching($services);
            if($request->is('g3/branches*')){
                return redirect()->route('branches.outsourceds.services', ['id'=>$id, $brc])->with('alert-success','Services has added successfully!');
            }else{
                return redirect()->route('employees.documents_services', ['id'=>$id])->with('alert-success','Services has added successfully!');
            }
                //dd($request->input('services'));
        }else{
            if(Auth::User()->can('master')){
                $employee = Employee::find($id);
                $services = Service::where('fl_deleted', 0)->whereNotIn('id', $employee->services()->pluck('id')->toArray())->get();
            }else{
                $employee = Employee::find($id);
                $services = Service::whereIn('company_id',[1,Auth::User()->company_id] )->where('fl_deleted', 0)->whereNotIn('id', $employee->services()->pluck('id')->toArray())->get();
            }
            return view('app.g3.employees.services_add', compact('employee', 'services'));
        }

    }


    public function servicesDetach(Request $request)
    {

        if ($request->input()) {

            $eid = $request->input('eid');
            $id = $request->input('id');
            $service = Service::findOrFail($id);
            $employee = Employee::findOrFail($eid);

            if (Auth::User()->can('master')) {
               if($employee->services()->detach($service)){
                return 1;
            }else{
                return 0;
            }
        //}elseif (Auth::User()->company->outsourceds->find($eid)) {
        }
            if($employee->services()->detach($service)){
                return 1;
            }else{
                return 0;
            }
    }


    return 0;

    }

    public function attach(Request $request, $brc = null){

        if ($request->input('cpf')) {

            if($request->is('g3/branches*')){


                $cpf = $request->input('cpf');
                $company = Company::find($brc);
                $employee = Employee::where('cpf', $cpf)->limit(1)->get();

                if(!$employee->isEmpty()){

                    if($company->outsourceds->find($employee[0]->id)){
                        return redirect()->route('employees.index')->withErrors(__('general.Outsourced')." ". $employee[0]->name." ". __('general.already exists!'));

                    }else{
                        $company->outsourceds()->attach($employee);
                        return redirect()->route('branches.outsourceds',$brc)->with('alert-success',__('general.Outsourced')." ". $employee[0]->name." ". __('general.has added successfully!'));
                    }

                }else{
                         //$users = User::all();
                         //$companies = Company::all();
                    $validator = JsValidator::make($this->validationRules);
                    return view('app.g3.employees.insert', compact('cpf', 'validator', 'brc'));
                }

                //$se = Service::find($services);
               //dd($client);
            }else{

                $cpf = $request->input('cpf');
                $company = Company::find(Auth::user()->company_id);
                $employee = Employee::where('cpf', $cpf)->limit(1)->get();

                if(!$employee->isEmpty()){

                    if($company->outsourceds->find($employee[0]->id)){
                        return redirect()->route('employees.index')->withErrors(__('general.Outsourced')." ". $employee[0]->name." ". __('general.already exists!'));

                    }else{
                        $company->outsourceds()->attach($employee);
                        return redirect()->route('employees.index')->with('alert-success',__('general.Employee')." ". $employee[0]->name." ". __('general.has added successfully!'));
                    }

                }else{
                         //$users = User::all();
                         //$companies = Company::all();
                    $validator = JsValidator::make($this->validationRules);
                    return view('app.g3.employees.insert', compact('cpf', 'validator'));
                }

            }
        }else{
            return view('app.g3.employees.attach');
        }


    }

    public function detach($cp, $id)
    {


     $company = Company::find($cp);
     $outsourced = $id;
           //dd($client);
     //$company->outsourceds()->detach($outsourced);
     $company->outsourceds()->updateExistingPivot($outsourced, ['fl_active' => 0]);
     return Redirect::back()->with('alert-success',__('general.Outsourced').' '. __('general.has removed successfully!'));



    }

    public function reattach($cp, $id)
    {


     $company = Company::find($cp);
     $outsourced = $id;
           //dd($client);
     //$company->outsourceds()->detach($outsourced);
     $company->outsourceds()->updateExistingPivot($outsourced, ['fl_active' => 1]);
     return Redirect::back()->with('alert-success',__('general.Outsourced').' '. __('general.has reactivated successfully!'));



    }

    public function employee($id, $brc=null){
        $branch = Company::find($brc);
        $employee = Employee::findOrFail($id);
        $outs = $employee->outsourceds()->whereNotIn('company_outsourced.company_id', $employee->companies()->pluck('id')->toArray())->get();
            //dd($employee);

        return view('app.g3.employees.employee', compact('employee', 'branch', 'outs'));
    }


    public function delivereds ($eid, $sid, $brc = null){


        $data = Array();
        $data['employee'] = Employee::find($eid);
        $data['service'] = Employee::find($eid)->Services()->find($sid);
        //dd($data['employee']->delivereds->where());


        $branch = Company::find($brc);
        $service=Employee::find($eid)->Services()->find($sid);
            //echo(date('Ymd')<date('Ymd',strtotime($service->documents[1]->delivereds[0]->expiration))?"sim" : "não");
            //dd($service->documents[0]->delivereds[0]->files);
        return view('app.g3.employees.delivereds', compact('data', 'branch'));

    }



    public function deliveredsAdd(Request $request, $eid, $did, $sid, $brc = null)
    {
        $validation = [
           // 'description'=>'required|max:120',
            'expiration'=>'required|min:9',
            'file'=>'mimes:jpg,jpeg,png,pdf',
        ];

        if ($request->input()) {

            $delivered = new Delivered;
            //$delivered->description = $request->input('description');
            $delivered->description = date("d/m/Y");
            $expiration = $request->input('expiration');
            $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
            $delivered->expiration = $expiration;
            $delivered->document_id = $did;
            $delivered->employee_id = $eid;
            $delivered->company_id = $request->input('company');
            if(Auth::User()->can('fornecedor')){
                $delivered->status = 2;
            }else{
                $delivered->status = $request->input('status');
            }
            $delivered->save();

            if ($request->hasFile('file')) {
                $name=$request->file->getClientOriginalName();
                $fileUpload = $request->file;
                $fileName=$request->file->getClientOriginalName();
                $upload = $fileUpload->store('public/uploads');


                $file = new File;
                $file->name = $name;
                $file->file = str_replace('public/uploads/', "", $upload);
                $file->save();

                $delivered->files()->attach($file);
            }

            if($request->is('g3/branches*')){
                return redirect()->route('branches.outsourceds.services.delivereds', [$eid, $sid, $brc])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
            }else{
                return redirect()->route('employees.delivereds', [$eid, $sid])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
            }
        }
        $branch = Company::find($brc);
        $data['employee'] = Employee::find($eid);
        $data['document'] = Document::find($did);
        $data['service'] = Service::find($sid);
        $validator = JsValidator::make($validation);
        if (Auth::User()->can('master')) {
            $clients = Company::where('fl_client', 1)->get();
            //dd($clients);
            return view('app.g3.employees.delivereds_add', compact('data', 'validator', 'branch', 'clients'));
        }else{
            return view('app.g3.employees.delivereds_add', compact('data', 'validator', 'branch'));
        }

    }


    public function deliveredsUpload(Request $request, $eid, $sid, $did, $brc = null)
    {

        $validations = [
            'file'=>'required|mimes:jpg,jpeg,png,pdf',
        ];

        if ($request->input()) {

             //dd($request->file->getClientOriginalName());
            $name = $request->name;
            if ($name == "") {
                $name=$request->file->getClientOriginalName();
            }


            $delivered = Delivered::findOrFail($request->delivered);

            $fileUpload = $request->file;
            $upload = $fileUpload->store('public/uploads');
            //dd($upload);

            $file = new File;
            $file->name = $name;
            $file->file = str_replace('public/uploads/', "", $upload);
            $file->save();

            $delivered->files()->attach($file);

            if ($request->is('g3/branches*')) {
                return redirect()->route('branches.outsourceds.services.delivereds', [$eid, $sid, $brc])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
            }else{
                return redirect()->route('employees.delivereds', [$eid, $sid])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
            }
        }
        $branch = Company::find($brc);
        $data['employee'] =  Employee::find($eid);
        $data['delivered'] =  Delivered::find($did);
        $data['service'] = Service::find($sid);
        $validator = JsValidator::make($validations);
        return view('app.g3.employees.delivereds_upload', compact('data', 'validator', 'branch'));

    }

    public function deliveredsEdit(Request $request, $eid, $sid, $did, $brc = null)
    {

        $validation = [
            'description'=>'required|max:120',
            'expiration'=>'required|min:9',


        ];

        if ($request->input()) {

                //dd($request->file);

            $delivered = Delivered::findOrFail($did);
            $delivered->description = $request->input('description');
            $expiration = $request->input('expiration');
            $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
            $delivered->expiration = $expiration;
                //$delivered->document_id = $did;
            $delivered->company_id = $request->input('company');
            if(Auth::User()->can('fornecedor')){
                $delivered->status = 2;
            }else{
                $delivered->status = $request->input('status');
            }
            $delivered->save();


        if($request->is('g3/branches*')){
            return redirect()->route('branches.outsourceds.services.delivereds', [$eid, $sid, $brc])->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
        }else{
            return redirect()->route('employees.delivereds', [$eid, $sid])->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
        }
        }
        $branch=Company::find($brc);
        $data['employee'] = Employee::find($eid);
        $data['delivered'] = Delivered::find($did);
        $validator = JsValidator::make($validation);
        if(Auth::User()->can('master')){
            $clients = Company::where('fl_client', 1)->get();
            return view('app.g3.employees.delivereds_edit', compact('data', 'validator', 'branch', 'clients'));
        }else{
            return view('app.g3.employees.delivereds_edit', compact('data', 'validator', 'branch'));
        }

    }

    public function fileDelete(Request $request){
        $id = $request->id;
        $eid = $request->eid;
        if ($request->input()) {
                    //    echo ("O id é ". $id . 'e o' . $eid);


            if (Auth::User()->can('master')) {
                if($file = File::find($id)){
                    $file->fl_deleted = 1;
                    $file->save();
                    return 1;
                }else{
                    return 0;
                }

            }else{

                //if (Auth::User()->company->outsourceds->find($eid)) {
                   if($file = File::find($id)){
                    $file->fl_deleted = 1;
                    $file->save();
                    return 1;
                }else{
                    return 0;
                }
            //} else {
              //  return 0;
            //}
        }


        }
    }

//-----------------------documents-----------------------------------------

    public function documents($id, $brc = null)
    {


        if (Auth::user()->can('master')) {
            $employee = employee::findOrFail($id);
            $documents = employee::findOrFail($id)->documents;
            //dd($documents);
            return view('app.g3.employees.documents', compact('documents', 'employee'));
        }
        else if(Auth::user()->can('fornecedor')){
             //dd(Auth::User()->Company->outsourceds);
           // if(Auth::User()->Company->outsourceds->find($id)){
            $employee = employee::findOrFail($id);
            $documents = employee::findOrFail($id)->documents;
            $branch = company::find($brc);
            return view('app.g3.employees.documents', compact('documents', 'employee', 'branch'));
        //}else{

          //  return redirect()->route('employees.index')->withErrors(__('general.Permission denied'));
        //}
        }
        else
        {
            //dd(Auth::User()->Company->outsourceds);
           // if(Auth::User()->Company->outsourceds->find($id)){
                $employee = employee::findOrFail($id);
                $documents = employee::findOrFail($id)->documents->whereIn('company_id', [1,Auth::User()->company_id]);
                $branch = company::find($brc);
                return view('app.g3.employees.documents', compact('documents', 'employee', 'branch'));
            //}else{

              //  return redirect()->route('employees.index')->withErrors(__('general.Permission denied'));
            //}
        }
    }
    public function documentsServices($id, $brc = null)
    {


        if (Auth::user()->can('master')) {
            $employee = employee::findOrFail($id);
            $documents = employee::findOrFail($id)->documents;
            //dd($documents);
            return view('app.g3.employees.documents_services', compact('documents', 'employee'));
        }
        else if(Auth::user()->can('fornecedor')){
             //dd(Auth::User()->Company->outsourceds);
           // if(Auth::User()->Company->outsourceds->find($id)){
            $employee = employee::findOrFail($id);
            $documents = employee::findOrFail($id)->documents;
            $branch = company::find($brc);
            return view('app.g3.employees.documents_services', compact('documents', 'employee', 'branch'));
        //}else{

          //  return redirect()->route('employees.index')->withErrors(__('general.Permission denied'));
        //}
        }
        else
        {
            //dd(Auth::User()->Company->outsourceds);
           // if(Auth::User()->Company->outsourceds->find($id)){
                $employee = employee::findOrFail($id);
                $documents = employee::findOrFail($id)->documents->whereIn('company_id', [1,Auth::User()->company_id]);
                $branch = company::find($brc);
                return view('app.g3.employees.documents_services', compact('documents', 'employee', 'branch'));
            //}else{

              //  return redirect()->route('employees.index')->withErrors(__('general.Permission denied'));
            //}
        }
    }

    public function clientDocuments($client, $id)
    {
            $employee = employee::findOrFail($id);
            $documents = employee::findOrFail($id)->documents;
            $client = $client;
            return view('app.g3.employees.documents', compact('documents', 'employee', 'client'));
    }

    public function documentsAttach(Request $request, $cid = null, $eid, $brc = null)
    {

        if ($request->input()){
            $employee = Employee::findOrFail($eid);
            $documents = $request->input('documents');
            $employee->documents()->syncWithoutDetaching($documents);

            if($brc){
                return redirect()->route('branches.outsourceds.documents', [$employee->id, $brc])->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
            }elseif($cid){
                return redirect()->route('clients.employees.documents_services', [$cid, $employee->id])->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
            }else{
                return redirect()->route('employees.documents_services', $employee->id)->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
            }

        }else{
            if(Auth::User()->can('master')){
                $client = $cid;
                $employee = Employee::findOrFail($eid);
                $documents = Document::whereNotIn('id', $employee->documents->pluck('id')->toArray())->get();
                return view('app.g3.employees.documents_attach', compact('documents', 'employee', 'client'));
            }else{
                $employee = Employee::findOrFail($eid);
                $documents = Document::whereNotIn('id', $employee->documents->pluck('id')->toArray())->whereIn('company_id', [1,Auth::User()->company_id])->get();
                return view('app.g3.employees.documents_attach', compact('documents', 'employee'));

            }
        }

    }

    public function docAttach(Request $request, $eid)
    {

        if ($request->input()){
            $employee = Employee::findOrFail($eid);
            $documents = $request->input('documents');
            $employee->documents()->syncWithoutDetaching($documents);



                return redirect()->route('employees.documents_services', $employee->id)->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));


        }else{
            if(Auth::User()->can('master')){
                //$client = $cid;
                $employee = Employee::findOrFail($eid);
                $documents = Document::whereNotIn('id', $employee->documents->pluck('id')->toArray())->get();
                return view('app.g3.employees.documents_attach', compact('documents', 'employee'));
            }else{
                $employee = Employee::findOrFail($eid);
                $documents = Document::whereNotIn('id', $employee->documents->pluck('id')->toArray())->whereIn('company_id', [1,Auth::User()->company_id])->get();
                return view('app.g3.employees.documents_attach', compact('documents', 'employee'));

            }
        }

    }

    public function documentsDeliveredsEdit(Request $request, $cid, $eid, $did, $brc=null)
    {

        $validation = [
            'description'=>'required|max:120',
            'expiration'=>'required|min:9',


        ];

        if ($request->input()) {

            //dd($request->file);

            $delivered = Delivered::findOrFail($did);
            $delivered->description = $request->input('description');
            $expiration = $request->input('expiration');
            $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
            $delivered->expiration = $expiration;
            //$delivered->document_id = $did;
            $delivered->company_id = $request->input('company');
            $delivered->status = $request->input('status');
            $delivered->save();


            if ($request->is('g3/branches*')) {
                return redirect()->route('branches.outsourceds.documents', [$eid, $brc])->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
            }elseif ($request->is('g3/clients*')) {
                return redirect()->route('clients.employees.documents_services', [$cid, $eid])->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
            }else{
                return redirect()->route('employees.documents_services', $eid)->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
            }
        }
        $branch = company::find($brc);
        $data['employee'] = employee::find($eid);
        $data['delivered'] = Delivered::find($did);
        $validator = JsValidator::make($validation);

        if(Auth::User()->can('master')){
            $clients = Company::where('fl_client', 1)->get();
            return view('app.g3.employees.document_delivereds_edit', compact('data', 'validator', 'branch', 'clients'));
        }else{
            return view('app.g3.employees.document_delivereds_edit', compact('data', 'validator', 'branch'));
        }
    }

    public function docDeliveredsEdit(Request $request, $eid, $did)
    {

        $validation = [
            'description'=>'required|max:120',
            'expiration'=>'required|min:9',


        ];

        if ($request->input()) {

            //dd($request->input());

            $delivered = Delivered::findOrFail($did);
            $delivered->description = $request->input('description');
            $expiration = $request->input('expiration');
            $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
            $delivered->expiration = $expiration;
            //$delivered->document_id = $did;
            $delivered->company_id = $request->input('company');
            $delivered->observation = $request->input('observation');
            if(Auth::User()->can('fornecedor')){
                $delivered->status = 2;
            }else{
                $delivered->status = $request->input('status');
            }
            $delivered->save();

            return redirect()->route('employees.documents_services', $eid)->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
        }
        $data['employee'] = employee::find($eid);
        $data['delivered'] = Delivered::find($did);
        $validator = JsValidator::make($validation);

        if(Auth::User()->can('master')){
            $clients = Company::where('fl_client', 1)->get();
            return view('app.g3.employees.document_delivereds_edit', compact('data', 'validator', 'clients'));
        }else{
            return view('app.g3.employees.document_delivereds_edit', compact('data', 'validator'));
        }
    }

    public function documentsDeliveredsAdd(Request $request,$cid, $eid, $did, $brc = null)
    {

        $validation = [
            'description'=>'required|max:120',
            'expiration'=>'required|min:9',
            'company'=>'required',
            'file'=>'mimes:jpg,jpeg,png,pdf',


        ];

        /* echo'<br>'. $cid;
        echo'<br>'. $eid;
        echo'<br>'. $did;
        die(); */


        if ($request->input()) {

            //dd($request->file);

            $delivered = new Delivered;
            //$delivered->description = $request->input('description');
            $delivered->description = date("d/m/Y");
            $expiration = $request->input('expiration');
            $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
            $delivered->expiration = $expiration;
            $delivered->document_id = $did;
            $delivered->employee_id = $eid;
            $delivered->company_id = $request->input('company');
            $delivered->status = $request->input('status');
            $delivered->save();


            if ($request->hasFile('file')) {
                $name=$request->file->getClientOriginalName();
                $fileUpload = $request->file;
                $fileName=$request->file->getClientOriginalName();
                $upload = $fileUpload->store('public/uploads');


                $file = new File;
                $file->name = $name;
                $file->file = str_replace('public/uploads/', "", $upload);
                $file->save();

                $delivered->files()->attach($file);
            }


                if ($request->is('g3/branches*')) {
                    # code...branches.outsourceds.documentsbranches.outsourceds.documents
                    return redirect()->route('branches.outsourceds.documents', [null, $eid, $brc])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
                }elseif ($request->is('g3/clients*')) {
                    return redirect()->route('clients.employees.documents_services', [$cid, $eid, $brc])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
                }else{
                    return redirect()->route('employees.documents_services', $eid)->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
                }
        }
        $branch=Company::find($brc);
        $data['employee'] = Employee::find($eid);
        $data['document'] = Document::find($did);
        $validator = JsValidator::make($validation);

        if (Auth::User()->can('master')) {
            $clients = Company::where('fl_client', 1)->get();
            //dd($clients);
            return view('app.g3.employees.documents_delivereds_add', compact('data', 'validator', 'branch', 'clients'));
        }else{
            return view('app.g3.employees.documents_delivereds_add', compact('data', 'validator', 'branch'));
        }

    }

    public function docDeliveredsAdd(Request $request, $eid, $did)
    {

        $validation = [
            'description'=>'required|max:120',
            'expiration'=>'required|min:9',
            'company'=>'required',


        ];




        if ($request->input()) {

            //dd($request->file);

            $delivered = new Delivered;
            //$delivered->description = $request->input('description');
            $delivered->description = date("d/m/Y");
            $expiration = $request->input('expiration');
            $expiration = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $expiration);
            $delivered->expiration = $expiration;
            $delivered->document_id = $did;
            $delivered->employee_id = $eid;
            $delivered->company_id = $request->input('company');
            if(Auth::User()->can('fornecedor')){
                $delivered->status = 2;
            }else{
                $delivered->status = $request->input('status');
            }
            $delivered->save();


            if ($request->hasFile('file')) {
                $name=$request->file->getClientOriginalName();
                $fileUpload = $request->file;
                $fileName=$request->file->getClientOriginalName();
                $upload = $fileUpload->store('public/uploads');


                $file = new File;
                $file->name = $name;
                $file->file = str_replace('public/uploads/', "", $upload);
                $file->save();

                $delivered->files()->attach($file);
            }

            return redirect()->route('employees.documents_services', $eid)->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
        }
        $data['employee'] = employee::find($eid);
        $data['document'] = Document::find($did);
        $validator = JsValidator::make($validation);

        if (Auth::User()->can('master')) {
            $clients = Company::where('fl_client', 1)->get();
            //dd($clients);
            return view('app.g3.employees.documents_delivereds_add', compact('data', 'validator', 'clients'));
        }else{
            return view('app.g3.employees.documents_delivereds_add', compact('data', 'validator'));
        }

    }

    public function documentsDetach(Request $request)
    {
        if ($request->input()) {
            $id = $request->input('id');
            $eid = $request->input('eid');
            $employee = Employee::findOrFail($eid);
            $employee->documents()->detach($id);
            return 1;
        }else{
            return 0;
        }


    }

    public function FileUpload(Request $request, $eid, $did, $brc = null)
{

        $validations = [
            'file'=>'required|mimes:jpg,jpeg,png,pdf',
         ];

    if ($request->input()) {

         //dd($request->file->getClientOriginalName());
        $name = $request->name;
    if ($name == "") {
            $name=$request->file->getClientOriginalName();
        }


        $delivered = Delivered::findOrFail($request->delivered);

        $fileUpload = $request->file;
        $upload = $fileUpload->store('public/uploads');
        //dd($upload);

        $file = new File;
        $file->name = $name;
        $file->file = str_replace('public/uploads/', "", $upload);
        $file->save();

        $delivered->files()->attach($file);

        if ($request->is('g3/branches*')) {
            return redirect()->route('branches.outsourceds.documents', [$eid, $brc])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
        }elseif ($request->is('g3/clients*')){
            return redirect()->route('clients.employees.documents_services', [$cid, $eid])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
        }else{
            return redirect()->route('employees.documents_services', $eid)->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
        }
    }
   // $data['employee'] =  Employee::find($eid);
    $employee = Employee::findOrFail($eid);
    $branch=company::find($brc);
    $data['delivered'] =  Delivered::find($did);
    $data['employee'] = Employee::find($eid);
    $validator = JsValidator::make($validations);
    return view('app.g3.employees.documents_fileupload', compact('data', 'validator', 'branch', 'employee'));

}
    public function FileUploademp(Request $request, $eid, $did, $brc = null)
{

        $validations = [
            'file'=>'required|mimes:jpg,jpeg,png,pdf',
         ];

    if ($request->input()) {

         //dd($request->file->getClientOriginalName());
        $name = $request->name;
    if ($name == "") {
            $name=$request->file->getClientOriginalName();
        }


        $delivered = Delivered::findOrFail($request->delivered);

        $fileUpload = $request->file;
        $upload = $fileUpload->store('public/uploads');
        //dd($upload);

        $file = new File;
        $file->name = $name;
        $file->file = str_replace('public/uploads/', "", $upload);
        $file->save();

        $delivered->files()->attach($file);

        if ($request->is('g3/branches*')) {
            return redirect()->route('branches.outsourceds.documents', [$eid, $brc])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
        }elseif ($request->is('g3/clients*')){
            //return redirect()->route('clients.employees.documents', [$cid, $eid])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
        }else{
            return redirect()->route('employees.documents_services', $eid)->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
        }
    }
   // $data['employee'] =  Employee::find($eid);
    $employee = Employee::findOrFail($eid);
    $branch=company::find($brc);
    $data['delivered'] =  Delivered::find($did);
    $data['employee'] = Employee::find($eid);
    $validator = JsValidator::make($validations);
    return view('app.g3.employees.documents_fileupload', compact('data', 'validator', 'branch', 'employee'));

}

    public function documentsFileDelete(Request $request)
    {
        $id = $request->id;
        $cid = $request->cid;
        //dd($id);
        if ($request->input()) {
            //    echo ("O id é ". $id . 'e o' . $eid);


            if (Auth::User()->can('master')) {
                if($file = File::find($id)){
                    $file->fl_deleted = 1;
                    $file->save();
                    return 1;
                }else{
                    return 0;
                }

            }else{

                //if (Auth::User()->company->clients->find($cid)) {
                if($file = File::find($id)){
                        $file->fl_deleted = 1;
                        $file->save();
                        return 1;
                  //  }else{
                    //    return 0;
                    //}
                } else {
                    return 0;
                }
            }


        }
    }



    public function checkActive(){
        //$branch = Company::find($brc);
        $employee_ok = 1;
        $service_ok = 1;
        $company_ok = 1;

        $employees = Employee::All()->where('fl_deleted', 0);
        //$employees = Employee::All()->where('id', 1861);
            foreach ($employees as $employee) {
                $employee_ok = 1;
                $service_ok = 1;
                $company_ok = 1;
                $valido = 1;
                echo($employee->name."<br/>");
                if(count($employee->documents) > 0){
                    foreach ($employee->documents as $document) {
                        //$delivered= 1;
                        $delivered = $document->Delivereds()->where('fl_deleted', 0)->where('employee_id',$employee->id )->orderBy('expiration', 'desc')->first();
                        if(isset($delivered->expiration)){
                            $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);

                            if($expires>date('Ymd') && $delivered->status == 0){
                                echo $document->name . '-- <span style="color:green;">Válido</span><br>';
                            }else{
                                if($delivered->status == 0){
                                    echo '-'.$document->name . '-- <span style="color:red;">Vencido</span><br>';
                                }else{
                                    echo '-'.$document->name . '-- <span style="color:red;">Aguardando aprovação ou correção</span><br>';

                                }
                                $employee_ok = 0;
                            }
                           // echo $document->name.' -- '. $expires.'--'.date('Ymd').'--'. ($expires>date('Ymd')?1:0). '<br/>';
                        }else{
                            $employee_ok = 0;
                            echo( $document->name.' -- '.' <span style="color:red;">Não entregue</span><br>');
                        }
                    }
                }else{
                    $employee_ok = 2;

                    echo( ' <span style="color:red;">Sem documentos</span><br>');
                }
                echo('<br/>');
                if(count($employee->services) > 0){
                    foreach($employee->services as $service){
                        echo (' ---'.$service->name.'<br/>');
                        foreach ($service->documents as $document) {
                            //$delivered= 1;
                            $delivered = $document->Delivereds()->where('fl_deleted', 0)->where('employee_id',$employee->id )->orderBy('id', 'desc')->first();
                            if(isset($delivered->expiration)){
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                if($expires>date('Ymd') && $delivered->status == 0){
                                    echo $document->name . '-- <span style="color:green;">Válido</span><br>';
                                }else{
                                    $service_ok = 0;
                                    if($delivered->status == 0){
                                        echo '-'.$document->name . '-- <span style="color:red;">Vencido</span><br>';
                                    }else{
                                        echo '-'.$document->name . '-- <span style="color:red;">Aguardando aprovação ou correção</span><br>';

                                    }
                                }
                                // echo $document->name.' -- '. $expires.'--'.date('Ymd').'--'. ($expires>date('Ymd')?1:0). '<br/>';
                            }else{
                                $service_ok = 0;
                                echo( $document->name.' -- '.' <span style="color:red;">Não entregue</span><br>');
                            }
                        }

                    }
                }else{
                    $service_ok = 2;
                    echo( ' --- '.' <span style="color:red;">Sem Empresas</span><br>');
                }
                echo('<br/>');
                if(count($employee->companies) > 0){
                    foreach($employee->companies as $company){
                        echo (' -----'.$company->name.'<br/>');
                        foreach ($company->documents as $document) {
                            //$delivered= 1;
                            $delivered = $document->Delivereds()->where('fl_deleted', 0)->where('employee_id',null )->orderBy('expiration', 'desc')->first();
                            if(isset($delivered->expiration)){
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                if($expires>date('Ymd')){
                                    echo $document->name . '-- <span style="color:green;">Válido</span><br>';
                                }else{
                                    $company_ok = 0;
                                    echo $document->name . '-- <span style="color:red;">Vencido</span><br>';
                                }
                                // echo $document->name.' -- '. $expires.'--'.date('Ymd').'--'. ($expires>date('Ymd')?1:0). '<br/>';
                            }else{
                                $company_ok = 0;
                                echo( $document->name.' -- '.' <span style="color:red;">Não entregue</span><br>');
                            }
                        }

                    }
                }else{
                    $company_ok = 2;
                    echo( ' --- '.' <span style="color:red;">Sem Empresas</span><br>');
                }

                if($employee_ok == 0 || $service_ok == 0 || $company_ok == 0 ){
                    $valido = 0;
                }

                if($employee_ok == 2 && $service_ok == 2  ){
                    $valido = 0;
                }
                if($employee->allowed != $valido){
                    $employee->allowed=$valido;
                    $employee->save();

                }
                echo('<br/>');echo('<br/>');

            }

        //return view('app.g3.employees.employee', compact('employee', 'branch'));
    }



}
