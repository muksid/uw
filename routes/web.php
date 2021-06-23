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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/home', 'HomeController@index')->name('home');

    // User Role
    Route::resource('admin/roles', 'RoleController');
    Route::get('admin/ora','RoleController@ora');

    // User
    Route::resource('admin/users','UserController');

    // User search
    Route::any('users-search','UserController@search')->name('users/search');

    // Department
    Route::resource('admin/departments','DepartmentController');

    Route::post('/get-department','DepartmentController@department');

    Route::post('/get-user-department','DepartmentController@userDepartment');

    Route::post('/postbranch','DepartmentController@subdep');

    // uw project begin
    Route::resource('uw/filials', 'FilialsController');
    Route::resource('uw/uw-users', 'UwUsersController');
    Route::resource('uw-clients', 'UwClientsController');
    Route::get('/uw/home', 'UwClientsController@home');
    Route::get('/uw-clients/create', 'UwClientsController@create');
    Route::get('/uw/client-katm/{id}/{claim_id}', 'UwClientsController@clientKatm')->name('uw-katm');
    #Route::post('/uw/create-client','UwClientsController@store');
    Route::post('/get-districts','UwClientsController@getDistricts');
    Route::post('/get-reg-districts','UwClientsController@getRegDistricts');
    #Route::get('/uw/get-client-katm/{cid}','UwClientsController@getClientKatm');
    #Route::get('/uw/get-client-inps/{cid}','UwClientsController@getClientInps');
    Route::post('uw-clients-edit','UwClientsController@storeEdit');
    Route::post('uw-risk-edit','UwClientsController@riskEdit');
    Route::post('/uw/cs-app-send', 'UwClientsController@csAppSend');
    Route::post('uw-client-files/upload', 'UwClientsController@fileUpload');
    Route::get('/uw/filePreView/{preViewFile}','UwClientsController@preViewPdf')->name('filePreView');
    Route::get('file-load/{file}','UwClientsController@downloadFile')->name('file-load');
    Route::get('/uw-client-file/delete/{id}', 'UwClientsController@destroyFile');
    Route::get('/uw/clients/{status}', 'UwClientsController@CsIndex');
    Route::any('/uw/loan-app/{status}','UwClientsController@riskAdminIndex');
    Route::any('/uw/all-clients','UwClientsController@allClients');
    Route::get('/uw/view-loan/{id}/{claim_id}', 'UwClientsController@riskAdminView');
    Route::get('/uw/view-loan-super-admin/{id}/{claim_id}', 'UwClientsController@superAdminView');
    Route::post('/uw/risk-admin-confirm', 'UwClientsController@riskAdminConfirm');
    Route::post('/uw/risk-admin-cancel', 'UwClientsController@riskAdminCancel');
    Route::get('/uw/loan-app-statistics', 'UwClientsController@loanAppStatistics');
    Route::post('/uw/calc-form','UwClientsController@calcForm');
    Route::get('/uw/get-app-blank/{claim_id}','UwClientsController@getAppBlank');
    Route::resource('uw-loan-types', 'UwLoanTypesController');
    Route::delete('uw/loan-types/{id}', 'UwLoanTypesController@destroy');

    Route::get('uw/testInsertKatm', 'UwInquiryIndividualController@getClientSalary');

    Route::get('/uw/get-loan-banks/{id}', 'UwLoanTypesController@getBanks');
    Route::post('/uw/store-loan-banks', 'UwLoanTypesController@storeBanks');

    Route::group(['prefix'=>'uw'], function(){
        Route::get('clients', 'UwCreateClientsController@index')->name('uw.create.clients.index');

        Route::get('get-loan-type', 'UwCreateClientsController@getLoanType')->name('uw.get.loan.type');

        Route::get('create-step-one/{id}', 'UwCreateClientsController@createStepOne')->name('uw.create.step.one');
        Route::post('create-step-one', 'UwCreateClientsController@postCreateStepOne')->name('uw.create.step.one.post');

        Route::get('create-step-two/{id}', 'UwCreateClientsController@createStepTwo')->name('uw.create.step.two');
        Route::post('create-step-two', 'UwCreateClientsController@postCreateStepTwo')->name('uw.create.step.two.post');

        Route::get('create-step-three/{id}', 'UwCreateClientsController@createStepThree')->name('uw.create.step.three');
        Route::post('create-step-three', 'UwCreateClientsController@postCreateStepThree')->name('uw.create.step.three.post');

        Route::get('create-step-result/{id}', 'UwCreateClientsController@createStepResult')->name('uw.create.step.result');

        Route::post('uw-online-registration', 'UwInquiryIndividualController@onlineRegistration')->name('uw.online.registration');
        Route::get('uw-get-result-buttons/{id}', 'UwInquiryIndividualController@getResultButtons')->name('uw.get-result-buttons');
        Route::get('get-client-res-k/{id}','UwInquiryIndividualController@getClientKatm');
        Route::get('get-client-res-i/{id}','UwInquiryIndividualController@getClientInps');
        Route::get('get-status-send/{id}/{sch_type}','UwInquiryIndividualController@getStatusSend');
        #Route::get('get-confirm-send/{id}','UwInquiryIndividualController@getConfirmSend');

        // GUAR
        Route::get('uw-get-client-guars/{id}', 'UwCreateClientsController@getClientGuars')->name('uw.get-client-guars');
        Route::post('create-client-guar', 'UwCreateClientsController@createClientGuar')->name('uw.create-client-guar');
        Route::get('edit-client-guar/{id}', 'UwCreateClientsController@editClientGuar');
        Route::get('delete-client-guar/{id}', 'UwCreateClientsController@deleteClientGuar');

        // FILE
        Route::get('uw-get-client-files/{id}', 'UwCreateClientsController@getClientFiles')->name('uw.get-client-files');
        Route::post('create-client-file', 'UwCreateClientsController@createClientFile')->name('uw.create-client-file');
        Route::get('delete-client-file/{id}', 'UwCreateClientsController@deleteClientFile');
    });
    // DEBTORS
    Route::resource('uw-debtors', 'UwClientDebtorsController');

    // Laravel log
    Route::get('/storage/log', 'HomeController@storageLog')->name('storage-log');

    Route::get('storage/{log}', function ($log)
    {
        $pathToFile = storage_path() . "/logs/". $log;
        return response()->file($pathToFile);
    });

    //Clear route cache:
    Route::get('/route-cache', function() {
        Artisan::call('route:cache');
        return 'Routes cache cleared';
    });

    //Clear config cache:
    Route::get('/config-cache', function() {
        Artisan::call('config:cache');
        return 'Config cache cleared';
    });

// Clear application cache:
    Route::get('/clear-cache', function() {
        Artisan::call('cache:clear');
        return 'Application cache cleared';
    });

    // Clear view cache:
    Route::get('/view-clear', function() {
        Artisan::call('view:clear');
        return 'View cache cleared';
    });

});

