<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\Delivered;
use App\Employee;
use App\Mailog;
use Auth;

class ReportController extends Controller
{
    public function expireds (){
        //dd(Auth::User()->company->branches->pluck('id')->toArray());
        if(Auth::User()->can('master')){
            $companies = Company::where('fl_client', 1)->get();
            //dd($companies);
        }else{
            if (count(Auth::User()->company->branches)) {
                //echo('HaveBranches');
                $companies = Company::whereIn('id',[Auth::User()->company_id, Auth::User()->company->branches->pluck('id')->toArray()])->get();
            }else{
                //echo('do not have branches');
                $companies = Company::whereIn('id',[Auth::User()->company_id])->get();
            }
        }
            //dd($companies);
            //$delivereds = Delivered::limit(5)->get();
        //echo('Foi');
        return view('app.g3.reports.expired', compact('companies'));
    }
    public function test (){
        echo 'test';
        $employee = Company::find(3)->outsourceds->find(2279)->services->find(19)->documents->find(32)->delivereds()->where('employee_id', 2279)->orderBy('id', 'desc')->take(1)->get();
        dd($employee);
    }

    public function aprove (){
        //dd(Auth::User()->company->branches->pluck('id')->toArray());
        $delivereds = Delivered::where('status', 2)->where('fl_deleted', 0)->orderBy('created_at')->get();
        $companies = Delivered::all()->where('status', 2)->where('employee_id', null)->where('fl_deleted', 0);
        $employees = Delivered::all()->where('status', 2)->whereNotIn('employee_id', [null])->where('fl_deleted', 0);
        //$delivered = Delivered::find(14534)->employee->services->find(13)->documents->find(13);
        //dd($delivered);
        return view('app.g3.reports.aprove', compact('delivereds'));

    }

    public function outsourcedsDocuments (){
        if(Auth::User()->can('master')){
            $companies = Company::where('fl_client', 0)->get();
            //dd($companies);
        }else{
            if (count(Auth::User()->company->branches)) {
                //echo('HaveBranches');
                $companies = Company::whereIn('id',[Auth::User()->company_id, Auth::User()->company->branches->pluck('id')->toArray()])->get();
            }else{
                //echo('do not have branches');
                $companies = Company::whereIn('id',[Auth::User()->company_id])->get();
            }
        }
            //dd($companies);
            //$delivereds = Delivered::limit(5)->get();
        //echo('Foi');
        return view('app.g3.reports.outsourceds_documents', compact('companies'));
    }

    public function outsourcedsDocumentsV2 (Request $request){
        if(Auth::User()->can('master')){
            $companies = Company::where('fl_client', 1)->get();
            //dd($companies);
        }else{
            if (count(Auth::User()->company->branches)) {
                //echo('HaveBranches');
                $companies = Company::whereIn('id',[Auth::User()->company_id, Auth::User()->company->branches->pluck('id')->toArray()])->get();
            }else{
                //echo('do not have branches');
                $companies = Company::whereIn('id',[Auth::User()->company_id])->get();
            }
        }
        $company_id = 0;
        $provider_id = 0;
        $cp = 0;
        if($request->input('company_id')){
            $company_id = $request->input('company_id');
            $cp = Company::findOrFail($company_id);
            if($request->input('provider_id')){
                $provider_id = $request->input('provider_id');
                $provider = Company::findOrFail($provider_id);
                $outsourceds = $provider->employees()->where('fl_deleted', 0)->whereIn('id',$cp->outsourceds->where('fl_deleted', 0)->pluck('id')->toArray())->orderBy('name', 'asc')->get();
                //dd($outsourceds);
            }
            $providers = $cp->clients()->where('fl_deleted', 0)->orderBy('name', 'asc')->get();
            //die($company_id);
            return view('app.g3.reports.outsourceds_documentsv2', compact('companies', 'company_id', 'provider_id', 'providers', 'outsourceds', 'cp'));
        }
            //dd($companies);
            //$delivereds = Delivered::limit(5)->get();
        //echo('Foi');
        return view('app.g3.reports.outsourceds_documentsv2', compact('companies', 'company_id'));
    }
    
    public function companiesDocuments (){
        if(Auth::User()->can('master')){
            $companies = Company::where('fl_client', 1)->get();
            //dd($companies);
        }else{
            if (count(Auth::User()->company->branches)) {
                //echo('HaveBranches');
                $companies = Company::whereIn('id',[Auth::User()->company_id, Auth::User()->company->branches->pluck('id')->toArray()])->get();
            }else{
                //echo('do not have branches');
                $companies = Company::whereIn('id',[Auth::User()->company_id])->get();
            }
        }
        //dd($companies);
        //$delivereds = Delivered::limit(5)->get();
        //echo('Foi');
        return view('app.g3.reports.companies_documents', compact('companies'));
    }

    public function maillog(){
        // phpinfo();
        $mails = Mailog::get();
        // dd($mails);
        return view('app.g3.reports.maillog', compact('mails'));
    }
}
