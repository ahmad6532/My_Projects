<?php

use App\Http\Controllers\API\Admin\ApprovalController;
use App\Http\Controllers\API\Admin\TaxController;
use App\Http\Controllers\HBLMFBController;
use App\Http\Controllers\ZktechoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\Admin\AdminEmployeeController;
use App\Http\Controllers\API\Admin\AssetController;
use App\Http\Controllers\API\Admin\Admin_Company_Setting_Controller;
use App\Http\Controllers\API\Admin\AdminHomeController;
use App\Http\Controllers\API\Admin\AdminProfileController;
use App\Http\Controllers\API\Admin\AdminSettingController;
use App\Http\Controllers\API\Admin\TerminationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AttendenceController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\CommunicationController;
use App\Http\Controllers\API\Admin\PromotionController;
use Laravel\passport\HasApiTokens;
use App\Http\Controllers\API\Admin\ShiftManagementController;
use App\Http\Controllers\API\Admin\PayrollController;
use App\Http\Controllers\API\Admin\LeaveManagementController;
use App\Http\Controllers\API\Admin\HolidayController;
use App\Http\Controllers\API\Admin\UserManagementController;
use App\Http\Controllers\API\Admin\AttendanceManagement;
use App\Http\Controllers\API\Admin\CompanyDocumentsController;
use App\Http\Controllers\API\Admin\DeviceManagementController;
use App\Http\Controllers\API\Admin\SmtpController;
use App\Http\Controllers\API\Admin\ThemeSettingsController;
use App\Http\Controllers\API\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\API\Admin\NotificationController;
use App\Http\Controllers\API\Admin\RolesPermissionController;
use App\Http\Controllers\CronsJobController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Cron job routes
Route::get('a', [ZktechoController::class, 'deviceStatusHistory']);
Route::get('zkt', [ZktechoController::class, 'conn']);
//to export data on hbl oracle server
// Route::get('/export-attendance-to-hbl-db', [HBLMFBController::class, 'exportDataToOracle']);
Route::get('/exportDataToOracle', [HBLMFBController::class, 'exportDataToOracle']);

// count users on zk device
// Route::get('/count-device-user', [ZktechoController::class, 'countUserOnDevice']);
Route::get('/countDeviceUser', [ZktechoController::class, 'countUserOnDevice']);
Route::get('/create-all-user-on-device', [ZktechoController::class, 'createAllUser']);

// create and update users
// Route::get('/createOrUpdate-user-on-device', [ZktechoController::class, 'createOrUpdateUser']);
Route::get('/createUser', [ZktechoController::class, 'createOrUpdateUser']);

// fetch employees form hbl database
// Route::get('/import-hbl-employee-in-db', [HBLMFBController::class, 'importEmployees']);
Route::get('/import-employee', [HBLMFBController::class, 'importEmployees']);

// fetch attendance form zkt device
// Route::get('/fetch-attendance-from-device', [ZktechoController::class, 'fetchAttendance'] );
Route::get('/fetchAttendance', [ZktechoController::class, 'fetchAttendance']);


Route::get('send_comm_email', [CronsJobController::class, 'send_comm_email']);
Route::get('send_comm_app_notification', [CronsJobController::class, 'send_comm_app_notification']);

Route::post('user-login', [LoginController::class, 'login']);
Route::post('admin-login', [LoginController::class, 'adminLogin']);
Route::post('forget-password', [LoginController::class, 'forgetPassword']);
Route::post('verify-password-otp', [LoginController::class, 'verifyPasswordOTP']);
Route::post('reset-password', [LoginController::class, 'resetPassword']);
Route::post('add-attendence', [AttendenceController::class, 'addAttendance']);
Route::get('get-all-attendance-list', [AttendenceController::class, 'getAllAttendances']);
Route::get('download-attendance-file', [AttendanceManagement::class, 'downloadAttendanceFile']);
Route::get('download-payroll-sheet', [PayrollController::class, 'downloadPayRollSheet']);


Route::get('sync-employees-data', [EmployeeController::class, 'SyncEmployeesData']);
Route::get('get-theme-setting', [ThemeSettingsController::class, 'getThemeSetting']);

Route::get('active-theme', [ThemeSettingsController::class, 'activeTheme']);

Route::middleware(['auth:api'])->group(function () {

    Route::get('user-dashboard', [UserController::class, 'userDashboard']);
    Route::get('users-list', [UserController::class, 'userlist']);
    Route::get('get-user-monthly-attendance', [AttendenceController::class, 'getUserMonthlyAttendances']);//get user monthly attendance
    Route::get('get-user-daily-attendance', [AttendenceController::class, 'getUserDailyAttendances']);//get user monthly attendance
    Route::post('update-userfinger', [BranchController::class, 'updateUserFinger']);

    //to get company logo and name
    Route::get('get-company-data', [AdminController::class, 'getCompanyData']);
    Route::get('get-branch-employees', [BranchController::class, 'getBranchEmployees']);

    // add employee details  & Pay
    Route::post('add-new-employee', [EmployeeController::class, 'addEmployeeDetails']);
    Route::post('add-employee-education', [EmployeeController::class, 'addEmpEducationDetails']);
    Route::post('add-employee-languages', [EmployeeController::class, 'addEmplanguages']);
    Route::post('add-employee-experience', [EmployeeController::class, 'addEmpExperiences']);
    Route::post('add-viion-employement', [EmployeeController::class, 'addprevEmpbyviion']);
    Route::post('add-employee-approval-details', [EmployeeController::class, 'addapprovalDetails']);
    Route::post('add-employee-family-details', [EmployeeController::class, 'addfamilyDetails']);
    Route::post('add-employee-relative-in-viion', [EmployeeController::class, 'addrelativeinViion']);
    Route::post('add-employee-related-refrences', [EmployeeController::class, 'addrelatedRefrences']);
    Route::post('add-employee-approval-details', [EmployeeController::class, 'addapprovalDetails']);

    // view employee details
    Route::get('employees-list', [EmployeeController::class, 'EmployeeDirectory']);//already exist
    Route::post('delete-employee-details', [EmployeeController::class, 'deleteEmployeedetails']);
    Route::get('search-employees-by-name', [EmployeeController::class, 'searchEmployeebyName']);
    Route::get('view-employees-details', [EmployeeController::class, 'viewEmployeedetails']);
    Route::get('view-employee-education-details', [EmployeeController::class, 'viewEmployeeEducationdetails']);
    Route::get('view-employee-languages-details', [EmployeeController::class, 'viewEmployeelanguagesdetails']);
    Route::get('view-employee-languages-details', [EmployeeController::class, 'viewEmployeeExperiencedetails']);
    Route::get('view-employee-experience-details', [EmployeeController::class, 'viewEmployeeExperiencedetails']);
    Route::get('view-viion-employement-details', [EmployeeController::class, 'viewViionEmplomentdetails']);
    Route::get('view-employee-family-details', [EmployeeController::class, 'viewEmployeefamilydetails']);
    Route::get('view-relative-employed-by-viion-details', [EmployeeController::class, 'viewrelativeViionEmployeementdetails']);
    Route::get('view-related-refrences-details', [EmployeeController::class, 'viewrelatedRefrencesdetails']);
    Route::get('view-employee-approval-details', [EmployeeController::class, 'viewApprovaldetails']);

    //  Update Employee details
    Route::post('update-employee-details', [EmployeeController::class, 'updateEmpDetails']);
    Route::post('update-employee-education', [EmployeeController::class, 'updateEmpEducationDetails']);
    Route::post('update-employee-languages-details', [EmployeeController::class, 'updateEmpLanguages']);
    Route::post('update-employee-experience-details', [EmployeeController::class, 'updateEmpExperiences']);
    Route::post('update-viion-employement', [EmployeeController::class, 'updateprevEmpbyviion']);
    Route::post('update-employee-family-details', [EmployeeController::class, 'updatefamilyDetails']);
    Route::post('update-employee-relative-in-viion', [EmployeeController::class, 'updaterelativeinViion']);
    Route::post('update-employee-related-refrences', [EmployeeController::class, 'updaterelatedRefrences']);
    Route::post('update-employee-approval-details', [EmployeeController::class, 'updateapprovalDetails']);

    //User Profile CRUD
    Route::get('user-profile', [UserController::class, 'getUserProfile']);
    Route::post('update-user-profile', [UserController::class, 'updateUserProfile']);
    Route::get('view-user-profile-details', [UserController::class, 'viewUserdetails']);
    Route::post('update-user-profile-details', [UserController::class, 'updateProfileDetails']);

    // User Management
    Route::get('search-user-by-name', [UserController::class, 'searchUserbyName']);
    Route::post('add-new-user', [UserController::class, 'addnewUser']);
    Route::post('edit-user-details', [UserController::class, 'editUserDetails']);
    Route::post('delete-user-details', [UserController::class, 'deleteUser']);

    // SMTP detail
    Route::get('view-smtp-details', [AdminController::class, 'Viewsmtpdetails']);
    Route::post('add-smtp-details', [AdminController::class, 'addSMTPdetails']);

    //Communication details
    Route::post('store-email-communication-details', [CommunicationController::class, 'storeEmailcommunication']);
    Route::post('store-sms-communication-details', [CommunicationController::class, 'storeSMScommunication']);
    Route::get('get-user-announcements', [UserController::class, 'getAnnouncements']);
    Route::get('read-announcement', [UserController::class, 'readAnnouncementStatus']);

    // Leave management
    Route::get('leave-request', [AdminController::class, 'leaveRequest']);
    Route::get('get-leave-types', [AdminController::class, 'getLeaveTypes']);
    Route::get('get-user-leaves', [AdminController::class, 'getUserLeaves']);
    Route::post('save-leave', [AdminController::class, 'saveLeave']);
    // });

    //ADMIN SIDE
    Route::group(['prefix' => 'admin'], function () {
        //DASHBOARD
        Route::get('dashboard', [AdminHomeController::class, 'index']);
        Route::get('get-attendance-present', [AdminHomeController::class, 'attendanceGraphPresent']);
        Route::get('get-attendance-absent', [AdminHomeController::class, 'attendanceGraphAbsent']);
        Route::post('get-attendance-graph', [AdminHomeController::class, 'attendanceGraph']);
        Route::post('branch-present-record', [AdminHomeController::class, 'EmpPresentData']);

        // global api's
        Route::get("get-companies", [AdminEmployeeController::class, 'getCompanies']);
        Route::post("get-companies-branches", [AdminEmployeeController::class, 'getBranches']);
        Route::get("get-countries", [AdminEmployeeController::class, 'getCountries']);
        Route::post("get-cities-by-country", [AdminEmployeeController::class, 'getCities']);
        Route::get("get-job-status", [AdminEmployeeController::class, 'getJobStatus']);
        Route::get("get-designations", [AdminEmployeeController::class, 'getDesignations']);
        Route::get("get-departments", [AdminEmployeeController::class, 'getDepartments']);
        Route::post("get-branch-employees", [AdminEmployeeController::class, 'getBranchEmployees']);
        Route::get("get-employees", [AdminEmployeeController::class, 'getEmployees']);

        // company management
        Route::post('save-company', [AdminSettingController::class, 'saveCompany']);
        Route::get('company-edit', [AdminSettingController::class, 'editCompany']);
        Route::get('get-company', [AdminSettingController::class, 'getCompany']);
        Route::post('company-update', [AdminSettingController::class, 'updateCompany']);
        Route::get('company-delete', [AdminSettingController::class, 'deleteCompany']);
        Route::get('countries', [AdminSettingController::class, 'countries']);
        Route::get('cities', [AdminSettingController::class, 'cities']);

        // Office Details
        Route::get('get-office-details', [AdminSettingController::class, 'getOfficeDetails']);
        Route::get('edit-office-details', [AdminSettingController::class, 'editOfficeDetails']);
        Route::post('save-office-details', [AdminSettingController::class, 'saveOfficeDetails']);
        Route::post('update-office-details', [AdminSettingController::class, 'updateOfficeDetails']);
        Route::post('delete-office-details', [AdminSettingController::class, 'deleteOfficeDetails']);

        // company configurations
        Route::post('save-company-configuration', [AdminSettingController::class, 'saveCompanyConfiguration']);
        Route::get('edit-company-configuration', [AdminSettingController::class, 'editCompanyConfiguration']);
        Route::post('update-company-configuration', [AdminSettingController::class, 'updateCompanyConfiguration']);
        Route::get('delete-company-configuration', [AdminSettingController::class, 'deleteCompanyConfiguration']);
        // Route::post('company-configurations',[AdminSettingController::class,'companyConfiguration']);
        Route::get('company-configurations', [AdminSettingController::class, 'companyConfiguration']);

        // Leave Settings
        Route::get('leave-settings', [AdminSettingController::class, 'leaveSetting']);
        Route::post('save-leave-setting', [AdminSettingController::class, 'saveLeaveSetting']);
        Route::get('edit-leave-setting', [AdminSettingController::class, 'editLeaveSetting']);
        Route::post('update-leave-setting', [AdminSettingController::class, 'updateLeaveSetting']);
        Route::get('delete-leave-setting', [AdminSettingController::class, 'deleteLeaveSetting']);

        //employee-profile-details
        Route::get('employee-profile', [EmployeeController::class, 'EmployeeProfile']);
        Route::get('get-employees-attendance', [EmployeeController::class, 'getEmployeeAttendance']);
        Route::get('employee-asset', [EmployeeController::class, 'employeeAsset']);
        //employeeDocumnets
        Route::get("get-employee-documents", [AdminEmployeeController::class, 'getEmployeeDocuments']); // Done
        Route::get("get-documents-name-list", [AdminEmployeeController::class, 'getDocNameList']);

        // get employee
        Route::get('get-allowance-records', [AdminEmployeeController::class, 'getAllowanceRecord']);
        Route::get('get-contribution-record', [AdminEmployeeController::class, 'getContributionRecord']);
        Route::get('get-deduction-record', [AdminEmployeeController::class, 'getDeductionRecord']);
        Route::get('get-compensation-record', [AdminEmployeeController::class, 'getCompensationRecord']);
        Route::get('get-pay-period', [AdminEmployeeController::class, 'getPayPeriod']);

        //employee
        Route::post("employees-list", [AdminEmployeeController::class, 'employeeList']); // Done
        Route::post('download-employee-list', [AdminEmployeeController::class, 'downloadEmployeeList']);

        Route::get('add-approval', [AdminEmployeeController::class, 'addApproval']);
        Route::post("employee-search", [AdminEmployeeController::class, 'getSearchedEmployee']);
        Route::post("save-employee", [AdminEmployeeController::class, 'storeEmployee']); // Done
        Route::post("save-education", [AdminEmployeeController::class, 'storeEmployeeEducation']); // Done
        Route::post("save-employment", [AdminEmployeeController::class, 'storeEmployeeExperience']);
        Route::post("save-references", [AdminEmployeeController::class, 'storeEmployeeFamilyData']);
        Route::post("save-approvals", [AdminEmployeeController::class, 'storeEmployeeApproval']);
        Route::post("save-account-detail", [AdminEmployeeController::class, 'storeAccountDetail']);
        Route::get('delete-documents-storing', [AdminEmployeeController::class, 'deleteDocumentWhileStoring']);
        Route::post('save-documents', [AdminEmployeeController::class, 'saveDocuments']);
        Route::get('log-records', [AdminEmployeeController::class, 'logRecords']);
        Route::get('delete-log', [AdminEmployeeController::class, 'logDelete']);

        // pay setup routes
        Route::post('save-employee-pay-details', [AdminEmployeeController::class, 'savePayDetails']);
        Route::post('edit-employee-pay-details', [AdminEmployeeController::class, 'editPayDetails']);
        Route::post('save-employee-sal-comp-types-details', [AdminEmployeeController::class, 'saveSalaryComponentsTypes']);
        Route::get('get-salary-component-types', [AdminEmployeeController::class, 'getComponentsTypes']);
        Route::get('salary-component-types', [AdminEmployeeController::class, 'salaryComponents']);

        // pay roll routes
        Route::post('save-payroll-period', [PayrollController::class, 'savePayRollPeriod']);
        Route::get('get-pay-periods', [PayrollController::class, 'listPayPeriod']);
        Route::get('get-payroll-approvals', [PayrollController::class, 'listPayRollApprval']);
        Route::post('update-payroll-status', [PayrollController::class, 'updatePayrollApprovalStatus']);
        Route::post('delete-pay-period', [PayrollController::class, 'destroyPayPeriod']);
        Route::get('download-payroll-csv', [PayrollController::class, 'downloadPayRollCSV']);

        // Edit Employee Data
        Route::get('edit-employee', [AdminEmployeeController::class, 'editEmployeeData']);
        Route::get('edit-education', [AdminEmployeeController::class, 'editEmployeeEducation']);
        Route::get('edit-experiences', [AdminEmployeeController::class, 'editEmployeeExperiences']);
        Route::get('edit-references', [AdminEmployeeController::class, 'editEmployeeRefrences']);
        Route::get('edit-account-detail', [AdminEmployeeController::class, 'editAccount']);
        Route::get('edit-approval', [AdminEmployeeController::class, 'editEmployeeApproval']);
        Route::get('edit-documnets', [AdminEmployeeController::class, 'editDocuments']);
        Route::get('edit-pay-period', [AdminEmployeeController::class, 'editPayPeriod']);

        // update Employeee Data
        Route::post('update-employee', [AdminEmployeeController::class, 'updateEmployeeDetails']);
        Route::post('update-employee-education', [AdminEmployeeController::class, 'updateEmployeeEducation']);
        Route::post('update-employee-experience', [AdminEmployeeController::class, 'updateEmployeeExperience']);
        Route::post('update-account-detail', [AdminEmployeeController::class, 'updateAccountDetail']);
        // Route::put('update-account-detail-profile',[AdminEmployeeController::class,'updateAccountDetailProfile']);
        Route::post('update-employee-approval', [AdminEmployeeController::class, 'updateEmployeeApproval']);
        Route::post('update-employee-reference', [AdminEmployeeController::class, 'updateEmployeeReference']);
        Route::get('change-employee-approval-status', [AdminEmployeeController::class, 'changeEmployeeApprovalStatus']);
        Route::post('update-documents', [AdminEmployeeController::class, 'updateDocuments']);
        Route::post('update-pay-period', [AdminEmployeeController::class, 'updatePayPeriod']);

        // delete api's while user update data of employees
        Route::get('delete-education-update', [AdminEmployeeController::class, 'deleteEducationWhileUpdate']);
        Route::get('delete-employement-update', [AdminEmployeeController::class, 'deleteEmploymentWhileUpdate']);
        Route::get('delete-references-update', [AdminEmployeeController::class, 'deleteReferencesWhileUpdate']);

        // Delete Employee Records
        Route::get('delete-record', [AdminEmployeeController::class, 'destroyEducation']);
        Route::get('employee-delete', [AdminEmployeeController::class, 'destroy']);
        Route::get('restore-employee', [AdminEmployeeController::class, 'restore']);
        Route::get('employee-hard-delete', [AdminEmployeeController::class, 'employeeHardDelete']);
        Route::get('delete-documents', [AdminEmployeeController::class, 'deleteDocuments']);
        Route::get('delete-compensation-record', [AdminEmployeeController::class, 'deleteCompensationRecord']);

        // company documents
        Route::get('company-documents', [CompanyDocumentsController::class, 'index']);
        Route::post('save-company-documents', [CompanyDocumentsController::class, 'saveCompanyDocument']);
        Route::get('change-status-company-documents', [CompanyDocumentsController::class, 'changeStatusCompanyDocument']);
        Route::get('delete-company-document', [CompanyDocumentsController::class, 'deleteCompanyDocument']);
        Route::post('update-company-document', [CompanyDocumentsController::class, 'updateCompanyDocument']);
        Route::get('edit-company-document', [CompanyDocumentsController::class, 'editCompanyDocument']);

        // designation
        Route::post('designation-list', [AdminEmployeeController::class, 'designationList']);
        Route::post('save-designation', [AdminEmployeeController::class, 'saveDesignation']);
        Route::get('edit-designation', [AdminEmployeeController::class, 'editDesignation']);
        Route::post('update-designation', [AdminEmployeeController::class, 'updateDesignation']);
        Route::post('search-designation', [AdminEmployeeController::class, 'searchDesignation']);
        Route::get('delete-designation', [AdminEmployeeController::class, 'deleteDesignation']);
        Route::post('download-employee-designation-list', [AdminEmployeeController::class, 'downloadEmployeeDesignationList']);

        // department
        Route::get('department-list', [AdminEmployeeController::class, 'departmentList']);
        Route::post('save-department', [AdminEmployeeController::class, 'saveDepartment']);
        Route::get('edit-department', [AdminEmployeeController::class, 'editDepartment']);
        Route::post('update-department', [AdminEmployeeController::class, 'updateDepartment']);
        Route::post('delete-department', [AdminEmployeeController::class, 'deleteDepartment']);
        Route::get('department-head-list', [AdminEmployeeController::class, 'getHeadList']);
        Route::post('search-department', [AdminEmployeeController::class, 'searchDepartment']);

        // resignation
        Route::get('resignation-list', [AdminEmployeeController::class, 'resignationList']);
        Route::post('save-resignation', [AdminEmployeeController::class, 'saveResignation']);
        Route::post('change-resignation-status', [AdminEmployeeController::class, 'changeResignationStatus']);
        Route::post('edit-resignation', [AdminEmployeeController::class, 'editResignation']);
        Route::post('update-resignation', [AdminEmployeeController::class, 'updateResignation']);
        Route::post('delete-resignation', [AdminEmployeeController::class, 'deleteResignation']);
        Route::post("search-resignation", [AdminEmployeeController::class, 'searchResignation']);
        Route::post('download-employee-resignation-list', [AdminEmployeeController::class, 'downloadEmployeeResignationList']);

        // termination
        Route::get('employee-termination', [TerminationController::class, 'termination']);
        Route::post('save-termination', [TerminationController::class, 'saveTermination']);
        Route::get('edit-termination', [TerminationController::class, 'editTermination']);
        Route::post('update-termination', [TerminationController::class, 'updateTermination']);
        Route::get('change-termination-status', [TerminationController::class, 'changeTerminationStatus']);
        Route::get('delete-termination', [TerminationController::class, 'deleteTermination']);
        Route::post('download-employee-termination-list', [TerminationController::class, 'downloadEmployeeTerminationList']);

        // promotion
        Route::get('promotion-list', [PromotionController::class, 'promotion']);
        Route::post('save-promotion', [PromotionController::class, 'savePromotion']);
        Route::post('edit-promotion', [PromotionController::class, 'editPromotion']);
        Route::post('get-details-to-add-promotion', [PromotionController::class, 'getDetailsForPromotion']);
        // Route::post('update-promotion',[PromotionController::class,'updatePromotion']);
        Route::get('change-promotion-status', [PromotionController::class, 'changePromotionStatus']);
        Route::get('delete-promotion', [PromotionController::class, 'deletePromotion']);
        Route::get('promotion-search', [PromotionController::class, 'promotionSearch']);
        Route::get('select-employee-details', [PromotionController::class, 'selectEmployeeDetails']);

        // shift management
        Route::post('shifts', [ShiftManagementController::class, 'shiftManagement']);
        Route::post('save-shift', [ShiftManagementController::class, 'saveShift']);
        Route::get('delete-shift', [ShiftManagementController::class, 'deleteShift']);
        Route::get('edit-shift', [ShiftManagementController::class, 'editShift']);
        Route::post('update-shift', [ShiftManagementController::class, 'updateShift']);

        // assign shift
        Route::post('assign-shifts', [ShiftManagementController::class, 'assignShiftList']);
        Route::post('save-assign-shift', [ShiftManagementController::class, 'saveAssignShift']);
        Route::get('delete-assign-shift', [ShiftManagementController::class, 'deleteAssignShift']);
        Route::get('edit-assign-shift', [ShiftManagementController::class, 'editAssignShift']);
        Route::post('update-assign-shift', [ShiftManagementController::class, 'updateAssignShift']);

        // tax settings
        Route::get('list-tax-setting', [TaxController::class, 'fetchTaxList']);
        Route::post('save-tex-setting', [TaxController::class, 'saveTexSetting']);
        Route::get('edit-tax-setting', [TaxController::class, 'editTaxSetting']);
        Route::post('update-tax-setting', [TaxController::class, 'updateTaxSetting']);

        // Leave Request
        Route::get('leave-request', [LeaveManagementController::class, 'leaveRequest']);
        Route::post("save-leave-request", [LeaveManagementController::class, 'saveLeaveRequest']);
        Route::get("edit-leave-request", [LeaveManagementController::class, 'editLeaveRequest']);
        Route::post("update-leave-request", [LeaveManagementController::class, 'updateLeaveRequest']);
        Route::get('get-remaining-leaves', [LeaveManagementController::class, 'getRemainingLeaves']);
        Route::get('get-all-remaining-leaves', [LeaveManagementController::class, 'getAllRemainingLeaves']);
        Route::get("update-leave-status", [LeaveManagementController::class, 'updateLeaveStatus']);
        Route::get("delete-leave-request", [LeaveManagementController::class, 'destroyLeaveRequest']);
        Route::get('leave-types', [LeaveManagementController::class, 'leaveTypes']);
        Route::post('save-leave-types', [LeaveManagementController::class, 'saveLeaveTypes']);

        // Leave Status
        Route::get('leave-status', [LeaveManagementController::class, 'leaveStatus']);

        // version
        Route::get('version-history', [AdminHomeController::class, 'versionHistory']);
        Route::post('save-version', [AdminHomeController::class, 'saveVersion']);
        Route::get('view-version', [AdminHomeController::class, 'viewVersion']);

        // Holiday
        Route::get("holidays", [HolidayController::class, 'holidaysList']);
        Route::post("save-holiday", [HolidayController::class, 'saveHolidays']);
        Route::get("edit-holiday", [HolidayController::class, 'editHoliday']);
        Route::post("update-holiday", [HolidayController::class, 'updateHoliday']);
        Route::get("delete-holiday", [HolidayController::class, 'destroyHoliday']);
        Route::get("holiday-search", [HolidayController::class, 'getsearchedHoliday']);
        Route::get("holiday-branches", [HolidayController::class, 'getHolidayBranche']);

        // user management
        Route::get("users-list", [UserManagementController::class, 'usersList']);
        Route::post("save-user", [UserManagementController::class, 'saveUser']);
        Route::get('delete-user', [UserManagementController::class, 'destroyUser']);
        Route::get('edit-user', [UserManagementController::class, 'editUserDetails']);
        Route::get('edit-profile', [UserManagementController::class, 'editProfile']);
        Route::post('update-user', [UserManagementController::class, 'updateUser']);

        // Roles and permission
        Route::get("roles_permission", [RolesPermissionController::class, 'RolesPermission']);
        Route::get("add-roles-permissions", [RolesPermissionController::class, 'addRolesPermission']);
        Route::post("save-roles-permissions", [RolesPermissionController::class, 'saveRolesPermission']);
        Route::get("edit-roles-permissions/{id}", [RolesPermissionController::class, 'editRolesPermission']);
        Route::post("update-roles-permissions/{id}", [RolesPermissionController::class, 'updateRolesPermission']);

        // attendence sheet
        Route::get("daily-attendance-sheet", [AttendanceManagement::class, 'dailyAttendance']);
        Route::get("get-attendance-details", [AttendanceManagement::class, 'getAttendanceDetails']);
        Route::post("add-manually-attendence", [AttendanceManagement::class, 'addManuallyAttendance']);
        Route::get("search-attendence", [AttendanceManagement::class, 'getEmpAttendenceSearch']);
        Route::get("monthly-attendance-sheet", [AttendanceManagement::class, 'monthlyAttenSheet']);
        Route::get('yearly-attendance-sheet', [AttendanceManagement::class, 'yearlyDetail']);
        // Route::get("download-attendance-sheet/{id}/{date}",[AttendanceManagement::class,'downloadAttendanceSheet']);
        Route::get("download-attendance-sheet", [AttendanceManagement::class, 'downloadAttendanceSheet']);


        //Route::get('download-attendance', [AttendanceController::class, 'downloadAttendanceSheet']);
        Route::get("employee-attendance", [AttendanceManagement::class, 'getattendance']);
        Route::get("leave-balance", [AttendanceManagement::class, 'LeaveBalance']);

        // device management
        Route::get('device-management', [DeviceManagementController::class, 'deviceManagement']);
        Route::post('save-device', [DeviceManagementController::class, 'saveDevice']);
        Route::get('edit-device', [DeviceManagementController::class, 'editDevice']);
        Route::post('update-device', [DeviceManagementController::class, 'updateDevice']);
        Route::post('add-device-type', [DeviceManagementController::class, 'addDeviceType']);
        Route::get('delete-device', [DeviceManagementController::class, 'deleteDevice']);
        Route::get('sync-all-devices', [DeviceManagementController::class, 'syncAllDevices']);
        Route::get('sync-single-device', [DeviceManagementController::class, 'syncSingleDevice']);

        // Device Dashboard Routes
        Route::get('device-listing', [DeviceManagementController::class, 'deviceList']);
        Route::get('device-counting', [DeviceManagementController::class, 'deviceCount']);

        // smtp's
        Route::get("smtp", [SmtpController::class, 'smtp']);
        Route::post('update-smtp', [SmtpController::class, 'updateSMTP']);

        // notification management
        Route::get("all-notification-management", [NotificationController::class, 'notificationManagement']);
        Route::post("store-notitification-settings", [NotificationController::class, 'notiSettingStore']);
        Route::get("notification-with-roles", [NotificationController::class, 'getNotificationRoles']);
        Route::post("save-notification-with-roles", [NotificationController::class, 'saveNotificationRoles']);
        Route::get('clear-notifications', [NotificationController::class, 'clearNotification']);
        Route::get('all-notifications', [NotificationController::class, 'allnotifications']);
        Route::get('get-notification', [NotificationController::class, 'getNotification']);
        Route::get('change-status', [NotificationController::class, 'changeStatus']);

        //asset

        Route::get('all-asset', [AssetController::class, 'allAsset']);
        Route::post('save-asset', [AssetController::class, 'saveAsset']);
        Route::post('update-asset', [AssetController::class, 'updateAsset']);
        Route::post('delete-assets', [AssetController::class, 'deleteAsset']);
        Route::post('add-asset-types', [AssetController::class, 'addAssetTypes']);
        Route::get('assign-avaiable-asset', [AssetController::class, 'assignAvaiableAssetRecord']);
        Route::get('employee-list', [AssetController::class, 'employeeList']);
        Route::get('get-avaiable-asset', [AssetController::class, 'getAvaiableAsset']);
        Route::get('get-assigned-assets', [AssetController::class, 'getAssignedAsset']);
        Route::get('get-deposed-assets', [AssetController::class, 'getDeposedAssets']);
        Route::get('get-asset-type', [AssetController::class, 'assetType']);
        Route::get('edit-assigned-asset', [AssetController::class, 'getAssignedAssetRecord']);
        Route::get('asset-details', [AssetController::class, 'getAvaiableAssetRecord']);
        Route::get('asset-history', [AssetController::class, 'assetHistory']);
        Route::get('delete-asset-history', [AssetController::class, 'deleteAssetHistory']);
        Route::post('assigned-asset', [AssetController::class, 'assignedAsset']);

        //profile-update

        Route::put('profile-update', [AdminProfileController::class, 'updateProfile']);

        // apperance
        Route::get('theme-list', [ThemeSettingsController::class, 'themeList']);
        Route::post('update-theme', [ThemeSettingsController::class, 'updatetheme']);
        Route::post('save-appearance', [ThemeSettingsController::class, 'saveAppearance']);
        Route::get('edit-apperance-theme', [ThemeSettingsController::class, 'editApperanceTheme']);
        Route::get('apply-theme', [ThemeSettingsController::class, 'setDefaultTheme']);
        Route::get('delete-theme', [ThemeSettingsController::class, 'deleteTheme']);


        // payroll level setting routes
        Route::get('get-emp-for-approvals',[ApprovalController::class, 'getEmpForApproval']);
        Route::get('get-approval-setting',[ApprovalController::class, 'getApprovalSetting']);
        Route::post('save-approval-setting', [ApprovalController::class, 'saveApprovalLevels']);
        Route::get('get-approval-status',[ApprovalController::class, 'getApprovalStatus']);



    });

    //logout
    Route::post('logout', [LoginController::class, 'logout']);

    Route::post('user-logout', [LoginController::class, 'userLogout']);
});


