<?php

namespace App\Http\Controllers;

use App\Company;
use App\Employee;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $company;
    public function companyEmployees($id){
    $this->company =  Company::findOrFail($id);
        $employees = $this->company->employees()->where('fl_deleted', 0)->get()->toJson(JSON_PRETTY_PRINT);
        return response($employees, 200);;
    }
}
