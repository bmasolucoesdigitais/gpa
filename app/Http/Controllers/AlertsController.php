<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Company;
use App\Maillog;
use App\Employee;
use App\Document;
use App\Delivered;
use App\File;
use App\Mailog;
use App\Serviceschedule;
Use Auth;

class AlertsController extends Controller
{



    public function index(){

        $companies = Company::where('fl_client', 1)->get();
        //dd($companies);
        foreach($companies as $company){
            $emails = explode(',', $company->manager_email );
            $teste = $this->send('juniormalk@gmail.com', "teste", 'teste');
            dd($teste);

        }
        //$message = $this->expiresdocs($companies, 'fl_client');
        //echo $message;
    }

    /* public function remember(){

        #$this->teste01();
        $clients = Company::where('fl_client', 1)->get();
        //dd($clients);
        foreach ($clients as $cli) {
            echo($this->docscol($cli->id));
        }

    } */
    public function remember(){
        $arr_ruf = array(
            'R - N'=>array('AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'),
            'R - NE'=>array('AL', 'BA', 'CE', 'MA', 'PI', 'PE', 'PB', 'RN'),
            'R - CO'=>array('GO', 'MT', 'MS', 'DF'),
            'R - S'=>array('PR', 'SC', 'RS'),
            'R - SE'=>array('MG', 'SP', 'ES', 'RJ'),
        );
        #$this->teste01();
        $services = Serviceschedule::where('fl_deleted', 0)->where('date_end', '>', date('Y-m-d'))->get();
        
        $companies = Company::where('fl_deleted', '0')->whereIn('id', $services->pluck('company_id')->toArray())->get();
        $stores = $services->pluck('store_id')->toArray();
        $users = User::get();
        $companyToStore = array();
        $userArray = array('sesmt'=>array(), 'gerente'=>array(), 'cd'=>array(), 'companies' => array());
        $companyEmployees = array();

        foreach ($services as $service) {
            if(!isset($companyToStore[$service->company_id])){
                $companyToStore[$service->company_id] = array();
            }
            if(!in_array($service->store_id, $companyToStore[$service->company_id])){
                array_push($companyToStore[$service->company_id], $service->store_id);
            }
            
            if(!isset($companyEmployees[$service->company_id])){
                $companyEmployees[$service->company_id] = array();
            }
            foreach ($service->employees as $employess) {
                if(!in_array($employess->id, $companyEmployees[$service->company_id])){
                    array_push( $companyEmployees[$service->company_id], $employess->id);
                }
            }
        }
        
        
        foreach($users as $user){
            if($user->hasRole('G3 Admin')){
                foreach ($arr_ruf as $key => $value) {
                    if($user->hasRole($key)){
                        if(!isset($userArray['sesmt'][$key])){
                            $userArray['sesmt'][$key] = array();
                        }
                        array_push($userArray['sesmt'][$key], array('name'=>$user->name, 'email'=>$user->email, 'exp15'=>array(), 'exp7'=>array(), 'exp'=>array()));
                    }
                    
                }
            }
            if($user->hasRole('CD')){
                    array_push($userArray['cd'], array('name'=>$user->name, 'email'=>$user->email, 'exp15'=>array(), 'exp7'=>array(), 'exp'=>array()));
            }
            if($user->hasRole('Gerente')){
                    array_push($userArray['gerente'], array('name'=>$user->name, 'email'=>$user->email, 'company_id'=>$user->company_id, 'exp15'=>array(), 'exp7'=>array(), 'exp'=>array()));
            }
        }

    
        
        $exp15 = date("Ymd", strtotime(date("m/d/y") . "+15 days"));
        $exp7 = date("Ymd", strtotime(date("m/d/y") . "+7 days"));
        $exp = date("Ymd", strtotime(date("m/d/y")));

        foreach ($companies as $client) {
            // echo $client->name;
            // echo '<br>';
            $storesClients = Company::whereIn('id', $companyToStore[$client->id])->get();
            //dd($storesClients);
            $userArray['companies'][$client->id] =  array('name'=>$client->name, 'email'=>$client->company_email, 'exp15'=>array(), 'exp7'=>array(), 'exp'=>array());
            foreach ($client->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->where('status', 0)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires == $exp15) {
                        $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                        array_push($userArray['companies'][$client->id]['exp15'], $a );
                        // echo 'vence em 15:';
                        // print_r($a);
                    }
                    if ($expires == $exp7) {
                        $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                        array_push($userArray['companies'][$client->id]['exp7'], $a );

                       

                        foreach($userArray['sesmt'] as $key => $value){
                            foreach($storesClients as $item){
                                if(in_array($item->state, $arr_ruf[$key])){
                                    foreach($value as $k2 => $user){
                                        if(!in_array($a, $userArray['sesmt'][$key][$k2]['exp7'])){
                                            array_push($userArray['sesmt'][$key][$k2]['exp7'], $a);
                                        }
                                    }
                                }
                            }
                        }

                        
                        // echo 'vence em 7:';
                        // print_r($a);
                    }
                    if ($expires < $exp) {
                        $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                        array_push($userArray['companies'][$client->id]['exp'], $a );

                        foreach($userArray['sesmt'] as $key => $value){
                            foreach($storesClients as $item){
                                if(in_array($item->state, $arr_ruf[$key])){
                                    foreach($value as $k2 => $user){
                                        if(!in_array($a, $userArray['sesmt'][$key][$k2]['exp'])){
                                            array_push($userArray['sesmt'][$key][$k2]['exp'], $a);
                                        }
                                    }
                                }
                            }
                        }

                        foreach($userArray['gerente'] as $key => $value){
                            if(in_array($value['company_id'], $companyToStore[$client->id])){
                                if(!in_array($a, $userArray['gerente'][$key]['exp'])){
                                    array_push($userArray['gerente'][$key]['exp'], $a);
                                }
                            }
                        }

                        foreach($storesClients as $item){
                            if($item->fl_cd == 1){
                                foreach($userArray['cd'] as $key => $value){
                                    if(!in_array($a, $userArray['cd'][$key]['exp'])){
                                        array_push($userArray['cd'][$key]['exp'], $a);
                                    }
                                }
                            }
                        }

                        // echo 'vencido:';
                        // print_r($a);
                    }
                    // echo '<br>';
                }
            }

            foreach ($client->employees()->whereIn('id', $companyEmployees[$client->id])->where('fl_deleted', 0)->orderBy('name')->get() as $employee) {
                foreach ($employee->documents->where('fl_deleted', 0) as $document) {
                    foreach ($document->delivereds()->where('employee_id', $employee->id)->where('status', 0)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                        if ($expires == $exp15) {
                            $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                            array_push($userArray['companies'][$client->id]['exp15'], $a );

                            // echo 'vence em 15:';
                            // print_r($a);
                        }
                        if ($expires == $exp7) {
                            $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                            array_push($userArray['companies'][$client->id]['exp7'], $a );

                            foreach($userArray['sesmt'] as $key => $value){
                                foreach($storesClients as $item){
                                    if(in_array($item->state, $arr_ruf[$key])){
                                        foreach($value as $k2 => $user){
                                            if(!in_array($a, $userArray['sesmt'][$key][$k2]['exp7'])){
                                                array_push($userArray['sesmt'][$key][$k2]['exp7'], $a);
                                            }
                                        }
                                    }
                                }
                            }

                            // echo 'vence em 7:';
                            // print_r($a);
                        }
                        if ($expires < $exp) {
                            $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                            array_push($userArray['companies'][$client->id]['exp'], $a );

                            foreach($userArray['sesmt'] as $key => $value){
                                foreach($storesClients as $item){
                                    if(in_array($item->state, $arr_ruf[$key])){
                                        foreach($value as $k2 => $user){
                                            if(!in_array($a, $userArray['sesmt'][$key][$k2]['exp'])){
                                                array_push($userArray['sesmt'][$key][$k2]['exp'], $a);
                                            }
                                        }
                                    }
                                }
                            }
                            
                            foreach($userArray['gerente'] as $key => $value){
                                if(in_array($value['company_id'], $companyToStore[$client->id])){
                                    if(!in_array($a, $userArray['gerente'][$key]['exp'])){
                                        array_push($userArray['gerente'][$key]['exp'], $a);
                                    }
                                }
                            }

                            foreach($storesClients as $item){
                                if($item->fl_cd == 1){
                                    foreach($userArray['cd'] as $key => $value){
                                        if(!in_array($a, $userArray['cd'][$key]['exp'])){
                                            array_push($userArray['cd'][$key]['exp'], $a);
                                        }
                                    }
                                }
                            }
                            // echo 'vencido:';
                            // print_r($a);
                        }
                    }
                }

                foreach ($employee->services as $service) {
                    foreach ($service->documents()->where('fl_deleted', 0)->orderBy('name')->get() as $document) {
                        foreach ($document->delivereds()->where('employee_id', $employee->id)->where('status', 0)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                            $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                            if ($expires == $exp15) {
                                $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                array_push($userArray['companies'][$client->id]['exp15'], $a );
                                
                                
                                // echo 'vence em 15:';    
                                // print_r($a);
                            }
                            if ($expires == $exp7) {
                                $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                array_push($userArray['companies'][$client->id]['exp7'], $a );

                                foreach($userArray['sesmt'] as $key => $value){
                                    foreach($storesClients as $item){
                                        if(in_array($item->state, $arr_ruf[$key])){
                                            foreach($value as $k2 => $user){
                                                if(!in_array($a, $userArray['sesmt'][$key][$k2]['exp7'])){
                                                    array_push($userArray['sesmt'][$key][$k2]['exp7'], $a);
                                                }
                                            }
                                        }
                                    }
                                }


                                // echo 'vence em 7:';
                                // print_r($a);
                            }
                            if ($expires < $exp) {
                                $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                array_push($userArray['companies'][$client->id]['exp'], $a );

                                foreach($userArray['sesmt'] as $key => $value){
                                    foreach($storesClients as $item){
                                        if(in_array($item->state, $arr_ruf[$key])){
                                            foreach($value as $k2 => $user){
                                                if(!in_array($a, $userArray['sesmt'][$key][$k2]['exp'])){
                                                    array_push($userArray['sesmt'][$key][$k2]['exp'], $a);
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                foreach($userArray['gerente'] as $key => $value){
                                    if(in_array($value['company_id'], $companyToStore[$client->id])){
                                        if(!in_array($a, $userArray['gerente'][$key]['exp'])){
                                            array_push($userArray['gerente'][$key]['exp'], $a);
                                        }
                                    }
                                }

                                foreach($storesClients as $item){
                                    if($item->fl_cd == 1){
                                        foreach($userArray['cd'] as $key => $value){
                                            if(!in_array($a, $userArray['cd'][$key]['exp'])){
                                                array_push($userArray['cd'][$key]['exp'], $a);
                                            }
                                        }
                                    }
                                }
                                // echo 'vencido:';
                                // print_r($a);
                            }
                        }
                    }
                }
                
            }

                

           
        }
        
        $mailArray= array();
        foreach ($userArray as $a => $b) {
            switch ($a) {
                case 'sesmt':
                    foreach ($b as $ba => $bb) {
                        foreach($bb as $bba){
                            array_push($mailArray, $bba);
                        }
                    }
                    break;
                    
                
                default:
                    foreach ($b as $ba) {
                        array_push($mailArray, $ba);
                    }
                    break;
            }
        }


        foreach ($mailArray as $mail) {
            $subject = 'ÁBACO - Documentos que requerem atenção';
            $message = '
                <style>
                .tabela-email {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
                max-width: 1200px;
                }

                .tabela-email td, .tabela-email th {
                border: 1px solid #ddd;
                padding: 8px;
                }

                .tabela-email tr:nth-child(even){background-color: #f2f2f2;}

                .tabela-email tr:hover {background-color: #ddd;}

                .tabela-email th {
                text-align: center;
                padding-top: 12px;
                padding-bottom: 12px;
                background-color: #619bff;
                color: white;
                }
                </style>
                <h3>
                    
                    Os documentos abaixo requrem atenção:
                </h3>
                <br>
                <br>
                <h3>
                    Vencem em 15 dias:
                </h3>
                <table class="tabela-email">
                    <tr>
                        <th>Terceiro</th>
                        <th>Empresa</th>
                        <th>Documento:</th>
                        <th>Vencimento:</th>
                    </tr>
            ';
            foreach ($mail['exp15'] as $exp15) {

                $message.= '
                        <tr>
                        <td>'.$exp15['employee'].'</td>
                        <td>'.$exp15['company'].'</td>
                        <td>'.$exp15['document'].'</td>
                        <td>'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $exp15['expirade']).'</td>
                
                    </tr>
                ';
                
            }
            $message.= '</table>';
            $message.= ' 
            <h3>
                Vencem em 7 dias:
            </h3>
            <table class="tabela-email">
                <tr>
                    <th>Terceiro</th>
                    <th>Empresa</th>
                    <th>Documento:</th>
                    <th>Vencimento:</th>
                </tr>
                ';
            
            foreach ($mail['exp7'] as $exp7) {

                $message.= '
                        <tr>
                        <td>'.$exp7['employee'].'</td>
                        <td>'.$exp7['company'].'</td>
                        <td>'.$exp7['document'].'</td>
                        <td>'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $exp7['expirade']).'</td>
                
                    </tr>
                ';
                
            }

            $message.= '</table>';
            $message.= '<h3>
               Vencidos:
            </h3>
            <table class="tabela-email">
                <tr>
                    <th>Terceiro</th>
                    <th>Empresa</th>
                    <th>Documento:</th>
                    <th>Vencimento:</th>
                </tr>
                ';
            
            foreach ($mail['exp'] as $exp) {

                $message.= '
                        <tr>
                        <td>'.$exp['employee'].'</td>
                        <td>'.$exp['company'].'</td>
                        <td>'.$exp['document'].'</td>
                        <td>'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $exp['expirade']).'</td>
                
                    </tr>
                ';
                
            }
            $message.= '</table>';

            $mail = $mail['email'];
            $send = $this->send($mail, $subject, $message);
            if(isset($send)){
                if($send == 1 || $send == true){
                    $send = 1;
                }else{
                    $send = 0;
                }
            }else{
                $send = 0;
            }
            echo '--------inicio do e-mail--------------<br/>';
            echo $mail.'<br/>';
            echo $subject.'<br/>';
            echo $message.'<br/><br/>';

            $savelog = new Mailog;
            $savelog->mails = $mail;
            $savelog->subject = $subject;
            $savelog->message = $message;
            $savelog->status = $send;
            $savelog->save();
        }
            echo '<br>';
            echo '<br>';
        
            
        
        //dd($mailArray, $userArray, $companyEmployees, $companyToStore, $companies);
    }
    
public function rememberServices(){

        $arr_ruf = array(
            'R - N'=>array('AC', 'AM', 'AP', 'PA', 'RO', 'RR', 'TO'),
            'R - NE'=>array('AL', 'BA', 'CE', 'MA', 'PI', 'PE', 'PB', 'RN'),
            'R - CO'=>array('GO', 'MT', 'MS', 'DF'),
            'R - S'=>array('PR', 'SC', 'RS'),
            'R - SE'=>array('MG', 'SP', 'ES', 'RJ'),
        );

        $users = User::get();
        $users_region = array();
        foreach($users as $user){
            if($user->hasRole('G3 Admin')){
                foreach ($arr_ruf as $key => $value) {
                    if($user->hasRole($key)){
                        if(!isset($users_region[$key])){
                            $users_region[$key] = array();
                        }
                        array_push($users_region[$key], array('name'=>$user->name, 'email'=>$user->email));
                    }
                    
                }
            }
        }
        //var_dump($users_region);
        $hours48 = date("Y-m-d H:i:s", strtotime(date("m/d/y H:i:s") . "-48 hours"));
        echo $hours48;
        echo '<br>';
        
        foreach($arr_ruf as $key => $r){           
            $subject  = 'ÁBACO G3  - Aviso de serviços para aprovação a mais de 48 horas';
            $cpr = Company::where('fl_deleted', 0)->where('fl_client', 1)->whereIn('state', $r)->pluck('id')->toArray();
            $filter_uf = implode(',', $cpr);
            $scheduledservices = Serviceschedule::where('fl_deleted', 0)->where('aproved', '>', 1)->where('clientaproved', '<', 1)->where('updated_at', '<', $hours48 )->whereIn('store_id', explode(',', $filter_uf))->get();
            if($scheduledservices->count() > 0){

                echo $key;
                echo '<br>';
                echo '<br>';
                $message = '
                                    <style>
                    .tabela-email {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    max-width: 1200px;
                    }

                    .tabela-email td, .tabela-email th {
                    border: 1px solid #ddd;
                    padding: 8px;
                    }

                    .tabela-email tr:nth-child(even){background-color: #f2f2f2;}

                    .tabela-email tr:hover {background-color: #ddd;}

                    .tabela-email th {
                    text-align: center;
                    padding-top: 12px;
                    padding-bottom: 12px;
                    background-color: #619bff;
                    color: white;
                    }
                    </style>
                    <h3>
                        
                        Os serviços abaixo estão aguardando a mais de 48 horas para serem aprovado:
                    </h3>
                    <table class="tabela-email">
                        <tr>
                            <th>Serviço</th>
                            <th>Empresa</th>
                            <th>Loja</th>
                            <th>Data Inicio:</th>
                            <th>Data Fim:</th>
                        </tr>
                ';
                foreach ($scheduledservices as $service) {

                    $message.= '
                            <tr>
                            <td>'.$service->service.'</td>
                            <td>'.$service->company->name.'</td>
                            <td>'.$service->store->filial.' - '.$service->store->name.'</td>
                            <td>'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_ini).'</td>
                            <td>'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$3/$2/$1', $service->date_end).'</td>
                    
                        </tr>
                    ';
                    
                }
                $message.= '</table>';
                foreach ($users_region[$key] as $value) {
                    


                    $mail = $value['email'];
                    $send = $this->send($mail, $subject, $message);
                    if(isset($send)){
                        if($send == 1 || $send == true){
                            $send = 1;
                        }else{
                            $send = 0;
                        }
                    }else{
                        $send = 0;
                    }
                    echo '--------inicio do e-mail--------------<br/>';
                    echo $mail.'<br/>';
                    echo $subject.'<br/>';
                    echo $message.'<br/><br/>';

                    $savelog = new Mailog;
                    $savelog->mails = $mail;
                    $savelog->subject = $subject;
                    $savelog->message = $message;
                    $savelog->status = $send;
                    $savelog->save();
                    }
                    echo '<br>';
                    echo '<br>';
            }
        }

    }

    public function teste01(){
        $company = Company::find(3);
        foreach ($company->clients as $client) {
            //dd($client);
            if(($client->pivot->mail_company != 'null' && $client->pivot->mail_company != '') || ($client->pivot->mail_client != 'null' && $client->pivot->mail_client != '')){
                //echo("Mail Company: ".$client->pivot->mail_company."<br/>");
                //echo("Mail Client: ".$client->pivot->mail_client."<br/>");
                if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                        $data[$client->pivot->mail_company] = ["exp15"=>[],"exp10"=>[],"exp5"=>[]];
                        echo('Company:' .$client->pivot->mail_company.'<br/>');
                }
                if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                    $data[$client->pivot->mail_client] = ["exp15"=>[],"exp10"=>[],"exp5"=>[]];
                    echo('Client:' .$client->pivot->mail_client.'<br/>');

                }
            }

        }
    }

    public function docscol($cp){
        $exp15 = date("Ymd", strtotime(date("m/d/y") . "+15 days"));
        $exp10 = date("Ymd", strtotime(date("m/d/y") . "+10 days"));
        $exp5 = date("Ymd", strtotime(date("m/d/y") . "+5 days"));
        $company = Company::find($cp);
        $outsourceds = $company->outsourceds()->wherePivot('fl_active', '=', 1)->pluck('id');
        //$outsourceds = $company->outsourceds()->find(2);//->pluck('id');
        //dd($outsourceds->pivot->fl_active);
        $data = [];
        foreach ($company->clients as $client) {
            //dd($client);
            if(($client->pivot->mail_company != 'null' && $client->pivot->mail_company != '') || ($client->pivot->mail_client != 'null' && $client->pivot->mail_client != '')){
                if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                        if(!isset($data[$mail])){
                            $data[$mail] = ["exp15"=>[],"exp10"=>[],"exp5"=>[]];
                            //echo $mail;
                        }
                    }
                }
                if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                        if(!isset($data[$mail])){
                            $data[$mail] = ["exp15"=>[],"exp10"=>[],"exp5"=>[]];
                        }
                    }
                }
                foreach ($client->documents->where('fl_deleted', 0) as $document) {
                    foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->where('status', 0)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                        if ($expires == $exp15) {
                            if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                    $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                    array_push($data[$mail]['exp15'], $a );
                                }
                            }
                            if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                    $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                    array_push($data[$mail]['exp15'], $a );
                                }
                            }
                        }
                        if ($expires == $exp10) {
                            if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                    $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                    array_push($data[$mail]['exp10'], $a );
                                }
                            }
                            if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                        $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                    array_push($data[$mail]['exp10'], $a );
                                }
                            }
                        }
                        if ($expires < $exp5) {
                            if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                    $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                    array_push($data[$mail]['exp5'], $a );
                                }
                            }
                            if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                        $a = ['employee'=> '', 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                    array_push($data[$mail]['exp5'], $a );
                                }
                            }
                        }
                    }
                }
                foreach ($client->employees()->whereIn('id', $outsourceds)->where('fl_deleted', 0)->orderBy('name')->get() as $employee) {
                    foreach ($employee->documents->where('fl_deleted', 0) as $document) {
                        foreach ($document->delivereds()->where('employee_id', $employee->id)->where('status', 0)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                            $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                            if ($expires == $exp15) {
                                if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                        $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                        array_push($data[$mail]['exp15'], $a );
                                    }
                                }
                                if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                        $a = ['employee'=> $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                        array_push($data[$mail]['exp15'], $a );
                                    }
                                }
                            }
                            if ($expires == $exp10) {
                                if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                        $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                        array_push($data[$mail]['exp10'], $a );
                                    }
                                }
                                if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                        $a = ['employee'=> $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                        array_push($data[$mail]['exp10'], $a );
                                    }
                                }
                            }
                            if ($expires < $exp5) {
                                if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                        $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                        array_push($data[$mail]['exp5'], $a );
                                        //echo $mail;
                                        //print_r($a);
                                    }
                                }
                                if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                    foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                        $a = ['employee'=> $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                        array_push($data[$mail]['exp5'], $a );
                                    }
                                }
                            }
                            /* if ($expires == $exp15) {
                                $message.= '<tr><td>Terceiro:</td><td>'.$employee->name . ' - CPF:' . $employee->cpf.'</td>';
                                $message.= '<td> '.$document->name.'</td>';
                                $message.= ' <td> '.$delivered->expiration.'</tr>';
                            }*/
                        }
                    }

                    foreach ($employee->services as $service) {
                        foreach ($service->documents()->where('fl_deleted', 0)->orderBy('name')->get() as $document) {
                            foreach ($document->delivereds()->where('employee_id', $employee->id)->where('status', 0)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                if ($expires == $exp15) {
                                    if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                        foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                            $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                            array_push($data[$mail]['exp15'], $a );
                                        }
                                    }
                                    if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                        foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                            $a = ['employee'=> $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                            array_push($data[$mail]['exp15'], $a );
                                        }
                                    }
                                }
                                if ($expires == $exp10) {
                                    if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                        foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                            $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                            array_push($data[$mail]['exp10'], $a );
                                        }
                                    }
                                    if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                        foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                            $a = ['employee'=> $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                            array_push($data[$mail]['exp10'], $a );
                                        }
                                    }
                                }
                                if ($expires < $exp5) {
                                    if($client->pivot->mail_company != 'null' && $client->pivot->mail_company != ''){
                                        foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_company))) as $mail){
                                            $a = ['employee'=>  $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                            array_push($data[$mail]['exp5'], $a );
                                        }
                                    }
                                    if($client->pivot->mail_client != 'null' && $client->pivot->mail_client != ''){
                                        foreach( explode(',', str_replace(' ', '', strtolower($client->pivot->mail_client))) as $mail){
                                            $a = ['employee'=> $employee->name.' - '.$employee->cpf, 'company'=>$client->name, 'document'=>$document->name, 'expirade'=>$delivered->expiration];
                                            array_push($data[$mail]['exp5'], $a );
                                        }
                                    }
                                }
                                /*if ($expires == $exp15) {
                                    $message.= '<tr><td>Terceiro:</td><td>'.$employee->name . ' - CPF:' . $employee->cpf.'</td>';
                                    $message.= '<td> '.$document->name.'</td>';
                                    $message.= ' <td> '.$delivered->expiration.'</tr>';
                                }*/
                            }
                        }
                    }
                    
                }
            }
        }

        foreach ($data as $mail => $data) {
            $message = '';
            $message.= '<table border="1">';
            $message.= '<tr><td colspan="4"><h2>Documetos de terceiros alocados na empresa: '.$company->name.'</h2></td></tr>';

            if(count($data['exp15'])>0) {

                $message.= '<tr><td colspan="4"><h3>Vencem em 15 dias</h3> </td></tr>';
                $message .= '<tr>';
                $message .= '<td>Terceiro - CPF</td>';
                $message .= '<td>Empresa</td>';
                $message .= '<td>Documento</td>';
                $message .= '<td>Vencimento</td>';
                $message .= '</tr>';
                foreach ($data['exp15'] as $docs) {
                    $message .= '<tr>';
                    $message .= '<td>'.$docs['employee'].'</td>';
                    $message .= '<td>'.$docs['company'].'</td>';
                    $message .= '<td>'.$docs['document'].'</td>';
                    $message .= '<td>'.$docs['expirade'].'</td>';
                    $message .= '</tr>';
                }
            }
            if(count($data['exp10'])>0) {
                $message.= '<tr><td colspan="4"><h3>Vencem em 10 dias</h3> </td></tr>';
                $message .= '<tr>';
                $message .= '<td>Terceiro - CPF</td>';
                $message .= '<td>Empresa</td>';
                $message .= '<td>Documento</td>';
                $message .= '<td>Vencimento</td>';
                $message .= '</tr>';
                foreach ($data['exp10'] as $docs) {
                    $message .= '<tr>';
                    $message .= '<td>'.$docs['employee'].'</td>';
                    $message .= '<td>'.$docs['company'].'</td>';
                    $message .= '<td>'.$docs['document'].'</td>';
                    $message .= '<td>'.$docs['expirade'].'</td>';
                    $message .= '</tr>';
                }
            }

            if(count($data['exp5'])>0) {
                $message.= '<tr><td colspan="4"><h3>Vencem em 5 dias ou menos e vencidos</h3> </td></tr>';
                $message .= '<tr>';
                $message .= '<td>Terceiro - CPF</td>';
                $message .= '<td>Empresa</td>';
                $message .= '<td>Documento</td>';
                $message .= '<td>Vencimento</td>';
                $message .= '</tr>';
                foreach ($data['exp5'] as $docs) {
                    $message .= '<tr>';
                    $message .= '<td>'.$docs['employee'].'</td>';
                    $message .= '<td>'.$docs['company'].'</td>';
                    $message .= '<td>'.$docs['document'].'</td>';
                    $message .= '<td>'.date('d/m/Y', strtotime($docs['expirade'])).'</td>';
                    $message .= '</tr>';
                }
            }
            $message .= '</table>';
            //echo($message);
            $subject = 'Aviso de vencimento de documentos - '.$company->name ;
            if(count($data['exp15']) > 0 || count($data['exp10']) > 0 || count($data['exp5']) > 0  ){


                $send = $this->send($mail, $subject, $message);
                if(isset($send)){
                    if($send == 1 || $send == true){
                        $send = 1;
                    }else{
                        $send = 0;
                    }
                }else{
                    $send = 0;
                }
                echo '--------inicio do e-mail--------------<br/>';
                echo $mail.'<br/>';
                echo $subject.'<br/>';
                echo $message.'<br/><br/>';

                $savelog = new Mailog;
                $savelog->mails = $mail;
                $savelog->subject = $subject;
                $savelog->message = $message;
                $savelog->status = $send;
                $savelog->save();


                //print_r($data);
            }
        }

        //echo($message);

        $result = $data;

        //return 'true';

    }

    public function activedOutsourceds(){

        $companies = Company::where('fl_client', 1)->get();
        //dd($companies);
        foreach ($companies as $company) {
            $outsourceds = $company->outsourceds()->wherePivot('fl_ready', 1)->wherePivot('dt_ready_sent', null)->where('fl_deleted', 0)->get();
            //$outsourceds = $company->outsourceds()->wherePivot('fl_ready', 1)->where('fl_deleted', 0)->get();
            if($outsourceds->count()>0){

                $message = '<h3>Os terceiros abaixo estão com a documentação em dia e aptos a prestarem serviços:</h3>';
                foreach ($outsourceds as $outsourced) {
                    $message .= '- Nome:'.$outsourced->name.' - CPF:'. $outsourced->cpf ."<br/>";
                    $company->outsourceds()->updateExistingPivot($outsourced, ['dt_ready_sent' => date('Y-m-d')]);
                   // $company->outsourceds()->updateExistingPivot($outsourced, ['dt_ready_sent' => date('Y-m-d')]);
                }
                $emails = explode(',', $company->manager_email );
                foreach ($emails as $email) {
                    $subject = "Terceiro aptos a prestarem serviço";
                    $this->send($email, $subject, $message );
                }
                echo($message);
            }
        }

        //$message = $this->expiresdocs($companies);
    }




    public function doccollection ($company){

        $exp15 = date("Ymd", strtotime(date("m/d/y") . "+15 days"));
        $exp10 = date("Ymd", strtotime(date("m/d/y") . "+10 days"));
        $exp5 = date("Ymd", strtotime(date("m/d/y") . "+5 days"));

        $message = '';
        $message.= '<table border="1">';
        $message.= '<tr><td colspan="4"><h2>'.$company->name.'</h2></td></tr>';
        $message.= '<tr><td colspan="4"><h3>Vencem em 15 dias</h3> </td></tr>';
        $message .= '<tr>';
        $message .= '<td>Tipo</td>';
        $message .= '<td>Nome</td>';
        $message .= '<td>Documento</td>';
        $message .= '<td>Vencimento</td>';
        $message .= '</tr>';
        foreach ($company->clients as $client) {
            foreach ($client->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires == $exp15) {
                        $message.= '<tr><td>Empresa:</td><td>'.$client->name.'</td>';
                        $message.= '<td>'.$document->name.'</td>';
                        $message.= ' <td>'.$delivered->expiration.'</td></tr>';
                    }
                }
            }
        }

        foreach ($company->outsourceds as $outsourced) {

            foreach ($outsourced->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', $outsourced->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires == $exp15) {
                        $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                        $message.= '<td> '.$document->name.'</td>';
                        $message.= ' <td> '.$delivered->expiration.'</tr>';
                    }
                }
            }

            foreach ($outsourced->services as $service) {
                foreach ($service->documents->where('fl_deleted', 0) as $document) {
                    foreach ($document->delivereds()->where('employee_id', $outsourced->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                        if ($expires == $exp15) {
                            $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                            $message.= '<td> '.$document->name.'</td>';
                            $message.= ' <td> '.$delivered->expiration.'</tr>';
                        }
                    }
                }
            }
        }
        $message.= '<tr><td colspan="4"><h3>Vencem em 10 dias</h3></td></tr>';
        $message .= '<tr>';
        $message .= '<td>Tipo</td>';
        $message .= '<td>Nome</td>';
        $message .= '<td>Documento</td>';
        $message .= '<td>Vencimento</td>';
        $message .= '</tr>';

        foreach ($company->clients as $client) {
            foreach ($client->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires == $exp10) {
                        $message.= '<tr><td>Empresa:</td><td>'.$client->name.'</td>';
                        $message.= '<td>'.$document->name.'</td>';
                        $message.= ' <td>'.$delivered->expiration.'</td></tr>';
                    }
                }

            }
        }

        foreach ($company->outsourceds as $outsourced) {
            foreach ($outsourced->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', $outsourced->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires == $exp10) {
                        $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                        $message.= '<td> '.$document->name.'</td>';
                        $message.= ' <td> '.$delivered->expiration.'</tr>';
                    }
                }
            }

            foreach ($outsourced->services as $service) {
                foreach ($service->documents->where('fl_deleted', 0) as $document) {
                    foreach ($document->delivereds()->where('employee_id', $outsourced->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                        if ($expires == $exp10) {
                            $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                            $message.= '<td> '.$document->name.'</td>';
                            $message.= ' <td> '.$delivered->expiration.'</tr>';
                        }
                    }
                }
            }
        }


        $message.= '<tr><td colspan="4"><h3>Vencem em 5 dias ou menos e vencidos</h3></td></tr>';
        $message .= '<tr>';
        $message .= '<td>Tipo</td>';
        $message .= '<td>Nome</td>';
        $message .= '<td>Documento</td>';
        $message .= '<td>Vencimento</td>';
        $message .= '</tr>';

        foreach ($company->clients as $client) {
            foreach ($client->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires <= $exp5) {
                        $message.= '<tr><td>Empresa:</td><td>'.$client->name.'</td>';
                        $message.= '<td>'.$document->name.'</td>';
                        $message.= ' <td>'.$delivered->expiration.'</td></tr>';
                    }
                }
            }
        }

        foreach ($company->outsourceds as $outsourced) {

            foreach ($outsourced->documents->where('fl_deleted', 0) as $document) {
                foreach ($document->delivereds()->where('employee_id', $outsourced->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                    if ($expires <= $exp5) {
                        $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                        $message.= '<td> '.$document->name.'</td>';
                        $message.= ' <td> '.$delivered->expiration.'</tr>';
                    }
                }
            }

            foreach ($outsourced->services as $service) {
                foreach ($service->documents->where('fl_deleted', 0) as $document) {
                    foreach ($document->delivereds()->where('employee_id', $outsourced->id)->where('fl_deleted', 0)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                        if ($expires <= $exp5) {
                            $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                            $message.= '<td> '.$document->name.'</td>';
                            $message.= ' <td> '.$delivered->expiration.'</tr>';
                        }
                    }
                }
            }
        }

        $message.=('</table>');
        return $message;

    }

    public function send ($email, $subj, $message ){
        // multiple recipients
        //$to  = 'junior@akinfo.com.br' . ','; // note the comma
        $to = $email;

        // subject
        $subject = $subj;

        // message
       /* $message = '
        <html>
        <head>
         <title>Birthday Reminders for August</title>
        </head>
        <body>
        </body>
        </html>
        ';*/
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        // Additional headers
        //$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        $headers .= 'From: Abaco - Sistema G3 <noreply@abacotecnologia.com.br>' . "\r\n";
        // $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

        // Mail it
       return mail($to, $subject, $message, $headers);
    }
    
 /*    public function expiresdocs ($company, $type){


        //echo date("Ymd", strtotime(date("m/d/y") . "+15 days")).'<br/><br/>';
        //echo date("Ymd", strtotime(date("m/d/y") . "+10 days")).'<br/><br/>';
        //echo date("Ymd", strtotime(date("m/d/y") . "+5 days")).'<br/><br/>';
        $exp15 = date("Ymd", strtotime(date("m/d/y") . "+15 days"));
        $exp10 = date("Ymd", strtotime(date("m/d/y") . "+10 days"));
        $exp5 = date("Ymd", strtotime(date("m/d/y") . "+5 days"));




            $message = '';
    	//foreach ($companies as $company) {
            $message.= '<table border="1">';
            $message.= '<tr><td colspan="4"><h2>'.$company->name.'</h2></td></tr>';
            //$message.= '<h2>'.$company->manager_email.'</h2>';
                //echo '-'.$company->name.'<br/>';
            $message.= '<tr><td colspan="4"><h3>Vencem em 15 dias</h3> </td></tr>';
            $message .= '<tr>';
            $message .= '<td>Tipo</td>';
            $message .= '<td>Nome</td>';
            $message .= '<td>Documento</td>';
            $message .= '<td>Vencimento</td>';
            $message .= '</tr>';
    		foreach ($company->clients as $client) {

    			//echo '  |_'.$client->name.'<br/>';
    			foreach ($client->documents->where('fl_deleted', 0) as $document) {
                    if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){

                        //echo '    |_'.$document->name.'<br/>';
                        foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                            $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                            if ($expires == $exp15) {
                                # code...
                                $message.= '<tr><td>Empresa:</td><td>'.$client->name.'</td>';
                                $message.= '<td>'.$document->name.'</td>';
                                $message.= ' <td>'.$delivered->expiration.'</td></tr>';
                            }
                        }

                    }
    			}


    		}
    		//$message.=('<br/>');
            //$message.=('<br/>');

            foreach ($company->outsourceds as $outsourced) {

                //echo '  |_'.$client->name.'<br/>';
                foreach ($outsourced->documents->where('fl_deleted', 0) as $document) {
                    if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                        //echo '    |_'.$document->name.'<br/>';
                        foreach ($document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        //$message.= '    |_'.$document->name.'<br/>'.'--'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);;
                            $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                            if ($expires == $exp15) {
                                # code...
                                $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                                $message.= '<td> '.$document->name.'</td>';
                                $message.= ' <td> '.$delivered->expiration.'</tr>';
                            }
                        }
                    }
                }




                foreach ($outsourced->services as $service) {
                        # code...
                    foreach ($service->documents->where('fl_deleted', 0) as $document) {

                        ////$message.= '    |_'.$document->name.'<br/>';
                        if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                            foreach ($document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                                    $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                    if ($expires == $exp15) {
                                        # code...
                                        $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                                        $message.= '<td> '.$document->name.'</td>';
                                        $message.= ' <td> '.$delivered->expiration.'</tr>';
                                    }
                                    //$message.=('<br/>');
                            }
                        }
                    }

                }
            }
            //$message.=('<br/>');

            $message.= '<tr><td colspan="4"><h3>Vencem em 10 dias</h3></td></tr>';
            $message .= '<tr>';
            $message .= '<td>Tipo</td>';
            $message .= '<td>Nome</td>';
            $message .= '<td>Documento</td>';
            $message .= '<td>Vencimento</td>';
            $message .= '</tr>';

            //echo '-'.$company->name.'<br/>';
            foreach ($company->clients as $client) {

                //echo '  |_'.$client->name.'<br/>';
                foreach ($client->documents->where('fl_deleted', 0) as $document) {
                    if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                        //echo '    |_'.$document->name.'<br/>';
                        foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                            if ($expires == $exp10) {
                        $message.= '<tr><td>Empresa:</td><td>'.$client->name.'</td>';
                            $message.= '<td>'.$document->name.'</td>';
                            $message.= ' <td>'.$delivered->expiration.'</td></tr>';
                            }
                        }
                    }

                }

            }

           // $message.=('<br/>');

             foreach ($company->outsourceds as $outsourced) {

                //echo '  |_'.$client->name.'<br/>';
                    foreach ($outsourced->documents->where('fl_deleted', 0) as $document) {
                        if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                            //echo '    |_'.$document->name.'<br/>';
                            foreach ($document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                            //$message.= '    |_'.$document->name.'<br/>'.'--'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);;
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                if ($expires == $exp10) {
                                    # code...
                                $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                                $message.= '<td> '.$document->name.'</td>';
                                $message.= ' <td> '.$delivered->expiration.'</tr>';
                                }
                            }
                        }
                    }


                    foreach ($outsourced->services as $service) {
                        # code...
                    foreach ($service->documents->where('fl_deleted', 0) as $document) {
                        if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                            ////$message.= '    |_'.$document->name.'<br/>';
                            foreach ($document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                    if ($expires == $exp10) {
                                        # code...
                                    $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                                $message.= '<td> '.$document->name.'</td>';
                                $message.= ' <td> '.$delivered->expiration.'</tr>';
                                    }
                                }

                            }
                        }
                    }

            }
            //$message.=('<br/>');
            //$message.=('<br/>');


            $message.= '<tr><td colspan="4"><h3>Vencem em 5 dias ou menos e vencidos</h3></td></tr>';
            $message .= '<tr>';
            $message .= '<td>Tipo</td>';
            $message .= '<td>Nome</td>';
            $message .= '<td>Documento</td>';
            $message .= '<td>Vencimento</td>';
            $message .= '</tr>';

            //echo '-'.$company->name.'<br/>';
            foreach ($company->clients as $client) {

                //echo '  |_'.$client->name.'<br/>';
                foreach ($client->documents->where('fl_deleted', 0) as $document) {
                    if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                        //echo '    |_'.$document->name.'<br/>';
                        foreach ($document->delivereds()->where('employee_id', null)->where('company_id', $client->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                        $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                            if ($expires <= $exp5) {
                            $message.= '<tr><td>Empresa:</td><td>'.$client->name.'</td>';
                            $message.= '<td>'.$document->name.'</td>';
                            $message.= ' <td>'.$delivered->expiration.'</td></tr>';
                            }
                        }
                    }
                }

            }
            //$message.=('<br/>');
             foreach ($company->outsourceds as $outsourced) {

                //echo '  |_'.$client->name.'<br/>';
                    foreach ($outsourced->documents->where('fl_deleted', 0) as $document) {
                        if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                            //echo '    |_'.$document->name.'<br/>';
                            foreach ($document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                            //$message.= '    |_'.$document->name.'<br/>'.'--'.preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);;
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                if ($expires <= $exp5) {
                                    # code...
                                $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                                $message.= '<td> '.$document->name.'</td>';
                                $message.= ' <td> '.$delivered->expiration.'</tr>';
                                }
                            }
                        }
                    }



                foreach ($outsourced->services as $service) {
                        # code...
                    foreach ($service->documents->where('fl_deleted', 0) as $document) {
                        if(isset($document->docsettings->where('company_id', $company->id)->where($type, 1)->first()->id)){
                            //$message.= '    |_'.$service->name.'<br/>';
                            foreach ($document->delivereds()->where('employee_id', $outsourced->id)->orderBy('id', 'desc')->take(1)->get() as $delivered) {
                                $expires = preg_replace('#(\d{4})-(\d{2})-(\d{2})#', '$1$2$3', $delivered->expiration);
                                    if ($expires <= $exp5) {
                                        # code...
                                    $message.= '<tr><td>Terceiro:</td><td>'.$outsourced->name . ' - CPF:' . $outsourced->cpf.'</td>';
                                $message.= '<td> '.$document->name.'</td>';
                                $message.= ' <td> '.$delivered->expiration.'</tr>';
                                    }
                                }

                            }
                        }
                    }

            }
            $message.=('</table>');
            //$message.=('<br/>');
            //$message.=('<br/>');



        //}
            //$message.=('______________________________________________________<br/><br/>');
    	//dd($clients[1]->clients[5]->documents->where('fl_deleted', 0));
        //return $this->send($message);
        return $message;

    } */
}
