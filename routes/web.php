<?php

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

    Route::get('cbids','HomeController@updateUserCBIds');
    // ADMIN
    Route::group(['prefix'=>'madmin'], function(){
        // LOG CACHE
        Route::get('storage-log', 'HomeController@storageLog');
        Route::get('get-log-file', 'HomeController@getLogFile');
        Route::get('cache/{type}', 'HomeController@cacheClear');

        // FILIAL
        Route::resource('filial','FilialController');
        Route::any('filial','FilialController@index');
        Route::get('filial-get-update-ora','FilialController@getUpdateOra');
        Route::get('update-role-department/{code}','FilialController@updateRoleDepartment');

        // OLD DEPARTMENT
        Route::resource('departments','DepartmentController');
        Route::any('departments','DepartmentController@index');
        Route::post('get-department','DepartmentController@getDepartment');
        //Route::get('update-department/{id}','DepartmentController@updateDepartment');

        // DEPARTMENT
        Route::any('s-departments','DepartmentController@sDepartments');
        Route::get('update-s-departments','DepartmentController@updateSDepartments');
        Route::get('get-s-department/{id}','DepartmentController@getSDepartment');
        Route::post('edit-s-department','DepartmentController@editSDepartment');
        Route::post('get-sub-department','DepartmentController@subDepartment');
        Route::post('get-districts','DepartmentController@getDistricts');
        Route::post('get-reg-districts','DepartmentController@getRegDistricts');

        // ROLES
        Route::resource('roles', 'RoleController');

        // CORE MENU
        Route::resource('menus','MCoreMenusController');
        Route::any('menus-search', 'MCoreMenusController@search');

        // MENU ROLES
        Route::resource('menu-roles','MMenuRolesController');
        Route::any('menu-roles-search', 'MMenuRolesController@search');

        // ROLE MENUS
        Route::resource('role-menus','MRoleMenusController');
        Route::any('role-menus-search', 'MRoleMenusController@search');
        Route::get('role-menus/user-role/{id}','MRoleMenusController@getParent');

        // USERS
        Route::resource('users','UserController');
        Route::any('users','UserController@index');
        Route::get('users-username-check/{username}','UserController@usernameCheck');
        Route::get('users-get-branch/{id}','UserController@getBranch');
        Route::post('users-update','UserController@updateUser');
        Route::post('user/username-update/{user_id}','UserController@usernameUpdate');
        Route::get('ora-emp-search','UwJuridicalClientsController@getOraEmpSearch');
        Route::get('user/profile/{id}','UserController@profile');
        Route::post('user/profile-update/{id}','UserController@profileUpdate');

        // ORA USERS
        Route::get('ora-index','UserController@oraIndex');
        Route::get('update-user-info/{id}','UserController@updateUserInfo');
        Route::get('update-user-work/{user_id}','UserController@updateUserWork');
        Route::post('user/role-update/{user_id}','UserController@updateUserRoles');
        Route::get('get-user-info/{id}','UserController@getUserInfo');
        Route::get('users/delete/{id}','UserController@destroy');
        Route::post('users/store-new','UserController@storeNew');

        // WORK USERS
        Route::resource('work-users','MWorkUsersController');
        Route::get('work-users-tab_num-check/{tab_num}','MWorkUsersController@tab_numCheck');
        Route::get('work-users/get-roles/{id}','MWorkUsersController@getRoles');
        Route::get('work-users/get-history-roles/{id}','MWorkUsersController@getHistoryRoles');
        Route::get('work-users/activate-user/{id}','MWorkUsersController@activateUser');

        // PERSONAL USERS
        Route::resource('personal-users','MPersonalUsersController');
        Route::any('personal-users-search', 'MPersonalUsersController@search');
        Route::get('personal-users-email-check/{email}','MPersonalUsersController@emailCheck');

        // LOAN TYPES
        Route::resource('loan-types', 'UwLoanTypesController');
        Route::delete('loan-types/{id}', 'UwLoanTypesController@destroy');
        Route::get('get-loan-banks/{id}', 'UwLoanTypesController@getBanks');
        Route::post('store-loan-banks', 'UwLoanTypesController@storeBanks');
        Route::get('get-loans/{code}', 'UwLoanTypesController@getLoans');

        // GUAR TYPES
        Route::resource('guar-type', 'UwGuarTypesController');
        Route::get('get-guar-types', 'UwGuarTypesController@getModel');

        //PHYSICAL
        Route::get('get-phy-client/{id}','UwClientsController@show');
        Route::post('uw-risk-edit','UwClientsController@riskEdit');


        Route::get('sms-send','HomeController@smsSend');

    });

    // MY ID CLIENTS
    Route::group(['prefix'=>'myid'], function(){
        //UW
        Route::any('adm/phy/index','PhysicalClientCreateController@physicalClients');
        Route::get('adm/phy/view/{id}/{pinfl}','PhysicalClientCreateController@physicalClientView');
        Route::post('adm/phy/edit','PhysicalClientCreateController@physicalClientEdit');
    });

    // PHYSICAL
    Route::group(['prefix'=>'phy'], function(){
        //UW
        Route::any('krd/index','UwClientsController@krdAllClients');
        Route::get('krd/view/{id}/{claim_id}','UwClientsController@krdView');
        Route::any('uw/clients/{q}','UwClientsController@uwIndex');
        Route::any('uw/all-clients','UwClientsController@uwAllClients');
        Route::get('uw/view-client/{id}/{claim_id}', 'UwClientsController@uwView');
        Route::get('get-loan-type', 'UwCreateClientsController@getLoanType')->name('uw.get.loan.type');
        Route::post('uw/risk-admin-confirm', 'UwClientsController@riskAdminConfirm');
        Route::post('uw/risk-admin-cancel', 'UwClientsController@riskAdminCancel');

        //INS
        Route::get('clients/{q}', 'UwClientsController@CsIndex');
        Route::get('client/create', 'UwCreateClientsController@create');
        Route::get('create-step-one/{id}', 'UwCreateClientsController@createStepOne')->name('phy.create.step.one');
        Route::post('create-step-one', 'UwCreateClientsController@postCreateStepOne')->name('phy.create.step.one.post');
        Route::get('create-step-two/{id}', 'UwCreateClientsController@createStepTwo')->name('phy.create.step.two');
        Route::post('create-step-two', 'UwCreateClientsController@postCreateStepTwo')->name('phy.create.step.two.post');
        Route::get('create-step-three/{id}', 'UwCreateClientsController@createStepThree')->name('phy.create.step.three');
        Route::post('create-step-three', 'UwCreateClientsController@postCreateStepThree')->name('phy.create.step.three.post');
        Route::get('create-step-result/{id}', 'UwCreateClientsController@createStepResult')->name('phy.create.step.result');
        Route::get('client/edit/{id}', 'UwClientsController@edit');
        Route::post('uw-clients-edit','UwClientsController@storeEdit');
        Route::post('client/online-reg', 'UwInquiryIndividualController@onlineRegistration');
        Route::get('client/get-result-buttons/{id}', 'UwInquiryIndividualController@getResultButtons');
        Route::get('client/get-scoring','UwInquiryIndividualController@getScoring');
        Route::get('client/get-salary','UwInquiryIndividualController@getSalary');
        Route::get('get-status-send/{id}/{sch_type}','UwInquiryIndividualController@getStatusSend');
        Route::get('uw/post-katm', 'UwKatmController@postKatm');
        Route::post('calc-form','UwClientsController@calcForm');
        Route::get('get-confirm-send/{id}','UwInquiryIndividualController@getConfirmSend');
        Route::post('client-send-to-uw', 'UwClientsController@csAppSend');
        //INS CREATE NEW
        Route::get('client/create/new', 'PhysicalClientCreateController@index');
        Route::post('client/create/personal', 'PhysicalClientCreateController@createPersonal');
        Route::get('client/form/{id}', 'PhysicalClientCreateController@clientForm');
        Route::post('client/create/app', 'PhysicalClientCreateController@createApp');
        Route::get('client/image/{id}', 'PhysicalClientCreateController@displayImage');


        // DEBTORS
        Route::resource('client-debtors', 'UwClientDebtorsController');
        Route::post('debtor-client/online-reg', 'UwClientDebtorsController@postKatm');
        Route::get('debtor/get-scoring', 'UwClientDebtorsController@getDebtorScoring');
        Route::get('debtor/get-salary', 'UwClientDebtorsController@getDebtorSalary');

        // GUAR
        Route::get('client/get-guars/{id}', 'UwCreateClientsController@getClientGuars');
        Route::post('client/create-guar', 'UwCreateClientsController@createClientGuar');
        Route::get('client/edit-guar/{id}', 'UwCreateClientsController@editClientGuar');
        Route::get('client/delete-guar/{id}', 'UwCreateClientsController@deleteClientGuar');

        // FILE
        Route::get('client/get-files/{id}', 'UwCreateClientsController@getClientFiles');
        Route::post('client/create-file', 'UwCreateClientsController@createClientFile');
        Route::get('client/download-file/{id}','UwClientsController@downloadFile');
        Route::get('client/delete-file/{id}', 'UwCreateClientsController@deleteClientFile');

        Route::post('phy-export', 'UwClientsController@export')->name('phy-export');
    });

    // JURIDICAL
    Route::group(['prefix'=>'jur'], function(){
        Route::resource('client','UwJuridicalClientsController');
        Route::any('clients/{q}','UwJuridicalClientsController@getClients');
        Route::any('adm/all-clients','UwJuridicalClientsController@getAdminAllClients');
        Route::any('uw/clients/{q}','UwJuridicalClientsController@getUwClients');
        Route::any('uw/all-clients','UwJuridicalClientsController@getAllClients');
        Route::get('uw/edit-client/{q}','UwJuridicalClientsController@uwEditClient');
        Route::post('uw/edit-client-post','UwJuridicalClientsController@uwEditClientPost');
        Route::get('uw/view-client/{q}','UwJuridicalClientsController@uwShow');

        Route::get('ora-search','UwJuridicalClientsController@getOraSearch');
        Route::get('view-form/{q}','UwJuridicalClientsController@getOraData');
        Route::post('guar-store','UwJuridicalClientsController@guarStore');
        Route::get('guar-delete/{id}','UwJuridicalClientsController@guarDelete');
        Route::post('files-store','UwJuridicalClientsController@filesStore');
        Route::get('file-download/{id}','UwJuridicalClientsController@fileDownload');
        Route::get('file-delete/{id}','UwJuridicalClientsController@fileDelete');
        Route::get('send-to-admin','UwJuridicalClientsController@sendToAdmin');

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

        Route::post('export', 'UwJuridicalClientsController@export')->name('export');

    });

    // End New User Position
    // User Role
    // User
    //Route::resource('admin/users','UserController');
    // User search
    //Route::get('admin/users-search','UserController@search');
    #Route::post('/get-user-department','DepartmentController@userDepartment');
    #Route::get('admin/ora','RoleController@ora');
    // uw project begin
    #Route::post('/uw/create-client','UwClientsController@store');
    #Route::get('/uw/get-client-katm/{cid}','UwClientsController@getClientKatm');
    #Route::get('/uw/get-client-inps/{cid}','UwClientsController@getClientInps');
    #Route::resource('uw/filials', 'FilialsController');
    #Route::resource('uw/uw-users', 'UwUsersController');
    #Route::get('/uw/home', 'UwClientsController@home');
    #Route::get('/uw-clients/create', 'UwClientsController@create');
    #Route::get('uw/testInsertKatm', 'UwInquiryIndividualController@getClientSalary');
    #Route::get('get-confirm-send/{id}','UwInquiryIndividualController@getConfirmSend');

    /*Route::get('/uw/client-katm/{id}/{claim_id}', 'UwClientsController@clientKatm')->name('uw-katm');
    Route::post('uw-clients-edit','UwClientsController@storeEdit');
    Route::post('uw-risk-edit','UwClientsController@riskEdit');
    Route::post('/uw/cs-app-send', 'UwClientsController@csAppSend');
    Route::post('uw-client-files/upload', 'UwClientsController@fileUpload');
    Route::get('/uw/filePreView/{preViewFile}','UwClientsController@preViewPdf')->name('filePreView');
    Route::get('/uw-client-file/delete/{id}', 'UwClientsController@destroyFile');
    Route::get('/uw/view-loan-super-admin/{id}/{claim_id}', 'UwClientsController@superAdminView');
    Route::post('/uw/risk-admin-confirm', 'UwClientsController@riskAdminConfirm');
    Route::post('/uw/risk-admin-cancel', 'UwClientsController@riskAdminCancel');
    Route::post('/uw/calc-form','UwClientsController@calcForm');
    Route::get('/uw/loan-app-statistics', 'UwClientsController@loanAppStatistics');
    Route::get('/uw/get-app-blank/{claim_id}','UwClientsController@getAppBlank');*/

});



