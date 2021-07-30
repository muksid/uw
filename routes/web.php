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

    // New User Position
    // User
    Route::get('insertUsers','UserController@getTest');

    Route::resource('admin/users','UserController');
    Route::any('admin/users-search','UserController@index');

    Route::get('admin/users-username-check/{username}','UserController@usernameCheck');
    Route::get('admin/users-get-branch/{id}','UserController@getBranch');
    Route::post('admin/users-update','UserController@updateUser');
    // Work Users
    Route::resource('admin/work-users','MWorkUsersController');
    Route::get('admin/work-users-tab_num-check/{tab_num}','MWorkUsersController@tab_numCheck');
    // User get history
    Route::get('admin/work-users/get-roles/{id}','MWorkUsersController@getRoles');
    Route::get('admin/work-users/get-history/{id}','MWorkUsersController@getHistory');
    Route::get('admin/work-users/get-history-roles/{id}','MWorkUsersController@getHistoryRoles');
    Route::get('admin/work-users/activate-user/{id}','MWorkUsersController@activateUser');
    // Admin Core Menu Controller
    Route::resource('admin/menus','MCoreMenusController');
    Route::any('admin/menus-search', 'MCoreMenusController@search')->name('admin-core-search');
    // Admin Menu Roles Controller
    Route::resource('admin/menu_roles','MMenuRolesController');
    Route::any('admin/menus_roles-search', 'MMenuRolesController@search')->name('admin-menu_roles-search');
    // Admin Role Menus Controller
    Route::resource('admin/role_menus','MRoleMenusController');
    Route::any('admin/role_menus-search', 'MRoleMenusController@search')->name('admin-role_menus-search');
    // Personal users Controller
    Route::resource('admin/personal-users','MPersonalUsersController');
    Route::any('admin/personal-users-search', 'MPersonalUsersController@search')->name('admin-personal-users-search');
    Route::get('admin/personal-users-email-check/{email}','MPersonalUsersController@emailCheck');

    Route::get('admin/role_menus/user_role/{id}','MRoleMenusController@getParent');
    // End New User Position

    // User Role
    Route::resource('admin/roles', 'RoleController');
    Route::get('admin/ora','RoleController@ora');

    // User
    //Route::resource('admin/users','UserController');

    // User search
    //Route::get('admin/users-search','UserController@search');

    // Department
    Route::resource('departments','DepartmentController');

    Route::post('/get-department','DepartmentController@department');

    Route::post('/get-user-department','DepartmentController@userDepartment');

    Route::post('/postbranch','DepartmentController@subdep');

    // uw project begin
    #Route::post('/uw/create-client','UwClientsController@store');
    #Route::get('/uw/get-client-katm/{cid}','UwClientsController@getClientKatm');
    #Route::get('/uw/get-client-inps/{cid}','UwClientsController@getClientInps');
    Route::resource('uw/filials', 'FilialsController');
    Route::resource('uw/uw-users', 'UwUsersController');
    Route::resource('uw-clients', 'UwClientsController');
    Route::get('/uw/home', 'UwClientsController@home');
    Route::get('/uw-clients/create', 'UwClientsController@create');
    Route::get('/uw/client-katm/{id}/{claim_id}', 'UwClientsController@clientKatm')->name('uw-katm');
    Route::post('/get-districts','UwClientsController@getDistricts');
    Route::post('/get-reg-districts','UwClientsController@getRegDistricts');
    Route::post('uw-clients-edit','UwClientsController@storeEdit');
    Route::post('uw-risk-edit','UwClientsController@riskEdit');
    Route::post('/uw/cs-app-send', 'UwClientsController@csAppSend');
    Route::post('uw-client-files/upload', 'UwClientsController@fileUpload');
    Route::get('/uw/filePreView/{preViewFile}','UwClientsController@preViewPdf')->name('filePreView');
    Route::get('file-load/{file}','UwClientsController@downloadFile')->name('file-load');
    Route::get('/uw-client-file/delete/{id}', 'UwClientsController@destroyFile');
    Route::get('/uw/clients/{status}', 'UwClientsController@CsIndex');
    Route::any('/uw/loan-app/{status}','UwClientsController@riskAdminIndex');
    Route::any('phy/all-clients','UwClientsController@allClients');
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
        Route::get('get-client-res-k','UwInquiryIndividualController@getClientKatm');
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

    // JURIDICAL
    Route::group(['prefix'=>'jur'], function(){
        Route::resource('client','UwJuridicalClientsController');
        Route::any('clients/{q}','UwJuridicalClientsController@getClients');
        Route::any('uw-clients/{q}','UwJuridicalClientsController@getUwClients');
        Route::any('all-clients','UwJuridicalClientsController@getAllClients');
        Route::get('ora-search','UwJuridicalClientsController@getOraSearch');
        Route::get('view-form/{q}','UwJuridicalClientsController@getOraData');
        Route::post('guar-store','UwJuridicalClientsController@guarStore');
        Route::get('guar-delete/{id}','UwJuridicalClientsController@guarDelete');
        Route::post('files-store','UwJuridicalClientsController@filesStore');
        Route::get('file-download/{id}','UwJuridicalClientsController@fileDownload');
        Route::get('file-delete/{id}','UwJuridicalClientsController@fileDelete');
        Route::get('send-to-admin','UwJuridicalClientsController@sendToAdmin');

        Route::get('uw/client-view/{q}','UwJuridicalClientsController@uwShow');
        Route::post('agr-confirm', 'UwJuridicalClientsController@agrConfirm');
        Route::post('agr-cancel', 'UwJuridicalClientsController@agrCancel');

        Route::get('online-reg','UwInquiryEntityController@onlineRegistration');
        Route::get('get-scoring-kias','UwInquiryEntityController@creditReportScoring');
        Route::get('get-balance-form','UwInquiryEntityController@getBalanceForm');

        Route::get('get-kias-modal','UwJuridicalClientsController@getKiasModal');
        Route::get('get-balance-modal','UwJurBalanceFormsController@getBalanceModal');
        Route::get('get-jur-saldo','UwJuridicalClientsController@getOraSaldo');
        Route::get('get-jur-k2','UwJuridicalClientsController@getOraK2');
        Route::get('get-jur-leads','UwJuridicalClientsController@getOraLeads');

        Route::get('get-select','UwJuridicalClientsController@getSelect');


        Route::resource('client-personal','UwJurClientPersonalController');

        Route::get('get-hr_emps','UwJuridicalClientsController@getHrEmps');

    });

    // DEBTORS
    Route::resource('uw-debtors', 'UwClientDebtorsController');
    Route::get('get-uw-debtor/{id}', 'UwClientDebtorsController@onlineDebtorsRegistration');

    // GUAR TYPES
    Route::resource('uw-guar-type', 'UwGuarTypesController');
    Route::get('get-guar-types', 'UwGuarTypesController@getModel');

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

