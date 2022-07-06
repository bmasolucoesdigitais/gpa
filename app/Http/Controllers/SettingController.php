<?php

namespace App\Http\Controllers;

use App\Company;
use App\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settingsdoc;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //documentsAlerts

    public function documentsAlerts(Request $request)
    {
        $selected = Company::findOrFail(1);;
        if ($request->input('company')) {
            $selected = Company::findOrFail($request->input('company'));
            //dd($company);
            $documents = Document::where('fl_deleted', 0)->WhereIn('company_id', [1, $selected->id])->get();
        }else{
            $documents= '';
        }
        if(Auth::User()->can('master')){

            $companies = Company::where('fl_client', 1)->get();
           // dd($companies);
        }else{
            $companies = Company::
            whereIn('id', [
                Auth::User()->company_id,
                Company::where('headquarter', Auth::User()->company_id)->pluck('id')->toArray()
                ])->get();
            //dd($companies->pluck('id'));
        }
        return view('app.g3.settings.alerts_documents', compact('companies', 'selected', 'documents'));
    }


    public function documentsAlertsChange(Request $request){



        if (Settingsdoc::find($request->input('id')) || isset(Settingsdoc::where('document_id', $request->input('doc'))->where('company_id', $request->input('cp'))->first()->id)) {
            if (Settingsdoc::find($request->input('id'))){
                $setting = Settingsdoc::find($request->input('id'));
            }else{
                $setting =  Settingsdoc::where('document_id', $request->input('doc'))->where('company_id', $request->input('cp'))->first();
            }
           switch ($request->input('type')) {
               case 'fl_client':
                   if($setting->fl_client == 1){
                        $setting->fl_client = 0;
                        $setting->save();
                    }else{
                        $setting->fl_client = 1;
                        $setting->save();
                   }
                   return $setting->id.','.$setting->fl_client;
                   break;
               case 'fl_provider':
                   if($setting->fl_provider == 1){
                        $setting->fl_provider = 0;
                        $setting->save();
                    }else{
                        $setting->fl_provider = 1;
                        $setting->save();
                   }
                   return $setting->id.','.$setting->fl_provider;
                   break;


               default:
                  if($setting->fl_abaco == 1){
                        $setting->fl_abaco = 0;
                        $setting->save();
                    }else{
                        $setting->fl_abaco = 1;
                        $setting->save();
                   }
                   return $setting->id.','.$setting->fl_abaco;
                   break;
           }
        }else{
            $setting = new Settingsdoc;
            $setting->document_id = $request->input('doc');
            $setting->company_id = $request->input('cp');
            switch ($request->input('type')) {
                case 'fl_client':
                    if($setting->fl_client == 1){
                         $setting->fl_client = 0;
                         $setting->save();
                     }else{
                         $setting->fl_client = 1;
                         $setting->save();
                    }
                    return $setting->id.','.$setting->fl_client;
                    break;
                case 'fl_provider':
                    if($setting->fl_provider == 1){
                         $setting->fl_provider = 0;
                         $setting->save();
                     }else{
                         $setting->fl_provider = 1;
                         $setting->save();
                    }
                    return $setting->id.','.$setting->fl_provider;
                    break;


                default:
                   if($setting->fl_abaco == 1){
                         $setting->fl_abaco = 0;
                         $setting->save();
                     }else{
                         $setting->fl_abaco = 1;
                         $setting->save();
                    }
                    return $setting->id.','.$setting->fl_abaco;
                    break;
            }
        }

    }
    public function documentsAditionalSave(Request $request){



        if (isset(Settingsdoc::where('document_id', $request->input('doc'))->where('company_id', $request->input('cp'))->first()->id)) {

                $setting =  Settingsdoc::where('document_id', $request->input('doc'))->where('company_id', $request->input('cp'))->first();


            $setting->aditional_client = $request->input('client');
            $setting->aditional_abaco = $request->input('abaco');
            $setting->save();
            return 'true';

        }else{
            $setting = new Settingsdoc;
            $setting->document_id = $request->input('doc');
            $setting->company_id = $request->input('cp');
            $setting->aditional_client = $request->input('client');
            $setting->aditional_abaco = $request->input('abaco');
            $setting->save();
            return 'true';


        }

    }
}
