<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Delivered;
use App\Document;
use App\Employee;
use App\Serviceschedule;
use Spatie\Permission\Models\Role;
use App\Trainingschedules;
use App\User;
Use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = array();
        $arr_ruf = array(
            'n'=>array('AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'),
            'ne'=>array('AL', 'BA', 'CE', 'MA', 'PI', 'PE', 'PB', 'RN'),
            'co'=>array('GO', 'MT', 'MS', 'DF'),
            's'=>array('PR', 'SC', 'RS'),
            'se'=>array('MG', 'SP', 'ES', 'RJ'),
        );

        //$cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $arr_ruf['co'])->pluck('id')->toArray();




        if(Auth::user()->can('fornecedor')){

            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->where('company_id', Auth::user()->company_id)->get();
            $data['services_count'] =  0;
            $data['services_to_aprove'] = 0;
        }elseif(Auth::user()->can('master')){
            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->get();
            $data['services_count'] = Serviceschedule::where('fl_deleted', 0)->count();
        $data['services_to_aprove'] = Serviceschedule::where('fl_deleted', 0)->whereIn('aproved', [0, 1])->count();
        }elseif(Auth::user()->can('tecnico')){

            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->get();
            $data['services_count'] =  Serviceschedule::where('fl_deleted', 0)->where('store_id', Auth::user()->company_id)->count();
            $data['services_to_aprove'] = Serviceschedule::where('fl_deleted', 0)->where('store_id',  Auth::user()->company_id)->where('aproved', 2)->where('clientaproved', 1)->count();
            //dd($data['services_to_aprove']);

        }elseif(Auth::user()->can('cd')){
                $filter_uf= '';
                if(Auth::user()->can('r-centro-oeste')){
                    $cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->where('fl_cd', 1)->whereIn('state', $arr_ruf['co'])->pluck('id')->toArray();
                    $filter_uf .= implode(',', $cpr);
                }
                if(Auth::user()->can('r-nordeste')){
                    $cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->where('fl_cd', 1)->whereIn('state', $arr_ruf['ne'])->pluck('id')->toArray();
                    $filter_uf .= implode(',', $cpr);

                }
                if(Auth::user()->can('r-norte')){
                    $cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->where('fl_cd', 1)->whereIn('state', $arr_ruf['n'])->pluck('id')->toArray();
                    $filter_uf .= implode(',', $cpr);

                }
                if(Auth::user()->can('r-sudeste')){
                    $cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->where('fl_cd', 1)->whereIn('state', $arr_ruf['se'])->pluck('id')->toArray();
                    $filter_uf .= implode(',', $cpr);

                }
                if(Auth::user()->can('r-sul')){
                    $cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->where('fl_cd', 1)->whereIn('state', $arr_ruf['s'])->pluck('id')->toArray();
                    $filter_uf .= implode(',', $cpr);

                }
                //dd($filter_uf);
                $scheduledservices = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->get();

                $data['services_count'] =  Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->count();
                $data['services_to_aprove'] = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->where('aproved', 2)->where('clientaproved', 0)->count();

        }else{
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
                $scheduledservices = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->get();

                $data['services_count'] =  Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->count();
                $data['services_to_aprove'] = Serviceschedule::where('fl_deleted', 0)->whereIn('store_id', explode(',', $filter_uf))->where('aproved', 2)->where('clientaproved', 0)->count();
        }



        $company = Company::findOrFail(Auth::user()->company_id);

        $data['stores_count'] = Company::where('fl_deleted', 0)->where('fl_client', 1)->count();
        $data['companies_count'] = Company::where('fl_deleted', 0)->where('fl_client', 0)->count();
        // $data['services_count'] = Serviceschedule::where('fl_deleted', 0)->count();
        // $data['services_to_aprove'] = Serviceschedule::where('fl_deleted', 0)->where('aproved', 2)->where('clientaproved', 0)->count();

        return view('home', compact('company', 'data'));
    }

    public function test()
    {
        //echo 'Teste';
        $employee = Employee::findOrFail(528); //->toArray();
        $delivereds = $employee->delivereds()->where('document_id', 4)->where('fl_deleted', 0)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'))->count();
        // $delivereds = $employee->delivereds()->where('fl_deleted', 0)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'));//->count()
        dd($employee, $delivereds);
    }


    public function genusers()
    {
       // $training = Trainingschedules::find(1)->students;
        //dd($training);
        /* foreach($training as $employee){
            echo($employee->pivot->fl_present);
        } */

        $usersArray = array(
            ['id' => 8, 'email' => "tatiane.matter@airdraw.com.br"],
            ['id' => 20, 'email' => "combatfireadm@gmail.com"],
            ['id' => 31, 'email' => "dominio@dominio-aut.com.br"],
            ['id' => 43, 'email' => "anacristina@gaengenharia.com.br"],
            ['id' => 44, 'email' => "daniel.planejamento@genpro.com.br"],
            ['id' => 49, 'email' => "jb.gringo@gmail.com"],
            ['id' => 56, 'email' => "rh@installautomacao.com.br"],
            ['id' => 72, 'email' => "juliana.kaufmann@payper.com.br"],
            ['id' => 85, 'email' => "log2@rochafortesaneamento.com.br"],
            ['id' => 87, 'email' => "rvmont.rh@gmail.com"],
            ['id' => 99, 'email' => "carla@uprbrasil.com.br"],
            ['id' => 105, 'email' => "sarita.comercial@zanuto.com.br"],
            ['id' => 154, 'email' => "adm@glbtechautomacao.com.br"],
            ['id' => 164, 'email' => "lindomar@guiterconstrucoes.com.br"],
            ['id' => 183, 'email' => "adm@caldeirariasantarosa.com.br"],
            ['id' => 285, 'email' => "Lauri.zanon@bertolini.com.br"],
            ['id' => 402, 'email' => "ysocaall@hotmail.com "],
            ['id' => 464, 'email' => "engenharia@nipo-br.com.br"],
            ['id' => 479, 'email' => "elisa.rabelo@polimatec.com.br "]
        );

        $companies = Company::where('fl_client', 1)->get();
        $mailTemp = '';
        foreach($companies as $company){
            foreach($company->clients as $client){
                //echo('<br>'.explode(',', $client->pivot->mail_client)[0]);
                $mailTemp = '';
                $mailTemp = explode(',', $client->pivot->mail_client)[0];
               if($mailTemp != ''){
                   array_push($usersArray, ['id' => $client->id, 'email' => strtolower($mailTemp)]);
                }
           }

        }
        //dd($usersArray);




        //dd($usersArray);
         //DB::connection()->enableQueryLog();
         foreach($usersArray as $newUser){
            $testUser = User::where('email', $newUser['email'])->count();
            if(!$testUser){

                $user = new User;
                $user -> name = $newUser['email'];
                $user -> email = $newUser['email'];
                $user -> password =  $newUser['email'];
                $user -> company_id = $newUser['id'];
                $user->save();
                //$queries = DB::getQueryLog();
                //dd($queries);

                //$user = User::create($request->only('email', 'name', 'password', 'company_id')); //Retrieving only the email and password data

                $roles = array(6); //Retrieving the roles field
                //Checking if a role was selected
                if (isset($roles)) {

                    foreach ($roles as $role) {
                        $role_r = Role::where('id', '=', $role)->firstOrFail();
                        $user->assignRole($role_r); //Assigning role to user
                    }
                }
                echo('<br>Added:'.$newUser['email']);
            }else{
                echo('<br>Já existe:'.$newUser['email']);

            }
        }

       //  $clients = $company- */>clients()->find(8)->employees()->whereIn('id', $outsourceds)->where('fl_deleted', 0)->orderBy('name')->get();
       /*  dd($clients); */
        /*
        foreach ($client->employees()->whereIn('id', $outsourceds)->where('fl_deleted', 0)->orderBy('name')->get() as $employee) {
            if($employee->pivot->fl_active == 1){

        $token = bin2hex(random_bytes(16));
        dd($token);
        */
    }


    public function auth()
    {
        $employee = Employee::findOrFail(528); //->toArray();
        $delivereds = $employee->delivereds()->where('document_id', 4)->where('fl_deleted', 0)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'))->count();
        // $delivereds = $employee->delivereds()->where('fl_deleted', 0)->where('status', 0)->whereDate('expiration', '>=', date('Y-m-d'));//->count()
        dd($employee, $delivereds);
    }
}
