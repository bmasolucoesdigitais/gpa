<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Support\Facades\Validator;
use JsValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Employee;
use App\Document;
use App\Trainingschedules;
use App\Delivered;
use App\File;
use App\Serviceschedule;
Use Auth;


class CompanyController extends Controller
{

	protected $validationRules = [
		'name'=>'required|max:120',
		'cep'=>'required|min:9',
		'address'=>'required',
		'number'=>'required',
		'complement'=>'nullable',
		'neighborhood'=>'required',
		'citie'=>'required',
		'state'=>'required',
		'country'=>'required',
		'cnpj'=>'required|min:18',

	];

	public function index (Company $company){

        
        //$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['co'])->pluck('id')->toArray();

		$companies = $this->userCompanies()->get();
		//dd($companies);
		return view('app.g3.companies.index', compact('companies'));

		/* if (Auth::user()->can('G3 Admin')) {

			

			$companies = $company->all()->where('fl_deleted', 0)->where('fl_client', 0);

		}else{
			$companies = $company->find(Auth::user()->company_id)->clients;

		}

		return view('app.g3.companies.index', compact('companies')); */

	}

	public function clients (Company $company){
		
		
		$companies=$this->userClients()->get(); 
		return view('app.g3.companies.clients', compact('companies'));
		
		

    }

	public function userClients(){
		

		if(Auth::user()->can('master') || Auth::user()->can('geral')){
			$companies = Company::where('fl_client', 1)->where('fl_deleted', 0);
			return $companies;
		}


		if (Auth::user()->can('G3 Admin') || Auth::user()->can('cd') || Auth::user()->can('tecnico'))  {

			
			$arr_ruf = array(
				'n'=>array('AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'),
				'ne'=>array('AL', 'BA', 'CE', 'MA', 'PI', 'PE', 'PB', 'RN'),
				'co'=>array('GO', 'MT', 'MS', 'DF'),
				's'=>array('PR', 'SC', 'RS'),
				'se'=>array('MG', 'SP', 'ES', 'RJ'),
			);

			$filter_uf= '';
			if(Auth::user()->can('r-centro-oeste')){
				$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['co'])->pluck('id')->toArray();
				$filter_uf .= implode(',', $cpr);
			}
			if(Auth::user()->can('r-nordeste')){
				$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['ne'])->pluck('id')->toArray();
				$filter_uf .= implode(',', $cpr);
				
			}
			if(Auth::user()->can('r-norte')){
				$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['n'])->pluck('id')->toArray();
				$filter_uf .= implode(',', $cpr);
				
			}
			if(Auth::user()->can('r-sudeste')){
				$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['se'])->pluck('id')->toArray();
				$filter_uf .= implode(',', $cpr);
				
			}
			if(Auth::user()->can('r-sul')){
				$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['s'])->pluck('id')->toArray();
				$filter_uf .= implode(',', $cpr);
				
			}
			//dd($filter_uf);
			//$scheduledservices = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->get();


			$companies = Company::where('fl_client', 1)->where('fl_deleted', 0)->whereIn('id', explode(',', $filter_uf));

			return $companies;
			
		}
		
		

		if(Auth::user()->can('gerente')){
			$companies = Company::where('fl_client', 1)->where('fl_deleted', 0)->where('id', Auth::User()->company_id);
			return $companies;
		}

		return false;

    }
	public function userCompanies(){
		

		if(Auth::user()->can('master') || Auth::user()->can('geral')){
			$companies = Company::where('fl_client', 0)->where('fl_deleted', 0);
			return $companies;
		}


		if (Auth::user()->can('G3 Admin') || Auth::user()->can('cd') || Auth::user()->can('tecnico'))  {

			
			//dd($filter_uf);
			//$scheduledservices = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->get();

			$companiesIds = app('App\Http\Controllers\ScheduleserviceController')->userServices()->pluck('company_id')->toArray();
			//dd($companiesIds);
			//$companies = Company::where('fl_client', 0)->where('fl_deleted', 0)->whereIn('id', $companiesIds);
			$companies = Company::where('fl_client', 0)->where('fl_deleted', 0);
			
			return $companies;
			
		}
		
		

		if(Auth::user()->can('gerente')){
			$companiesIds = app('App\Http\Controllers\ScheduleserviceController')->userServices()->pluck('company_id')->toArray();
			//dd($companiesIds);
			$companies = Company::where('fl_client', 0)->where('fl_deleted', 0)->whereIn('id', $companiesIds);

			return $companies;
		}

		if(Auth::user()->can('fornecedor')){
			//dd($companiesIds);
			$companies = Company::where('fl_client', 0)->where('fl_deleted', 0)->where('id', Auth::User()->company_id);

			return $companies;
		}

		return false;

    }

    public function savemail (Request $request, $id ){
        if($request->input('cp')){
            $cp = $request->input('cp');
            $company = Company::find($id);
            $client = $company->clients->find($cp);
            $company->clients()->updateExistingPivot($client, ['mail_company' => $request->input('company'), 'mail_client' => $request->input('provider')]);
            //dd($company);
            //$company->pivot->mail_company = $request->input('company');
            //$company->pivot->mail_client = $request->input('provider');
            //$company->save();
            return 'true';
        }else{
            abort(400, 'Inavlid post content or company not found');
        }
    }

	public function branches ($company = null){

		if (Auth::user()->can('master')) {
			$owner = Company::findOrFail($company);
			$companies = Company::find($company)->branches->where('fl_deleted', 0);

		}else{
			$owner = Auth::User()->company_id;
			$companies = Company::find(Auth::user()->company_id)->branches->where('fl_deleted', 0);
			//dd($companies);
		}

		return view('app.g3.companies.branches', compact('companies', 'owner'));

	}

	public function insert(Request $request, $cnpj = null, $brc=null ){
       //$cnpj ='';
        if ($request->input()) {
            $company = new Company;
            $company->name = $request ->input('name');
            $company->cep = $request ->input('cep');
            $company->address  = $request ->input('address');
            $company->number   = $request ->input('number');
            $company->complement   = $request ->input('complement');
            $company->neighborhood = $request ->input('neighborhood');
            $company->citie    = $request ->input('citie');
            $company->state    = $request ->input('state');
            $company->country  = $request ->input('country');
            $company->cnpj = $request ->input('cnpj');
            $company->filial = $request ->input('filial');
            $company->flag = $request ->input('flag');
            $company->manager_email = $request ->input('manager_email');
            $company->company_email = $request ->input('company_email');
            $company->abaco_email = $request ->input('abaco_email');
            $company->fl_aprove    = ($request ->input('fl_aprove'))? 1 : 0 ;
            $company->fl_active    = ($request ->input('fl_active'))? 1 : 0;
            $company->fl_billing   = ($request ->input('fl_billing'))? 1 : 0;
            $company->fl_client   =  ($request ->input('fl_client'))? 1 : 0;
            $company->company_id   = $request ->input('company_id');


        //dd($company);
            if(!Auth::user()->can('master')){
                if($request->is('g3/branches/add*')){
                    //$branch = Company::find($brc);
                    $company->headquarter = Auth::user()->company_id;
                    $company->company_id   = Auth::user()->company_id;
                    $company->save();
                    return redirect()->route('branches')->with('alert-success',__('general.Branches').' '. $company->name.' '. __('general.has added successfully!'));
                }elseif($request->is('g3/branches/client/add*')){
                    $company->company_id = $brc;
                    $company->save();
                    $owner = Company::find($brc);
                    $client = $company->id;
                    $owner->clients()->attach($client);
                    return redirect()->route('branches.clients', $brc)->with('alert-success',__('general.Company'). ' '. $company->name.' '. __('general.has added successfully!'));
                }else{
                    $company->company_id = Auth::user()->company_id;
                    $company->save();
                    $owner = Company::find(Auth::user()->company_id);
                    $client = $company->id;
                    $owner->clients()->attach($client);


                    if(!$request ->input('fl_client')){

                        $replace = array('.', '/', '-');
                        $user = new User;
                        $this->validationRules['password'] = 'required|min:10';
                        $user->name = $request->input('name');
                        $user->company_id =  $company->id;
                        $user->email =  str_replace($replace, '',Company::where('cnpj', $request->input('cnpj'))->get()[0]->cnpj).'@abaco.com.br';
                        $user->password = $request->input('password');
                        $user->save();
                        $user->assignRole('G3 Fornec');
                    }


                    return redirect()->route('companies.index')->with('alert-success',__('general.Company'). ' '. $company->name.' '. __('general.has added successfully!'));
                }
            }else{
                $company->save();
                if(!$request ->input('fl_client')){

                    $replace = array('.', '/', '-');
                    $this->validationRules['password'] = 'required|min:10';
                    $user = new User;
                    $user->name = $request->input('name');
                    $user->company_id =  $company->id;
                    $user->email = str_replace($replace, '',Company::where('cnpj', $request->input('cnpj'))->get()[0]->cnpj).'@abaco.com.br';
                    $user->password = $request->input('password');
                    $user->save();
                    $user->assignRole('G3 Fornec');
                }
                return redirect()->route('companies.index')->with('alert-success',__('general.Company').' '. $company->name.' '. __('general.has added successfully!'));
            }


        }
        $users = User::all();
        $companies = Company::all();
        $validator = JsValidator::make($this->validationRules);
		return view('app.g3.companies.insert', compact('users', 'companies', 'cnpj', 'validator'));
    }

	public function create(Request $request, $brc=null)
	{
		$company = new Company;
		$company->name = $request ->input('name');
		$company->cep = $request ->input('cep');
		$company->address  = $request ->input('address');
		$company->number   = $request ->input('number');
		$company->complement   = $request ->input('complement');
		$company->neighborhood = $request ->input('neighborhood');
		$company->citie    = $request ->input('citie');
		$company->state    = $request ->input('state');
		$company->country  = $request ->input('country');
		$company->cnpj = $request ->input('cnpj');
		$company->manager_email = $request ->input('manager_email');
		$company->manager_email = $request ->input('manager_email');
		$company->manager_email = $request ->input('manager_email');
		$company->fl_aprove    = ($request ->input('fl_aprove'))? 1 : 0 ;
		$company->fl_active    = ($request ->input('fl_active'))? 1 : 0;
		$company->fl_billing   = ($request ->input('fl_billing'))? 1 : 0;
		$company->fl_client   =  ($request ->input('fl_client'))? 1 : 0;



       //dd($company);
		if(!Auth::user()->can('master')){
			if($request->is('g3/branches/add*')){
				//$branch = Company::find($brc);
				$company->headquarter = $brc;
				$company->save();
				return redirect()->route('branches')->with('alert-success',__('general.Branches').' '. $company->name.' '. __('general.has added successfully!'));
			}elseif($request->is('g3/branches/client/add')){
				$company->save();
				$owner = Company::find($brc);
				$client = $company->id;
				$owner->clients()->attach($client);
				return redirect()->route('branches.clients', $brc)->with('alert-success',__('general.Company'). ' '. $company->name.' '. __('general.has added successfully!'));
			}else{
				$company->save();
				$owner = Company::find(Auth::user()->company_id);
				$client = $company->id;
				$owner->clients()->attach($client);
			}
		}else{
			$company->company_id   = $request ->input('company_id');
			$company->save();
		return redirect()->route('companies.index')->with('alert-success',__('general.Company').' '. $company->name.' '. __('general.has added successfully!'));
		}

	}

	public function show($id)
	{
	//
	}

	public function branchDetach($id)
	{
		$branch = Company::find($id);
		$branch->headquarter = null;
		$branch->save();

		return redirect()->route('branches')->with('alert-success', __('general.Company').' ' . $branch->name .' '. __('general.has removed successfully!'));
	}

	public function edit(Request $request, $id)
	{

		if ($request->input()) {
			$company = Company::findOrFail($id);

            if($request ->input('password') != '' && $company->fl_client == 0 && !$request->input('fl_client')){

                $replace = array('.', '/', '-');
                $this->validationRules['password'] = 'required|min:10';
                $user = User::where('email', str_replace($replace, '',Company::where('cnpj', $request->input('cnpj'))->get()[0]->cnpj).'@abaco.com.br')->get()[0];
                /* dd($user); */
                if(isset($user->name)){

                    $user->name = $request->input('name');
                    $user->email = str_replace($replace, '',Company::where('cnpj', $request->input('cnpj'))->get()[0]->cnpj).'@abaco.com.br';
                    $user->password = $request->input('password');
                    $user->save();
                }
                //$user->assignRole('G3 Fornec');
            }




			$company->name          = $request ->input('name');
			$company->filial		= $request ->input('filial');
            $company->flag 			= $request ->input('flag');
			$company->manager_email = $request ->input('manager_email');
			$company->abaco_email = $request ->input('abaco_email');
			$company->cep           = $request ->input('cep');
			$company->address       = $request ->input('address');
			$company->number        = $request ->input('number');
			$company->complement        = $request ->input('complement');
			$company->neighborhood  = $request ->input('neighborhood');
			$company->citie         = $request ->input('citie');
			$company->state         = $request ->input('state');
			$company->country       = $request ->input('country');
			$company->cnpj          = $request ->input('cnpj');
			$company->headquarter 	= $request->input('headquarter');
			if (Auth::user()->can('master')) {
				$company->fl_aprove     = ($request ->input('fl_aprove'))? '1' : '0' ;
				$company->fl_active     = ($request ->input('fl_active'))? '1' : '0';
				$company->fl_billing    = ($request ->input('fl_billing'))? '1' : '0';
				$company->fl_client     = ($request ->input('fl_client'))? '1' : '0';
				$company->company_id    = $request ->input('company_id');
			}
			$company->save();



			if($request->is('g3/branches*')){
				return redirect()->route('branches')->with('message', 'Company updated successfully!');
			}else{
				return redirect()->route('companies.index')->with('message', 'Company updated successfully!');
			}
		}




		$users = User::all();
		//if(Auth::User()->company->clients()->find($id) || Auth::user()->can('master')){
			$headquarters = Company::where('fl_deleted', 0)->whereNotIn('id', [1,$id])->orderBy('name')->get();
			$company = Company::findOrFail($id);
			$validator = JsValidator::make($this->validationRules);

			return view('app.g3.companies.edit',compact('company', 'users','validator', 'headquarters'));
		//}else{
		//	return redirect()->route('companies.index')->withErrors(__('general.Permission denied'));;
		//}

	}

	public function update(Request $request, $id)
	{


		$company = Company::findOrFail($id);
		$company->name          = $request ->input('name');
		$company->manager_email = $request ->input('manager_email');
		$company->cep           = $request ->input('cep');
		$company->address       = $request ->input('address');
		$company->number        = $request ->input('number');
		$company->complement        = $request ->input('complement');
		$company->neighborhood  = $request ->input('neighborhood');
		$company->citie         = $request ->input('citie');
		$company->state         = $request ->input('state');
		$company->country       = $request ->input('country');
		$company->cnpj          = $request ->input('cnpj');
		if (Auth::user()->can('master')) {
			$company->fl_aprove     = ($request ->input('fl_aprove'))? '1' : '0' ;
			$company->fl_active     = ($request ->input('fl_active'))? '1' : '0';
			$company->fl_billing    = ($request ->input('fl_billing'))? '1' : '0';
			$company->fl_client     = ($request ->input('fl_client'))? '1' : '0';
			$company->company_id    = $request ->input('company_id');
		}
		$company->save();

		if($request->is('g3/branches*')){
			return redirect()->route('branches')->with('message', 'Company updated successfully!');
		}else{
			return redirect()->route('companies.index')->with('message', 'Company updated successfully!');
		}
	}

	public function delete($id)
	{

		$company = Company::findOrFail($id);

		return view('app.g3.companies.delete',compact('company'));
	}

	public function destroy(Request $request)
	{
		$company = Company::findOrFail($request->input('id'));
	 //$company = Company::findOrFail($id);
		$company->fl_deleted   = 1;
		$company->save();
		return redirect()->route('companies.index')->with('alert-success','Company has been deleted!');
	}

	public function employees(Request $request, $id, $brc = null){


			// $outsurceds = Company::findOrFail(Auth::User()->company_id)->outsourceds->pluck('id')->toArray();
			$employees = Company::findOrFail($id)->employees()->where('fl_deleted', 0)->get();
			$company = Company::findOrFail($id);
		
            return view('app.g3.employees.index', compact('employees', 'company'));
	}

	public function employeesAdd(Request $request, $id, $brc = null)
	{
		if ($request->input()) {

			$employee = $request->input('employee');
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->employees()->attach($employee);

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.employees', ['id'=>$id, $brc])->with('alert-success','Employee has added successfully!');
			}else{
				return redirect()->route('companies.employees', ['id'=>$id])->with('alert-success','Employee has added successfully!');
			}
	    //dd($request->input('services'));
		}

		$company = Company::find($id);
		if($request->is('g3/branches*')){
			$branch = Company::find($brc);
			$employees = $branch->outsourceds->where('fl_deleted', 0)->whereNotIn('id', $company->employees->pluck('id')->toArray());

			//dd($branch->outsourceds->where('fl_deleted', 0));
		}else{
			//$employees = $company->outsourceds;
			$employees = Employee::whereDoesntHave("companies", function($query) use ($id){
				$query->where('id', '=', $id);
			})->where('fl_deleted', 0)->get();
		}
	//dd($employees);
		return view('app.g3.companies.employees_add', compact('company', 'employees'));

	}


	public function employeesRemove(Request $request, $id, $employee_id, $brc=null)
	{
		if ($employee_id) {

			$employee = $employee_id;
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->employees()->detach($employee);
			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.employees', ['id'=>$id, $brc])->with('alert-success','Employee has removed successfully!');
			}else{
				return redirect()->route('companies.employees', ['id'=>$id])->with('alert-success','Employee has removed successfully!');
			}
	    //dd($request->input('services'));
		}
		$company = Company::find($id);
		$employees = Employee::whereDoesntHave("companies", function($query) use ($id){
			$query->where('id', '=', $id);
		})->get();
	//dd($employees);
		return view('app.g3.companies.employees_add', compact('company', 'employees'));

	}

	public function employeesCreate(Request $request, $id, $brc=null)
	{
		$validation = [
			'name'=>'required|max:120',
			'cpf'=>'required|min:14',
			'rg'=>'required',
			'borndate'=>'required',
		];
		if ($request->input()) {

			if ($request->input('allowed')) {
				$allowed = 1;
			}else{
				$allowed = 0;
			}
			$employee = new Employee;
			$employee->name = $request->input('name');
			$employee->cpf = $request->input('cpf');
			$employee->rg = $request->input('rg');
			$borndate = $request->input('borndate');
			$borndate = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $borndate);
			$employee->borndate = $borndate;
			$employee->allowed = 0;
			$employee->save();

			$company = Company::findOrFail($id);
			$company->employees()->attach($employee->id);
			if($request->is('g3/branches*')){
				$branch = Company::find($brc);
				$branch->outsourceds()->attach($employee->id);
			return redirect()->route('branches.clients.employees', [$id, $brc])->with('message', 'Employee created successfully!');
			}


			return redirect()->route('companies.employees', $id)->with('message', 'Employee created successfully!');
		} else {
			$validator = JsValidator::make($validation);
			return view('app.g3.companies.employeeInsert', compact('id','validator'));
		}
	}



	public function outsourceds($id){


		if (Auth::user()->hasAnyPermission('master', 'G3 Admin', 'cd', 'tecnico', 'geral', 'gerente')) {
   // $outsurceds = Company::findOrFail(Auth::User()->company_id)->outsourceds->pluck('id')->toArray();

			$services = Serviceschedule::where('fl_deleted', 0)->where('store_id', $id)->get();
			$employeeIds = '';
			foreach($services as $service){
				
				if($employeeIds == ''){
					$employeeIds .= implode(',', $service->employees()->pluck('id')->toArray());
				}else{
					$employeeIds .= ','.implode(',', $service->employees()->pluck('id')->toArray());

				}
			}
			//die($employeeIds);
			
			$employees = Employee::whereIn('id', explode(',', $employeeIds))->get();
			$company = Company::findOrFail($id);
    		//dd($employees);
			return view('app.g3.companies.outsourceds', compact('employees', 'company'));
		}else{
			return view('home');
		}
    //dd($company);
	}


	public function outsourcedsAdd(Request $request, $id)
	{
		if ($request->input()) {

			$employee = $request->input('employee');
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->outsourceds()->attach($employee);

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has added successfully!');
			}elseif($request->is('g3/clients*')){
				return redirect()->route('clients.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has added successfully!');
			}else{
				return redirect()->route('companies.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has added successfully!');
			}
	    //dd($request->input('services'));
		}

		$company = Company::find($id);
		$employees = Employee::whereNotIn('id', $company->outsourceds->pluck('id'))->where('fl_deleted', 0)->get();
	//dd($employees);
		return view('app.g3.companies.outsourceds_add', compact('company', 'employees'));

	}
	public function outsourcedsRemove(Request $request, $id, $employee_id)
	{
		if ($employee_id) {

			$employee = $employee_id;
			$cp = Company::find($id);
	    //$se = Service::find($services);
	    //dd($se);
			$cp->outsourceds()->detach($employee);
			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has removed successfully!');
			}else{
				return redirect()->route('companies.outsourceds', ['id'=>$id])->with('alert-success','Outsourced has removed successfully!');
			}
	    //dd($request->input('services'));
		}


	}

	public function client($id)
	{

	   //$company = Company::find(2);
	   //$client = Company::find(3);
	    //$se = Service::find($services);
	    //dd($se);
      //$company->clients()->attach($client);
      //dd($company->clients);
			
			$services = Serviceschedule::where('fl_deleted', 0)->where('store_id', $id)->/* where('date_end', '>=', date('Y-m-d'))-> */pluck('company_id')->toArray();
			$companies = Company::whereIn('id', $services)->get();
			// dd($companies);
			$owner = Company::find($id);
			return view('app.g3.companies.providers', compact('companies', 'owner'));
	

	}
    public function trainingReserve(){
        $companies = Company::find(Auth::User()->company_id)->providers->pluck('id')->toArray();
        $company = Company::find(Auth::User()->company_id);
        //dd($companies);
        $trainings = Trainingschedules::all()->where('fl_deleted', 0)->where('fl_accomplished', 0)->whereIn('company_id', $companies);

        //$trtest = Trainingschedules::find(7);
        //$trtest->students()->attach(27);
        //$trtest = Trainingschedules::find(7)->students;
        //dd($trtest);
        return view('app.g3.companies.training_reserve', compact('trainings', 'company'));
    }

    public function trainingReserveAttach(Request $request, $id){

        $training = Trainingschedules::find($id);
        if($request->input()){

			$employee = Employee::find($request->input('employee_id'));
            $training->students()->attach($employee);
			$training->students()->updateExistingPivot($employee, ['email'=>$request->input('email')]);

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: Abaco - Sistema G3 <noreply@abacotecnologia.com.br>' . "\r\n";

			$subject = 'Inscrição de treinamento';
			$to = $request->input('email');
			$message = 'Olá '.$employee->name;
			$message .= '<br>Você foi inscrito no treinamento: '.$training->name.' - da empresa - '.$training->company->name;
			$message .= '<br>Data inicial: ' . preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $training->dt_ini);
			$message .= '<br>Data Final: '. preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $training->dt_end);
			$message .= '<br><br>link do treinamento: <a href="'.$training->url.'" target="_blank">'. $training->url.'</a>';
			//echo '<br>'.$to;
			//echo '<br>'.'<br>'.$message;


			mail($to, $subject, $message, $headers);
			if(Auth::User()->can('fornecedor')){
				return redirect()->route('provider.trainingreserve')->with('alert-success',$employee->name. ' '.__('general.has added successfully!'));
			}else{
				return redirect()->route('trainingschedule.index')->with('alert-success',$employee->name. ' '.__('general.has added successfully!'));

			}
        }
		if(Auth::User()->can('fornecedor')){

			$employees = Company::find(Auth::User()->company_id)
			->employees
			->whereNotIn('id', $training->students->pluck('id')->toArray());
		}else{
			$employees = Company::find($training->company_id)
			->outsourceds()->orderBy('name', 'asc')
			->whereNotIn('id', $training->students->pluck('id')->toArray())->get();
		}

        //$trtest = Trainingschedules::find(7);
        //$trtest->students()->attach(27);
        //$trtest = Trainingschedules::find(7)->students;
        //dd($trtest);
        return view('app.g3.companies.training_reserve_add', compact('training', 'employees'));
    }

    public function trainingReserveDetach(Request $request){

        $training = Trainingschedules::find($request->input('id'));
        //$employee = Employee::find(Auth::User()->company_id);
        if($request->input()){
            $employee = Employee::find($request->input('employee_id'));
			$email = $training->students->find($employee->id)->pivot->email;

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: Abaco - Sistema G3 <noreply@abacotecnologia.com.br>' . "\r\n";

			$subject = 'Cancelamento de treinamento';
			$to = $email;
			$message = 'Olá '.$employee->name;
			$message .= '<br>Você foi retirado do treinamento: '.$training->name.' - da empresa - '.$training->company->name;
			$message .= '<br>Data inicial: ' . preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $training->dt_ini);
			$message .= '<br><br>Caso tenha alguma dúvida, consulte seu gestor';

			//echo '<br>'.$to;
			//echo '<br>'.'<br>'.$message;

            $training->students()->detach($employee);

			mail($to, $subject, $message, $headers);
            //return redirect()->route('provider.trainingreserve')->with('alert-success',$employee->name. ' '.__('general.has added successfully!'));
            echo 1;
        }
        $employees = Company::find(Auth::User()->company_id)
        ->employees
        ->whereNotIn('id', $training->students->pluck('id')->toArray());

        //$trtest = Trainingschedules::find(7);
        //$trtest->students()->attach(27);
        //$trtest = Trainingschedules::find(7)->students;
        //dd($trtest);
        //return view('app.g3.companies.training_reserve_add', compact('training', 'employees'));
    }

    public function trainingReserveEmployees($id){

        $training = Trainingschedules::find($id);
        $employees = Company::find(Auth::User()->company_id)
        ->employees
        ->whereIn('id', $training->students->pluck('id')->toArray());

        //$trtest = Trainingschedules::find(7);
        //$trtest->students()->attach(27);
        //$trtest = Trainingschedules::find(7)->students;
        //dd($trtest);
        return view('app.g3.companies.training_reserve_employees', compact('training', 'employees'));
    }


	public function attach(Request $request, $id=0 ){
      //dd($id);
		if (Auth::user()->can('master')) {
			if ($request->input()) {

				$clients = $request->input('clients');
				$company = Company::find($request->input('company'));
	    //$se = Service::find($services);
	    //dd($se);
                $company->clients()->sync($clients);
                if ($request->is('g3/clients*')) {
                    return redirect()->route('clients.providers', [$company])->with('alert-success',__('general.has added successfully!'));
                }else{
                    return redirect()->route('companies.clients', [$company])->with('alert-success',__('general.has added successfully!'));
                }
	    //dd($request->input('services'));


	//$se = Service::find($services);
       //dd($client);
			}else{

				$companies = Company::find($id)->whereNotIn('id', [1, $id])->whereIn('company_id', [1,$id])->where('fl_deleted', 0)->get();
				$owner = Company::find($id);
				$data = Array();
				$data['companies'] = $companies;
				$data['owner'] = $owner;
	  //dd($data);

				return view('app.g3.companies.attach_multiples', compact('data'));
			}
		}else{

			if ($request->input('cnpj')) {

				//dd(Request::url());

				$cnpj = $request->input('cnpj');
				if (Auth::user()->can('master')) {
					$company = Company::find($request->input('id'));
				}else{

					if ($request->is('g3/branches/attach')) {
						if(count(Company::where('cnpj', $cnpj)->get())>0){
							//dd(count(Company::where('cnpj', $cnpj)->get()));
							$id = Company::where('cnpj', $cnpj)->limit(1)->get()[0]->id;
							$branch = Company::find($id);
							$branch->headquarter = Auth::User()->company_id;
							$branch->save();

							return redirect()->route('branches')->with('alert-success',__('general.Branches')." ". $branch->name." ". __('general.has added successfully!'));
						}else{

							$cnpj = str_replace(array('.','/','-'), '', $cnpj);
							return redirect()->route('branches.add', $cnpj);

						}

					}

					if ($request->is('g3/branches/client*')) {
							if(count(Company::where('cnpj', $cnpj)->get())>0){
								//dd($client);
							$client = Company::where('cnpj', $cnpj)->limit(1)->get();
							$company = Company::find($request->input('id'));

							if($company->clients->find($client[0]->id)){
								return redirect()->route('branches.clients', $id)->withErrors(__('general.Company')." ".$client[0]->name ." ". __('general.already exists!'));


							}else{

							//$cnpj = str_replace(array('.','/','-'), '', $cnpj);
							//return redirect()->route('companies.branches.add', $cnpj)


							//$branch->headquarter = Auth::User()->company_id;
							//$branch->save();
							$company->clients()->attach($client);

							return redirect()->route('branches.clients', $id)->with('alert-success',__('general.Branches')." ". $client[0]->name." ". __('general.has added successfully!'));
						}
						}else{
							$cnpj = str_replace(array('.','/','-'), '', $cnpj);
							//die('here');
							return redirect()->route('branches.clients.add', [$cnpj, $id]);
						}



					}

					if ($request->is('*companies/client*'))
					{
						$company = Company::find($request->input('id'));
					}else{
						$company = Company::find(Auth::user()->company_id);
					}

				}
				$client = Company::where('cnpj', $cnpj)->limit(1)->get();

				if(!$client->isEmpty()){

					if($company->clients->find($client[0]->id)){
						if (Auth::user()->can('master')) {
							return redirect()->route('companies.clients', $company)->withErrors(__('general.Company')." ". $client[0]->name." ". __('general.already exists!'));
						}else{

							if ($request->is('*companies/client*'))
							{
								return redirect()->route('companies.clients', $company)->withErrors(__('general.Company')." ". $client[0]->name." ". __('general.already exists!'));

							}else{

								return redirect()->route('companies', $company)->withErrors(__('general.Company')." ". $client[0]->name." ". __('general.already exists!'));

							}


						}

					}else{
						$company->clients()->attach($client);
						if (Auth::user()->can('master')) {
							return redirect()->route('companies.clients', $company)->with('alert-success',__('general.Company')." ". $client[0]->name." ". __('general.has added successfully!'));
						}else{

							if ($request->is('*companies/client*'))
							{
								return redirect()->route('companies.clients', $company)->with('alert-success',__('general.Company')." ". $client[0]->name." ". __('general.has added successfully!'));

							}else{
								return redirect()->route('companies')->with('alert-success',__('general.Company')." ". $client[0]->name." ". __('general.has added successfully!'));

							}

						}
					}



				}else{
		 //$users = User::all();
		 //$companies = Company::all();
					$cnpj = str_replace(array('.','/','-'), '', $cnpj);
					return redirect()->route('companies.add', $cnpj);
				}

	//$se = Service::find($services);
       //dd($client);
			}else{
				return view('app.g3.companies.attach', compact('id'));
			}

		}

	}

	public function detach(Request $request, $cp=0, $id, $brc=null)
	{

		if (Auth::user()->can('master')) {
			$company = Company::find($cp);
		}else{
			if($request->is('g3/branches*')){
				$company = Company::find($brc);
			}else{
				$company = Company::find(Auth::user()->company_id);
			}
		}
       //$company = Company::find(Auth::user()->company_id);
		$client = $id;
       //dd($client);
		$company->clients()->detach($client);
		if($request->is('g3/branches*')){
            return redirect()->route('branches.clients', $brc)->with('alert-success',__('general.Company').' '. __('general.has removed successfully!'));
		}else{
			return redirect()->route('companies.index')->with('alert-success',__('general.Company').' '. __('general.has removed successfully!'));

		}


    }
	public function detachModal(Request $request)
	{

		if ($request->input()) {
            $cid = $request->input('cid');
            $pid = $request->input('pid');
            $company = Company::find($cid);
            $company->clients()->detach($pid);
            return 1;
		}else{
			return 0;
		}


    }


	public function documents(Request $request, $id =null, $brc=null)
	{


		if (Auth::user()->can('master')) {
			$company = Company::findOrFail($id);
			$documents = Company::findOrFail($id)->documents;
			return view('app.g3.companies.documents', compact('documents', 'company'));
		}else if(Auth::user()->can('fornecedor')){
            $company = Company::findOrFail(Auth::User()->company_id);
			$documents = $company->documents;
			return view('app.g3.companies.documents', compact('documents', 'company'));
        }
        else{
			//if(Auth::User()->company->clients->find($id)){
				$company = Company::findOrFail($id);
				$branch = Company::Find($brc);
				if ($request->is('g3/branches*')) {
					# code...
				$documents = Company::findOrFail($id)->documents->whereIn('company_id', [1,$brc]);
				}else{

				$documents = Company::findOrFail($id)->documents->whereIn('company_id', [1,Auth::User()->company_id]);
				}
				return view('app.g3.companies.documents', compact('documents', 'company', 'branch'));
			//}else{

			//	return redirect()->route('companies.index')->withErrors(__('general.Permission denied'));
			//}
		}
	}

	public function branchesClientDocuments($id, $cid)
	{



		if(Auth::User()->company->branches->find($id)->clients->find($cid)){
			$company = Company::findOrFail($cid);
			$documents = Company::findOrFail($cid)->documents->whereIn('company_id', [1,$id]);
				//dd($documents);
			$branch = $id;
			return view('app.g3.companies.branches_clients_documents', compact('documents', 'company', 'branch'));
		}else{

			return redirect()->route('companies.branches.client', [$id, $cid])->withErrors(__('general.Permission denied'));
		}

	}

	public function documentsAttach(Request $request, $id, $brc=null)
	{

		if ($request->input()){
			$company = Company::findOrFail($id);
			$documents = $request->input('documents');
			$company->documents()->syncWithoutDetaching($documents);

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.documents', [$company->id, $brc])->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
			}elseif ($request->is('g3/clients*')){
				return redirect()->route('clients.documents', $company->id)->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
			}else{
                if(Auth::User()->can('fornecedor')){
                    return redirect()->route('provider.documents')->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
                }else{
                    return redirect()->route('companies.documents', $company->id)->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));
                }
			}
		}else{
			if(Auth::User()->can('master')){
				$company = Company::findOrFail($id);
				$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->get();
				return view('app.g3.companies.documents_attach', compact('documents', 'company'));
			}else{
				$company = $company = Company::findOrFail($id);
				$branch = Company::find($brc);
				if ($request->is('g3/branches*')) {
					$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->whereIn('company_id', [1,$brc])->get();
				}else{
					$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->whereIn('company_id', [1,Auth::User()->company_id])->get();
				}

				return view('app.g3.companies.documents_attach', compact('documents', 'company', 'branch'));

			}
		}

	}



	public function branchesClientDocumentsAttach(Request $request, $id, $cid)
	{

		if ($request->input()){
			$company = Company::findOrFail($cid);

			$documents = $request->input('documents');
			$company->documents()->syncWithoutDetaching($documents);

			return redirect()->route('companies.branches.client.documents', [$id, $cid])->with('alert-success',__('general.Documents').' '. __('general.has added successfully!'));

		}else{

			$company = Company::findOrFail($cid);
			$documents = Document::whereNotIn('id', $company->documents->pluck('id')->toArray())->whereIn('company_id', [1,$id])->get();
			return view('app.g3.companies.branches_client_documents_attach', compact('documents', 'company'));


		}

	}

	public function deliveredsEdit(Request $request, $cid, $did, $brc=null)
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
            //$delivered->company_id = $cid;
            if(Auth::User()->can('fornecedor')){
                $delivered->status = 2;
            }else{
                $delivered->status = $request->input('status');
            }
			$delivered->save();

			//dd($delivered->toArray());

			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.documents', [$cid, $brc])->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
			}else if(Auth::User()->can('fornecedor'))
                return redirect()->route('provider.documents')->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
            {
				return redirect()->route('companies.documents', $cid)->with('alert-success',__('general.Delivered').' '. __('general.has edited successfully!'));
			}
		}
		$branch=Company::find($brc);
		$data['company'] = company::find($cid);
		$data['delivered'] = Delivered::find($did);
		$validator = JsValidator::make($validation);


		return view('app.g3.companies.delivereds_edit', compact('data', 'validator', 'branch'));

	}

	public function deliveredsAdd(Request $request, $cid, $did, $brc=null)
	{

		$validation = [
			//'description'=>'required|max:120',
			'expiration'=>'required|min:9',
			'file'=>'mimes:jpg,jpeg,png,pdf',


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
			$delivered->company_id = $cid;
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


			if ($request->is('g3/branches*')) {
				return redirect()->route('branches.clients.documents', [$cid, $brc])->with('alert-success',__('general.Delivered').' '. __('general.has added successfully!'));
			}else if(Auth::User()->can('fornecedor')){
                return redirect()->route('provider.documents')->with('alert-success',__('general.Delivered').' '. __('general.has added successfully!'));
            }else{
				return redirect()->route('companies.documents', $cid)->with('alert-success',__('general.Delivered').' '. __('general.has added successfully!'));
			}
		}
		$branch = Company::find($brc);
		$data['company'] = company::find($cid);
		$data['document'] = Document::find($did);
		$validator = JsValidator::make($validation);
		return view('app.g3.companies.delivereds_add', compact('data', 'validator', 'branch'));

	}


	public function branchesClientDocumentsDeliveredsAdd(Request $request, $cid, $did, $bid)
	{

		$validation = [
			//'description'=>'required|max:120',
			'expiration'=>'required|min:9',
			'file'=>'mimes:jpg,jpeg,png,pdf',


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
			$delivered->company_id = $cid;
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



			return redirect()->route('companies.branches.client.documents', [$bid, $cid])->with('alert-success',__('general.Document').' '. __('general.has added successfully!'));
		}
		$data['company'] = company::find($cid);
		$data['document'] = Document::find($did);
		$validator = JsValidator::make($validation);
		return view('app.g3.companies.branchesClientDocumentsDelivereds_add', compact('data', 'validator'));

	}

	public function documentsDetach(Request $request)
	{
		if ($request->input()) {
			$id = $request->input('id');
			$cid = $request->input('cid');
			$company = Company::findOrFail($cid);
			$company->documents()->detach($id);
			return 1;
		}else{
			return 0;
		}


	}

	public function outourcedActivate($cp, $ep)
	{
		if(Auth::user()->can('master')){
			$company = Company::find($cp);
			$outsourced = $company->outsourceds()->find($ep);
			if($outsourced->pivot->fl_ready == 0){
				$company->outsourceds()->updateExistingPivot($outsourced, ['fl_ready' => 1]);
			}else{
				if($outsourced->pivot->dt_ready_sent == null){
					$company->outsourceds()->updateExistingPivot($outsourced, ['fl_ready' => 0]);
				}

			}
			//dd($company->id.' - ' .$outsourced->id. '-' . $outsourced->pivot->fl_ready);
			return back();
		}else{
			return back();
		}


	}

	public function fileUpload(Request $request, $cid, $did, $brc=null)
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

				return redirect()->route('branches.clients.documents', [$cid, $brc])->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
			}else{

				return redirect()->route('companies.documents', $cid)->with('alert-success',__('general.File').' '. __('general.has added successfully!'));
			}
		}
   // $data['employee'] =  Employee::find($eid);
		$branch=Company::find($brc);
		$data['delivered'] =  Delivered::find($did);
		$data['company'] = Company::find($cid);
		$validator = JsValidator::make($validations);
		return view('app.g3.companies.documents_fileupload', compact('data', 'validator', 'branch'));

	}

	public function fileDelete(Request $request)
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

				if (Auth::User()->company->clients->find($cid)) {
					if($file = File::find($id)){
						$file->fl_deleted = 1;
						$file->save();
						return 1;
					}else{
						return 0;
					}
				} else {
					return 0;
				}
			}


		}
	}

	public function emailupdate(Request $request){
		$company = Company::findOrFail($request->input('id'));
		$company->company_email = $request->input('email');
		if($company->save()){
			echo 1;
		}else{
			echo'error';
		}
	}



}


