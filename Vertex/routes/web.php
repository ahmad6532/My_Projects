<?php

use App\Http\Controllers\HBLMFBController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin_controller;
use App\Http\Controllers\ZktechoController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\ThemeSettingsController;
use App\Http\Controllers\RolesPermissonController;
use App\Http\Controllers\Company_Setting_Controller;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\CronsJobController;

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
Route::get('system_response',function(){
    return view('system_response.system_response');
});
Route::post('save-system-response', [AdminController::class, 'saveSystemResponse'])->name('saveResponse');







Route::get('/cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/', [LoginController::class, 'adminLogin'])->name('login.submit');
Route::get('forget-password', [LoginController::class, 'forgetPasswordForm'])->name('forget.password');
Route::POST('submit-forget-password', [LoginController::class, 'forgetPassword'])->name('send.email.forgetPassword');//forget password
Route::post('verify-password-otp', [LoginController::class, 'verifyPasswordOTP']);//verify forget password token
Route::post('reset-password', [LoginController::class, 'resetPassword']);//verify forget password token
Route::get('zkt',[ZktechoController::class,'conn']);
Route::get('/exportDataToOracle', [HBLMFBController::class, 'exportDataToOracle'] );
Route::get('/zkteco', [ZktechoController::class, 'zkAttendance'] );
Route::get('/syncCount', [ZktechoController::class, 'syncCounts']);
Route::get('/countDeviceUser', [ZktechoController::class, 'countUserOnDevice']);
Route::get('/getAllUser', [ZktechoController::class, 'getAllUser'] );
Route::get('/createAllUser', [ZktechoController::class, 'createAllUser'] );
Route::get('/createOrUpdateUser', [ZktechoController::class, 'createOrUpdateUser'] );
Route::get('/removeUser', [ZktechoController::class, 'removeUser'] );
Route::get('/clearUser', [ZktechoController::class, 'clearUsers'] );
Route::get('/restart', [ZktechoController::class, 'restartDevice'] );
Route::get('/shutdown', [ZktechoController::class, 'shutdownDevice'] );
Route::get('/updateDeviceStatus', [ZktechoController::class, 'updateDeviceStatus'] );
Route::get('/sleep', [ZktechoController::class, 'sleepDevice'] );
Route::get('/resume', [ZktechoController::class, 'resumeDevice'] );
Route::get('/testVoice', [ZktechoController::class, 'testVoice'] );
Route::get('/clearLCD', [ZktechoController::class, 'clearLCD'] );
Route::get('/writeLCD', [ZktechoController::class, 'writeLCD'] );
Route::get('/setTime', [ZktechoController::class, 'setTime'] );
Route::get('/getTime', [ZktechoController::class, 'getTime'] );
Route::get('/clearAttendance', [ZktechoController::class, 'clearAttendance'] );
Route::get('/fetchAttendance', [ZktechoController::class, 'fetchAttendance'] );
Route::get('/import-employee', [HBLMFBController::class, 'importEmployees'] );
Route::get('/get-fingerprint', [ZktechoController::class, 'getFingerprint'] );
Route::get('/set-fingerprint', [ZktechoController::class, 'setFingerprint'] );
Route::get('/remove-fingerprint', [ZktechoController::class, 'removeFingerprint'] );
//Cron-job for monthly and daily attendance
// Route::get('/update-user-monthly-record', [CronsJobController::class,'updateUserMonthlyRecord']);
// Route::get('/update-user-daily-record', [CronsJobController::class,'updateUserDailyAttendance']);

Route::group(['middleware'=>['auth']],function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/branch-present-record', [HomeController::class, 'EmpPresentData'])->name('home.present.chart');
    Route::post('/dashboard-main-graph', [HomeController::class, 'attendanceGraph'])->name('dashboard.main.graph');
    Route::PUT('smtp-update/',[Admin_controller::class,'updateSMTPgateway'])->name('update.smtp');
    Route::get("/SMTP",[Admin_controller::class,'smtp']);
    Route::get('/profile', [Admin_controller::class,'viewprofile'])->name('profile');
    Route::get("calender",[Admin_controller::class,'index']);
    Route::get("home/recently-activity",[Admin_controller::class,'recentlyActivity'])->name('recently.activity');
    // Route::get("recently-activity",[Admin_controller::class,'adminDeleteNotifications'])->name('admin.delete.notifications');

    //Cron-job Time
    Route::get('/cronjob-history', [Admin_controller::class,'cronJobDetal']);

    //manually
    // Route::get('/manual-update-user-monthly-record/{date}', [CronsJobController::class,'updateUserMonthlyRecord']);
    // Route::get('/manual-update-user-daily-record/{date}', [CronsJobController::class,'updateUserDailyAttendance']);

    // User Management
    Route::get("/user-management",[Admin_controller::class,'UserManagement'])->name('user.management');
    // Route::get("/toDuplicateEmail",[Admin_controller::class,'toDuplicateEmail']);
    Route::get("/add-user",[Admin_controller::class,'addNewUser'])->name('add.user');
    Route::post("/insert-new-user",[Admin_controller::class,'storeUser'])->name('insert.new.user');
    Route::get('delete-user/{id}', [Admin_controller::class,'destroyUser']);
    Route::get('edit-user/{id}',[Admin_controller::class,'editUserDetails']);
    Route::put('Update-user-Details/{id}',[Admin_controller::class,'Updateuser']);

    // Branches
    Route::get("/branch-management",[Admin_Controller::class,'branchIndex'])->name('branch.management');
    Route::get("/branch-management/add-branch",[Admin_Controller::class,'branchAdd'])->name('add.branch');
    Route::post("/branch-management/store-branch",[Admin_Controller::class,'branchStore'])->name('store.branch');
    Route::get('/branch-management/edit/{id}',[Admin_Controller::class,'editBranchData']);
    Route::post('/branch-management/Branch-update',[Admin_Controller::class,'updateBranchData'])->name('update.BranchData');
    Route::get('getcities',[Admin_Controller::class,'getcities'])->name('getcities');
    Route::get('deleteBranch/{id}', [Admin_Controller::class,'destroyBranch']);
    Route::get("/branch-search",[Admin_Controller::class,'getsearchedbranch'])->name('branch.search');

    // holidaysadd-sms-notifications
    Route::get("/holidays",[Admin_Controller::class,'holidaysList'])->name('holidays.list');
    Route::post("/save-holiday",[Admin_Controller::class,'saveHolidays'])->name('save.holiday');
    Route::get("/edit-holiday",[Admin_Controller::class,'getHolidayDetail'])->name('edit.holiday');
    Route::post("/update-holiday",[Admin_Controller::class,'updateHoliday'])->name('update.holiday');
    Route::get("/deleteholiday/{id}",[Admin_Controller::class,'destroyHoliday'])->name('destroy.holiday');
    Route::get("/holiday-search",[Admin_Controller::class,'getsearchedHoliday'])->name('holiday.search');
    Route::get("/Holiday/branches",[Admin_Controller::class,'getHolidayBranche'])->name('holiday.branch');

    // Roles and permission
    Route::get("/roles-permissions",[RolesPermissonController::class,'RolesPermission'])->name('roles.list');
    Route::get("/add-roles-permissions",[RolesPermissonController::class,'addRolesPermission'])->name('add.roles.permissions');
    Route::post("/save-roles-permissions",[RolesPermissonController::class,'saveRolesPermission'])->name('save.roles');
    Route::get("/edit-roles-permissions/{id}",[RolesPermissonController::class,'editRolesPermission'])->name('edit.roles.permissions');
    Route::post("/update-roles-permissions",[RolesPermissonController::class,'updateRolesPermission'])->name('update.roles.permissions');

    // Employee Attendance sheet
    Route::get("/daily-attendance-sheet",[Admin_controller::class,'dailyAttendance'])->name('daily.attend.sheet');
    Route::POST("/add-employee-attendence",[Admin_controller::class,'addManuallyAttendance'])->name('addManuallyAttendance');
    Route::get("/search-employee-attendence",[Admin_controller::class,'getEmpAttendenceSearch'])->name('search.employee.attendence');
    Route::get("/monthly-attendance-sheet",[Admin_controller::class,'monthlyAttenSheet'])->name('monthly.attend.sheet');
    Route::post("/AttendanceSheet",[Admin_controller::class,'getAttenSheetbySearch'])->name('search.AttendanceSheet');
    Route::get("/filter-AttendanceSheet",[Admin_controller::class,'filterAttendanceSheet']);//this is not in use
    Route::get('yearly-attendance-sheet',[Admin_controller::class,'yearlyDetail'])->name('yearly.attend.sheet');
    Route::get('yearly-attendancebranch-sheet',[Admin_controller::class,'yearlyDetail'])->name('yearly.search.branch');
  //  Route::get("/download-AttendanceSheet/{id}/{date}",[Admin_controller::class,'AttenSheetdownload'])->name('downloadCSV');
    // Route::POST("/createCSVempAttendance",[Admin_controller::class,'createCSVempAttendance'])->name('createCSVempAttendance');
    Route::get("/getEmployees",[Admin_controller::class,'getEmployees'])->name('getEmployees');
    Route::get("/getattendance",[Admin_controller::class,'getattendance'])->name('getattendance');
    Route::get("/getDesination",[Admin_controller::class,'getDesination'])->name('getDesination');

    //device management
    Route::get('/device-management',[Admin_controller::class,'deviceManagement'])->name('device.management');
    Route::get('/device-management/add-device',[Admin_controller::class,'addDevice'])->name('add.device');
    Route::post('/device-management/save-device',[Admin_controller::class,'saveDevice'])->name('save.device');
    Route::get('/device-management/edit-device/{id}',[Admin_controller::class,'editDevice'])->name('edit.device');
    Route::PUT('/device-management/update-device',[Admin_controller::class,'updateDevice'])->name('update.device');

    //CRUD for user management
    Route::get('/show-roles',[Admin_controller::class,'showRoles']);//this is not in use

    // company setting
    Route::get('/company-setting',[Company_Setting_Controller::class,'companySetting']);
    Route::get('/company-setting/add-company',[Company_Setting_Controller::class,'addCompany'])->name('add.company.setting');
    Route::POST('/store-company',[Company_Setting_Controller::class,'storeCompany'])->name('store-Company');
    Route::get('/company-setting/edit-company/{id}',[Company_Setting_Controller::class,'editCompany'])->name('edit.company.setting');
    Route::PUT('/update-company/{id}',[Company_Setting_Controller::class,'updateCompany'])->name('update-company');
    Route::get('deleteCompany/{id}', [Company_Setting_Controller::class,'destroyCompany']);

    //leave setting
    Route::get('/leave-settings',[Company_Setting_Controller::class,'leaveSetting'])->name('leave.setting');
    Route::get('/leave-settings/add-leave-setup',[Company_Setting_Controller::class,'addLeaveSetup'])->name('add.leave.setup');
    Route::post('/leave-settings/save-leave-setup',[Company_Setting_Controller::class,'saveLeaveSetup'])->name('save.setup');
    Route::get('/leave-settings/edit-leave-setup/{id}',[Company_Setting_Controller::class,'LeaveSettingsEdit'])->name('leave.settings.edit');
    Route::PUT('/leave-settings/update-leave-setup/{id}',[Company_Setting_Controller::class,'UpdateSetupSettings'])->name('update.setup.setting');
    Route::get('/leave-settings/delete-leave-setup/{id}',[Company_Setting_Controller::class,'DeleteLeaveSetup'])->name('delete.leave.setup');

    Route::get('/get-branch',[Company_Setting_Controller::class,'getbranch'])->name('get-branch');
    Route::get('/get-multiple-branches',[Company_Setting_Controller::class,'getMultipleBranches'])->name('get.multiple.branches');
    Route::get('/get-multi-branches',[Company_Setting_Controller::class,'getMultiBranches'])->name('get.multi.branches');

    // Company Management
    Route::get('/company-management',[Company_Setting_Controller::class,'CompanyManagement'])->name('company.management');
    Route::get('/company-management/add-company',[Company_Setting_Controller::class,'AddCompanyManage'])->name('add.company.manage');
    Route::post('/company-management/store',[Company_Setting_Controller::class,'StoreCompanyManage'])->name('store.company.manage');
    Route::get('/company-management/edit/{id}',[Company_Setting_Controller::class,'EditCompanyManage'])->name('edit.company.manage');
    Route::PUT('/company-management/update/{id}',[Company_Setting_Controller::class,'UpdateCompanyManage'])->name('update.company.manage');
    Route::get('/company-management/delete/{id}',[Company_Setting_Controller::class,'DeleteCompanyManage'])->name('delete.company.manage');

    // themes
    Route::get('/Theme-settings',[ThemeSettingsController::class,'themeSettings']);
    Route::PUT('/updatetheme',[ThemeSettingsController::class,'updatetheme'])->name('update.theme');
    // appearance
    Route::get('/Appearance-settings',[ThemeSettingsController::class,'AppearanceSetting'])->name('appearance.setting');
    Route::get('/new-appearance-setting',[ThemeSettingsController::class,'NewAppearanceSetting'])->name('new.appearance.setting');
    Route::post('/save-appearance',[ThemeSettingsController::class,'saveAppearance'])->name('save.appearance');
    Route::get('/set-default-theme/{id}',[ThemeSettingsController::class,'setDefaultTheme'])->name('set.default.theme');
    Route::get('/update-appearance/{id}',[ThemeSettingsController::class,'updateAppearance'])->name('update.appearance');
    Route::get('/delete-theme/{id}',[ThemeSettingsController::class,'deleteTheme'])->name('delete.theme');


    // designation
    Route::get('/employee/designation',[Admin_controller::class,'designation'])->name('designation');
    Route::post('/save-designation',[Admin_controller::class,'saveDesignation'])->name('save.designation');
    Route::post('/update-designation',[Admin_controller::class,'updateDesignation'])->name('update.designation');
    Route::get('/designationSearch',[Admin_controller::class,'designationSearch'])->name('designationSearch');

    // department
    Route::get('/employee/department',[Admin_controller::class,'department'])->name('department');
    Route::post('/save-department',[Admin_controller::class,'saveDepartment'])->name('save.department');
    Route::post('/update-department',[Admin_controller::class,'updateDepartment'])->name('update.department');
    Route::get('/get-department',[Admin_controller::class,'getDepartment'])->name('getDepartments');
    Route::get('/departmentSearch',[Admin_controller::class,'departmentSearch'])->name('departmentSearch');

    // resignation
    Route::get('/employee/resignation',[Admin_controller::class,'resignation'])->name('index.resignation');
    Route::get('/employee/resignation',[Admin_controller::class,'resignation'])->name('resignation.search.branch');
    Route::get('/add-resignation',[Admin_controller::class,'addResignation'])->name('add.resignation');
    Route::post('/save-resignation',[Admin_controller::class,'saveResignation'])->name('save.resignation');
    Route::get('/change-resignation-status',[Admin_controller::class,'changeResignationStatus'])->name('change.resignation.status');
    Route::get('/edit-resignation/{id}',[Admin_controller::class,'editResignation'])->name('edit.resignation');
    Route::post('/update-resignation',[Admin_controller::class,'updateResignation'])->name('update.resignation');
    Route::get('/delete-resignation/{id}',[Admin_controller::class,'deleteResignation'])->name('delete.resignation');
    Route::get("/resignation-search",[Admin_Controller::class,'resignationSearch'])->name('resignation.search');

    // termination
    Route::get('/employee/termination',[Admin_controller::class,'termination'])->name('termination');
    Route::get('/employee/termination',[Admin_controller::class,'termination'])->name('termination.search.branch');
    Route::get('/employee/add-termination',[Admin_controller::class,'addTermination'])->name('add.termination');
    Route::POST('/employee/save-termination',[Admin_controller::class,'saveTermination'])->name('save.termination');
    Route::get('/edit-termination/{id}',[Admin_controller::class,'editTermination'])->name('edit.termination');
    Route::PUT('/update-termination',[Admin_controller::class,'updatetermination'])->name('update.termination');
    Route::get('/change-Termination-status',[Admin_controller::class,'changeTerminationStatus'])->name('change.termination.status');
    Route::get('/delete-termination/{id}',[Admin_controller::class,'deleteTermination'])->name('delete.termination');
    Route::get('/termination-search',[Admin_controller::class,'terminationSearch'])->name('termination.search');

    // promotion
    Route::get('/employee/promotion',[Admin_controller::class,'promotion'])->name('promotion');
    Route::post('/save-promotion',[Admin_controller::class,'savePromotion'])->name('save.promotion');
    Route::get('/change-promotion-status',[Admin_controller::class,'changePromotionStatus'])->name('change.promotion.status');
    Route::get('/employee/add-promotion',[Admin_controller::class,'addPromotion'])->name('add.promotion');
    Route::get('/edit-promotion/{id}',[Admin_controller::class,'editPromotion'])->name('edit.promotion');
    Route::PUT('/update-promotion/{id}',[Admin_controller::class,'updatepromotion'])->name('update.promotion');
    Route::get('/employee/promotion',[Admin_controller::class,'promotion'])->name('promotion.search.branch');
    Route::get('/delete-promotion/{id}',[Admin_controller::class,'deletePromotion'])->name('delete.promotion');
    Route::get('/promotion-search',[Admin_controller::class,'promotionSearch'])->name('promotion.search');

    // shift and schedule
    Route::get('/employee/shift-management',[Admin_controller::class,'shiftManagement'])->name('shift.index');
    Route::get('/employee/shift-management/add-shift',[Admin_controller::class,'addShift'])->name('add.shift');
    Route::post('/employee/shift-management/save-shift',[Admin_controller::class,'saveShift'])->name('save.shift');
    Route::get('/employee/shift-management/edit-shift/{id}',[Admin_controller::class,'editShift'])->name('edit.shift');
    Route::PUT('/employee/shift-management/update-shift',[Admin_controller::class,'updateShift'])->name('update.shift');
    Route::get('/employee/shift-management/save-schedule',[Admin_controller::class,'saveSchedule'])->name('save.schedule');

    //Payraoll
    Route::get('/payroll/employee/salary',[Admin_controller::class,'payRollEmpSalary'])->name('payroll.emp_salary');
    Route::get('/payyroll/add-salary',[Admin_controller::class,'payRollAddSalary'])->name('payroll.add_salary');
    Route::post('/payyroll/save-salary',[Admin_controller::class,'saveEmpSalary'])->name('save.emp_salary');
    Route::get('/payyroll/edit-salary/{id}', [Admin_controller::class, 'editEmpSalary'])->name('edit.emp_salary');
    Route::post('/payyroll/update-salary/{id}', [Admin_controller::class, 'UpdateEmpSalary'])->name('Update.emp_salary');

    //Monthly Payroll
    Route::get('/monthly/payroll/employee-salary',[Admin_controller::class,'monthlyPayRollEmpSalary'])->name('monthly.payroll.emp_salary');
    Route::post('/save/monthly/payroll',[Admin_controller::class,'saveMonthlyPayRoll'])->name('save.monthly.payroll.emp_salary');
    Route::get('/monthly/payroll/process/approve',[Admin_controller::class,'saveMonthlyPayRollProcessApprove'])->name('monthly.payroll.process.approve');
    Route::get('/monthly/payroll/process/decline',[Admin_controller::class,'saveMonthlyPayRollProcessDeline'])->name('monthly.payroll.process.decline');
    Route::get('/monthly/payroll',[Admin_controller::class,'MonthlyPayRoll'])->name('monthly.payroll');

    // leave
    Route::get('/leave-request',[Admin_controller::class,'leaveRequest'])->name('leave.request');
    Route::get('/leave-request/add-leave',[Admin_controller::class,'addLeaveRequest'])->name('add.leave.request');
    Route::post("/leave-request/save-leave",[Admin_Controller::class,'saveLeave'])->name('save.leave.request');
    Route::get("/leave-request/edit-leave/{id}",[Admin_Controller::class,'editLeaveRequest']);
    Route::post("/update-leave-request",[Admin_Controller::class,'updateLeaveRequest'])->name('update.leave.request');
    Route::get('getRemainingLeaves',[Admin_Controller::class,'getRemainingLeaves'])->name('getRemainingLeaves');
    Route::get('getTotalRemainingLeaves',[Admin_Controller::class,'getTotalRemainingLeaves'])->name('getTotalRemainingLeaves');
    Route::get("/update-leave-status/{id}/{status}",[Admin_Controller::class,'updateLeaveStatus'])->name('decline-leave');
    // Route::get("/approve-leave/{id}",[Admin_Controller::class,'approveLeave'])->name('approve-leave');
    Route::get("/delete-leave-request/{id}",[Admin_Controller::class,'destroyLeaveRequest'])->name('delete.leave.request');
    Route::get("/leave-search",[Admin_Controller::class,'leavesearch'])->name('leave.search');

    Route::get('/version-history',[Admin_controller::class,'versionHistory'])->name('version.history');
    Route::POST('/save-version',[Admin_controller::class,'saveVersion'])->name('save.version');

    //for search option
    Route::get("/user-search",[Admin_controller::class,'getsearchedUser'])->name('user.search');

    //employee
    Route::get("/employee/directory",[EmployeeController::class,'EmployeeDirectory'])->name('emp.directory');
    Route::get("/employee/directory",[EmployeeController::class,'EmployeeDirectory'])->name('emp.directory.status');
    Route::get("/employee-search",[EmployeeController::class,'getsearchedEmployee'])->name('employee.search');

    Route::get("/employee/directory/add-employee",[EmployeeController::class,'createEmployeee'])->name('add.employee');
    Route::post("/employee/directory/save-employee",[EmployeeController::class,'storeEmployee'])->name('save.employee');

    Route::get('/employee/directory/add-education', function () {
        return view('directory.add.education');
    })->name('add.education');
    Route::post("/employee/directory/save-education",[EmployeeController::class,'storeEmployeeEducation'])->name('save.education');

    Route::get('/employee/directory/add-employment', function () {
        return view('directory.add.employment');
    })->name('add.employment');
    Route::post("/employee/directory/save-employment",[EmployeeController::class,'storeEmployeeExperience'])->name('save.employment');

    Route::get('/employee/directory/add-references', function () {
        return view('directory.add.references');
    })->name('add.references');
    Route::post("/employee/directory/save-references",[EmployeeController::class,'storeEmployeeFamilyData'])->name('save.references');

    Route::get('/employee/directory/add-account-detail', function () {
        return view('directory.add.account_detail');
    })->name('add.account.detail');
    Route::post("/employee/directory/save-account-detail",[EmployeeController::class,'storeAccountDetail'])->name('save.account.detail');

    Route::get('/employee/directory/add-approvals', [EmployeeController::class,'addApprovals'])->name('add.approval');
    Route::post("/employee/directory/save-approvals",[EmployeeController::class,'storeEmployeeApproval'])->name('save.approval');

    //get global data
    Route::get("/cities-by-state",[EmployeeController::class,'getcities'])->name('get.cities');
    Route::get('getemployees',[Admin_Controller::class,'getemployeesbybranch'])->name('get.branch.employees');

    //edit Employee data
    Route::get('/employee/directory/edit-employee/{id}',[EmployeeController::class,'editEmployeeData'])->name('edit.employee');
    Route::get('/employee/directory/edit-education/{id}',[EmployeeController::class,'editEmployeeEducation'])->name('directory.editEmpEducation');
    Route::get('/employee/directory/edit-experiences/{id}',[EmployeeController::class,'editEmployeeExperiences'])->name('directory.editEmpExperiences');
    Route::get('/employee/directory/edit-references/{id}',[EmployeeController::class,'editEmployeeRefrences'])->name('directory.editEmpRefrences');
    Route::get('/employee/directory/edit-account-detail/{id}',[EmployeeController::class,'editAccount'])->name('directory.editAccount');
    Route::get('/employee/directory/edit-approval/{id}',[EmployeeController::class,'editEmployeeApproval'])->name('directory.editEmpApprovals');

    //update Employee data
    Route::get("/employee/directory/employee-profile/{id}",[EmployeeController::class,'profileDetail'])->name('profile.profile_detail');
    Route::get("/employee/directory/edit-account",[EmployeeController::class,'editBankAccount'])->name('edit.bank.account');
    Route::get("search-employee-attendance",[EmployeeController::class,'searchEmployeeAttendance'])->name('search.user.data');
    Route::get("/employee/directory/edit-emergency",[EmployeeController::class,'editEmergency'])->name('edit.emergency.contact');
    Route::post("/employee/directory/update-emergency",[EmployeeController::class,'updateEmergency'])->name('update.emergency');

    // views
    Route::get('/employee/directory/add-employee/{id}/{edit?}',[EmployeeController::class,'editEmployeeData']);
    Route::get('/employee/directory/add-education/{id}/{edit?}',[EmployeeController::class,'editEmployeeEducation']);
    Route::get('/employee/directory/add-experiences/{id}/{edit?}',[EmployeeController::class,'editEmployeeExperiences']);
    Route::get('/employee/directory/add-references/{id}/{edit?}',[EmployeeController::class,'editEmployeeRefrences']);
    Route::get('/employee/directory/add-account-detail/{id}/{edit?}',[EmployeeController::class,'editAccount']);
    Route::get('view-approval/{id}/{edit?}',[EmployeeController::class,'editEmployeeApproval']);

    // update emplyee data
    Route::put('update-employee/{id}',[EmployeeController::class,'updateEmployeeDetails']);
    Route::put('update-employee-education/{id}',[EmployeeController::class,'updateEmployeeEducation']);
    Route::put('update-employee-experience/{id}',[EmployeeController::class,'updateEmployeeExperience']);
    Route::put('update-employee-references/{id}',[EmployeeController::class,'updateEmployeeRefrences']);
    Route::put('update-account-detail/{id}',[EmployeeController::class,'updateAccountDetail'])->name('update.accountdetail');
    Route::put('update-account-detail-profile/{id}',[EmployeeController::class,'updateAccountDetailProfile']);
    Route::put('update-employee-approval/{id}',[EmployeeController::class,'updateEmployeeApproval']);
    Route::get('change-employee-approval-status/{id}',[EmployeeController::class,'changeEmployeeApprovalStatus'])->name('change.employee.status');

    //Delete Employee Data
    Route::get('/delete-record', [EmployeeController::class,'destroyEducation']);
    Route::get('/employee/directory/delete/{id}', [EmployeeController::class,'destroy'])->name('delete.employee');
    Route::get('/delete_document',[EmployeeController::class,'delete']);
    Route::get('/employee/directory/restore-employee/{id}', [EmployeeController::class,'restore'])->name('restore.employee');
    Route::get('/employee/directory/hard/delete/{id}', [EmployeeController::class,'EmployeeHardDelete'])->name('hard.delete.employee');
    //update user details
    Route::put('Update-user/{id}',[Admin_controller::class,'updateUserDetails']);

    //communication system
    Route::get("/communication",[CommunicationController::class,'sendEmail']);
    Route::post("/communication/add-popup-details",[CommunicationController::class,'storePopupBanner'])->name('add.popup.details');
    Route::get("/communication/add-email-notifications",[CommunicationController::class,'sendEmail'])->name('Communication.comm_email');
    Route::post("/communication/add-email-details",[CommunicationController::class,'storeEmail'])->name('add.email.details');
    Route::get("/communication/add-sms-notifications",[CommunicationController::class,'sendSMS'])->name('Communication.comm_sms');
    Route::get("/communication/add-mobile-app-notifications",[CommunicationController::class,'sendMobileNot'])->name('Communication.mob_not');
    Route::post("/communication/store-mobile-app-notifications",[CommunicationController::class,'storeMobileApp'])->name('Communication.store.mobile.application');
    Route::post("/communication/add-sms-details",[CommunicationController::class,'storeSMS'])->name('add.sms.details');
    // Route::get('/communication/add-mobile-app-notifications', function () {
    //     return view('communication.comm_mobile_app');
    // })->name('Communication.comm_mobile_app');
    Route::get('/communication/add-mobile-popup', function () {
        return view('communication.popup_banner');
    })->name('Communication.comm_popup');
    Route::post("/communication/get-branches",[CommunicationController::class,'getBranch'])->name('Communication.get.branch');
    Route::get("/send-email",[CommunicationController::class,'send_comm_email']);
    Route::get("/send-app",[CommunicationController::class,'send_app_notification']);
    Route::get("/send-sms",[CommunicationController::class,'send_comm_sms']);
    Route::get("/all-notification",[NotificationController::class,'notificationManagement'])->name('notification.management');
    Route::post("/notification-setting-store",[NotificationController::class,'notiSettingStore'])->name('notifi.setting.store');
    Route::post("/save-notification-roles",[NotificationController::class,'saveNotificationRoles'])->name('save.notification.roles');
    Route::post("/fetch-role-notification",[NotificationController::class,'getNotificationRoles'])->name('fetch.role.notification');
    Route::get('/notification-setting', [NotificationController::class,'notificationManagement'])->name('admin.notifi.management');
});


Auth::routes();
