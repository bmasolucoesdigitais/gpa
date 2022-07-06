<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Test;
use App\Company;
use App\Document;
use App\Delivered;
use App\Question;
use App\Trainingschedules;
use App\EmployeeTraining;
use Auth;
use JsValidator;
use App\User;

class TestController extends Controller
{

    protected $validationRules = [
        'name'=>'required|max:120',
        'company_id'=>'required',
        'minutes'=>'required',
        'questions'=>'required',


    ];
    protected $questValidationRules = [
        'question'=>'required',
        'correct_answer'=>'required',
        'answer1'=>'required',
        'answer2'=>'required',


    ];
    public function index(){
        $tests = Test::all()->where('fl_deleted', 0);

        return view('app.g3.tests.index', compact('tests'));
    }


    public function impfunc(){
        echo 'ok';

        $carga = file("listafunc.csv");

        // $dibcarga = file("dibcarga.txt");





        /*
        $result = mysqli_query($conexao, "update  company_client set mail_company = '".strtolower($linha[28])."', updated_at = now() where id = "$linha[23]";"

        ");*/



        // for ($d = 0; $d < 50; $d++) {
        for ($d = 0; $d < count($carga); $d++) {
            $linha = explode(',', $carga[$d]);
            if($d==0){
                foreach ($linha as $key=>$value) {
                    echo $key. ' : ' . $value.'<br>';
                }

            }else{
                echo $linha[4] . ' - ' . $linha[5] .' - '.$linha[7].'<br>';
                $company = Company::where('filial', $linha[4])->get();
                if(isset($company[0])){
                    printf ("%s <br>", $company[0]->name);
                    $usercheck = User::where('email', strtolower($linha[7]))->get();
                    if(isset($usercheck[0]->email)){
                        echo 'já existe<br>';
                    }else{
                        $user = new User;
                        $user->name = $linha[5];
                        $user->company_id =  $company[0]->id;
                        $user->email =  strtolower($linha[7]);
                        $user->password = 'G3GPA@mudar123';
                        $user->save();
                        $user->assignRole('G3 View');
                    }

                }else{
                    echo'Not found<br>';
                }
                // $query = "SELECT * FROM companies where filial = '".$linha[4]."' limit 1;";
                // echo $query."<br>";
                // if ($result = $mysqli->query($query)) {

                //     /* fetch associative array */
                //     $row = $result->fetch_array(MYSQLI_ASSOC);
                //     if($row){
                //         printf ("%s <br>", $row["name"]);

                //     }else{
                //         echo'Not found<br>';
                //     }


                //     /* free result set */
                //     $result->close();
                // }
            }


        }
    }
    public function implojas(){
        //echo 'ok';

        $carga = file("lojaspiped.tsv");

        // $dibcarga = file("dibcarga.txt");





        /*
        $result = mysqli_query($conexao, "update  company_client set mail_company = '".strtolower($linha[28])."', updated_at = now() where id = "$linha[23]";"

        ");*/



        // for ($d = 0; $d < 50; $d++) {
            echo 'Linas: '.count($carga).'<br>';
        for ($d = 0; $d < count($carga); $d++) {
            $linha = explode('|', $carga[$d]);
            if($d==0){
                foreach ($linha as $key=>$value) {
                    echo $key. ' : ' . $value.'<br>';
                }
                //die();
            }else{
                echo $linha[4] . ' - ' . $linha[5] .' - '.$linha[7].'<br>';
                $company = Company::where('name', $linha[3])->get();
                if(isset($company[0])){
                   $company_change = $company[0];
                   $company_change->name = $linha[4];
                   $company_change->filial = $linha[0];
                   $company_change->cnpj = $linha[18];
                   if($company_change->save()){
                       echo'Changed';
                   };
                }else{
                    echo'Not found<br>';
                }
                // $query = "SELECT * FROM companies where filial = '".$linha[4]."' limit 1;";
                // echo $query."<br>";
                // if ($result = $mysqli->query($query)) {

                //     /* fetch associative array */
                //     $row = $result->fetch_array(MYSQLI_ASSOC);
                //     if($row){
                //         printf ("%s <br>", $row["name"]);

                //     }else{
                //         echo'Not found<br>';
                //     }


                //     /* free result set */
                //     $result->close();
                // }
            }


        }
    }



    public function test($token, $cpf = null,  Request $request){
        $twoYears = array(10,14,15,290,20,39,328,50,141,251,229,139,68,69,73,74,78,92,147,281,231,391,280,234,418,69,73,74,136,137,138);
        $student = EmployeeTraining::where('token', $token)->get();
        $cpfValid = false;
        if(!isset($student[0])){
            //echo 'Tem não';
            return redirect()->route('home');
            //die();
        }
        $student = $student[0];
        $training = Trainingschedules::find($student->trainingschedule_id);
        $employee = $student->employee;
        //$test = $training->test;
        //dd($employee);
        if(!$cpf){
          //echo 'Favor informar o CPF';
          return view('app.g3.tests.login', compact('student'));
        }
        if($cpf != $employee->cpf){

            return view('app.g3.tests.login', compact('student', 'cpfValid'));
        }
        //dd($test);
        //die();
        if($request->input('resp')){
            $correctAnswears = array();
            $answers = $request->input('resp');
            $typeErrors = array();
            foreach($training->test->quests as $quest){
                $correctAnswears[$quest->id] = [$quest->correct_answer, $quest->type];

            }
            foreach($correctAnswears as $key=>$value){
                if($correctAnswears[$key][0] == $answers[$key]){
                    //echo '<br>'.$correctAnswears[$key][0].' - '.$answers[$key].'  --  tipo:'.$correctAnswears[$key][1]. '-- Correta';
                }else{
                    if(!isset($typeErrors[$correctAnswears[$key][1]])){
                        $typeErrors[$correctAnswears[$key][1]] = 1;
                    }else{
                        $typeErrors[$correctAnswears[$key][1]] ++;
                    }
                   // echo '<br>'.$correctAnswears[$key][0].' - '.$answers[$key].'  --  tipo:'.$correctAnswears[$key][1]. '-- Errada';
                }
            }
            $aproved = true;
            foreach($typeErrors as $errors){
               // echo('<br>Erros: '.$errors);

                if($errors > 1){
                    $aproved = false;
                }

            }
            $json_resp = json_decode($student->answers_json, true);
            //echo('<br>Aprovado: '.$aproved);
            //echo('<br>Status:'.$student->status_test);
            //echo('<br>'.json_encode($answers));
            $hoje = Date('Y-m-d');
            $isTwo = false;
            foreach($employee->companies->pluck('id')->toArray() as $cp){
                if(in_array($cp, $twoYears)){
                    $isTwo = true;
                }
            }
            if($isTwo){
                $validade = date('Y-m-d', strtotime('+2 years', strtotime($hoje)));
            }else{
                $validade = date('Y-m-d', strtotime('+1 years', strtotime($hoje)));

            }

            switch ($student->status_test) {
                case '1':
                    if($aproved){
                        $student->status_test = 2;
                        $json_resp[0] = $answers;
                        $student->answers_json = json_encode($json_resp);

                        $delivered = new Delivered;
                        $delivered->description = Date('d/m/Y');
                        $delivered->expiration = $validade;
                        $delivered->document_id = $training->test->document_id;
                        $delivered->employee_id = $student->employee_id;
                        $delivered->company_id = $training->company_id;
                        $delivered->fl_deleted = 0;
                        $delivered->save();


                    }else{
                        $student->status_test = 3;
                        $json_resp[0] = $answers;
                        $student->answers_json = json_encode($json_resp);

                    }
                    break;
                case '3':
                    if($aproved){
                        $student->status_test = 4;
                        $json_resp[1] = $answers;
                        $student->answers_json = json_encode($json_resp);

                        $delivered = new Delivered;
                        $delivered->description = Date('d/m/Y');
                        $delivered->expiration = $validade;
                        $delivered->document_id = $training->test->document_id;
                        $delivered->employee_id = $student->employee_id;
                        $delivered->company_id = $training->company_id;
                        $delivered->fl_deleted = 0;
                        $delivered->save();
                    }else{
                        $student->status_test = 5;
                        $json_resp[1] = $answers;
                        $student->answers_json = json_encode($json_resp);

                    }
                    break;


                default:
                    # code...
                    break;
            }
            //die();
            $student->save();
            //dd($student);
            //dd($request->input('resp'));
            return view('app.g3.tests.prova', compact('student', 'training', 'employee', 'token', 'cpf' ));
        }else{

            return view('app.g3.tests.prova', compact('student', 'training', 'employee', 'token', 'cpf' ));
        }
        //return view('app.g3.tests.index', compact('tests'));
    }

    public function add(Request $request){

        if($request->input()){
            $test = new Test;
            $test->name = $request->input('name');
            $test->minutes = $request->input('minutes');
            $test->questions = $request->input('questions');
            $test->company_id = $request->input('company_id');
            $test->document_id = $request->input('document_id');

            $test->save();

            return redirect()->route('tests')->with('message', __('general.Test').' '.__('general.has added successfully!'));
        }
        //$tests = Test::all()->where('fl_deleted', 0);
        if(Auth::User()->can('master')){
            $companies = Company::all()->where('fl_client', 1);
            $documents = Document::where('fl_deleted', 0)->orderBy('name', 'asc')->get();
            //dd($documents);
        }else{
            $companies = Company::find(Auth::User()->company_id);
            $documents = Document::where('fl_deleted', 0)->whereIn('company_id', [0,Auth::User()->company_id])->orderBy('name', 'asc')->get();

        }
        $validator = JsValidator::make($this->validationRules);
        return view('app.g3.tests.insert', compact('companies', 'validator', 'documents'));
    }
    public function edit(Request $request, $id){

        if($request->input()){
            $test = Test::find($id);
            $test->name = $request->input('name');
            $test->minutes = $request->input('minutes');
            $test->questions = $request->input('questions');
            $test->company_id = $request->input('company_id');
            $test->document_id = $request->input('document_id');

            $test->save();

            return redirect()->route('tests')->with('message', __('general.Test').' '.__('general.has edited successfully!'));
        }
        $test = Test::find($id);
        if(Auth::User()->can('master')){
            $companies = Company::all()->where('fl_client', 1);
            $documents = Document::where('fl_deleted', 0)->orderBy('name', 'asc')->get();
        }else{
            $companies = Company::find(Auth::User()->company_id);
            $documents = Document::where('fl_deleted', 0)->whereIn('company_id', [0,Auth::User()->company_id])->orderBy('name', 'asc')->get();

        }
        $validator = JsValidator::make($this->validationRules);
        return view('app.g3.tests.edit', compact('companies','test', 'validator', 'documents'));
    }

    public function delete(Request $request){

        if($request->input('id')){

            $test = Test::find($request->input('id'));
            $test->fl_deleted = 1;
            $test->save();
            echo 1;
        }else{
            echo 0;
        }
    }

    public function questions($id){

       $test = Test::find($id);

       //dd($test);

       return view('app.g3.tests.questions', compact('test'));
    }

    public function questionsInsert(Request $request, $id){

       $test = Test::find($id);

        if($request->input()){
            $question = new Question;
            $question->question = $request->input('question');
            $question->answer1 = $request->input('answer1');
            $question->answer2 = $request->input('answer2');
            if($request->input('answer3')){
                $question->answer3 = $request->input('answer3');
            }
            if($request->input('answer4')){
                $question->answer4 = $request->input('answer4');
            }
            if($request->input('answer5')){
                $question->answer5 = $request->input('answer5');
            }
            if($request->input('answer6')){
                $question->answer6 = $request->input('answer6');
            }
            if($request->input('answer7')){
                $question->answer7 = $request->input('answer7');
            }
            if($request->input('answer8')){
                $question->answer8 = $request->input('answer8');
            }
            if($request->input('answer9')){
                $question->answer9 = $request->input('answer9');
            }
            if($request->input('answer10')){
                $question->answer10 = $request->input('answer10');
            }

            $question->correct_answer = $request->input('correct_answer');
            $question->test_id = $id;
            $question->type = $request->input('type');
            $question->save();

            return redirect()->route('tests.questions', $id)->with('message', __('general.Question').' '.__('general.has added successfully!'));

        }
    }

    public function questionsDelete(Request $request){

       //$test = Test::find($request->input('id'));

        if($request->input()){
            $question = Question::findOrFail($request->input('id'));
            $question->fl_deleted =  1;
            if($question->save()){
                echo 1;
            }else{
                echo '0';
            }

        }

       //dd($test);

    }

    public function questionsEdit(Request $request, $id, $qid){

        $test = Test::find($id);
        $question = Question::find($qid);

         if($request->input()){

             $question->question = $request->input('question');
             $question->answer1 = $request->input('answer1');
             $question->answer2 = $request->input('answer2');
             if($request->input('answer3')){
                 $question->answer3 = $request->input('answer3');
             }
             if($request->input('answer4')){
                 $question->answer4 = $request->input('answer4');
             }
             if($request->input('answer5')){
                 $question->answer5 = $request->input('answer5');
             }
             if($request->input('answer6')){
                 $question->answer6 = $request->input('answer6');
             }
             if($request->input('answer7')){
                 $question->answer7 = $request->input('answer7');
             }
             if($request->input('answer8')){
                 $question->answer8 = $request->input('answer8');
             }
             if($request->input('answer9')){
                 $question->answer9 = $request->input('answer9');
             }
             if($request->input('answer10')){
                 $question->answer10 = $request->input('answer10');
             }

             $question->correct_answer = $request->input('correct_answer');
             $question->test_id = $id;
             $question->type = $request->input('type');
             $question->save();

             return redirect()->route('tests.questions', $id)->with('message', __('general.Question').' '.__('general.has edited successfully!'));
         }

        //dd($test);
        $validator = JsValidator::make($this->questValidationRules);
        return view('app.g3.tests.question_edit', compact('test', 'question', 'validator'));
     }
}
