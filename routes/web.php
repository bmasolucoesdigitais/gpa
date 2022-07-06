<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

##Route::get('', function () {
##    return view('welcome');
##});



Auth::routes();

Route::resource('users', 'UserController');
Route::get('implojas', 'TestController@implojas');
Route::get('users', 'UserController@index')->name('users.index');
Route::post('users/store', 'UserController@store')->name('users.store');
Route::post('users/update/{id}', 'UserController@update')->name('users.update');
Route::any('profile', 'UserController@profile')->name('users.profile');
Route::any('/test', 'PostController@edit');
//Route::get('roles', 'RoleController@index')->name('roles.index');
Route::resource('roles', 'RoleController');
Route::post('roles/store', 'RoleController@store')->name('roles.store');
Route::post('roles/update/{id}', 'RoleController@update')->name('roles.update');
Route::any('upload', 'UploadController@upload')->name('upload');


Route::resource('permissions', 'PermissionController');
Route::post('permissions/store', 'PermissionController@store')->name('permissions.store');
Route::post('permissions/update/{id}', 'PermissionController@update')->name('permissions.update');

Route::get('home', 'HomeController@index')->name('home');
Route::get('test', 'HomeController@test')->name('test');
//Route::get('adlogin/login', 'AdtestController@index')->name('auth');

Route::group(['prefix' => 'g3/api', 'middleware' => 'auth'], function () {
    Route::get('company/{id}/employees/', 'ApiController@companyEmployees')->name('api.companies.employees');


});

Route::group(['prefix' => 'adlogin'], function () {
    Route::get('login', 'AdtestController@index')->name('ad.login');
    Route::get('callback', 'AdtestController@callback')->name('ad.callback');
    Route::get('logoff', 'AdtestController@logoff')->name('ad.logoff');

});

Route::group(['prefix' => 'g3/clients', 'middleware' => 'auth'], function () {
    Route::get('', 'CompanyController@clients')->name('clients');
    Route::get('{id}/providers/', 'CompanyController@client')->name('clients.providers');
    Route::get('{id}/servicesscheduled', 'ScheduleserviceController@listclient')->name('clients.servicesscheduled.listcompany');
    Route::post('/providers/detach/', 'CompanyController@detachModal')->name('clients.providers.detach');
    Route::any('{id?}/providers/add', 'CompanyController@attach')->name('clients.providers.attach');
    Route::any('{id}/savemail', 'CompanyController@savemail')->name('clients.providers.savemail');
    Route::get('{id}/outsourceds/', 'CompanyController@outsourceds')->name('clients.outsourceds');
    Route::any('{id}/outsourceds/add', 'CompanyController@outsourcedsAdd')->name('clients.outsourceds.add');
    Route::get('{id}/documents', 'CompanyController@documents')->name('clients.documents');
    Route::any('{id}/documents/attach', 'CompanyController@documentsAttach')->name('clients.documents.attach');
    Route::any('{id}/employees/{eid}/documents', 'EmployeeController@clientDocuments')->name('clients.employees.documents');
    Route::any('{cid}/employee/{eid}/documents/{did}/delivereds/add', 'EmployeeController@documentsDeliveredsAdd')->name('clients.employees.documents.delivereds.add');
    Route::any('{cid}/employee/{eid}/documents/delivereds/{did}/edit', 'EmployeeController@documentsDeliveredsEdit')->name('clients.employees.documents.delivereds.edit');
    Route::any('{cid}/employee/{eid}/documents/{did}/fileupload', 'EmployeeController@fileUpload')->name('clients.employees.documents.fileupload');
    Route::any('{cid}/employees/{eid}/documents/attach', 'EmployeeController@documentsAttach')->name('clients.employees.documents.attach');


});

Route::any('/prova/{token}/{cpf?}/', 'TestController@test')->name('tests.test');

Route::group(['prefix' => 'g3/tests', 'middleware' => 'auth'], function () {
    Route::get('', 'TestController@index')->name('tests');
    //Route::get('test/{token}/{cpf?}', 'TestController@test')->name('tests.test');
    Route::any('edit/{id}', 'TestController@edit')->name('tests.edit');
    Route::any('add/', 'TestController@add')->name('tests.add');
    Route::post('delete', 'TestController@delete')->name('tests.delete');
    Route::get('{id}/questions', 'TestController@questions')->name('tests.questions');
    Route::any('{id}/questions/add', 'TestController@questionsInsert')->name('tests.questions.add');
    Route::post('/questions/delete', 'TestController@questionsDelete')->name('tests.questions.delete');
    Route::any('{id}/questions/{qid}/edit', 'TestController@questionsEdit')->name('tests.questions.edit');


});

Route::group(['prefix' => 'g3/companies', 'middleware' => 'auth'], function () {
    Route::get('', 'CompanyController@index')->name('companies');
    Route::get('index', 'CompanyController@index')->name('companies.index');
    Route::any('add/{cnpj?}', 'CompanyController@insert')->name('companies.add');
    Route::post('create', 'CompanyController@create')->name('companies.create');
    Route::any('edit/{id}', 'CompanyController@edit')->name('companies.edit');
    Route::post('update/{id}', 'CompanyController@update')->name('companies.update');
    Route::get('delete/{id}', 'CompanyController@delete')->name('companies.delete');
    Route::post('destroy', 'CompanyController@destroy')->name('companies.destroy');
    Route::get('{id}/employees', 'CompanyController@employees')->name('companies.employees');
    Route::any('{id}/employees/create', 'CompanyController@employeesCreate')->name('companies.employees.create');
    Route::any('{id}/employees/add', 'CompanyController@employeesAdd')->name('companies.employees.add');
    Route::get('{id}/employees/delete/{employees_id}', 'CompanyController@employeesRemove')->name('companies.employees.delete');
    Route::get('{id}/outsourceds', 'CompanyController@outsourceds')->name('companies.outsourceds');
    Route::any('{id}/outsourceds/add', 'CompanyController@outsourcedsAdd')->name('companies.outsourceds.add');
    Route::get('{id}/outsourceds/delete/{employees_id}', 'CompanyController@outsourcedsRemove')->name('companies.outsourceds.delete');
    Route::get('client/{id}', 'CompanyController@client')->name('companies.clients');
    Route::any('client/attach/{id}', 'CompanyController@attach')->name('companies.clients.attach');
    Route::any('attach/', 'CompanyController@attach')->name('companies.attach');
    Route::get('detach/{cp}/{id}', 'CompanyController@detach')->name('companies.detach');
    Route::get('{id}/documents/', 'CompanyController@documents')->name('companies.documents');
    Route::any('{id}/documents/attach', 'CompanyController@documentsAttach')->name('companies.documents.attach');
    Route::any('{cid}/documents/{did}/delivereds/add', 'CompanyController@deliveredsAdd')->name('companies.documents.delivereds.add');
    Route::any('{cid}/documents/delivereds/{did}/edit', 'CompanyController@deliveredsEdit')->name('companies.documents.delivereds.edit');
    Route::post('documents/detach', 'CompanyController@documentsDetach')->name('companies.documents.detach');
    Route::post('companies/documents/filedelete', 'CompanyController@fileDelete')->name('companies.documents.filedelete');
    Route::any('{cid}/documents/{did}/fileupload', 'CompanyController@fileUpload')->name('companies.documents.fileupload');
    //Route::any('{cid}/servicesscheduled', 'ScheduleserviceController@index')->name('companies.servicesscheduled');
    Route::any('{cid}/servicesscheduled/add', 'ScheduleserviceController@insert')->name('companies.servicesscheduled.insert');
    Route::any('servicesscheduled/{sid}/edit', 'ScheduleserviceController@edit')->name('companies.servicesscheduled.edit');
    Route::post('/servicesscheduled/delete', 'ScheduleserviceController@delete')->name('companies.servicesscheduled.delete');
    Route::get('/servicesscheduled', 'ScheduleserviceController@list')->name('companies.servicesscheduled.list');
    Route::get('/servicesscheduled/aprove', 'ScheduleserviceController@listAprove')->name('companies.servicesscheduled.listaprove');
    Route::get('{id}/servicesscheduled', 'ScheduleserviceController@listcompany')->name('companies.servicesscheduled.listcompany');
    Route::any('/servicesscheduled/add', 'ScheduleserviceController@insert')->name('companies.servicesscheduled.insert');
    Route::any('/servicesscheduled/{id}/employeesstatus', 'ScheduleserviceController@employeesStatus')->name('companies.servicesscheduled.employeesstatus');
    Route::any('/servicesscheduled/changeaprovation', 'ScheduleserviceController@changeAprovation')->name('companies.servicesscheduled.changeaprovation');
    Route::get('/servicesscheduled/changetechaprovation/{id}', 'ScheduleserviceController@changeTechAprovation')->name('companies.servicesscheduled.changetechaprovation');
    Route::any('/servicesscheduled/{id}/aprupload', 'ScheduleserviceController@aprUpload')->name('companies.servicesscheduled.aprupload');

    Route::get('/servicesscheduled/apr/{id}/chnagestatus/{status}', 'ScheduleserviceController@aprChangeAprovation')->name('companies.servicesscheduled.apr.changeaprovation');


    Route::any('/servicesscheduled/{sid}/apr', 'ScheduleserviceController@aprCreate')->name('companies.servicesscheduled.aprcreate');
    Route::any('/servicesscheduled/{sid}/aprPrint', 'ScheduleserviceController@aprPrint')->name('companies.servicesscheduled.aprprint');

    Route::post('/emailupdate', 'CompanyController@emailupdate')->name('companies.emailupdate');
    //branches
    /*
    Route::get('{ow}/branches', 'CompanyController@branches')->name('companies.branches');
    Route::get('{ow}/branches/add/{cnpj?}', 'CompanyController@insert')->name('branches.add');
    Route::get('{ow}/branches/edit/{id}', 'CompanyController@edit')->name('companies.branches.edit');
    Route::get('{ow}/branches/{id}/outsourceds', 'CompanyController@outsourceds')->name('companies.branches.outsourceds');
    Route::any('{ow}/branches/{id}/outsourceds/add', 'CompanyController@outsourcedsAdd')->name('companies.branches.outsourceds.add');
    Route::get('{ow}/branches/clients/{id}', 'CompanyController@client')->name('companies.branches.clients');
    Route::any('{ow}/branches/client/attach/{id}', 'CompanyController@attach')->name('companies.branches.clients.attach');
    Route::any('{ow}/branches/attach/', 'CompanyController@attach')->name('companies.branches.attach');
    Route::get('{ow}/branches/detach/{cp}/{id}', 'CompanyController@detach')->name('companies.branches.detach');
    */


});
Route::group(['prefix' => 'g3/provider', 'middleware' => 'auth'], function () {
    Route::get('', 'ProviderController@index')->name('companies');
    Route::any('/documents', 'CompanyController@documents')->name('provider.documents');
    Route::any('/trainingreserve', 'CompanyController@trainingReserve')->name('provider.trainingreserve');
    Route::any('/trainingreserve/attach/{id}', 'CompanyController@trainingReserveAttach')->name('provider.trainingreserve.attach');
    Route::post('/trainingreserve/detach', 'CompanyController@trainingReserveDetach')->name('provider.trainingreserve.detach');
    Route::any('/trainingreserve/employees/{id}', 'CompanyController@trainingReserveEmployees')->name('provider.trainingreserve.employees');

});


Route::group(['prefix' => 'g3/branches', 'middleware' => 'auth'], function () {
    Route::any('/', 'CompanyController@branches')->name('branches');
    //Route::get('{id?}/branches', 'CompanyController@branches')->name('branches');
    Route::any('add/{cnpj?}', 'CompanyController@insert')->name('branches.add');
    Route::any('edit/{id}', 'CompanyController@edit')->name('branches.edit');
    Route::get('{brc}/outsourceds', 'EmployeeController@index')->name('branches.outsourceds');
    Route::get('employee/{id}/{brc}', 'EmployeeController@employee')->name('branches.outsourceds.outsourced');
    Route::any('{brc}/outsourceds/attach', 'EmployeeController@attach')->name('branches.outsourceds.attach');
    Route::any('{brc}/outsourceds/insert', 'EmployeeController@insert')->name('branches.outsourceds.insert');
    Route::any('outsourceds/{id}/documents/{brc}/', 'EmployeeController@documents')->name('branches.outsourceds.documents');
    Route::any('outsourceds/{id}/documents/{brc}/attach/', 'EmployeeController@documentsAttach')->name('branches.outsourceds.documents.attach');
    Route::any('{cid}/outsourceds/{eid}/documents/{did}/delivereds/{brc}/add', 'EmployeeController@documentsDeliveredsAdd')->name('branches.outsourceds.employees.documents.delivereds.add');
    Route::any('outsourceds/{cid}/documents/{did}/fileupload/{brc}', 'EmployeeController@fileUpload')->name('branches.outsourceds.documents.fileupload');
    Route::any('outsourceds/{cid}/documents/delivereds/{did}/edit/{brc}', 'EmployeeController@documentsDeliveredsEdit')->name('branches.outsourceds.documents.delivereds.edit');
    Route::any('/outsourceds/{id}/services/{brc}', 'EmployeeController@services')->name('branches.outsourceds.services');
    Route::any('/outsourceds/{id}/services/{sid}/delivereds/{brc}', 'EmployeeController@delivereds')->name('branches.outsourceds.services.delivereds');
    Route::any('/outsourceds/{id}/services/{brc}/attach', 'EmployeeController@servicesAdd')->name('branches.outsourceds.services.attach');
    Route::any('/outsourceds/services/{eid}/documents/{did}/add/{sid}/{brc}', 'EmployeeController@deliveredsAdd')->name('branches.outsourceds.services.delivereds.add');
    Route::any('outsourceds/{eid}/services/{sid}/delivereds/{did}/{brc}', 'EmployeeController@deliveredsUpload')->name('branches.outsourceds.services.delivereds.upload');
    Route::any('outsourceds/{eid}/services/{sid}/delivereds/{did}/edit/{brc}', 'EmployeeController@deliveredsEdit')->name('branches.outsourceds.services.delivereds.edit');
    Route::any('{brc}/outsourceds/delete/{emp}', 'CompanyController@outsourcedsRemove')->name('branches.outsourceds.delete');
    Route::get('{brc}/clients/', 'CompanyController@client')->name('branches.clients');
    Route::get('clients/{cli}/employees/{brc}/', 'CompanyController@employees')->name('branches.clients.employees');
    Route::any('{id}/employees/{brc}/create', 'CompanyController@employeesCreate')->name('branches.clients.employees.create');
    Route::any('{id}/employees/{brc}/add', 'CompanyController@employeesAdd')->name('branches.clients.employees.add');
    Route::get('{id}/employees/{employees_id}/delete/{brc}', 'CompanyController@employeesRemove')->name('branches.clients.employees.delete');
    Route::any('client/attach/{id}', 'CompanyController@attach')->name('branches.clients.attach');
    Route::any('client/add/{cnpj?}/{brc}', 'CompanyController@insert')->name('branches.clients.add');
    Route::get('detach/{cp}/{id}/{brc}', 'CompanyController@detach')->name('branches.clients.detach');
    Route::get('{id}/documents/{brc}', 'CompanyController@documents')->name('branches.clients.documents');
    Route::any('{id}/documents/attach/{brc}', 'CompanyController@documentsAttach')->name('branhes.clients.documents.attach');
    Route::any('{cid}/documents/{did}/delivereds/add/{brc}', 'CompanyController@deliveredsAdd')->name('branches.clients.documents.delivereds.add');
    Route::any('{cid}/documents/{did}/fileupload/{brc}', 'CompanyController@fileUpload')->name('branches.clients.documents.fileupload');
    Route::any('{cid}/documents/delivereds/{did}/edit/{brc}', 'CompanyController@deliveredsEdit')->name('branches.clients.documents.delivereds.edit');
    Route::any('attach/', 'CompanyController@attach')->name('branches.attach');
    Route::get('detach/{id}', 'CompanyController@branchDetach')->name('branches.detach');
    Route::get('{cp}/outsourced/{ep}/acitvate', 'CompanyController@outourcedActivate')->name('company.outsourceds.activate');




    //Route::any('/', 'CompanyController@branches')->name('branches');
    //    Route::any('/{id}/outsourceds', 'CompanyController@branchesOutsourceds')->name('companies.branches.outsourceds');
    //Route::any('/{id?}/clients', 'CompanyController@branchesClient')->name('companies.branches.client');
    //    Route::any('/{id}/edit', 'CompanyController@branchesEdit')->name('companies.branches.edit');
    //    Route::any('/{id}/clients/{cid}/documents', 'CompanyController@branchesClientDocuments')->name('companies.branches.client.documents');
    //    Route::any('/{id}/clients/{cid}/documents/attach', 'CompanyController@branchesClientDocumentsAttach')->name('companies.branches.client.documents.attach');
    //   Route::any('/{id}/clients/{cid}/documents/{did}/delivereds/add', 'CompanyController@branchesClientDocumentsDeliveredsAdd')->name('companies.branches.client.documents.delivereds.add');
    //   Route::any('/{id}/clients/{cid}/employees', 'CompanyController@branchesClientEmployees')->name('companies.branches.client.employees');
    //   Route::any('/{id}/client/{cid}/edit', 'CompanyController@branchesClientEdit')->name('companies.branches.client.edit');
    //   Route::any('/{id}/client/{cid}/detach', 'CompanyController@branchesClientDetach')->name('companies.branches.client.detach');
    //   Route::any('/{id}/client/{cid}/edit', 'CompanyController@branchesClientEdit')->name('companies.branches.client.edit');
});

Route::group(['prefix' => 'g3/delivereds', 'middleware' => 'auth'], function () {
    Route::get('', 'DeliveredController@index');
    Route::get('index', 'DeliveredController@index')->name('delivereds.index');
    Route::get('add', 'DeliveredController@insert')->name('delivereds.add');
    Route::post('create', 'DeliveredController@create')->name('delivereds.create');
    Route::get('edit/{id}', 'DeliveredController@edit')->name('delivereds.edit');
    Route::post('update/{id}', 'DeliveredController@update')->name('delivereds.update');
    Route::get('delete/{id}', 'DeliveredController@delete')->name('delivereds.delete');
    Route::post('destroy', 'DeliveredController@destroy')->name('delivereds.destroy');
});

Route::group(['prefix' => 'g3/trainingschedule', 'middleware' => 'auth'], function () {
    Route::get('', 'TrainingscheduleController@index')->name('trainingschedule');
    Route::get('', 'TrainingscheduleController@index')->name('trainingschedule.index');
    Route::get('employees/{id}', 'TrainingscheduleController@employees')->name('trainingschedule.employees');
    Route::any('changepresence', 'TrainingscheduleController@employeesChangePresence')->name('trainingschedule.employees.changepresence');
    Route::any('add', 'TrainingscheduleController@create')->name('trainingschedule.add');
    Route::any('edit/{id}', 'TrainingscheduleController@edit')->name('trainingschedule.edit');
    Route::post('delete', 'TrainingscheduleController@delete')->name('trainingschedule.delete');
    Route::post('changeaccomplished', 'TrainingscheduleController@changeAccomplished')->name('trainingschedule.changeaccomplished');
});

Route::group(['prefix' => 'g3/documents', 'middleware' => 'auth'], function () {
    Route::get('', 'DocumentController@index')->name('documents');
    Route::get('index', 'DocumentController@index')->name('documents.index');
    Route::get('add', 'DocumentController@insert')->name('documents.add');
    Route::post('create', 'DocumentController@create')->name('documents.create');
    Route::get('edit/{id}', 'DocumentController@edit')->name('documents.edit');
    Route::post('update/{id}', 'DocumentController@update')->name('documents.update');
    Route::get('delete/{id}', 'DocumentController@delete')->name('documents.delete');
    Route::post('destroy', 'DocumentController@destroy')->name('documents.destroy');
});



Route::group(['prefix' => 'g3/employees', 'middleware' => 'auth'], function () {
    Route::get('', 'EmployeeController@index')->name('employees');
    Route::get('index/{brc?}', 'EmployeeController@index')->name('employees.index');
    Route::get('outsourceds', 'EmployeeController@outsourceds')->name('employees.outsourceds');
    Route::get('add', 'EmployeeController@insert')->name('employees.add');
    Route::post('create', 'EmployeeController@create')->name('employees.create');
    Route::get('edit/{id}', 'EmployeeController@edit')->name('employees.edit');
    Route::any('allow/{id}', 'EmployeeController@allow')->name('employees.allow');
    Route::post('update/{id}', 'EmployeeController@update')->name('employees.update');
    Route::get('delete/{id}', 'EmployeeController@delete')->name('employees.delete');
    Route::post('destroy', 'EmployeeController@destroy')->name('employees.destroy');
    Route::get('test', 'EmployeeController@test')->name('employees.test');
    Route::get('{id}/services/', 'EmployeeController@services')->name('employees.services');
    Route::any('/{id}/services/add/', 'EmployeeController@servicesAdd')->name('employees.services.add');
    Route::any('/services/detach', 'EmployeeController@servicesDetach')->name('employees.services.detach');
    Route::any('attach', 'EmployeeController@attach')->name('employees.attach');
    Route::get('{cp}/detach/{id}', 'EmployeeController@detach')->name('employees.detach');
    Route::get('{cp}/reattach/{id}', 'EmployeeController@reattach')->name('employees.reattach');
    Route::get('employee/{id}', 'EmployeeController@employee')->name('employees.employee');
    Route::get('{eid}/services/{sid}/delivereds', 'EmployeeController@delivereds')->name('employees.delivereds');
    Route::any('/{eid}/services/{sid}/delivereds/{did}/edit', 'EmployeeController@deliveredsEdit')->name('employees.delivereds.edit');
    Route::any('/{eid}/documents/{did}/add/{sid}', 'EmployeeController@deliveredsAdd')->name('employees.delivereds.add');
    Route::any('/{eid}/services/{sid}/delivereds/{did}', 'EmployeeController@deliveredsUpload')->name('employees.delivereds.upload');
    Route::post('outsourced/filedelete', 'EmployeeController@fileDelete')->name('employees.outsourced.filedelete');
    Route::get('{id}/documents/', 'EmployeeController@documents')->name('employees.documents');
    Route::get('{id}/documents_services/', 'EmployeeController@documentsServices')->name('employees.documents_services');
    //Route::any('{eid}/documents/attach', 'EmployeeController@documentsAttach')->name('employees.documents.attach');
    Route::any('{eid}/documents/attach', 'EmployeeController@docAttach')->name('employees.documents.attach');
    //Route::any('{cid}/documents/{did}/delivereds/add', 'EmployeeController@documentsDeliveredsAdd')->name('employees.documents.delivereds.add');
    Route::any('{cid}/documents/{did}/delivereds/add', 'EmployeeController@docDeliveredsAdd')->name('employees.documents.delivereds.add');
    //Route::any('{cid}/documents/delivereds/{did}/edit', 'EmployeeController@documentsDeliveredsEdit')->name('employees.documents.delivereds.edit');
    Route::any('{cid}/documents/delivereds/{did}/edit', 'EmployeeController@docDeliveredsEdit')->name('employees.documents.delivereds.edit');
    Route::post('documents/detach', 'EmployeeController@documentsDetach')->name('employees.documents.detach');
    Route::post('documents/filedelete', 'EmployeeController@fileDelete')->name('employees.documents.filedelete');
    Route::any('{eid}/documents/{did}/fileupload', 'EmployeeController@fileUploademp')->name('employees.documents.fileuploademp');
});

Route::group(['prefix' => 'g3/services', 'middleware' => 'auth'], function () {
    Route::get('', 'ServiceController@index')->name('services');
    Route::get('index', 'ServiceController@index')->name('services.index');
    Route::get('add', 'ServiceController@insert')->name('services.add');
    Route::post('create', 'ServiceController@create')->name('services.create');
    Route::get('edit/{id}', 'ServiceController@edit')->name('services.edit');
    Route::post('update/{id}', 'ServiceController@update')->name('services.update');
    Route::get('delete/{id}', 'ServiceController@delete')->name('services.delete');
    Route::post('destroy', 'ServiceController@destroy')->name('services.destroy');
    Route::get('{id}/documents/', 'ServiceController@documents')->name('services.documents');
    Route::any('/{id}/documents/add/', 'ServiceController@documentsAdd')->name('services.documents.add');
});

Route::group(['prefix' => 'g3/settings', 'middleware' => 'auth'], function () {
    Route::any('documents_alerts', 'SettingController@documentsAlerts')->name('settings.documents_alerts');
    Route::post('documents_alerts_change', 'SettingController@documentsAlertsChange')->name('settings.documents_alerts_change');
    Route::post('documents_aditional_save', 'SettingController@documentsAditionalSave')->name('settings.documents_aditional_save');
});

Route::group(['prefix' => 'alerts'], function () {
    Route::get('', 'AlertsController@index')->name('alerts');
    Route::get('index', 'AlertsController@index')->name('alerts.index');
    Route::get('remember', 'AlertsController@remember')->name('alerts.remember');
    Route::get('send', 'AlertsController@send')->name('alerts.send');
    Route::get('activeds', 'AlertsController@activedOutsourceds')->name('alerts.activeds');
    Route::get('generateusers', 'HomeController@genusers')->name('alerts.generate.users');
    Route::get('remember/services', 'AlertsController@rememberServices')->name('alerts.remember.services');

});

Route::group(['prefix' => 'routines'], function () {
    Route::any('check_actives', 'EmployeeController@checkActive')->name('routines.actives');
    Route::any('check_services', 'ScheduleserviceController@checkAproved')->name('routines.check.services');
});

Route::group(['prefix' => 'g3/reports', 'middleware' => 'auth'], function () {
    Route::get('expired', 'ReportController@expireds')->name('expired');
    Route::get('outsourceds_documents', 'ReportController@outsourcedsDocuments')->name('outsourceds_documents');
    Route::any('outsourceds_documentsv2', 'ReportController@outsourcedsDocumentsv2')->name('outsourceds_documentsv2');
    Route::get('companies_documents', 'ReportController@companiesDocuments')->name('outsourceds_documents');
    Route::get('aprove', 'ReportController@aprove')->name('outsourceds.aprove');
    Route::get('maillog', 'ReportController@maillog')->name('reports.maillog.aprove');
    Route::get('test', 'ReportController@test')->name('reports.aprove');
});

Route::get('home', 'HomeController@index')->name('home');
Route::any('test', 'HomeController@test')->name('test');
Route::get('', 'HomeController@index')->name('home');
