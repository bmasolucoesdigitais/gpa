<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trainingschedules;
use Illuminate\Support\Facades\Validator;
use JsValidator;
use App\Company;
use App\Test;
use Auth;

class TrainingscheduleController extends Controller
{
    protected $validationRules = [
		'name'=>'required|max:120',
		'company'=>'required',
		'dt_ini'=>'required',
		'dt_end'=>'required',
		'vacancies'=>'required',

	];

    public function index(){
        $trainings = Trainingschedules::all()->where('fl_deleted', 0);
        //dd($trainings->company());

        return view('app.g3.trainingschedules.index', compact('trainings'));
    }

    public function employees($id){
        $training = Trainingschedules::find($id);
        $employees = $training->students;
        //dd($employees);

        return view('app.g3.trainingschedules.employees', compact('training', 'employees'));
    }
    public function employeesChangePresence(Request $request){
        $training = Trainingschedules::find($request->input('id'));
        $employee = $training->students->find($request->input('student'));
        if($employee->pivot->fl_present == 0){
            $training->students()->updateExistingPivot($employee, ['fl_present' => 1]);
            if(!$employee->pivot->token){
                $token = bin2hex(random_bytes(16));
                $training->students()->updateExistingPivot($employee, ['token' => $token]);


                //------------envia e-mail -------------------

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $headers .= 'From: Abaco - Sistema G3 <noreply@abacotecnologia.com.br>' . "\r\n";
                
                $subject = 'Treinamento - Link para prova';
                $to = $employee->pivot->email;
                $message = 'Olá '.$employee->name;
                $message .= '<br>Você está recebendo o link para a prova do treinamento: '.$training->name.' - da empresa - '.$training->company->name;
                
                $message .= '<br><br>link da prova: <a href="http://g3.abacotecnologia.com.br/prova/'.$token.'" target="_blank">Fazer prova</a>';
                //echo '<br>'.$to;
                //echo '<br>'.'<br>'.$message;
                

                mail($to, $subject, $message, $headers);

                //-------------------------------------------------------------------------
            }
            echo 1;
        }else{
            $training->students()->updateExistingPivot($employee, ['fl_present' => 0]);
            echo 0;
        }

        //return view('app.g3.trainingschedules.employees', compact('training', 'employees'));
    }

    public function create(Request $request){
        if ($request->input()) {
            $dt_ini = preg_replace('#(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})#', '$3-$2-$1 $4:$5', $request ->input('dt_ini'));
            $dt_end = preg_replace('#(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})#', '$3-$2-$1 $4:$5', $request ->input('dt_end'));
            $training = new Trainingschedules;
            $training->name = $request ->input('name');
            $training->company_id = $request ->input('company');
            $training->url = $request ->input('url');
            $training->test_id = $request ->input('test_id');
            $training->dt_ini = $dt_ini;
            $training->dt_end = $dt_end;
            $training->vacancies = $request ->input('vacancies');

            $training->save();

            return redirect()->route('trainingschedule.index')->with('message', __('general.Training created sucessfull!'));

        }else{
            $validator = JsValidator::make($this->validationRules);

            if(Auth::User()->can('master')){
                $companies = Company::all()->where('fl_deleted', 0)->where('fl_client', 1);
                $tests = Test::where('fl_deleted', 0)->orderBy('name', 'asc')->get();
            }else{
                $companies = Company::find(Auth::User()->company_id);
                $tests = Test::where('fl_deleted', 0)->where('company_id', Auth::User()->company_id)->orderBy('name', 'asc')->get();
    
            }

            return view('app.g3.trainingschedules.insert', compact('companies', 'validator', 'tests'));
        }
    }

    public function edit(Request $request, $id){
        if ($request->input()) {
            $dt_ini = preg_replace('#(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})#', '$3-$2-$1 $4:$5', $request ->input('dt_ini'));
            $dt_end = preg_replace('#(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})#', '$3-$2-$1 $4:$5', $request ->input('dt_end'));

            $training = Trainingschedules::find($id);
            $training->name = $request ->input('name');
            $training->company_id = $request ->input('company');
            $training->url = $request ->input('url');
            $training->test_id = $request ->input('test_id');
            $training->dt_ini = $dt_ini;
            $training->dt_end = $dt_end;
            $training->vacancies = $request ->input('vacancies');

            $training->save();

            return redirect()->route('trainingschedule.index')->with('message', __('general.Training edited sucessfull!'));

        }else{
            $validator = JsValidator::make($this->validationRules);
            if(Auth::User()->can('master')){
                $companies = Company::all()->where('fl_deleted', 0)->where('fl_client', 1);
                $tests = Test::where('fl_deleted', 0)->orderBy('name', 'asc')->get();
            }else{
                $companies = Company::find(Auth::User()->company_id);
                $tests = Test::where('fl_deleted', 0)->where('company_id', Auth::User()->company_id)->orderBy('name', 'asc')->get();
    
            }
            $training = Trainingschedules::find($id);
		return view('app.g3.trainingschedules.edit', compact('companies', 'validator', 'training', 'tests'));
        }
    }

    public function delete(Request $request){
        $training = Trainingschedules::find( $request ->input('id'));
        $training->fl_deleted = 1;

        if($training->save()){
           echo 1;
        }
        //dd($training);
    }

    public function changeAccomplished(Request $request){
        $training = Trainingschedules::find( $request ->input('id'));
        if($training->fl_accomplished == 1){
            //echo ('St to 0');
            $training->fl_accomplished = 0;
        }else{
            //echo 'set to 1';
            $training->fl_accomplished = 1;
        }

        if($training->save()){
            echo $training->fl_accomplished;
        }
        //dd($training);
    }


}
