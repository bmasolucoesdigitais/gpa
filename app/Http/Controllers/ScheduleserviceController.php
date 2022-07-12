<?php

namespace App\Http\Controllers;

use App\Apr;
use App\AprItem;
use JsValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\File;
use App\Employee;
use App\Service;
use App\Mailog;
use App\Serviceschedule;
Use Auth;

class ScheduleserviceController extends Controller
{
    protected $validationRules = [
		'store'=>'required',
		'employees'=>'required',
		'service'=>'required',
		'date'=>'required',


	];

    public function index($company_id){

        $company= Company::findOrFail($company_id);
        //$scheduledservices = Serviceschedule::get();
        //dd($scheduledservices);

        return view('app.g3.serviceschedule.index', compact('company'));
    }

    public function list(){

       
            //dd($scheduledservices);
            $scheduledservices = $this->userServices()->get();
            
            return view('app.g3.serviceschedule.list', compact('scheduledservices'));
    }

    
   

    public function listAprove(){

        $companiesIds = app('App\Http\Controllers\CompanyController')->userClients()->pluck('id')->toArray();
        $scheduledservices = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', $companiesIds)->where('aproved', 2)->where('clientaproved', 0)->get();
        

        

        return view('app.g3.serviceschedule.list', compact('scheduledservices'));
    }

    public function employeesStatus($id){
        $service = Serviceschedule::findOrFail($id)->employees;
        dd($service);
    }
    
    public function listcompany($id){

        //$company= Company::findOrFail($company_id);
        if(Auth::user()->can('fornecedor')){

            return redirect()->route('companies.servicesscheduled.list');
        }else{
            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->where('company_id', $id)->get();
        }
        //dd($scheduledservices);

        return view('app.g3.serviceschedule.list', compact('scheduledservices'));
    }

    public function listclient($id){

        //$company= Company::findOrFail($company_id);
        if(Auth::user()->can('fornecedor')){

            return redirect()->route('companies.servicesscheduled.list');
        }else{
            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->where('store_id', $id)->get();
        }
        //dd($scheduledservices);

        return view('app.g3.serviceschedule.list', compact('scheduledservices'));
    }

    public function insert(Request $request){

        if(AUth::user()->hasAnyPermission(['master', 'G3 Admin'])){
            $company_id = Auth::user()->company_id;
            $companies= Company::where('fl_client', 0)->get();
        }else{
            $company_id = Auth::user()->company_id;
            $company= Company::findOrFail($company_id);
            $companies= '';
        }
        if ($request->input()) {
            $service = new Serviceschedule();
            if(AUth::user()->hasAnyPermission(['master', 'G3 Admin'])){
                $service->company_id = $request->input('company_id');
            }else{
                $service->company_id = $company_id;

            }
            $service->date_ini = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $request->input('date_ini'));
            $service->date_end = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $request->input('date_end'));
            $service->service = $request->input('service');
            $service->aproved = $request->input('aproved')?1:0;
            $service->store_id = $request->input('store');
            
            if ($request->hasFile('file')) {
                $name=$request->file->getClientOriginalName();
				$fileUpload = $request->file;
				$upload = $fileUpload->store('public/uploads');
                
                
				$file = new File;
				$file->name = $name;
				$file->file = str_replace('public/uploads/', "", $upload);
				$file->save();
                
                $service->file_id = $file->id;
			}else{
                //$service->file_id = '';
            }
            
            $service->save();
            
            $service->employees()->sync($request->input('employees'));

            return redirect()->route('companies.servicesscheduled.list', $company_id)->with('alert-success',__('general.Scheduled services').' '. __('general.has added successfully!'));
        }

        if (!Auth::user()->can('master')){
            $stores = Company::where('fl_client', 1);

        }

        
        $stores = Company::where('fl_client', 1)->where('fl_deleted', 0)->get();
        if(Auth::user()->can('fornecedor')){
            $employees = $company->employees()->where('fl_deleted', 0)->get();
        }else{
            $employees = Employee::where('fl_deleted', 0)->get();
        }
        //dd($employees);
        //$scheduledservices = Serviceschedule::get();
        //dd($scheduledservices);
        $validator = JsValidator::make($this->validationRules);
        return view('app.g3.serviceschedule.insert', compact('validator', 'employees', 'stores', 'companies'));
    }

    public function edit(Request $request, $service_id){
        if(AUth::user()->hasAnyPermission(['master', 'G3 Admin'])){
            $company_id = Auth::user()->company_id;
            $companies= Company::where('fl_client', 0)->get();
        }else{
            $company_id = Auth::user()->company_id;
            $company= Company::findOrFail($company_id);
            $companies= '';
        }
        if ($request->input()) {
            $service = Serviceschedule::findOrFail($service_id);
            if(AUth::user()->hasAnyPermission(['master', 'G3 Admin'])){
                $service->company_id = $request->input('company_id');
            }else{
                $service->company_id = $company_id;

            }
            //$service->employee_id = $request->input('employee');
            $service->date_ini = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $request->input('date_ini'));;
            $service->date_end = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $request->input('date_end'));;
            $service->service = $request->input('service');
            $service->aproved = $request->input('aproved')?$request->input('aproved'):0;
            $service->store_id = $request->input('store');
            
            if ($request->hasFile('file')) {
                $name=$request->file->getClientOriginalName();
				$fileUpload = $request->file;
				//$fileName=$request->file->getClientOriginalName();
				$upload = $fileUpload->store('public/uploads');
                
                
				$file = new File;
				$file->name = $name;
				$file->file = str_replace('public/uploads/', "", $upload);
				$file->save();
                
                $service->file_id = $file->id;
			}else{
                //$service->file_id = '';
            }
            
            $service->save(); 
            $service->employees()->sync($request->input('employees'));

            return redirect()->route('companies.servicesscheduled.list')->with('alert-success',__('general.Scheduled services').' '. __('general.has edited successfully!'));
        }

        
       
        $stores = Company::where('fl_client', 1)->where('fl_deleted', 0)->get();
        $service = Serviceschedule::findOrFail($service_id);
        $company = Company::findOrFail( $service->company_id);
        $employees = Company::findOrFail( $service->company_id)->employees()->where('fl_deleted', 0)->get();
        $employeesOnService = $service->employees()->pluck('id')->toArray();
        //$scheduledservices = Serviceschedule::get();
        //dd($scheduledservices);
        $validator = JsValidator::make($this->validationRules);
        return view('app.g3.serviceschedule.edit', compact('company', 'validator', 'employees', 'stores', 'service', 'employeesOnService', 'companies'));
    }

    public function aprCreate(Request $request, $sid){
       // die('meu caralho');
       
        $service = Serviceschedule::findOrFail($sid);
       //dd($service->apr()->count());
        

        if($request->input()){

            if($service->apr()->where('fl_deleted', '0')->count() == 0){
                $apr = new Apr;
            }else{
                $apr = $service->apr()->where('fl_deleted', 0)->first();
                foreach($apr->items()->where('fl_deleted', 0)->get() as $apr_item){
                    $apr_item->fl_deleted = 1;
                    $apr_item->save();
                }
            } 
            
            $apr->company_id = $service->company->id;
            $apr->service_id = $service->id;
            $apr->maker = $request->input('maker');
            $apr->observation = $request->input('observation');
            $apr->save();

           foreach($request->input('activity') as $item){
               if($item['activity']){
                   //echo($item['activity']);
                    $apr_item = new AprItem;
                    $apr_item->apr_id = $apr->id;
                    $apr_item->activity = $item['activity'];
                    $apr_item->risk_source = $item['source'];
                    $apr_item->risk_factor = $item['factor'];
                    $apr_item->consequence = $item['cons'];
                    $apr_item->action = $item['action'];
                    $apr_item->save();

                }

            }
            //die();
            return redirect()->route('companies.servicesscheduled.list')->with('alert-success','APR '. __('general.has added successfully!'));

        }else{
           //dd($service->apr);

            if($service->apr()->where('fl_deleted', '0')->count() == 0){
                //dd($service);
                return view('app.g3.serviceschedule.apr_create',compact('service'));
            }else{
                if(Auth::User()->hasAnyPermission(['master', 'G3 Admin'])){
                    //die('master');
                    $apr = $service->apr()->where('fl_deleted', '0')->first();
                    return view('app.g3.serviceschedule.apr_view',compact('service', 'apr'));
                }else{

                    $apr = $service->apr()->where('fl_deleted', '0')->first();
                    if($apr->fl_status == 3){
                        return view('app.g3.serviceschedule.apr_view',compact('service', 'apr'));
                    }else{
                        return view('app.g3.serviceschedule.apr_edit',compact('service', 'apr'));
                    }
                }
            }
        }
    }

    public function aprPrint($sid){
        // die('meu caralho');
        
         $service = Serviceschedule::findOrFail($sid);
        //dd($service->apr()->count());
         
            //dd($service->apr);
 
             if($service->apr()->where('fl_deleted', '0')->count() == 0){
                 //dd($service);
                 return view('app.g3.serviceschedule.apr_create',compact('service'));
             }else{
                 if(Auth::User()->hasAnyPermission(['master', 'G3 Admin'])){
                     //die('master');
                     $apr = $service->apr()->where('fl_deleted', '0')->first();
                     return view('app.g3.serviceschedule.apr_view',compact('service', 'apr'));
                 }else{
 
                     $apr = $service->apr()->where('fl_deleted', '0')->first();
                         return view('app.g3.serviceschedule.apr_view',compact('service', 'apr'));
                 }
             }
         
     }

    public function aprUpload(Request $request, $id) {
        $service = Serviceschedule::findOrFail($id);
        if ($request->hasFile('file')) {
            $name=$request->file->getClientOriginalName();
            $fileUpload = $request->file;
            //$fileName=$request->file->getClientOriginalName();
            $upload = $fileUpload->store('public/uploads');
            
            
            $file = new File;
            $file->name = $name;
            $file->file = str_replace('public/uploads/', "", $upload);
            $file->save();
            
            $service->aprsigned_id = $file->id;
            $service->save();
            return redirect()->route('companies.servicesscheduled.list')->with('alert-success','APR '. __('general.has added successfully!'));
        }else{
            return view('app.g3.serviceschedule.apr_upload',compact('service'));
        }
    }
    public function changeAprovation(Request $request) {

        $id = $request->input('id');
        $status = $request->input('status');
        $obs = $request->input('obs');
        $service = Serviceschedule::findOrFail($id);
        
        if(isset($service->observation) && $obs != null){
            $obsArray = json_decode($service->observation, true);
            if(!$obsArray){
                $obsArray = array();
            }
            array_push($obsArray, $obs);
            $service->observation = json_encode($obsArray);
           
        }
        
       
        $service->clientaproved = $status;
        $service->user_id = Auth::User()->id;
        $service->save();
        $message = '<h3>Prezado'.$service->company->name.'<h3><br>';
        if($status == 0){
            $subj =  'Aviso de necessidade de documento para serviços';
            $message .= 'O Serviço abaixo descrito precisa de documentos';
            $message .= '<br><br>';
            $message .= '<strong>Serviço: </strong>'.$service->service.'<br>';
            $message .= '<strong>Loja: </strong>'.$service->store->name.'<br>';
            $message .= '<strong>Observações: </strong>'.str_replace("\n", "<br>", $obs)."<br>";
            $message .= '<br><br>';
            $message .= 'Acesse o link a frente e corrija o quanto antes: <a href="https://g3gpa.abacotecnologia.com.br/g3/companies/servicesscheduled/'.$service->id.'/edit">Cique aqui</a>';
            $message .= '<br><br>';
            $message .= 'Atenciosamente<br>';
            $message .= Auth::User()->user.'<br>';
            
        }
        
        
        if($status == 1){
            $subj =  'Aviso de necessidade de correção de serviços';
            $message .= 'O Serviço abaixo descrito precisa de correções';
            $message .= '<br><br>';
            $message .= '<strong>Serviço: </strong>'.$service->service.'<br>';
            $message .= '<strong>Loja: </strong>'.$service->store->name.'<br>';
            $message .= '<strong>Observações: </strong>'.str_replace("\n", "<br>", $obs)."<br>";
            $message .= '<br><br>';
            $message .= 'Acesse o link a frente e corrija o quanto antes: <a href="https://g3gpa.abacotecnologia.com.br/g3/companies/servicesscheduled/'.$service->id.'/edit">Cique aqui</a>';
            $message .= '<br><br>';
            $message .= 'Atenciosamente<br>';
            $message .= Auth::User()->user.'<br>';
            
        }
        if($status == 2){
            $subj =  'Aviso de aprovação parcial de serviços';
            $message = '<h3>Prezado '.$service->company->name.'<h3><br>';
            $message .= 'O Serviço abaixo foi aprovado parcialmente e ainda precisa de alguns documentos';
            $message .= '<br><br>';
            $message .= '<strong>Serviço:</strong>'.$service->service.'<br>';
            $message .= '<strong>Loja:</strong>'.$service->store->name.'<br>';
            $message .= '<strong>Observações: </strong>'.str_replace("\n", "<br>", $obs)."<br>";
            $message .= '<br><br>';
            $message .= 'Acesse o link a veja os detalhes: <a href="https://g3gpa.abacotecnologia.com.br/g3/companies/servicesscheduled/'.$service->id.'/edit">Cique aqui</a>';
            $message .= '<br><br>';
            $message .= 'Atenciosamente<br>';
            $message .= Auth::User()->user.'<br>';
            
        }
        if($status == 3){
            $subj =  'Aviso de aprovação de serviços';
            $message = '<h3>Prezado '.$service->company->name.'<h3><br>';
            $message .= 'O Serviço abaixo foi aprovado e esta em conformidade para início da execução';
            $message .= '<br><br>';
            $message .= '<strong>Serviço:</strong>'.$service->service.'<br>';
            $message .= '<strong>Loja:</strong>'.$service->store->name.'<br>';
            $message .= '<strong>Observações: </strong>'.str_replace("\n", "<br>", $obs)."<br>";
            $message .= '<br><br>';
            $message .= 'Acesse o link a veja os detalhes: <a href="https://g3gpa.abacotecnologia.com.br/g3/companies/servicesscheduled/'.$service->id.'/edit">Cique aqui</a>';
            $message .= '<br><br>';
            $message .= 'Atenciosamente<br>';
            $message .= Auth::User()->user.'<br>';
            
        }

       
        $email = $service->company->company_email;
        
        $to = $email;
        $subject = $subj;
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Abaco - Sistema G3 <noreply@abacotecnologia.com.br>' . "\r\n";
        
        $send = mail($to, $subject, $message, $headers);
        $savelog = new Mailog;
        $savelog->mails = $email;
        $savelog->subject = $subject;
        $savelog->message = $message;
        $savelog->status = $send;
        $savelog->save();

        /* if($service->clientaproved == 1){
            $service->clientaproved = 0;
        }else{
            $service->clientaproved = 1;
        }
        $service->user_id = Auth::user()->id;
        $service->save(); */
        //echo $service->clientaproved;
    }

    public function changeTechAprovation($id) {
        $service = Serviceschedule::findOrFail($id);
        if($service->techaproved == 1){
            $service->techaproved = 0;
        }else{
            $service->techaproved = 1;
        }
        $service->save();
        echo $service->techaproved;
    }

    public function aprChangeAprovation($id, $status) {
        $apr = Apr::findOrFail($id);
        $apr->fl_status = $status;
        if($apr->save()){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function delete(Request $request){

        if ($request->input()) {
            $service = Serviceschedule::findOrFail($request->input('id'));
            $service->fl_deleted = 1;

            if($service->save()){
                echo 1;
            }

        }



    }

    public function userServices(){
        
        if(Auth::user()->can('fornecedor')){
			//dd($companiesIds);
			//$companies = Company::where('fl_client', 0)->where('fl_deleted', 0)->where('id', App::User()->company_id);
            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->where('company_id', Auth::User()->company_id);

		}else{
            $companiesIds = app('App\Http\Controllers\CompanyController')->userClients()->pluck('id')->toArray();
            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', $companiesIds);

        }

        return $scheduledservices;
        
       
    }

    public function checkAproved(){
        $services = Serviceschedule::where('fl_deleted', 0)->get();

        foreach($services as $service){
            $aproved = true;
            $aprovedPartial = false;
            $docsExpired = false;
            $companyOK = false;
            echo '------'.$service->service.' - '.$service->company->name.' - '. $service->store->name.'---------<br>';
            echo '------'.$service->date_ini.' - '.$service->date_end.'<br>';
            $date_ini = $date1 = date_create_from_format('Y-m-d', $service->date_ini);
            $date_end = $date1 = date_create_from_format('Y-m-d', $service->date_end);
            $diff = (array) date_diff($date_ini, $date_end);
            echo '------'.$diff['days']. ' Dias <br>';

            $allok = true;

            if($diff['days'] > 30){

                if($service->company->documents()->count() < 1){
                    $allok = false;
                    echo' -----------Sem documentos<br>';
                }
                foreach($service->company->documents as $document){
                    
                    $delivered = $document->delivereds()->where('fl_deleted', 0)->where('company_id', $service->company_id)->where('status', 0)->orderBy('id', 'desc')->first();
                    if(!isset($delivered->id)){
                        $allok = false;
                        echo' -----------Sem entregas <br>';
                    }else{
                        //dd($delivered);
                        if(date('Ymd', strtotime($delivered->expiration)) < date('Ymd')){
                            echo' -----------Documento expirado <br>';
                            $docsExpired = true;
                            $allok = false;
                        }
                        
                    };
                }
                if($allok){
                    $companyOK = true;
                    echo' -----------Company Documents OK<br><br>';
                }
            }else{
                $companyOK = true;
            }

            if($companyOK){

                if($service->employees->count() < 1 ){
                    $aproved = false;
                }  
                foreach($service->employees as $employee){
                    $allok = true;
                    echo $employee->name.' - '.$employee->documents()->count() .'<br>';
                    if($employee->documents()->count() < 1){
                        $allok = false;
                    }
                    foreach($employee->documents as $document){
                        echo '|__'.$document->name.'<br>';
                        
                        $okCount =$document->delivereds()->where('fl_deleted', 0)->where('employee_id', $employee->id)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'))->count() .'<br>';
                        if($okCount < 1){
                            $allok = false;
                        }
                        echo '&nbsp;&nbsp;&nbsp;|____ENTREGAS OK = '. $okCount;
                    }
                    if($allok){
                        $aprovedPartial = true;
                    }else{
                        $aproved = false;

                    }
                    
                    echo '<br><br>';
                }
                if(!$aproved){
                   if( $aprovedPartial){

                       $service->aproved = 3;
                       $service->save();
                       echo '****APROVADO PARCIAL<br>';
                    }else{
                        $service->aproved = 0;
                        $service->save();
                        echo '****AGUARDANDO<br>';
                    }
                }else{

                    $service->aproved = 4;
                    $service->save();
                    echo '****APROVADO<br>';

                }
                
            }else{
                if($docsExpired){
                    $service->aproved = 2;

                }else{

                    $service->aproved = 0;
                }
                $service->save();
                echo '****AGUARDANDO<br>';
            }
            echo '<br><br>';
        }

        dd($services);
    }


}
