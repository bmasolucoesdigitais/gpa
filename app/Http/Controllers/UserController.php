<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use JsValidator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Company;
use Auth;


//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
        protected $validationRules = [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',

        ];
	/*public function index(User $user)
	{
		$users = $user->all();
		foreach ($users as $key) {
			echo $key->name;
		}

	}*/

	public function index() {
    //Get all users and pass it to the view
        //dd(Auth::user()->can('master'));
        /* if (Auth::user()->can('master')) {
        }else{
            $users = User::where('company_id', Auth::user()->company_id)->get();
        } */
        $users = User::all();
        return view('users.index')->with('users', $users);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
    //Get all roles and pass it to the view
         $validator = JsValidator::make($this->validationRules);

         if (Auth::user()->can('master')) {
                $roles = Role::get(); //Get all roles
                $companies = Company::all();
               // return view('users.create',compact( 'roles', 'companies'));
            }else{
                 $roles = Auth::user()->roles()->get();
                 $companies = '';


            }

            return view('users.create')->
                with([
                    'validator' => $validator,
                    'roles' => $roles,
                    'companies' => $companies,
                ]);
        //$companies = Company::all();

    }



    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
    //Validate name, email and password fields
        $validation = Validator::make($request->all(), $this->validationRules);

         if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'min:10|confirmed',

        ]);

        //DB::connection()->enableQueryLog();
        $user = new User;
        $user -> name = $request->input('name');
        $user -> email = $request->input('email');
        $user -> password = $request->input('password');
        $user -> company_id = $request->input('company_id');
        $user->save();
        $queries = DB::getQueryLog();
        //dd($queries);

        //$user = User::create($request->only('email', 'name', 'password', 'company_id')); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
    //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();
            $user->assignRole($role_r); //Assigning role to user
            }
        }
    //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully added.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        return redirect('users');
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
         /* if (Auth::user()->can('master')) {
                $roles = Role::get(); //Get all roles
                 $companies = Company::orderBy('fl_client', 'filial')->where('fl_deleted', '0')->get();
                }else{
                    $roles = Auth::user()->roles()->get();
                    $companies = '';
                } */
                $roles = Role::get(); //Get all roles
                 $companies = Company::orderBy('fl_client', 'filial')->where('fl_deleted', '0')->get();
        //dd(Auth::user()->roles()->get());
        //$companies = Company::all();

        return view('users.edit', compact('user', 'roles', 'companies')); //pass user and roles data to view

    }

    public function profile(Request $request) {

        if($request->input()){
        $user = User::findOrFail(Auth::User()->id); //Get role specified by id

    //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.Auth::User()->id,
            //'password'=>'min:6|confirmed',
            //'company_id' => 'required',

        ]);
        if ($request->input('password') != "") {
            $this->validate($request, [
           // 'name'=>'required|max:120',
            //'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'min:10|confirmed',
            //'company_id' => 'required',

        ]);
        }
        //$input = $request->only(['name', 'email', 'password', 'company_id']); //Retreive the name, email and password fields
        //$roles = $request['roles']; //Retreive all roles
        //$user = new User;
        $user -> name = $request->input('name');
        $user -> email = $request->input('email');
        if ($request->input('password') != "") {
            $user -> password = $request->input('password');
        }
        //$user -> company_id = $request->input('company_id');
        $user->save();
        //$user->fill($input)->save();
        return redirect()->route('users.profile')
            ->with('flash_message',
             'Alterção feita com sucesso.');
        }else{

        $user = User::findOrFail(Auth::User()->id); //Get user with specified id
        //dd($user->name);
        //dd(Auth::user()->roles()->get());
        //$companies = Company::all();

        return view('users.profile', compact('user')); //pass user and roles data to view
        }

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            //'password'=>'min:6|confirmed',
            'company_id' => 'required',

        ]);
        if ($request->input('password') != "") {
            $this->validate($request, [
           // 'name'=>'required|max:120',
            //'email'=>'required|email|unique:users,email,'.$id,
            'password'=>'min:10|confirmed',
            //'company_id' => 'required',

        ]);
        }
        //$input = $request->only(['name', 'email', 'password', 'company_id']); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles
        //$user = new User;
        $user -> name = $request->input('name');
        $user -> email = $request->input('email');
        if ($request->input('password') != "") {
            $user -> password = $request->input('password');
        }
        $user -> company_id = $request->input('company_id');
        $user->save();
        //$user->fill($input)->save();

        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
        }
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
    //Find a user with a given id and delete
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }
}
