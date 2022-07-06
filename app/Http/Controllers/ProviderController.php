<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use Auth;

class ProviderController extends Controller
{
    public function index (Company $company){


		$employees = $company->find(Auth::user()->company_id)->employees;
        //dd($employees);

		return view('app.g3.providers.index', compact('employees'));

	}
}
