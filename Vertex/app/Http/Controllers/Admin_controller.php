<?php

namespace App\Http\Controllers;

use App\Models\CronJobHistory;
use App\Models\Emp_salary;
use App\Models\Monthly_payroll;
use App\Models\ZKSyncEmp;
use App\User;
use DateTime;
use stdClass;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Leave;
use App\Models\Location;
use App\Models\Config;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\Setting;
use App\Models\City;
use App\Models\Language;
use App\Models\Department;
use App\Models\DeviceType;
use App\Models\Leave_Type;
use Carbon\CarbonInterval;
use App\Models\Country;
use App\Models\CronJobTime;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\EmployeePromotion;
use App\Models\Leave_setting;
use App\Models\Theme_Setting;
use App\Models\user_approval;
use App\Models\user_language;
use App\Models\EmployeeEducationDetail;
use App\Models\EmployeeResignation;
use App\Models\UserAttendence;
use App\Models\CompanySetting;
use App\Models\Emp_termination;
use App\Models\EmployeeDetail;
use App\Models\ShiftManagement;
use App\Models\user_experience;
use App\Models\UserDailyRecord;
use App\Models\Version_History;
use App\Models\DeviceManagement;
use App\Models\related_refrence;
use Yajra\DataTables\DataTables;
use App\Models\EmployeeHistory;
use App\Models\NotificationEmail;
use App\Models\UserMonthlyRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\CSV;
use App\Models\user_family_refrence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\NotificationManagement;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CronsJobController;
use App\Helper\AppHelper;

class Admin_controller extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:attendance-sheet-all|attendance-sheet-read', ['only' => ['AttenSheet']]);
        $this->middleware('permission:today-attendance-all|today-attendance-read', ['only' => ['EmpAttendence']]);
    }

    public function viewprofile()
    {
        $users = User::where('id', Auth::user()->id)->first();
        return view('profile', compact('users'));
    }

    // public function toDuplicateEmail(){
    //     $employees = EmployeeDetail::all();
    //     foreach($employees as $employee){
    //         $employee->official_email = $employee->emp_email;
    //         $employee->update();
    //     }

    //     $users = User::all();
    //     foreach($users as $user){
    //         $user->personal_email = $user->email;
    //         $user->update();
    //     }
    // }

    public function getsearchedUser(Request $request)
    {
        $fetchUsers = User::where('fullname', 'LIKE', $request->user_name . '%')
            ->select('id', 'fullname', 'role_id', 'email', 'is_active', 'is_deleted')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($fetchUsers as $user) {
            $user['role_name'] = Role::where('id', $user->role_id)->first()['role_name'];
            if ($user->is_deleted == 1) {
                continue;
            } else {
                //    $fetchUsers[] =  $user;
            }
        }

        if (isset($fetchUsers) && count($fetchUsers) > 0) {
            return response()->json(["success" => true, "data" => ["user" => $fetchUsers]]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function addNewUser()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $roles = Role::orderBy('role_name','asc')->get();
            $companies = Company::where('is_active', '1')->where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        } else {
            $roles = Role::where('user_id', $user->id)->get();
            $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name', 'asc')->get();
            $branches = Location::whereIn('company_id', $user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->get();
        }

        return view('user_management.addUser', compact('roles', 'branches', 'companies'));
    }

    public function payRollEmpSalary(Request $request)
    {
        $user = auth()->user();
        $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);
        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';
        if($user_role == '1'){
            if ($selectBranch == 'all') {
                $emp_salary = Emp_salary::orderBy('emp_salary.id', 'desc')
                    ->get();
            } else {
                $emp_salary = Emp_salary::where('emp_salary.branch_id', $selectBranch)
                    ->orderBy('emp_salary.id', 'desc')
                    ->get();
            }
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        }else{
            if ($selectBranch == 'all') {
                $emp_salary = Emp_salary::whereIn('emp_salary.company_id',$user_company_id)
                    ->whereIn('emp_salary.branch_id',$user_branch_id)
                    ->orderBy('emp_salary.id', 'desc')
                    ->get();
            } else {
                $emp_salary = Emp_salary::whereIn('emp_salary.company_id',$user_company_id)
                    ->where('emp_salary.branch_id', $selectBranch)
                    ->orderBy('emp_salary.id', 'desc')
                    ->get();
            }
            $branches = Location::whereIn('company_id',$user_company_id)
                ->whereIn('id',$user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->get();
        }
        foreach ($emp_salary as $key => $emp_name) {
            $emp_name->emp_name = EmployeeDetail::where('id', $emp_name->emp_id)->where('is_deleted','0')->first();
        }
        foreach ($emp_salary as $key => $emp_desig) {
            $emp_desig->emp_desig = user_approval::where('emp_id', $emp_desig->emp_id)->where('is_deleted','0')->first();
            if($emp_desig->emp_desig){
                $emp_desig->designation_name = Designation::where('id',$emp_desig->emp_desig->designation_id)->first();
                if($emp_desig->designation_name){
                    $emp_desig->department = Department::where('id',$emp_desig->designation_name->department_id)->first();
                }else{
                    $emp_desig->department = '';
                }
            }else{
                $emp_desig->designation_name = '';
            }
        }
        // return $emp_salary;
        return view('payroll.empsalary.index',compact('emp_salary','user','branches','selectBranch'));
    }

    public function payRollAddSalary(Request $request){
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);
        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }
        return view('payroll.empsalary.addsalary',compact('companies'));
    }

    public function saveEmpSalary(Request $request){
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'net_salary' => 'required',
            'basic' => 'required',
            'tax' => 'required',
            'mobile_allowance' => 'required',
            'leave_charges' => 'required',
            'fuel_allownce' => 'required',
            'prof_tax' => 'required',
            'car_allownce' => 'required',
            'other_charges' => 'required',
            'medical_allownce' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $emp_salary = new Emp_salary;
        $emp_salary->company_id = $request->company_id;
        $emp_salary->branch_id = $request->branch_id;
        $emp_salary->emp_id = $request->emp_id;
        $emp_salary->basic_salary = $request->basic;
        $emp_salary->net_salary = $request->net_salary;
        $emp_salary->mobile_allowance = $request->mobile_allowance;
        $emp_salary->fuel_allowance = $request->fuel_allownce;
        $emp_salary->car_allowance = $request->car_allownce;
        $emp_salary->medical_allowance = $request->medical_allownce;
        $emp_salary->salary_tax = $request->tax;
        $emp_salary->leave_charges = $request->leave_charges;
        $emp_salary->other = $request->other_charges;
        $emp_salary->prof_tax = $request->prof_tax;
        $emp_salary->save();
        $employee_detail = EmployeeDetail::where('id', $request->emp_id)->first();
        $msg = 'Employee"'.ucwords($employee_detail->emp_name).'" Salary Added Successfully';
        createLog('emp_salary_action',$msg);
        return redirect('payroll/employee/salary')->with('success', 'Employee Salary Added Successfully');
    }
    public function editEmpSalary(Request $request,$id){
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);
        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }
        $emp_salary = Emp_salary::where('id',$id)->first();
        // return $emp_salary;
        return view('payroll.empsalary.editsalary',compact('emp_salary','companies'));
    }

    public function UpdateEmpSalary(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'net_salary' => 'required',
            'basic' => 'required',
            'tax' => 'required',
            'mobile_allowance' => 'required',
            'leave_charges' => 'required',
            'fuel_allownce' => 'required',
            'prof_tax' => 'required',
            'car_allownce' => 'required',
            'other_charges' => 'required',
            'medical_allownce' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $emp_salary = Emp_salary::findOrFail($id);
        $emp_salary->company_id = $request->company_id;
        $emp_salary->branch_id = $request->branch_id;
        $emp_salary->emp_id = $request->emp_id;
        $emp_salary->basic_salary = $request->basic;
        $emp_salary->net_salary = $request->net_salary;
        $emp_salary->mobile_allowance = $request->mobile_allowance;
        $emp_salary->fuel_allowance = $request->fuel_allownce;
        $emp_salary->car_allowance = $request->car_allownce;
        $emp_salary->medical_allowance = $request->medical_allownce;
        $emp_salary->salary_tax = $request->tax;
        $emp_salary->leave_charges = $request->leave_charges;
        $emp_salary->other = $request->other_charges;
        $emp_salary->prof_tax = $request->prof_tax;
        $emp_salary->update();
        $employee_detail = EmployeeDetail::where('id', $request->emp_id)->first();
        $msg = 'Employee"'.ucwords($employee_detail->emp_name).'" Salary Updated Successfully';
        createLog('emp_salary_action',$msg);
        return redirect('payroll/employee/salary')->with('success', 'Employee Salary Updated Successfully');
    }
    public function monthlyPayRollEmpSalary(Request $request){
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selected = isset($request->branch_id) ? $request->branch_id : 'all';
        $current_month_year = isset($request->year_month) ? date('F Y', strtotime($request->year_month)) : date('F Y');
        $month_number = isset($request->year_month) ? date('m', strtotime($request->year_month)) : date('m');
        $number_of_days = Carbon::now()->month($month_number)->daysInMonth;
        $start_date = isset($request->year_month) ? date('Y-m-01', strtotime($request->year_month)) : date('Y-m-01');
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }
        if (isset($request->branch_id) && $request->branch_id != 'all') {
            $branch = Location::where('is_deleted', '0')->where('id', $request->branch_id)->first();
            $branch_id = $branch->branch_id;
        } else {
            $branch_id = null;
        }
        $employee_salary = Emp_salary::select('emp_id')->get();
        $employeeIdsWithSalary = $employee_salary->pluck('emp_id');
        if ($selected != 'all') {
            if ($user_role == '1') {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->where('employee_details.branch_id', $request->branch_id)
                ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                ->where('employee_details.is_deleted', '0')
                ->whereIn('employee_details.id', $employeeIdsWithSalary)
                ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            } else {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                    ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                    ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->where('employee_details.branch_id', $request->branch_id)
                    ->where('employee_details.is_deleted', '0')
                    ->whereIn('employee_details.id', $employeeIdsWithSalary)
                    ->where('employee_details.status', '1')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->get();
            }
        } else {
            if ($user_role == '1') {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                ->where('employee_details.is_deleted', '0')
                ->whereIn('employee_details.id', $employeeIdsWithSalary)
                ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            } else {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                ->whereIn('employee_details.company_id', $user_company_id)
                ->whereIn('employee_details.branch_id', $user_branch_id)
                ->where('employee_details.is_deleted', '0')
                ->whereIn('employee_details.id', $employeeIdsWithSalary)
                ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            }
        }
        foreach($employees as $employee){
            $employee->branch = Location::where('id',$employee->branch_id)->where('is_deleted', '0')->select('branch_name')->first();
            if($employee->approval){
                $employee->designation_name = Designation::where('id',$employee->approval->designation_id)
                ->select('name')->first();
            }else {
                $employee->designation_name = '';
            }
            $employee->salary = Emp_salary::where('emp_id',$employee->id)
            ->select('emp_id','basic_salary','net_salary','mobile_allowance')->first();
            $employee->Monthly_payroll = Monthly_payroll::where('emp_id',$employee->id)
            ->where('year',$year)
            ->where('month',$month)
            ->select('id','conveince_allowance','remarks','status')
            ->first();
        }
        // return $employees;
        return view('payroll.monthly.index',compact('employees','user','selected','branches','current_month_year'));
    }
    public function saveMonthlyPayRoll(Request $request){
        // return $request->all();
         $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
            $monthly_payroll_record = Monthly_payroll::where('emp_id',$request->emp_id)
            ->where('year',$year)
            ->where('month',$month)
            ->first();
            if(!$monthly_payroll_record){
                $monthly_payroll = new Monthly_payroll;
                $monthly_payroll->company_id = $request->company_id;
                $monthly_payroll->branch_id = $request->branch_id;
                $monthly_payroll->emp_id = $request->emp_id;
                $monthly_payroll->current_salary = $request->current_salary;
                $monthly_payroll->net_salary = $request->net_salary;
                $monthly_payroll->conveince_allowance = $request->conveince_allowance;
                $monthly_payroll->increment = $request->increment;
                $monthly_payroll->arrears = $request->arrears;
                $monthly_payroll->late_count = $request->late_count;
                $monthly_payroll->absent_ELA = $request->absent_ELA;
                $monthly_payroll->absent_L_adj = $request->absent_L_adj;
                $monthly_payroll->mobile_allowance = $request->mobile_allowance;
                $monthly_payroll->late_deduction = $request->late_deduction;
                $monthly_payroll->remarks = $request->remark;
                $monthly_payroll->year = $year;
                $monthly_payroll->month = $month;
                $monthly_payroll->status = $request->approval;
                $monthly_payroll->save();
            }else{
                $monthly_payroll = Monthly_payroll::where('id',$monthly_payroll_record->id)->first();
                $monthly_payroll->conveince_allowance = $request->conveince_allowance;
                $monthly_payroll->remarks = $request->remark;
                $monthly_payroll->update();
            }
        return redirect()->back();
    }
    public function saveMonthlyPayRollProcessApprove(Request $request){
         $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
            $monthly_payroll_record = Monthly_payroll::where('emp_id',$request->id)
            ->where('year',$year)
            ->where('month',$month)
            ->first();
            if(!$monthly_payroll_record){
                return redirect()->back()->with('error', "Firstly Add Conveince and Remarks");
              }else{
                  $monthly_payroll = Monthly_payroll::where('id',$monthly_payroll_record->id)->first();
                  $monthly_payroll->status = '1';
                  $monthly_payroll->update();
              }
        return redirect()->back();
    }
    public function saveMonthlyPayRollProcessDeline(Request $request){
         $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
            $monthly_payroll_record = Monthly_payroll::where('emp_id',$request->id)
            ->where('year',$year)
            ->where('month',$month)
            ->first();
            if(!$monthly_payroll_record){
              return redirect()->back()->with('error', "Firstly Add Conveince and Remarks");
            }else{
                $monthly_payroll = Monthly_payroll::where('id',$monthly_payroll_record->id)->first();
                $monthly_payroll->status = '2';
                $monthly_payroll->update();
            }
        return redirect()->back();
    }
    public function MonthlyPayRoll(Request $request){
        // return $request->all();
        // $companies = Company::orderBy('company_name','asc')->get();
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selected = isset($request->company_id) ? $request->company_id : 'all';
        $current_month_year = isset($request->year_month) ? date('F Y', strtotime($request->year_month)) : date('F Y');
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->get();
        } else {
            $companies = Company::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }
        if (isset($request->company_id) && $request->company_id != 'all') {
            $company = Company::where('is_deleted', '0')->where('id', $request->company_id)->first();
            $company_id = $company->company_id;
        } else {
            $company_id = null;
        }

        if ($selected != 'all') {
            if ($user_role == '1') {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->where('employee_details.company_id', $request->company_id)
                ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                ->where('employee_details.is_deleted', '0')
                ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            } else {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                    ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                    ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->where('employee_details.branch_id', $request->branch_id)
                    ->where('employee_details.is_deleted', '0')
                    ->where('employee_details.status', '1')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->get();
            }
        } else {
            if ($user_role == '1') {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                ->where('employee_details.is_deleted', '0')
                ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            } else {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->select('employee_details.id','employee_details.emp_id','employee_details.company_id','employee_details.emp_image','employee_details.branch_id','employee_details.emp_name')
                ->whereIn('employee_details.company_id', $user_company_id)
                ->whereIn('employee_details.branch_id', $user_branch_id)
                ->where('employee_details.is_deleted', '0')
                ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            }
        }
        // return $employees;
        return view('payroll.company.index',compact('companies','current_month_year','selected'));
    }
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'user_password' => ['required', 'string', 'min:8'],
            'expiry_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $company_id = $request->company_id;
        $companyString = implode(',', $company_id);


        if (in_array('all', $request->company_id)) {
            $company_id = Company::where('is_active', '1')->where('is_deleted', '0')->pluck('id')->toArray();
            $companyString = implode(',', $company_id);
        } else {
            $company_id = $request->company_id;
            $companyString = implode(',', $company_id);
        }
        if (in_array('all', $request->branch_id)) {
            $branch_company_id = explode(',',$companyString);
                $branch_id = Location::where('is_deleted', '0')->whereIn('company_id',$branch_company_id)->pluck('id')->toArray();
                $branchString = implode(',', $branch_id);
            } else {
                $branch_id = $request->branch_id;
                $branchString = implode(',', $branch_id);
            }
        $user = User::create([
            'role_id' => $request->input('user_role_id'),
            'branch_id' => $branchString,
            'company_id' => $companyString,
            'email' => $request->email,
            'fullname' => ucwords($request->user_name),
            'password' => Hash::make($request->user_password),
            'is_active' => $request->input('user_status'),
            'is_pin_enable' => $request->input('user_pin_status'),
            'can_update_face' => $request->input('user_face_status'),
            'is_attendance_allowed' => $request->input('is_app_attendance_allowed'),
            'expiry_date' => Carbon::parse($request->expiry_date)->format('Y-m-d')
        ]);
        $msg = 'Added "'.$user->fullname.'"';
        createLog('user_action',$msg);
        $user->syncRoles($request->input('user_role_id'));

        return redirect('/user-management')->with('success', "User Added Successfully");
    }

    public function editUserDetails(Request $request, int $id)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $roles = Role::orderBy('role_name','asc')->get();
            $companies = Company::orderBy('company_name','asc')->get();
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name','asc')->get();
        } else {
            $roles = Role::where('user_id', $user->id)->get();
            $companies = Company::whereIn('id', $user_company_id)->orderBy('company_name','asc')->get();
            $branches = Location::whereIn('company_id', $user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name','asc')
                ->get();
        }

        $Userdetails = User::findOrFail($id);
        return view('user_management.editUser', compact('Userdetails', 'roles', 'branches', 'companies'));
    }

    public function Updateuser(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email,' . $request->id,
            'expiry_date' => 'required',
            'branch_id' => 'required',
            'company_id' => 'required',
            'user_role_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $company_id = $request->company_id;
        $companyString = implode(',', $company_id);

        $branch_id = $request->branch_id;
        $branchString = implode(',', $branch_id);

        $user = User::where('id', $id)->first();
        $user->role_id = $request->user_role_id;
        $user->branch_id = $branchString;
        $user->company_id = $companyString;

        if (!empty($request->email)) {
            $user->email = $request->email;
        }
        $user->fullname = $request->user_name;
        if (!empty($request->new_password)) {
            $newpass = Hash::make($request->new_password);
        } else {
            $newpass = $user->password;
        }
        $user->password = $newpass;
        $user->is_active = $request->input('user_status');
        $user->is_pin_enable = $request->input('user_pin_status');
        $user->can_update_face = $request->input('user_face_status');
        $user->is_attendance_allowed = $request->input('is_app_attendance_allowed');
        $user->expiry_date = Carbon::parse($request->expiry_date)->format('Y-m-d');
        $user->update();

        DB::table('model_has_roles')
            ->where('model_id', $id)
            ->update([
                'role_id' => $request->user_role_id,
            ]);

        $msg = 'updated "'.$user->fullname.'"';
        createLog('user_action',$msg);

        return redirect('/user-management')->with('success', 'User Updated Successfully');
    }

    public function updateUserDetails(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'user_phone' => 'nullable|min:10|max:11',
            'new_password' => 'nullable|min:8',
            'password_confirmation' => 'same:new_password',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $UpdateUser = User::findOrFail($id);
        if ($UpdateUser) {

            $uploadPath = 'images/';
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move('images/', $filename);
                $image = $uploadPath . $filename;
            } else {
                $image = $UpdateUser->image;

            }
        }
        if (!empty($request->new_password)) {
            $newpass = Hash::make($request->new_password);
        } else {
            $newpass = $UpdateUser->password;
        }
        User::where('id', Auth::user()->id)->update([
            'fullname' => $request->user_name,
            'phone' => $request->user_phone,
            'password' => $newpass,
            'image' => $image,
        ]);

        $msg = 'Profile Updated Successfully';
        createLog('user_action',$msg);

        return redirect()->back()->with('success', 'Profile details updated successfully');
    }

    public function destroyUser(int $id)
    {
        $delUser = User::findOrFail($id);
        $delUser->is_deleted = '1';
        $delUser->update();

        $msg = 'Deleted to "'.$delUser->fullname.'"';
        createLog('user_action',$msg);
        return redirect()->back()->with('success', 'User Deleted Successfully');
    }

    public function calender()
    {
        return view('Admin.calender');
    }

    public function Communication()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $getBranches = Location::where('is_deleted', '0')->orderBy('branch_name','asc')->get();
        } else {
            $getBranches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name','asc')
                ->get();
        }

        return view('communication.comm_email', compact('getBranches'));
    }

    public function dailyAttendance(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $searched_date = isset($request->searchDate) ? date('Y-m-d', strtotime($request->searchDate)) : Carbon::now()->format('Y-m-d');
        $current_date = isset($request->searchDate) ? date('d-m-Y', strtotime($request->searchDate)) : Carbon::now()->format('d-m-Y');
        $selected = isset($request->branch_id) ? $request->branch_id : 'all';

        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->paginate(20);
        }

        if ($selected == 'all') {
            if ($user_role == 1) {
                $employees = EmployeeDetail::with([
                    'user_attendance' => function ($query) use ($searched_date) {
                        $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                    }
                ])
                    ->with('resignations', 'approval', 'terminations','leaves', 'holidays')
                    ->where('employee_details.is_deleted', '0')
                    ->where('employee_details.status','1')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->paginate(20);
            } else {
                $employees = EmployeeDetail::with([
                    'user_attendance' => function ($query) use ($searched_date) {
                        $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                    }
                ])
                    ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->whereIn('employee_details.branch_id', $user_branch_id)
                    ->where('employee_details.status','1')
                    ->where('employee_details.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->paginate(20);

            }
        } else {
            if ($user_role == 1) {
                $employees = EmployeeDetail::with([
                    'user_attendance' => function ($query) use ($searched_date) {
                        $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                    }
                ])
                    ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                    ->where('employee_details.branch_id', $selected)
                    ->where('employee_details.is_deleted', '0')
                    ->where('employee_details.status','1')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->paginate(20);
            } else {
                $employees = EmployeeDetail::with([
                    'user_attendance' => function ($query) use ($searched_date) {
                        $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                    }
                ])
                    ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->where('employee_details.branch_id', $selected)
                    ->where('employee_details.is_deleted', '0')
                    ->where('employee_details.status','1')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->paginate(20);
            }
        }

        $attendanceData = [];

        foreach ($employees as $key => $employee) {
            $company_setting = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('is_deleted', '0')
                ->first();

            $workingHours = NULL;
            $absentCount = 0;
            $LateCount = 0;
            $halfCount = 0;

            $holidaysArray = [];
            $leavesArray = [];
            $check_in_address = [];
            $check_out_address = [];
            $get_check_in_ip_address = [];
            $get_check_out_ip_address = [];
            //get Holiday
            $eligibleHolidays = Holiday::where('is_deleted', '0')
            ->where('is_active', '1')
            ->get();
            foreach ($eligibleHolidays as $holiday) {
                $holidayCompanyIds = explode(',', $holiday->company_id);
                $holidayBranchIds = explode(',', $holiday->branch_id);

                if (in_array($employee->company_id, $holidayCompanyIds) && in_array($employee->branch_id, $holidayBranchIds)) {
                    $startDate = Carbon::parse($holiday->start_date);
                    $endDate = Carbon::parse($holiday->end_date);

                    while ($startDate->lte($endDate)) {
                        if (strtotime($holiday['start_date']) <= strtotime($searched_date) || strtotime($holiday['end_date']) <= strtotime($searched_date)) {
                            $holidaysArray[$startDate->toDateString()] = 'Holiday';
                        }
                        $startDate->addDay();
                    }
                }
            }
            //get leaves
            foreach ($employee->leaves as $leaves) {
                if (strtotime($leaves['from_date']) <= strtotime($searched_date) || strtotime($leaves['to_date']) <= strtotime($searched_date)) {
                    $startDate = Carbon::parse($leaves['from_date']);
                    $endDate = Carbon::parse($leaves['to_date']);

                    while ($startDate->lte($endDate)) {
                        if ($startDate->month == Date('m', strtotime($searched_date)) && $startDate->year == Date('Y', strtotime($searched_date))) {
                            $leavesArray[$startDate->toDateString()] = 'Leave';
                        }

                        // Move to the next date
                        $startDate->addDay();
                    }
                }
            }

            $data = UserAttendence::where('emp_id', $employee->id)
                ->whereDate('created_at', 'LIKE', $searched_date . '%')
                ->get();

            if ($data->isEmpty()) {
                if (array_key_exists($searched_date, $holidaysArray)) {
                    $attendance = 'Holiday';
                } elseif (array_key_exists($searched_date, $leavesArray)) {
                    $attendance = 'Leave';
                } else {
                    $attendance = 'Absent';
                }
            } else {
                foreach ($data as $attendanceRecord) {
                    if ($company_setting && $attendanceRecord->check_out != null && $attendanceRecord->check_out != '') {
                        $endTime = Carbon::parse($attendanceRecord->check_out);
                        $startTime = Carbon::parse($attendanceRecord->check_in);
                        $duration = $endTime->diffInMinutes($startTime);
                        $workingHours = floor($duration / 60) . 'h:' . ($duration - floor($duration / 60) * 60) . 'm';
                        $halfDayInMinuts = ($company_setting->half_day)*60;
                        if ($duration <= $halfDayInMinuts) {
                            $attendance = 'Half Leave';
                            $halfCount++;
                        } elseif ($startTime < $company_setting->late_time) {
                            $attendance = 'Late';
                            $LateCount++;
                        } else {
                            $attendance = 'Full Day';
                        }
                        $get_check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address:'';
                        $get_check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address:'';
                        $check_out_address = $attendanceRecord->check_out_address;
                        $check_in_address = $attendanceRecord->check_in_address;
                    } else {
                        $attendance = 'Present';
                        $get_check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address:'';
                        $get_check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address:'';
                        $check_in_address = $attendanceRecord->check_in_address;
                    }
                }
            }
            $attendanceData[] = [
                'employee_id' => $employee->id,
                'employee_name' => $employee->emp_name,
                'attendance' => $attendance,
                'workingHours' => isset($workingHours) ? $workingHours : null,
                'check_in_ip_address' => $get_check_in_ip_address,
                'check_out_ip_address' => $get_check_out_ip_address,
                'check_in_address' => $check_in_address,
                'check_out_address' => $check_out_address,
            ];
        }
        return view('attendence.EmpAttendence', compact('selected','user' ,'current_date', 'employees', 'branches', 'attendanceData'));
    }

    public function getEmployees(Request $request)
    {
        $data = EmployeeDetail::where('is_deleted', '0')
            ->where('status','1')
            ->where('branch_id', $request->branch_id)
            ->orderBy('emp_name', 'asc')
            ->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getattendance(Request $request)
    {
        $date = $request->dateInput1;
        $emp_id = $request->emp_id;

        // Convert $date to the correct format if needed
        $date = date('Y-m-d', strtotime($date));

        $data = UserAttendence::where('emp_id', $emp_id)
            ->whereDate('created_at', '=', $date)
            ->first();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getDesination(Request $request)
    {
        $job_position = user_approval::where('is_deleted', '0')
            ->where('emp_id', $request->emp_id)
            ->first()['designation_id'];
        $data = Designation::where('id', $job_position)
            ->first();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function addManuallyAttendance(Request $request)
    {
        if (isset($_FILES["attendance_list"])) {
            $request->validate([
                'attendance_list' => 'required|file|max:30550',
            ]);
            $filename = $_FILES["attendance_list"]["tmp_name"];
            ;
            $attendance_list = [];
            if ($_FILES["attendance_list"]["size"] > 0) {
                $tempFile = $request->file('attendance_list');
                $destinationPath = public_path('/csvFiles');
                $csvfile = time() . '_' . $tempFile->getClientOriginalName();
                $tempFile->move($destinationPath, $csvfile);
                $filePath = $destinationPath . '/' . $csvfile;
                $file = fopen($filePath, "r");
                $data = [];
                while (!feof($file)) {
                    $line = fgets($file);
                    $parts = explode("\t", $line);
                    if (count($parts) >= 3) {
                        $id = trim($parts[0]);
                        $name = trim($parts[1]);
                        $created_at = trim($parts[2]);
                        $dateParts = explode(" ", $created_at);
                        $date = $dateParts[0];
                        $time = explode(":", $dateParts[1]);
                        $hour = intval($time[0]);
                        $minute = intval($time[1]);
                        if ($hour < 12) {
                            $check_in = $hour . ':' . $minute;
                            $check_out = null;
                        } else {
                            $check_in = null;
                            $check_out = $hour . ':' . $minute;
                        }
                        $key = $id . ' ' . $date;
                        if (!isset($data[$key])) {
                            $data[$key] = [
                                'emp_id' => $id,
                                'emp_name' => $name,
                                'created_at' => $created_at,
                                'check_in' => $check_in,
                                'check_out' => $check_out
                            ];
                        } else {
                            if ($check_in != null) {
                                $data[$key]['check_in'] = $check_in;
                            }
                            if ($check_out != null) {
                                $data[$key]['check_out'] = $check_out;
                            }
                        }
                    }
                }

                fclose($file);
                $result = array_values($data);
                foreach ($result as $emp_attendance) {
                    $emp_id = $emp_attendance['emp_id'];
                    $query = EmployeeDetail::where('emp_id', $emp_id)->first();
                    if ($query) {
                        $existingAttendance = UserAttendence::where('emp_id', $query->id)
                            ->whereDate('created_at', Carbon::parse($emp_attendance['created_at'])->format('Y-m-d'))
                            ->first();

                        if (!$existingAttendance) {
                            $empAttendance = new UserAttendence;
                            $empAttendance->emp_id = $query->id;
                            $empAttendance->name = $query->emp_name;
                            $empAttendance->check_in = $emp_attendance['check_in'];
                            $empAttendance->check_out = $emp_attendance['check_out'];
                            $empAttendance->check_in_status = 'M';
                            $empAttendance->check_in_ip_address = $request->ip();
                            $empAttendance->created_at = $emp_attendance['created_at'];
                            $empAttendance->save();
                        } else if ($existingAttendance && $existingAttendance->check_in != null) {
                            $existingAttendance->check_out = $emp_attendance['check_out'];
                            $existingAttendance->check_out_status = 'M';
                            $existingAttendance->check_in_ip_address = $request->ip();
                            $existingAttendance->update();
                        }
                    }
                }
                if (!empty($errors)) {
                    return redirect()->back()->withErrors($errors);
                } else {
                    $msg = '"Monthly" CSV Uploaded Successfully';
                    createLog('timesheet_action',$msg);

                    $success_message = count($attendance_list) . ' Attendance List added successfully.';
                    return redirect()->back()->with('success_message', $success_message);
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'branch_id' => 'required',
                'employee_id' => 'required',
                'check_in' => 'required',
                'created_date' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }
            $created_date = Carbon::parse($request->created_date)->format('Y-m-d');
            $attendance_data = UserAttendence::where('emp_id', $request->employee_id)->whereDate('created_at', $created_date)->first();
            $employee = EmployeeDetail::where('id', $request->employee_id)->first();
            if (!$attendance_data) {
                $dated = date('Y-m-d H:i:s', strtotime($request->created_date . $request->check_in));
                $attendance = new UserAttendence;
                $attendance->emp_id = $request->employee_id;
                $attendance->name = $employee->emp_name;
                $attendance->check_in = $request->check_in;
                $attendance->check_out = $request->check_out;
                $attendance->created_at = $dated;
                $attendance->check_in_status = "M";
                $attendance->check_in_ip_address = $request->ip();
                $attendance->save();

            } else if ($attendance_data && $attendance_data->check_in != null) {
                $attendance_data->check_out = $request->check_out;
                $attendance_data->check_in = $request->check_in;
                $attendance_data->check_out_status = 'M';
                $attendance_data->check_in_ip_address = $request->ip();
                $attendance_data->update();
            }
            $msg = '"Daily" Added Manually';
            createLog('timesheet_action',$msg);
            return redirect()->back()->with('success', 'User Attendance Updated Successfully');
        }
    }

    public function getEmpAttendenceSearch(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $current_date = isset($request->searchDate) ? date('d-m-Y', strtotime($request->searchDate)) : Carbon::now()->format('d-m-Y');
        if (isset($request->input)) {
            $searched_name = $request->input;
            $selected = $request->branch_id;
            $searched_date = $request->searchDate;
            if ($selected == 'all') {
                if ($user_role == 1) {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                    ->where(function ($query) use ($searched_name) {
                        $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                            ->orWhere('emp_id','LIKE', '%' . $searched_name . '%');
                    })
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->paginate(10);
                } else {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                    ->where(function ($query) use ($searched_name) {
                        $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                            ->orWhere('emp_id','LIKE', '%' . $searched_name . '%');
                    })
                    ->whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->paginate(10);
                }
            } else {
                if ($user_role == 1) {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where(function ($query) use ($searched_name) {
                            $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                                ->orWhere('emp_id','LIKE', '%' . $searched_name . '%');
                        })
                        ->where('branch_id', $selected)
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->orderBy('emp_id', 'asc')
                        ->paginate(10);
                } else {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where(function ($query) use ($searched_name) {
                            $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                                ->orWhere('emp_id', 'LIKE', '%' . $searched_name . '%');
                        })
                        ->whereIn('company_id', $user_company_id)
                        ->where('branch_id', $selected)
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->orderBy('emp_id', 'asc')
                        ->paginate(10);
                }
            }
        }else{
            $selected = $request->branch_id;
            $searched_date = $request->searchDate;
            if ($selected == 'all') {
                if ($user_role == 1) {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->paginate(20);
                } else {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                    ->whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->paginate(20);
                }
            } else {
                if ($user_role == 1) {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                    ->where('branch_id', $selected)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->paginate(20);
                } else {
                    $employees = EmployeeDetail::with([
                        'user_attendance' => function ($query) use ($searched_date) {
                            $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                        }
                    ])
                    ->whereIn('company_id', $user_company_id)
                    ->where('branch_id', $selected)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->paginate(20);
                }
            }
        }

        $attendanceData = [];
        foreach ($employees as $key => $employee) {
            $workingHours = '';
            $absentCount = 0;
            $LateCount = 0;
            $halfCount = 0;
            $get_check_in = '';
            $get_check_out = '';
            $get_check_in_address = '';
            $get_check_out_address = '';
            $get_check_in_status = '';
            $get_check_out_status = '';
            $get_check_in_ip_address = '';
            $get_check_out_ip_address = '';
            $data = UserAttendence::where('emp_id', $employee->id)
                ->whereDate('created_at', 'LIKE', Carbon::parse($searched_date)->format('Y-m-d') . '%')
                ->get();

            if ($data->isEmpty()) {
                $attendance = 'Absent';
                $absentCount++;
            } else {


                foreach ($data as $attendanceRecord) {
                    $check_in_time = $attendanceRecord->check_in != null ? Carbon::parse($attendanceRecord->check_in)->format('h:i A'):'';
                    $check_out_time = $attendanceRecord->check_out != null ? Carbon::parse($attendanceRecord->check_out)->format('h:i A'):'';
                    $check_in_address = $attendanceRecord->check_in_address != null ? $attendanceRecord->check_in_address:'';
                    $check_out_address = $attendanceRecord->check_out_address != null ? $attendanceRecord->check_out_address:'';
                    $check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address:'';
                    $check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address:'';
                    if($attendanceRecord->check_out != null){
                        $endTime = Carbon::parse($attendanceRecord->check_out);
                        $startTime = Carbon::parse($attendanceRecord->check_in);
                        $duration = $endTime->diffInMinutes($startTime);
                        $workingHours = floor($duration / 60) . 'h:' . ($duration - floor($duration / 60) * 60) . 'm';

                        if ($duration <= 240) {
                            $attendance = 'Half Leave';
                            $halfCount++;
                        } elseif ($duration < 480) {
                            $attendance = 'Late';
                            $LateCount++;
                        } else {
                            $attendance = 'Present';
                        }
                    }else{
                        $attendance = 'Present';
                    }

                    // Append check-in and check-out times to arrays
                    $get_check_in = $check_in_time;
                    $get_check_out = $check_out_time;
                    $get_check_in_address = $check_in_address;
                    $get_check_out_address = $check_out_address;
                    $get_check_in_status = $attendanceRecord->check_in_status ? $attendanceRecord->check_in_status : '';
                    $get_check_out_status = $attendanceRecord->check_out_status ? $attendanceRecord->check_out_status : '';
                    $get_check_in_ip_address = $check_in_ip_address;
                    $get_check_out_ip_address = $check_out_ip_address;
                }
            }
            $attendanceData[] = [
                'employee_id' => $employee->id,
                'employee_name' => $employee->emp_name,
                'attendance' => $attendance,
                'check_in' => $get_check_in,
                'check_out' => $get_check_out,
                'check_in_address' => $get_check_in_address,
                'check_out_address' => $get_check_out_address,
                'check_in_status' => $get_check_in_status,
                'check_out_status' => $get_check_out_status,
                'check_in_ip_address' => $get_check_in_ip_address,
                'check_out_ip_address' => $get_check_out_ip_address,
                'workingHours' => $workingHours,
                'half_count' => $halfCount,
                'late_count' => $LateCount,
                'absent_count' => $absentCount,
            ];

            $emp_image = $employee->emp_image;
            $imageSrc = '';
            if ($emp_image != null) {
                $imagePath = public_path($emp_image);

                if (file_exists($imagePath)) {
                    $imageSrc = asset($emp_image);
                } else {
                    if ($employee->emp_gender == 'M') {
                        $imageSrc = '/assets/images/male.png';
                    } else if ($employee->emp_gender == 'F') {
                        $imageSrc = '/assets/images/female.png';
                    }
                }
            } else {
                if ($employee->emp_gender == 'M') {
                    $imageSrc = '/assets/images/male.png';
                } else if ($employee->emp_gender == 'F') {
                    $imageSrc = '/assets/images/female.png';
                }
            }
            $employee->emp_image = $imageSrc;
        }
        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }

        if(count($employees) > 0){
            $data['selected'] = $selected;
            $data['current_date'] = $current_date;
            $data['employees'] = $employees;
            $data['branches'] = $branches;
            $data['attendanceData'] = $attendanceData;

            return response()->json(["success" => true,"data" => $data]);
        }else{
            return response()->json(["success" => false]);
        }
    }

    public function deviceManagement(Request $request){
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_branch_id = $user->branch_id;
        $user_company_id = $user->company_id;

        $selectedBranch = isset($request->branch_id)?$request->branch_id:'all';

        if ($user_role == 1) {
            $devicesData = DeviceManagement::leftjoin('companies','devices.company_id','=','companies.id')
                ->leftjoin('locations','devices.branch_id','=','locations.id')
                ->leftjoin('device_types','devices.device_type_id','=','device_types.id')
                ->select('locations.branch_name','companies.company_name','device_types.name as type_name','devices.*')
                ->where('devices.is_deleted','0')->orderBy('id','desc');
            $devices = $devicesData->get();
            $tableDevices = $devicesData->get();
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $devicesData = DeviceManagement::leftjoin('companies','devices.company_id','=','companies.id')
                ->leftjoin('locations','devices.branch_id','=','locations.id')
                ->leftjoin('device_types','devices.device_type_id','=','device_types.id')
                ->select('locations.branch_name','companies.company_name','device_types.name as type_name','devices.*')
                ->where('devices.company_id',$user_company_id)
                ->where('devices.branch_id',$user_branch_id)
                ->where('devices.is_deleted','0')
                ->get();

            $devices = $devicesData->get();
            $tableDevices = $devicesData->get();

            $branches = Location::where('company_id', $user_company_id)
                ->where('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }

        return view('device_management.index',compact('branches','user','devices','tableDevices','selectedBranch'));
    }

    public function addDevice()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_branch_id = $user->branch_id;
        $user_company_id = $user->company_id;

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->get();
        }else{
            $companies = Company::where('id',$user_company_id)
                ->where('is_deleted', '0')->get();
        }

        $deviceTypes = DeviceType::orderBy('name')->get();
        return view('device_management.add_device', compact('companies','deviceTypes'));
    }

    public function saveDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'device_name' => 'required|unique:devices,device_name',
            'device_type' => 'required',
            'device_ip' => 'required',
            'serial_number' => 'required',
            'device_model' => 'required',
            'expiryDate' => 'required',
            'heartbeat' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $device = new DeviceManagement();
        $device->company_id = $request->company_id;
        $device->branch_id = $request->branch_id;
        $device->device_name = $request->device_name;
        $device->device_model = $request->device_model;
        $device->device_type_id = $request->device_type;
        $device->device_ip = $request->device_ip;
        $device->serial_number = $request->serial_number;
        $device->heartbeat = $request->heartbeat;
        $device->expiry_date =Carbon::parse($request->expiryDate)->format('Y-m-d H:i:s');
        $device->save();
        $msg = 'Device "'.ucwords($request->device_name).'" Added Successfully';
        createLog('device_action',$msg);

        return redirect('device-management')->with('success', 'Device Added Successfully');
    }

    public function editDevice(Request $request, $id)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_branch_id = $user->branch_id;
        $user_company_id = $user->company_id;

        if($user_role == 1){
            $companies = Company::where('is_deleted', '0')->get();
        }else{
            $companies = Company::where('id',$user_company_id)
                ->where('is_deleted', '0')->get();
        }

        $deviceTypes = DeviceType::orderBy('name')->get();
        $device = DeviceManagement::where('id',$id)->first();
        return view('device_management.edit_device', compact('companies','deviceTypes','device'));
    }

    public function updateDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'device_name' => 'required|unique:devices,device_name,' . $request->id,
            'device_type' => 'required',
            'device_ip' => 'required',
            'serial_number' => 'required',
            'device_model' => 'required',
            'expiryDate' => 'required',
            'heartbeat' => 'required',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('device_name')) {
                return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "The device name already exists");
            } else {
                return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
            }
        }

        $device = DeviceManagement::where('id',$request->id)->first();
        $device->company_id = $request->company_id;
        $device->branch_id = $request->branch_id;
        $device->device_name = $request->device_name;
        $device->device_model = $request->device_model;
        $device->device_type_id = $request->device_type;
        $device->device_ip = $request->device_ip;
        $device->serial_number = $request->serial_number;
        $device->heartbeat = $request->heartbeat;
        $device->expiry_date = Carbon::parse($request->expiryDate)->format('Y-m-d H:i:s');
        $device->update();
        $msg = 'Device "'.ucwords($request->device_name).'" Updated Successfully';
        createLog('device_action',$msg);

        return redirect('device-management')->with('success', 'Device Updated Successfully');
    }

    public function monthlyAttenSheet(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selected = isset($request->branch_id) ? $request->branch_id : 'all';
        $current_month_year = isset($request->year_month) ? date('F Y', strtotime($request->year_month)) : date('F Y');
        $month_number = isset($request->year_month) ? date('m', strtotime($request->year_month)) : date('m');
        $number_of_days = Carbon::now()->month($month_number)->daysInMonth;
        $start_date = isset($request->year_month) ? date('Y-m-01', strtotime($request->year_month)) : date('Y-m-01');
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;

        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }

        if (isset($request->branch_id) && $request->branch_id != 'all') {
            $branch = Location::where('is_deleted', '0')->where('id', $request->branch_id)->first();
            $branch_id = $branch->branch_id;
        } else {
            $branch_id = null;
        }

        if ($selected != 'all') {
            if ($user_role == '1') {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->where('employee_details.branch_id', $request->branch_id)
                ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
                ->where('employee_details.is_deleted', '0')
                // ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            } else {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                    ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                    ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->where('employee_details.branch_id', $request->branch_id)
                    ->where('employee_details.is_deleted', '0')
                    // ->where('employee_details.status', '1')
                    ->orderBy('employee_details.emp_id', 'asc')
                    ->get();
            }
        } else {
            if ($user_role == '1') {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
                ->where('employee_details.is_deleted', '0')
                // ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            } else {
                $employees = EmployeeDetail::with([
                    'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                        $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                    }
                ])
                ->with([
                    'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                        $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                        ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                    }
                ])
                ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
                ->whereIn('employee_details.company_id', $user_company_id)
                ->whereIn('employee_details.branch_id', $user_branch_id)
                ->where('employee_details.is_deleted', '0')
                // ->where('employee_details.status', '1')
                ->orderBy('employee_details.emp_id', 'asc')
                ->get();
            }
        }

        $attendanceArray = [];
        foreach ($employees as $employee) {
            $employeeAttendance = $employee['get_user_daily_attendance']; // Assuming this is a collection or array of attendance records for the employee

            for ($i = 0; $i < $number_of_days; $i++) {
                $current_date = strtotime($start_date);
                $next_date = strtotime("+" . $i . " day", $current_date);
                $date = date('Y-m-d', $next_date);

                $attendanceStatus = 'free'; // Initialize the status

                foreach ($employeeAttendance as $dailyAttendace) {
                    if ($date == $dailyAttendace['dated']) {
                        // Check attendance conditions for the given date
                        if ($dailyAttendace['weekend']) {
                            if ($dailyAttendace['present']) {
                                $attendanceStatus = 'present_on_weekend';
                            } else {
                                $attendanceStatus = 'weekend';
                            }
                        } elseif ($dailyAttendace['holiday']) {
                            if ($dailyAttendace['present']) {
                                $attendanceStatus = 'present_on_holiday';
                            } else {
                                $attendanceStatus = 'holiday';
                            }
                        } elseif ($dailyAttendace['leave']) {
                            if ($dailyAttendace['present']) {
                                $attendanceStatus = 'present_on_leave';
                            } else {
                                $attendanceStatus = 'leave';
                            }
                        } elseif ($dailyAttendace['absent']) {
                            $attendanceStatus = 'absent';
                        } elseif ($dailyAttendace['present']) {
                            if ($dailyAttendace['half_leave']) {
                                $attendanceStatus = 'half_leave';
                            } elseif ($dailyAttendace['late_coming']) {
                                $attendanceStatus = 'late_coming';
                            } else {
                                $attendanceStatus = 'present';
                            }
                        } elseif ($dailyAttendace['is_new_joining']) {
                            $attendanceStatus = 'new_joining';
                        } elseif ($dailyAttendace['is_resigned']) {
                            $attendanceStatus = 'resigned';
                        } elseif ($dailyAttendace['is_terminated']) {
                            $attendanceStatus = 'terminated';
                        }
                        // Break out of the inner loop since we found a match
                        break;
                    }
                }
                $attendanceArray[] = [
                    'employee_id' => $employee->id,
                    'date' => $date,
                    'attendance_status' => $attendanceStatus,
                ];
            }

            $employeeMonthlySummary = $employee['get_user_monthly_attendance'];
            if($employeeMonthlySummary){
                $actualHours = $employeeMonthlySummary != null ?$employeeMonthlySummary->actual_working_hours:0;
                $act_hours = floor($actualHours / 60);
                $act_minutes = $actualHours % 60;

                $workingHours = $employeeMonthlySummary != null ?$employeeMonthlySummary->working_hours:0;
                $w_hours = floor($workingHours / 60);
                $w_minutes = $workingHours % 60;

                // Format the result
                $employee['get_user_monthly_attendance']['actual_working_hours'] = sprintf("%02dh:%02dm", $act_hours, $act_minutes);
                $employee['get_user_monthly_attendance']['working_hours'] = sprintf("%02dh:%02dm", $w_hours, $w_minutes);
            }
        }

        return view('attendence.AttenSheet', compact('month','year','branches', 'user', 'branch_id', 'selected', 'current_month_year', 'number_of_days','currentMonth', 'employees','attendanceArray'));
    }

    public function filterAttendanceSheet(Request $request)
    {
        $selected = $request->branch_id;
        $branch_id = 0;

        if ($selected == 'all') {
            $now_date = Carbon::now()->format('Y-m-d');
            $current_month_year = date('F Y', strtotime($request->year_month));
            $month_number = date('m', strtotime($request->year_month));
            $date = \Carbon\Carbon::createFromFormat('Y-m', $request->year_month);
            $number_of_days = $date->daysInMonth;
            $start_date = date('Y-m-01', strtotime($request->year_month));
            $currentMonth = $request->year_month;
            $employees = EmployeeDetail::with([
                'user_attendance' => function ($query) use ($currentMonth) {
                    $query->where('created_at', 'LIKE', $currentMonth . '%');
                }
            ])
                ->where('is_active', '1')
                ->where('is_deleted', '0')
                ->orderBy('emp_id', 'asc')
                ->paginate(10);
            $attendanceData = [];
            $weekendDays = [0, 6];
            foreach ($employees as $key => $employee) {
                $absentCount = 0;
                if (in_array(Carbon::now()->dayOfWeek, $weekendDays)) {
                    $attendanceData[] = [
                        'employee_name' => $employee->emp_name,
                        'attendance' => 'Weekend',
                        'workingHours' => '0',
                        'half_count' => 0,
                        'absent_count' => 0,
                    ];
                    continue;
                }

                // Get user data by id
                $data = UserAttendence::where('emp_id', $employee->id)
                    ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                    ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                    ->get();

                if ($data->isEmpty()) {
                    $absentCount++;
                } else {
                    $totalWorkingHours = 0;
                    $workingHours = '-';
                    $lateCount = 0;
                    $halfCount = 0;
                    foreach ($data as $attendanceRecord) {
                        $startTime = Carbon::parse($attendanceRecord->check_in);
                        $endTime = Carbon::parse($attendanceRecord->check_out);
                        $duration = $endTime->diffInMinutes($startTime);
                        $workingHours = floor($duration / 60) . ':' . ($duration - floor($duration / 60) * 60);

                        if ($duration <= 450) {
                            $attendance = 'Half Leave';
                            $halfCount++;
                        } elseif ($duration < 480) {
                            $attendance = 'Late';
                            $lateCount++;
                        }
                        $totalWorkingHours += $duration;
                    }
                    $totalWorkingHoursInHours = floor($totalWorkingHours / 60) . 'h:' . ($totalWorkingHours - floor($totalWorkingHours / 60) * 60) . 'm';
                    $attendanceData[] = [
                        'employee_id' => $employee->id,
                        'employee_name' => $employee->emp_name,
                        'workingHours' => isset($totalWorkingHoursInHours) ? $totalWorkingHoursInHours : '0',
                        'half_count' => $halfCount,
                        'late_count' => $lateCount,
                        'absent_count' => $absentCount,
                    ];
                }
            }
        } else {
            $branch_id = Location::where('id', $request->branch_id)->select('branch_id')->first()['branch_id'];
            $now_date = Carbon::now()->format('Y-m-d');
            $current_month_year = date('F Y', strtotime($request->year_month));
            $month_number = date('m', strtotime($request->year_month));
            $date = \Carbon\Carbon::createFromFormat('Y-m', $request->year_month);
            $number_of_days = $date->daysInMonth;
            $start_date = date('Y-m-01', strtotime($request->year_month));

            $currentMonth = $request->year_month;
            $employees = EmployeeDetail::with([
                'user_attendance' => function ($query) use ($currentMonth) {
                    $query->where('created_at', 'LIKE', $currentMonth . '%');
                }
            ])
                ->where('branch_id', $request->branch_id)
                ->where('is_active', '1')
                ->where('is_deleted', '0')
                ->orderBy('emp_id', 'asc')
                ->paginate(10);

            $attendanceData = [];
            $weekendDays = [0, 6];

            foreach ($employees as $key => $employee) {
                $absentCount = 0;
                if (in_array(Carbon::now()->dayOfWeek, $weekendDays)) {
                    $attendanceData[] = [
                        'employee_name' => $employee->emp_name,
                        'attendance' => 'Weekend',
                        'workingHours' => '0',
                        'half_count' => 0,
                        'absent_count' => 0,
                    ];
                    continue;
                }

                // Get user data by id
                $data = UserAttendence::where('emp_id', $employee->id)
                    ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                    ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                    ->get();

                if ($data->isEmpty()) {
                    $absentCount++;
                } else {
                    $totalWorkingHours = 0;
                    $workingHours = '-';
                    $lateCount = 0;
                    $halfCount = 0;
                    foreach ($data as $attendanceRecord) {
                        $startTime = Carbon::parse($attendanceRecord->check_in);
                        $endTime = Carbon::parse($attendanceRecord->check_out);
                        $duration = $endTime->diffInMinutes($startTime);
                        $workingHours = floor($duration / 60) . ':' . ($duration - floor($duration / 60) * 60);

                        if ($duration <= 450) {
                            $attendance = 'Half Leave';
                            $halfCount++;
                        } elseif ($duration < 480) {
                            $attendance = 'Late';
                            $lateCount++;
                        }
                        $totalWorkingHours += $duration;
                    }
                    $totalWorkingHoursInHours = floor($totalWorkingHours / 60) . 'h:' . ($totalWorkingHours - floor($totalWorkingHours / 60) * 60) . 'm';
                    $attendanceData[] = [
                        'employee_id' => $employee->id,
                        'employee_name' => $employee->emp_name,
                        'workingHours' => isset($totalWorkingHoursInHours) ? $totalWorkingHoursInHours : '0',
                        'half_count' => $halfCount,
                        'late_count' => $lateCount,
                        'absent_count' => $absentCount,
                    ];
                }
            }
        }

        $branches = Location::where('is_deleted', '0')->get();
        return view('attendence.AttenSheet', compact('selected', 'branch_id', 'branches', 'now_date', 'current_month_year', 'number_of_days', 'start_date', 'currentMonth', 'employees', 'attendanceData'));
    }

    public function smtp()
    {
        $user = auth()->user();
        $smtp_from_email = DB::table('settings')
        ->where('perimeter', 'smtp_from_email')
        ->first();
        $smtp_from_name = DB::table('settings')
            ->where('perimeter', 'smtp_from_name')
            ->first();
        $smtp_encryption = DB::table('settings')
            ->where('perimeter', 'smtp_encryption')
            ->first();
        $smtp_user_name = DB::table('settings')
            ->where('perimeter', 'smtp_email')
            ->first();
        $smtp_host = DB::table('settings')
            ->where('perimeter', 'smtp_host')
            ->first();
        $smtp_password = DB::table('settings')->where('perimeter','smtp_password')->first();
        $smtp_port = DB::table('settings')
            ->where('perimeter', 'smtp_port')
            ->first();
        return view('SMTP.gateway', compact('user','smtp_from_email','smtp_from_name','smtp_encryption','smtp_user_name','smtp_host','smtp_password','smtp_port'));
    }

    public function updateSMTPgateway(Request $request)
    {
        $password = DB::table('settings')->where('perimeter', 'smtp_password')->first();
        if($password->value == '' || $password->value != $request->password){
            $updateFields = [
                'email' => 'smtp_from_email',
                'from_name' => 'smtp_from_name',
                'encryption' => 'smtp_encryption',
                'username' => 'smtp_email',
                'smtphost' => 'smtp_host',
                'password' => 'smtp_password',
                'port' => 'smtp_port',
            ];
        }else{
            $updateFields = [
                'email' => 'smtp_from_email',
                'from_name' => 'smtp_from_name',
                'encryption' => 'smtp_encryption',
                'username' => 'smtp_email',
                'smtphost' => 'smtp_host',
                'port' => 'smtp_port',
            ];
        }

        foreach ($updateFields as $field => $perimeter) {
            $value = $request->$field;

            DB::table('settings')->where('perimeter', $perimeter)->update(['value' => $value]);
        }

        Session::flash('success', 'SMTP Updated Successfully');
        Session::flash('alert-class', 'alert-success');

        $msg = 'SMTP Updated';
        createLog('global_action',$msg);

        return redirect()->back()->with('Success', 'SMTP Updated Successfully');
    }

    public function UserManagement()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $users = User::where('is_deleted', '0')->orderBy('id','desc')->get();
        } else {
            $users = User::whereIn('company_id', $user_company_id)
                ->whereIn('branch_id', $user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('id','desc')
                ->get();
        }

        foreach($users as $user){
            $branch_id = explode(',', $user->branch_id);
            $branches = Location::whereIn('id',$branch_id)->pluck('branch_name')->implode(', ');;
            $user->branch_names = $branches;
        }

        return view('user_management.user', compact('users','user'));
    }

    public function addCompany()
    {
        return view('company.add_company');
    }

    public function companySetting()
    {
        return view('company.index');
    }

    public function appearanceSettings()
    {
        return view('theme.appearance_setting');
    }

    public function addTheme()
    {
        return view('theme.add_theme');
    }

    public function leaveRequest(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selectedBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';

        if ($user_role == '1') {
            if ($selectedBranch == 'all') {
                $leaves = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                    ->leftjoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
                    ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
                    ->leftjoin('leave-settings', 'emp_leaves.company_id', '=', 'leave-settings.company_id')
                    ->select('employee_details.emp_name', 'employee_details.emp_image', 'employee_details.emp_gender', 'emp_leaves.*', 'locations.branch_name', 'employee_details.emp_id', 'leave_types.types','leave-settings.annual_days')
                    ->where('emp_leaves.is_deleted', '0')
                    ->orderBy('emp_leaves.from_date', 'desc')
                    ->with('roles')
                    ->get();

            } else {
                $leaves = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                    ->leftjoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
                    ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
                    ->leftjoin('leave-settings', 'emp_leaves.company_id', '=', 'leave-settings.company_id')
                    ->select('employee_details.emp_name', 'employee_details.emp_image', 'employee_details.emp_gender', 'emp_leaves.*', 'locations.branch_name', 'employee_details.emp_id', 'leave_types.types','leave-settings.annual_days')
                    ->where('emp_leaves.branch_id', $selectedBranch)
                    ->where('emp_leaves.is_deleted', '0')
                    ->orderBy('emp_leaves.from_date', 'desc')
                    ->with('roles')
                    ->get();

            }

            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        } else {
            if ($selectedBranch == 'all') {
                $leaves = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                    ->leftjoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
                    ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
                    ->leftjoin('leave-settings', 'emp_leaves.company_id', '=', 'leave-settings.company_id')
                    ->select('employee_details.emp_name', 'employee_details.emp_image', 'employee_details.emp_gender', 'emp_leaves.*', 'locations.branch_name', 'employee_details.emp_id', 'leave_types.types','leave-settings.annual_days')
                    ->whereIn('emp_leaves.company_id', $user_company_id)
                    ->whereIn('emp_leaves.branch_id', $user_branch_id)
                    ->where('emp_leaves.is_deleted', '0')
                    ->orderBy('emp_leaves.from_date', 'desc')
                    ->with('roles')
                    ->get();
            } else {
                $leaves = Leave::leftjoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                    ->leftjoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
                    ->leftjoin('leave_types', 'emp_leaves.leave_type', '=', 'leave_types.id')
                    ->leftjoin('leave-settings', 'emp_leaves.company_id', '=', 'leave-settings.company_id')
                    ->select('employee_details.emp_name', 'employee_details.emp_image', 'employee_details.emp_gender', 'emp_leaves.*', 'locations.branch_name', 'employee_details.emp_id', 'leave_types.types','leave-settings.annual_days')
                    ->whereIn('emp_leaves.company_id', $user_company_id)
                    ->where('emp_leaves.branch_id', $selectedBranch)
                    ->where('emp_leaves.is_deleted', '0')
                    ->orderBy('emp_leaves.from_date', 'desc')
                    ->with('roles')
                    ->get();
            }

            $branches = Location::whereIn('company_id', $user_company_id)
                    ->whereIn('id', $user_branch_id)
                    ->where('is_deleted', '0')
                    ->orderBy('branch_name', 'asc')
                    ->get();
        }

        $Leave_types = Leave_type::orderBy('types','asc')->get();

        return view('leave.leave_request', compact('branches', 'Leave_types','user' ,'leaves', 'selectedBranch'));
    }

    public function getemployeesbybranch(Request $request)
    {
        $getemployees = EmployeeDetail::where('is_deleted', '0')
            ->where('branch_id', $request->branch_id)
            ->orderBy('emp_name', 'asc')
            ->get();
        return response()->json(['success' => true, 'data' => $getemployees]);
    }

    public function getTotalRemainingLeaves(Request $request)
    {
        $emp_id = $request->emp_id;
        $company_id = $request->company_id;
        $leave_settings = Leave_setting::where('company_id',$company_id)
            ->where('is_active', '1')
            ->where('is_deleted', '0')
            ->first();

        $annualDays = $leave_settings->annual_days;

        $totalleaveSum = Leave::where('is_approved', '1')
                ->where('company_id', $leave_settings->company_id)
                ->where('emp_id', $emp_id)
                ->sum('approved_days');

        $totalremainingLeaves = $annualDays - $totalleaveSum;

        return response()->json(['success' => true,'totalremainingLeaves' => $totalremainingLeaves]);
    }

    public function getRemainingLeaves(Request $request)
    {
        $emp_id = $request->emp_id;
        $company_id = $request->company_id;
        $leave_type = $request->leave_type;
        $leave_settings = Leave_setting::where('company_id',$company_id)
            ->where('is_active', '1')
            ->where('is_deleted', '0')
            ->first();

        if($leave_type == '1'){
            $numberOfDays = $leave_settings->annual_days;
        }elseif($leave_type == '2'){
            $numberOfDays = $leave_settings->casual_days;
        }elseif($leave_type == '3'){
            $numberOfDays = $leave_settings->sick_days;
        }elseif($leave_type == '4'){
            $numberOfDays = $leave_settings->maternity_days;
        }

        // Check the logic for fetching approved leaves
        if($leave_type == '1'){
            $leaveSum = Leave::where('is_approved', '1')
                ->where('company_id', $leave_settings->company_id)
                ->where('emp_id', $emp_id)
                ->sum('approved_days');

            if($leaveSum == 0){
                $remainingLeaves = $numberOfDays;
            }else{
                $remainingLeaves = $numberOfDays - $leaveSum;
            }
        }else{
            $leaveSum = Leave::where('is_approved', '1')
                ->where('company_id', $leave_settings->company_id)
                ->where('emp_id', $emp_id)
                ->where('leave_type',$leave_type)
                ->sum('approved_days');

            if($leaveSum == 0){
                $remainingLeaves = $numberOfDays;
            }else{
                $remainingLeaves = $numberOfDays - $leaveSum;
            }
        }

        $leave = Leave_Type::where('id',$leave_type)->first();
        $leave_title = ucwords($leave->types);

        return response()->json(['success' => true,'leaveTitle'=>$leave_title, 'remainingLeaves' => $remainingLeaves]);
    }

    public function addLeaveRequest()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }
        $Leave_types = Leave_Type::orderBy('types', 'asc')->get();
        return view('leave.add_leave_request', compact('Leave_types', 'companies'));
    }

    public function saveLeave(Request $request)
    {
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'leave_type' => 'required',
            'from_date' => [
                'required',
                function ($attribute, $value, $fail) use ($from_date,$to_date,$request) {
                    $leaveExists = Leave::where('emp_id', $request->emp_id)
                        ->where(function ($query) use ($from_date,$to_date,$request) {
                            $query->where('from_date', '<=', $to_date)
                                ->where('to_date', '>=', $from_date);
                        })
                        ->exists();
                    if ($leaveExists) {
                        $fail('Leave already exists for the specified date range.');
                    }
                },
            ],
            'to_date' => 'required',
            'remarks' => 'required'
        ],[
            'from_date.unique' => 'Leave already exists for the specified date range.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', 'Some of the fields are missing');
        }

        $startDate = Carbon::parse($request->from_date);
        $endDate = Carbon::parse($request->to_date);
        $difference = $startDate->diffInDays($endDate) + 1;
        Leave::create([
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'emp_id' => $request->emp_id,
            'remaining' => $request->remaining_leaves,
            'leave_type' => $request->leave_type,
            'requested_days' => $difference,
            'approved_days' => $request->approved_days,
            'from_date' => Carbon::parse($request->from_date)->format('Y-m-d'),
            'to_date' => Carbon::parse($request->to_date)->format('Y-m-d'),
            'remarks' => $request->remarks
        ]);
        $employee = EmployeeDetail::where('id',$request->emp_id)->first();
        $getLeaveName = Leave_Type::where('id',$request->leave_type)->first()['types'];

        $data = array();
        $type = "Leave";
        $branch = $request->branch_id;
        $data['emp_name'] = $employee->emp_name;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['leave_type'] = $getLeaveName;
        $createNotification = new NotificationController();
        $createNotification->generateNotification($type,$data,$branch);

        // $checkMail = NotificationManagement::where('type','Leave')->first();
        // if($checkMail->send_email == "Y") {
        //     if($checkMail->role_id != null){
        //         $getRoles = explode(",",$checkMail->role_id);
        //         foreach($getRoles as $role){
        //             $getUsers = User::where('branch_id',$request->branch_id)->where('role_id',$role)->get();
        //             if($getUsers != "" && $getUsers != null){
        //                 foreach($getUsers as $user){
        //                     // $emailresponse = $this->emailStructure($checkMail->header, $checkMail->footer);
        //                     $patterns = [
        //                         '/\{(user_name)}]?/',
        //                         '/\{(employee_name)}]?/',
        //                         '/\{(from_date)}]?/',
        //                         '/\{(to_date)}]?/',
        //                         '/\{(leave_type)}]?/',
        //                     ];
        //                     $replacements = [
        //                         $user->fullname,
        //                         $employee->emp_name,
        //                         $from_date,
        //                         $to_date,
        //                         $getLeaveName,
        //                     ];

        //                     $mail = preg_replace($patterns, $replacements, $checkMail->mail);

        //                     $emailnotification = new NotificationEmail();
        //                     $emailnotification->user_id = $user->id;
        //                     $emailnotification->to_email = $user->email;
        //                     $emailnotification->email_subject = $checkMail->mail_subject;
        //                     $emailnotification->email_body = $mail;
        //                     // $emailnotification->email_body = $emailresponse[0].$mail.$emailresponse[1];
        //                     $emailnotification->schedule_date = date('Y-m-d H:i:s');
        //                     $emailnotification->email_sent_status = 'N';
        //                     $emailnotification->save();
        //                 }
        //             }
        //         }
        //     }
        // }

        $employee = EmployeeDetail::where('id',$request->emp_id)->first();

        $msg = 'for "'.ucwords($employee->emp_name).'" Added Successfully';
        createLog('leave_action',$msg);

        return redirect('/leave-request')->with('success', 'Leave Request Added Successfully');
    }

    public function editLeaveRequest(Request $request, $leave_id)
    {
        $id = base64_decode($leave_id);
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }

        $Leave_types = Leave_Type::orderBy('types', 'asc')->get();
        $leave_detail = Leave::where('id', $id)->first();

        return view('leave.edit_leave_request', compact('leave_detail', 'Leave_types', 'companies'));
    }

    public function updateLeaveRequest(Request $request)
    {
        $from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'leave_type' => 'required',
            'from_date' => [
                'required',
                function ($attribute, $value, $fail) use ($from_date,$to_date,$request) {
                    $leaveExists = Leave::where('emp_id', $request->emp_id)
                        ->where('id','!=',$request->id)
                        ->where(function ($query) use ($from_date,$to_date,$request) {
                            $query->where('from_date', '<=', $to_date)
                                ->where('to_date', '>=', $from_date);
                        })
                        ->exists();
                    if ($leaveExists) {
                        $fail('Leave already exists for the specified date range.');
                    }
                },
            ],
            'to_date' => 'required',
            'remarks' => 'required'
        ],[
            'from_date.unique' => 'Leave already exists for the specified date range.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', 'Some of the fields are missing');
        }
        $startDate = Carbon::parse($request->from_date);
        $endDate = Carbon::parse($request->to_date);
        $difference = $startDate->diffInDays($endDate) + 1;
        $data = Leave::where('id', $request->id)->first();
        $data->company_id = $request->company_id;
        $data->branch_id = $request->branch_id;
        $data->emp_id = $request->emp_id;
        $data->remaining = $request->remaining_leaves;
        $data->leave_type = $request->leave_type;
        $data->requested_days = $difference;
        $data->approved_days = $request->approved_days;
        $data->from_date = Carbon::parse($request->from_date)->format('Y-m-d');
        $data->to_date = Carbon::parse($request->to_date)->format('Y-m-d');
        $data->approved_by = $request->approved_by;
        $data->remarks = $request->remarks;
        $data->update();

        $employee = EmployeeDetail::where('id',$request->emp_id)->first();
        $msg = 'for "'.ucwords($employee->emp_name).'" Updated Successfully';
        createLog('leave_action',$msg);

        return redirect('/leave-request')->with('success', 'Leave Request Udpated Successfully');
    }

    public function updateLeaveStatus($id,$status)
    {
        $user = Auth::user();
        $leave = Leave::findOrFail($id);

        $startDate = Carbon::parse($leave->from_date);
        $endDate = Carbon::parse($leave->to_date);
        $difference = $startDate->diffInDays($endDate) + 1;

        $leave->is_approved = $status;
        $leave->approved_by = $user->role_id;
        $leave->approved_days = $difference;

        $leave->update();

        if($leave){
            $employee = EmployeeDetail::where('id',$leave->emp_id)->first();
            if($status == 1){
                $msg = 'Leave of "'.ucwords($employee->emp_name).'" Approved Successfully';
            createLog('leave_action',$msg);
                return redirect()->back()->with('success', 'Leave Approved Successfully');
            } elseif($status == 2){
                $msg = 'Leave of "'.ucwords($employee->emp_name).'" Declined Successfully';
            createLog('leave_action',$msg);
                return redirect()->back()->with('success', 'Leave Declined Successfully');
            }else{
                $msg = 'Leave of "'.ucwords($employee->emp_name).'" Pending Successfully';
            createLog('leave_action',$msg);
                return redirect()->back()->with('success', 'Leave Pending Successfully');
            }
        }else{
            return redirect()->back()->with('error', 'Leave Status Not Updated');
        }
    }

    public function destroyLeaveRequest(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();
        $employee = EmployeeDetail::where('id',$leave->emp_id)->first();
        $msg = 'for "'.ucwords($employee->emp_name).'" Deleted Successfully';
        createLog('leave_action',$msg);

        return redirect()->back()->with(['success' => 'Leave Request Deleted Successfully']);
    }

    public function leavesearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));
        $selectBranch = $request->input('selectBranch');
        if (isset($selectBranch) && $selectBranch != null) {
            $fetchData = Leave::where('emp_leaves.branch_id', $selectBranch)
                ->orderBy('emp_leaves.id', 'asc')
                ->leftJoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'employee_details.emp_name', 'emp_leaves.leave_type', 'emp_leaves.id', 'emp_leaves.from_date', 'emp_leaves.to_date')
                ->get();
        } else {
            $fetchData = Leave::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where('emp_leaves.is_deleted', '0')
                    ->where(function ($query) use ($searchValue, $selectBranch) {
                        $query->whereRaw('LOWER(leave_types.types) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereHas('employee', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                                    ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                            })
                            ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                                $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                            });
                    });
            })
                ->orderBy('emp_leaves.id', 'asc')
                ->leftJoin('locations', 'emp_leaves.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_leaves.emp_id', '=', 'employee_details.id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'employee_details.emp_name', 'emp_leaves.leave_type', 'emp_leaves.id', 'emp_leaves.from_date', 'emp_leaves.to_date')
                ->get();
        }
        return $fetchData;
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function resignationSearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));
        $selectBranch = $request->input('selectBranch');
        // if (isset($selectBranch) && $selectBranch != null) {
        //     if($selectBranch == 'all'){
        //         $fetchData = EmployeeResignation::leftJoin('locations', 'emp_resignations.branch_id', '=', 'locations.id')
        //         ->leftJoin('employee_details', 'emp_resignations.emp_id', '=', 'employee_details.id')
        //         ->leftJoin('emp_approvals','emp_resignations.emp_id','emp_approvals.emp_id')
        //         ->select('employee_details.emp_id', 'locations.branch_name','emp_approvals.designation_id', 'employee_details.emp_name', 'emp_resignations.id', 'emp_resignations.resignation_date', 'emp_resignations.notice_date','emp_resignations.is_approved')
        //         ->orderBy('emp_resignations.id', 'asc')
        //         ->get();
        //     }else{
        //     $fetchData = EmployeeResignation::leftJoin('locations', 'emp_resignations.branch_id', '=', 'locations.id')
        //         ->leftJoin('employee_details', 'emp_resignations.emp_id', '=', 'employee_details.id')
        //         ->leftJoin('emp_approvals', 'emp_resignations.emp_id', 'emp_approvals.emp_id')
        //         ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_resignations.id', 'emp_resignations.resignation_date', 'emp_resignations.notice_date', 'emp_resignations.is_approved')
        //         ->where('emp_resignations.branch_id', $selectBranch)
        //         ->orderBy('emp_resignations.id', 'asc')
        //         ->get();
        //     }
        // } else {
            if($selectBranch == 'all'){
            $fetchData = EmployeeResignation::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_resignations.id', 'asc')
                ->leftJoin('locations', 'emp_resignations.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_resignations.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_resignations.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_resignations.id', 'emp_resignations.resignation_date', 'emp_resignations.notice_date', 'emp_resignations.is_approved')
                // ->where('emp_resignations.branch_id', $selectBranch)
                ->get();
        }else{
            $fetchData = EmployeeResignation::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_resignations.id', 'asc')
                ->leftJoin('locations', 'emp_resignations.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_resignations.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_resignations.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_resignations.id', 'emp_resignations.resignation_date', 'emp_resignations.notice_date', 'emp_resignations.is_approved')
                ->where('emp_resignations.branch_id', $selectBranch)
                ->get();
        }
        // }
        foreach($fetchData as $designation){
            if($designation->desgn){
                $designation->desgn = Designation::where('id',$designation->designation_id)->first();
            }
            else{
                $designation->desgn = "N/A";
            }
        }
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function versionHistory()
    {
        $user = auth()->user();
        $versions = Version_History::orderBy('id','desc')->get();

        return view('web_version.version_history',compact('versions','user'));
    }

    public function saveVersion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'version' => 'required',
            'reason' => 'required',
            'type' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $version = new Version_History;
        $version->version = $request->version;
        $version->reason = $request->reason;
        $version->type = $request->type;
        $version->save();
        $msg = 'Version "'.ucwords($request->type). ' ' .ucwords($request->version).'" Added Successfully';
        createLog('version_action',$msg);
        return redirect('version-history')->with('success','Version Save Successfully');
    }

    public function yearlyDetail(Request $request)
    {
        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $current_year = isset($request->year) ? date('Y', strtotime($request->year)) : date('Y');
        $monthCount = 12;
        $allMonthsData = [];

        if($selectBranch == 'all'){
            if ($user_role == 1) {
                $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->with('resignations', 'approval', 'leaves', 'holidays')
                ->select('employee_details.*')
                ->where('employee_details.is_deleted', '0')
                // ->where('status', '1')
                ->where('locations.is_deleted', '0')
                ->orderBy('employee_details.emp_id', 'asc')
                ->paginate(12);
            } else {
                $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->with('resignations', 'approval', 'leaves', 'holidays')
                ->select('employee_details.*')
                ->whereIn('company_id', $user_company_id)
                ->whereIn('branch_id', $user_branch_id)
                ->where('employee_details.is_deleted', '0')
                // ->where('status', '1')
                ->where('locations.is_deleted', '0')
                ->orderBy('employee_details.emp_id', 'asc')
                ->paginate(12);
            }
        }else{
            if ($user_role == 1) {
                $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->with('resignations', 'approval', 'leaves', 'holidays')
                ->select('employee_details.*')
                ->where('employee_details.branch_id', $selectBranch)
                ->where('employee_details.is_deleted', '0')
                // ->where('status', '1')
                ->where('locations.is_deleted', '0')
                ->orderBy('employee_details.emp_id', 'asc')
                ->paginate(12);
            } else {
                $employees = EmployeeDetail::leftjoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                ->with('resignations', 'approval', 'leaves', 'holidays')
                ->select('employee_details.*')
                ->where('employee_details.branch_id', $selectBranch)
                ->whereIn('company_id', $user_company_id)
                ->where('employee_details.is_deleted', '0')
                // ->where('status', '1')
                ->where('locations.status', '0')
                ->orderBy('employee_details.emp_id', 'asc')
                ->paginate(12);
            }
        }
        $attendanceData = [];
        foreach ($employees as $employee) {
            $currentDate = [];
            $monthlyAttendanceDataArray = [];
            for ($month = 1; $month <= $monthCount; $month++) {
                // return $current_year;
                $currentMonth = Carbon::create($current_year, $month);
                $monthlyData = [];
                    $empResignation = $employee->resignations ? $employee->resignations->is_approved : null;
                    $resignationDate = $empResignation == '1' ? Carbon::parse($employee->resignations->resignation_date)->addDay() : null;
                    $joining_date = $employee->approval ? Carbon::parse($employee->approval->joining_date)->subDay() : null;
                    $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                        ->where('is_deleted', '0')
                        ->first();

                    $total_hours = 0;
                    $total_minutes = 0;
                    $total_seconds = 0;
                    $workingHours = 0;
                    $absentCount = 0;
                    $lateCount = 0;
                    $halfCount = 0;
                    $totalWorkingDays = 0;

                    $start_time = ($company_detail ? $company_detail->start_time : Carbon::parse('00:00:00')->format('H:i:s'));
                    $end_time = ($company_detail ? $company_detail->end_time : Carbon::parse('00:00:00')->format('H:i:s'));
                    $start = new DateTime($start_time);
                    $end = new DateTime($end_time);
                    $diff = $start->diff($end);
                    $hours = $diff->h;
                    $minutes = $diff->i;
                    $seconds = $diff->s;
                    $total_hours += $hours;
                    $total_minutes += $minutes;
                    $total_seconds += $seconds;
                    $extra_hours = floor($total_minutes / 60);
                    $total_hours += $extra_hours;
                    $total_minutes %= 60;
                    $extra_minutes = floor($total_seconds / 60);
                    $total_minutes += $extra_minutes;
                    $total_seconds %= 60;
                    $total_time = sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds);

                    $currentMonth = Carbon::create($current_year, $month)->endOfMonth();

                    for ($day = 1; $day <= $currentMonth->day; $day++) {
                        $currentDate = Carbon::create($currentMonth->year, $currentMonth->month, $day);
                        if ($currentDate->isWeekend() || isset($dateArray[$currentDate->toDateString()])) {
                            continue;
                        }
                        if ($currentDate <= now()->startOfDay()) {
                            $totalWorkingDays++;
                        }
                    }

                    $data = UserAttendence::where('emp_id', $employee->id)
                        ->whereDate('created_at', 'LIKE', Carbon::parse($currentMonth)->format('Y-m') . '%')
                        ->get();

                    $absentDates = [];
                    $freeDates = [];
                    $resignedDates = [];
                    $newJoining = [];
                    $attendance = [];
                    $half_attendance = [];
                    $presentDates = [];
                    $halfDates = [];
                    $weekends = [];

                    //make loop to make attendance
                    for ($day = 1; $day <= $currentMonth->day; $day++) {
                        $currentDate = Carbon::create($currentMonth->year, $currentMonth->month, $day);
                        $dateArray = [];
                        $leavesArray = [];

                        //get holidays
                        foreach ($employee->holidays as $holiday) {
                            if ($holiday['start_date'] <= $currentMonth->endOfMonth() || $holiday['end_date'] <= $currentMonth->endOfMonth()) {
                                $startDate = Carbon::parse($holiday['start_date']);
                                $endDate = Carbon::parse($holiday['end_date']);
                                while ($startDate->lte($endDate)) {
                                    if ($startDate->month == $currentMonth->month && $startDate->year == $currentMonth->year) {
                                        $dateArray[$startDate->toDateString()] = ['Holiday', $holiday->event_name];
                                    }
                                    $startDate->addDay();
                                }
                            }
                        }

                        //get leaves
                        foreach ($employee->leaves as $leaves) {
                            if ($leaves['from_date'] <= $currentMonth->endOfMonth() || $leaves['to_date'] <= $currentMonth->endOfMonth()) {
                                $startDate = Carbon::parse($leaves['from_date']);
                                $endDate = Carbon::parse($leaves['to_date']);

                                while ($startDate->lte($endDate)) {
                                    if ($startDate->month == $currentMonth->month && $startDate->year == $currentMonth->year) {
                                        $leavesArray[$startDate->toDateString()] = 'Leave';
                                    }

                                    // Move to the next date
                                    $startDate->addDay();
                                }
                            }
                        }

                        if ($currentDate->isWeekend()) {
                            $weekends[$currentDate->toDateString()] = 'weekend';
                        } elseif (count($dateArray) > 0 && in_array($currentDate->toDateString(), array_keys($dateArray))) {
                            continue;
                        } else {
                            $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
                                ->where('created_at', '<=', $currentDate->endOfDay())
                                ->first();
                            if (!$attendanceRecord) {
                                if ($joining_date !== null && strtotime($currentDate->toDateString()) <= strtotime($joining_date)) {
                                    $newJoining[$currentDate->toDateString()] = 'newJoining';
                                } elseif ($resignationDate !== null && strtotime($currentDate->toDateString()) >= strtotime($resignationDate)) {
                                    $resignedDates[$currentDate->toDateString()] = 'resigned';
                                } elseif (count($leavesArray) > 0 && in_array($currentDate->toDateString(), array_keys($leavesArray))) {
                                    $leavesArray[$currentDate->toDateString()] = 'Leave';
                                } elseif ($currentDate <= now()->startOfDay()) {
                                    $absentDates[$currentDate->toDateString()] = 'absent';
                                } else {
                                    if ($currentDate->isWeekend()) {
                                        $weekends[$currentDate->toDateString()] = 'weekend';
                                    } elseif (count($dateArray) > 0 && in_array($currentDate->toDateString(), array_keys($dateArray))) {
                                        $dateArray[$currentDate->toDateString()] = ['Holiday', $holiday->event_name];
                                        continue;
                                    } else {
                                        $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
                                            ->where('created_at', '<=', $currentDate->endOfDay())
                                            ->first();
                                        $freeDates[$currentDate->toDateString()] = '-';
                                    }
                                }
                            } else {
                                $late_time = ($company_detail ? $company_detail->late_time : null);
                                $checkPL = $attendanceRecord->check_in;
                                $startTime = Carbon::parse($attendanceRecord->check_in);
                                $endTime = Carbon::parse($attendanceRecord->check_out);
                                $duration = $endTime->diffInMinutes($startTime);
                                $workingHours += $duration;
                                $hours = ($company_detail ? $company_detail->half_day : 0);
                                $late_time = ($company_detail ? $company_detail->late_time : 0);
                                $hour = date('H', strtotime($late_time));
                                $minute = date('i', strtotime($late_time));
                                $minutes = $hour * 60;
                                $late = $minutes + $minute;
                                $half_day = $hours * 60;
                                if ($duration <= $half_day) {
                                    $half_attendance = 'Half Leave';
                                    $halfCount++;
                                }
                                if ($checkPL !== null || $checkPL !== '') {
                                    $attendance = 'Present';
                                    if ($late_time !== null) {
                                        // Check if $startTime is later than $late_time
                                        if (strtotime($checkPL) > strtotime($late_time)) {
                                            $lateCount++;
                                        }
                                    }
                                }
                                $presentDates[$currentDate->toDateString()] = $attendance;
                                $halfDates[$currentDate->toDateString()] = $half_attendance;
                            }
                        }
                    }
                    $absentCount = count($absentDates);

                    $monthlyData = [

                        'month' => $currentMonth->format('F Y'),
                        'Present' => count($presentDates),
                        'absent_count' => $absentCount,
                    ];
                    array_push($monthlyAttendanceDataArray,$monthlyData);
            }
            $allMonthsData []  =  [
            'employee_id' => $employee->id,
            'attendanceData' => $monthlyAttendanceDataArray
            ];
        }
        // return $allMonthsData ;
        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }
        return view('attendence.yearly_detail', compact('allMonthsData','user','employees', 'branches','current_year','selectBranch'));
    }

    public function designation(Request $request)
    {
        $user = auth()->user();
        $departments = Department::orderBy('name', 'asc')->get();
        $designations = Designation::with('department')->orderBy('id', 'desc')->get();
        return view('designation.index',compact('designations','user','departments'));
    }

    public function saveDesignation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'designation_name' => 'required|unique:designations,name',
            'department_id' => 'required'
        ], [
            'designation_name.required' => 'The designation name is required.',
            'designation_name.unique' => 'The designation name is already taken.'
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors();

            if ($errors->has('designation_name')) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', $errors->first('designation_name'));
            } else {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', 'Something went wrong');
            }
        }
        $data = new Designation;
        $data->name = $request->designation_name;
        $data->department_id = $request->department_id;
        $data->save();

        $msg = 'Designation "'.ucwords($request->designation_name).'" Added Successfully';
        createLog('employee_action',$msg);

        return redirect()->back()->with(['success' => 'Designation Added Successfully']);
    }

    public function updateDesignation(Request $request)
    {
        $designation = Designation::find($request->desg_id);
        $designationNameChanged = ($request->desg_name != $designation->name);

        $rules = [
            'desg_name' => 'required' . ($designationNameChanged ? '|unique:designations,name,' . $request->desg_id : ''),
            'dept_id' => 'required'
        ];

        $messages = [
            'desg_name.required' => 'The designation name is required.',
            'desg_name.unique' => 'The designation name is already taken.'
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            $errors = $validate->errors();

            if ($errors->has('desg_name')) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', $errors->first('desg_name'));
            } else {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', 'Something went wrong');
            }
        }

        // Rest of your update logic
        $data = Designation::where('id', $request->desg_id)->first();
        $data->name = $request->desg_name;
        $data->department_id = $request->dept_id;
        $data->update();
        $msg = 'Designation "'.ucwords($data->name).'" Updated as "'.ucwords($request->desg_name).'"';
        createLog('employee_action',$msg);

        return redirect()->back()->with(['success' => 'Designation Updated Successfully']);
    }

    public function designationSearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));

        $fetchData = Designation::where(function ($query) use ($searchValue) {
            $query->whereRaw('LOWER(designations.name) LIKE ?', ['%' . $searchValue . '%'])
                  ->orWhereRaw('LOWER(departments.name) LIKE ?', ['%' . $searchValue . '%']);
        })
        ->orderBy('designations.id', 'asc')
        ->leftJoin('departments', 'designations.department_id', '=', 'departments.id')
        ->select('designations.name', 'departments.name as department_name', 'designations.updated_at', 'designations.id','designations.department_id')
        ->get();
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function department()
    {
        $user = auth()->user();
        $departments = Department::orderBy('id', 'desc')->get();
        return view('department.index', compact('user','departments'));
    }

    public function saveDepartment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'department_name' => 'required|unique:departments,name'
        ], [
            'department_name.required' => 'The designation name is required.',
            'department_name.unique' => 'The designation name is already taken.'
        ]);

        if ($validate->fails()) {
            $errors = $validate->errors();

            if ($errors->has('department_name')) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', $errors->first('department_name'));
            } else {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', 'Something went wrong');
            }
        }
        $data = new Department;
        $data->name = $request->department_name;
        $data->save();

        $msg = 'Department "'.ucwords($request->department_name).'" added successfully';
        createLog('employee_action',$msg);

        return redirect()->back()->with(['success' => 'Department Added Successfully']);
    }

    public function updateDepartment(Request $request)
    {
        $department = Department::find($request->dept_id);
        $departmentNameChanged = ($request->dept_name != $department->name);

        $rules = [
            'dept_name' => 'required' . ($departmentNameChanged ? '|unique:departments,name,' . $request->dept_id : '')
        ];

        $messages = [
            'dept_name.required' => 'The designation name is required.',
            'dept_name.unique' => 'The designation name is already taken.'
        ];

        $validate = Validator::make($request->all(), $rules, $messages);

        if ($validate->fails()) {
            $errors = $validate->errors();

            if ($errors->has('dept_name')) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', $errors->first('dept_name'));
            } else {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput($request->all())
                    ->with('error', 'Something went wrong');
            }
        }

        // Rest of your update logic
        $data = Department::where('id', $request->dept_id)->first();
        $data->name = $request->dept_name;
        $data->update();
        $msg = 'Department "'.ucwords($data->name).'" Updated as "'.ucwords($request->dept_name).'"';
        createLog('employee_action',$msg);

        return redirect()->back()->with(['success' => 'Department Updated Successfully']);
    }

    public function departmentSearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));

        $fetchData = Department::where(function ($query) use ($searchValue) {
            $query->whereRaw('LOWER(departments.name) LIKE ?', ['%' . $searchValue . '%']);
        })
        ->orderBy('departments.id', 'asc')
        ->select('departments.name', 'departments.updated_at', 'departments.id')
        ->get();
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function getDepartment()
    {
        $departments = Department::orderBy('name', 'asc')->get();

        return response()->json(['success' => true, 'data' => $departments]);
    }

    public function resignation(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';

        if($user_role == '1'){
            if ($selectBranch == 'all') {
                $employees = EmployeeResignation::with('employee_detail', 'employee_approval', 'branch')
                    ->orderBy('emp_resignations.id', 'desc')
                    ->get();
            } else {
                $employees = EmployeeResignation::with('employee_detail', 'employee_approval', 'branch')
                    ->where('emp_resignations.branch_id', $selectBranch)
                    ->orderBy('emp_resignations.id', 'desc')
                    ->get();
            }

            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        }else{
            if ($selectBranch == 'all') {
                $employees = EmployeeResignation::with('employee_detail', 'employee_approval', 'branch')
                    ->whereIn('emp_resignations.company_id',$user_company_id)
                    ->whereIn('emp_resignations.branch_id',$user_branch_id)
                    ->orderBy('emp_resignations.id', 'desc')
                    ->get();
            } else {
                $employees = EmployeeResignation::with('employee_detail', 'employee_approval', 'branch')
                    ->whereIn('emp_resignations.company_id',$user_company_id)
                    ->where('emp_resignations.branch_id', $selectBranch)
                    ->orderBy('emp_resignations.id', 'desc')
                    ->get();
            }
            $branches = Location::whereIn('company_id',$user_company_id)
                ->whereIn('id',$user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->get();
        }
        $employeeIds = $employees->pluck('employee_approval.designation_id')->toArray();
        $designations = Designation::whereIn('id', $employeeIds)->get();
        foreach($employees as $item){
            if ($item->employee_approval && $item->employee_approval->designation_id) {
                $item->designation = $designations->where('id', $item->employee_approval->designation_id)->first();
            } else {
                $item->designation = null;
            }
        }
        return view('resignation.index', compact('employees', 'branches','user', 'selectBranch'));
    }

    public function addResignation()
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $companies = Company::where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }
        return view('resignation.add_resignation', compact('companies'));
    }

    public function saveResignation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'resignation_date' => 'required|date_format:d-m-Y',
            'notice_date' => [
                'required',
                'date_format:d-m-Y',
                function ($attribute, $value, $fail) use ($request) {
                    $resignationDate = \DateTime::createFromFormat('d-m-Y', $request->input('resignation_date'));
                    $noticeDate = \DateTime::createFromFormat('d-m-Y', $value);

                    if ($noticeDate >= $resignationDate) {
                        $fail('The notice date must be earlier than the resignation date.');
                    }
                },
            ],
            'reason' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing or the notice date is not earlier than the resignation date");
        }

        $record = EmployeeResignation::where('emp_id', $request->emp_id)->first();
        if (!$record) {
            $data = new EmployeeResignation;
            $data->company_id = $request->company_id;
            $data->branch_id = $request->branch_id;
            $data->emp_id = $request->emp_id;
            $data->resignation_date = Carbon::parse($request->resignation_date)->format('Y-m-d');
            $data->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
            $data->reason = $request->reason;
            $data->save();

            $employee = EmployeeDetail::where('id',$request->emp_id)->first();
            $msg = 'Resignation for "'.ucwords($employee->emp_name).'" Added Successfully';
            createLog('employee_action',$msg);
            $data = array();
            $getEmployee = EmployeeDetail::find($request->emp_id);
            $userApproval = user_approval::where('emp_id', $request->emp_id)->first();
            $getDesignationName = null;
            $user_id = User::where('email',$getEmployee->emp_email)->select('id')->first();

            if ($userApproval && isset($userApproval['designation_id'])) {
                $designation = Designation::where('id', $userApproval['designation_id'])->first();

                if ($designation && isset($designation['name'])) {
                    $getDesignationName = $designation['name'];
                }
            }
            $type = "Employee Resignation";
            $branch = $request->branch_id;
            if($user_id){
                $data['emp_name'] = $getEmployee->emp_name;
                $data['user_id'] = $user_id->id;
                $data['employee_personal_email'] = $getEmployee->personal_email;
                $data['emp_position'] = $getDesignationName;
                $data['last_date'] = Carbon::parse($request->resignation_date)->format('Y-m-d');
                $createNotification = new NotificationController();
                $createNotification->generateNotification($type,$data,$branch);
            }

            return redirect('/employee/resignation')->with('success', 'Resignation Form Submitted');
        } else {
            return redirect()->back()->with('error', 'Resignation Form already exists');
        }
    }

    public function editResignation(Request $request, $id)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $companies = Company::where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }

        $resignation_data = EmployeeResignation::where('id', $id)->first();

        return view('resignation.edit_resignation', compact('companies', 'resignation_data'));
    }

    public function updateResignation(Request $request)
    {
        $selectBranch = 'all';
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'resignation_date' => 'required',
            'notice_date' => 'required',
            'reason' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }

        $data = EmployeeResignation::where('id', $request->id)->first();
        $data->company_id = $request->company_id;
        $data->branch_id = $request->branch_id;
        $data->emp_id = $request->emp_id;
        $data->resignation_date = Carbon::parse($request->resignation_date)->format('Y-m-d');
        $data->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
        $data->reason = $request->reason;
        $data->update();

        if ($data) {
            $employee = EmployeeDetail::where('id',$request->emp_id)->first();
            $msg = 'Resignation for "'.ucwords($employee->emp_name).'" Updated Successfully';
            createLog('employee_action',$msg);

            return redirect('/employee/resignation')->with('success', 'Resignation Form Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Resignation Form not updated');
        }
    }

    public function deleteResignation(Request $request, $id)
    {
        $resignation_data = EmployeeResignation::where('id', $id)->first();
        $resignation_data->delete();
        if($resignation_data) {
            $employee = EmployeeDetail::where('id',$resignation_data->emp_id)->first();
            $employee->status = "1";
            $employee->update();
        }
        $employee = EmployeeDetail::where('id',$resignation_data->emp_id)->first();
        $msg = 'Resignation for "'.ucwords($employee->emp_name).'" Deleted Successfully';
        createLog('employee_action',$msg);
        return redirect()->back()->with(['success' => 'Resignation Deleted Successfully']);
    }

    public function changeResignationStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == "0") {
            $statusValue = "0";
            $statusStr = "declined";
        } else if ($status == "2") {
            $statusValue = null;
            $statusStr = "disapproved";
        } else {
            $statusValue = "1";
            $statusStr = "approved";
        }

        $data = EmployeeResignation::where('id', $id)->first();
        $data->is_approved = $statusValue;
        $data->update();

        $employee = EmployeeDetail::where('id',$data->emp_id)->first();
        $msg = 'Resignation for "'.ucwords($employee->emp_name).'" '.$statusStr;
        createLog('employee_action',$msg);

        if ($data && $status == "1") {
            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->status = "3";
            $user_detail->update();
        }
        if($data && $status == "2") {
            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->status = "1";
            $user_detail->update();
        }
        if($data && $status == "0") {
            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->status = "1";
            $user_detail->update();
        }
        return response()->json(['status' => '1']);
    }

    public function termination(Request $request)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';

        if($user_role == '1'){
            if ($selectBranch == 'all') {
                $employees = Emp_termination::with('employee_detail', 'employee_approval', 'branch')
                    ->orderBy('emp_terminations.id', 'desc')
                    ->get();
            } else {
                $employees = Emp_termination::with('employee_detail', 'employee_approval', 'branch')
                    ->where('emp_terminations.branch_id', $selectBranch)
                    ->orderBy('emp_terminations.id', 'desc')
                    ->get();
            }

            $branches = Location::where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->get();
        }else{
            if ($selectBranch == 'all') {
                $employees = Emp_termination::with('employee_detail', 'employee_approval', 'branch')
                    ->whereIn('emp_terminations.company_id',$user_company_id)
                    ->whereIn('emp_terminations.branch_id',$user_branch_id)
                    ->orderBy('emp_terminations.id', 'desc')
                    ->get();
            } else {
                $employees = Emp_termination::with('employee_detail', 'employee_approval', 'branch')
                    ->whereIn('emp_terminations.company_id',$user_company_id)
                    ->where('emp_terminations.branch_id', $selectBranch)
                    ->orderBy('emp_terminations.id', 'desc')
                    ->get();
            }

            $branches = Location::whereIn('company_id',$user_company_id)
                ->whereIn('id',$user_branch_id)
                ->where('is_deleted', '0')
                ->orderBy('branch_name', 'asc')
                ->get();
        }

        foreach ($employees as $item) {
            if ($item->employee_approval && $item->employee_approval->designation_id) {
                $designation = Designation::find($item->employee_approval->designation_id);
                if ($designation) {
                    $item->designation = $designation;
                } else {
                    $item->designation = "N/A";
                }
            } else {
                $item->designation = "N/A";
            }
        }

        return view('termination.index', compact('employees','user', 'branches', 'selectBranch'));
    }

    public function addTermination()
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $companies = Company::where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
            ->where('is_deleted', '0')
            ->orderBy('company_name', 'asc')
            ->get();
        }

        return view('termination.add_termination', compact('companies'));
    }

    public function saveTermination(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'termination' => 'required',
            'termination_date' => 'required',
            'notice_date' => 'required',
            'reason' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $record = Emp_termination::where('emp_id', $request->emp_id)->first();
        if (!$record) {
            $data = new Emp_termination;
            $data->company_id = $request->company_id;
            $data->branch_id = $request->branch_id;
            $data->emp_id = $request->emp_id;
            $data->termination_type = $request->termination;
            $data->termination_date = Carbon::parse($request->termination_date)->format('Y-m-d');
            $data->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
            $data->reason = $request->reason;
            $data->save();

            $data = array();
            $getEmployee = EmployeeDetail::find($request->emp_id);
            $getDesignation = user_approval::where('emp_id',$request->emp_id)->first()['designation_id'];
            $getDesignationName = Designation::where('id',$getDesignation)->first()['name'];
            $user_id = User::where('email',$getEmployee->emp_email)->select('id')->first();
            $type = "Employee Termination";
            $branch = $request->branch_id;
            if($user_id){
                $data['emp_name'] = $getEmployee->emp_name;
                $data['user_id'] = $user_id->id;
                $data['emp_position'] = $getDesignationName;
                $data['termination_type'] = $request->termination;
                $data['employee_personal_email'] = $getEmployee->personal_email;
                $data['last_date'] = Carbon::parse($request->resignation_date)->format('Y-m-d');
                $createNotification = new NotificationController();
                $createNotification->generateNotification($type,$data,$branch);
            }
            $EmpDetails = EmployeeDetail::where('id',$request->emp_id)->first();
            $msg = '"' . ucwords($EmpDetails->emp_name) . ' ' . ucwords($request->termination) . '" Added Successfully';
            createLog('termination_action', $msg);
            return redirect('/employee/termination')->with('success', 'Termination Form Submitted');
        } else {
            return redirect()->back()->with('error', 'Termination Form already exists');
        }
    }

    public function editTermination(Request $request, $id)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $companies = Company::where('is_deleted', '0')->orderBy('company_name', 'asc')->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }

        $termination_data = Emp_termination::where('id', $id)->first();

        return view('termination.edit_termination', compact('companies', 'termination_data'));
    }

    public function updatetermination(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'termination' => 'required',
            'termination_date' => 'required',
            'notice_date' => 'required',
            'reason' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $data = Emp_termination::where('id', $request->id)->first();
        $data->company_id = $request->company_id;
        $data->branch_id = $request->branch_id;
        $data->emp_id = $request->emp_id;
        $data->termination_type = $request->termination;
        $data->termination_date = Carbon::parse($request->termination_date)->format('Y-m-d');
        $data->notice_date = Carbon::parse($request->notice_date)->format('Y-m-d');
        $data->reason = $request->reason;
        $data->update();
        $EmpDetails = EmployeeDetail::where('id',$request->emp_id)->first();
        $msg = '"' . ucwords($EmpDetails->emp_name) . ' ' . ucwords($request->termination) . '" Updated Successfully';
        createLog('termination_action', $msg);
        if ($data) {
            return redirect('/employee/termination')->with('success', 'Termination Form Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Termination Form not updated');
        }
    }

    public function terminationSearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));
        $selectBranch = $request->input('selectBranch');
            if($selectBranch == 'all'){
            $fetchData = Emp_termination::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_terminations.id', 'asc')
                ->leftJoin('locations', 'emp_terminations.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_terminations.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_terminations.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_terminations.id', 'emp_terminations.termination_date', 'emp_terminations.notice_date', 'emp_terminations.is_approved')
                // ->where('emp_resignations.branch_id', $selectBranch)
                ->get();
        }else{
            $fetchData = Emp_termination::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_terminations.id', 'asc')
                ->leftJoin('locations', 'emp_terminations.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_terminations.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_terminations.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_terminations.id', 'emp_terminations.termination_date', 'emp_terminations.notice_date', 'emp_terminations.is_approved')
                ->where('emp_terminations.branch_id', $selectBranch)
                ->get();
        }
        foreach($fetchData as $designation){
            if($designation->designation_id){
                $designation->desgn = Designation::where('id',$designation->designation_id)->first();
            }
            else{
                $designation->desgn = "N/A";
            }
        }
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function changeTerminationStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == "0") {
            $statusValue = "0";
        } else if ($status == "2") {
            $statusValue = null;
        } else {
            $statusValue = "1";
        }
        $data = Emp_termination::where('id', $id)->first();
        $data->is_approved = $statusValue;
        $data->update();
        if ($data && $status == "1") {
            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->status = "4";
            $user_detail->update();
        } else if ($data && $status == "2") {
            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->status = "1";
            $user_detail->update();
        }
        return response()->json(['status' => '1']);
    }

    public function deleteTermination(Request $request, $id)
    {
        $termination_data = Emp_termination::where('id', $id)->first();
        $EmpDetails = EmployeeDetail::where('id',$termination_data->emp_id)->first();
        Emp_termination::where('id', $id)->delete();
        $msg = '"' . ucwords($EmpDetails->emp_name) . ' ' . ucwords($termination_data->termination_type) . '" Deleted Successfully';
        createLog('termination_action', $msg);
        return redirect()->back()->with(['success' => 'Termination Deleted Successfully']);
    }

    public function promotion(Request $request)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $selectBranch = isset($request->selectBranch) ? $request->selectBranch : 'all';

        if($user_role == '1'){
            if ($selectBranch == 'all') {
                $employees = EmployeePromotion::with('employee_detail', 'employee_approval', 'branch','designation','preDesignation')
                    ->orderBy('emp_promotions.id', 'desc')
                    ->get();
            } else {
                $employees = EmployeePromotion::with('employee_detail', 'employee_approval', 'branch','designation','preDesignation')
                    ->where('emp_promotions.branch_id', $selectBranch)
                    ->orderBy('emp_promotions.id', 'desc')
                    ->get();
            }
            $branches = Location::where('is_deleted', '0')->orderBy('branch_name', 'asc')->get();
        }else{
            if ($selectBranch == 'all') {
                $employees = EmployeePromotion::with('employee_detail', 'employee_approval', 'branch','designation','preDesignation')
                    ->whereIn('emp_promotions.company_id', $user_company_id)
                    ->whereIn('emp_promotions.branch_id', $user_branch_id)
                    ->orderBy('emp_promotions.id', 'desc')
                    ->get();
            } else {
                $employees = EmployeePromotion::with('employee_detail', 'employee_approval', 'branch','designation','preDesignation')
                    ->whereIn('emp_promotions.company_id', $user_company_id)
                    ->where('emp_promotions.branch_id', $selectBranch)
                    ->orderBy('emp_promotions.id', 'desc')
                    ->get();
            }
            $branches = Location::where('is_deleted', '0')
                ->whereIn('id',$user_branch_id)
                ->whereIn('company_id',$user_company_id)
                ->orderBy('branch_name', 'asc')
                ->get();
        }

        return view('promotion.index', compact('employees', 'branches','user' ,'selectBranch'));
    }

    public function addPromotion()
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $companies = Company::where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }

        $designations = Designation::orderBy('name','asc')->get();

        return view('promotion.add_promotion',compact('companies','designations'));
    }

    public function savePromotion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'from_date' => 'required',
            'designation_id' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $record = EmployeePromotion::where('emp_id', $request->emp_id)->first();
        if (!$record) {
            $data = new EmployeePromotion;
            $data->company_id = $request->company_id;
            $data->branch_id = $request->branch_id;
            $data->emp_id = $request->emp_id;
            $data->from_date = Carbon::parse($request->from_date)->format('Y-m-d');
            $data->emp_desig = $request->emp_desig_id;
            $data->designation_id = $request->designation_id;
            $data->save();
            $aproval = user_approval::where('emp_id', $request->emp_id)->first();
            if ($aproval) {
                $aproval->designation_id = $request->designation_id;
                $aproval->update();
            }
            $EmpDetails = EmployeeDetail::where('id',$request->emp_id)->first();
            $msg = '"' .ucwords($EmpDetails->emp_name). '" Added';
            createLog('promotion_action', $msg);
            return redirect('/employee/promotion')->with('success', 'Promotion Form Submitted');
        } else {
            return redirect()->back()->with('error', 'Promotion Form already exists');
        }
    }

    public function editPromotion(Request $request, $id)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if($user_role == '1'){
            $companies = Company::where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }else{
            $companies = Company::whereIn('id',$user_company_id)
                ->where('is_deleted', '0')
                ->orderBy('company_name', 'asc')
                ->get();
        }
        $designations = Designation::orderBy('name','asc')->get();
        $promotion_data = EmployeePromotion::with('designation','preDesignation')->where('id', $id)->first();
        ;

        return view('promotion.edit_promotion', compact('companies', 'promotion_data','designations'));
    }

    public function updatepromotion(Request $request,$id)
    {
        $validate = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'from_date' => 'required',
            'emp_desig' => 'required',
            'designation_id' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
           $data = EmployeePromotion::where('id', $id)->first();
            // $data = EmployeePromotion::where('id', $request->id)->first();
            $data->company_id = $request->company_id;
            $data->branch_id = $request->branch_id;
            $data->emp_id = $request->emp_id;
            $data->from_date = Carbon::parse($request->from_date)->format('Y-m-d');
            $data->emp_desig = $request->emp_desig_id;
            $data->designation_id = $request->designation_id;
            $data->update();
            $aproval = user_approval::where('emp_id', $request->emp_id)->first();
            if ($aproval) {
                $aproval->designation_id = $request->designation_id;
                $aproval->update();
            }
            $promotion_data = EmployeePromotion::with('designation','preDesignation')->where('id', $id)->first();
            $EmpDetails = EmployeeDetail::where('id',$promotion_data->emp_id)->first();
            $msg = '"' .ucwords($EmpDetails->emp_name). '" Update Successfully';
            createLog('promotion_action', $msg);
            if($data){
            return redirect('/employee/promotion')->with('success', 'Promotion Form Updated');
            } else {
                return redirect()->back()->with('error', 'Promotion Form already exists');
            }
    }

    public function promotionSearch(Request $request)
    {
        $searchValue = strtolower($request->input('searchValue'));
        $selectBranch = $request->input('selectBranch');
            if($selectBranch == 'all'){
            $fetchData = EmployeePromotion::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_promotions.id', 'asc')
                ->leftJoin('locations', 'emp_promotions.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_promotions.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_promotions.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_promotions.id', 'emp_promotions.from_date', 'emp_promotions.emp_desig', 'emp_promotions.is_approved')
                // ->where('emp_resignations.branch_id', $selectBranch)
                ->get();
        }else{
            $fetchData = EmployeePromotion::where(function ($query) use ($searchValue, $selectBranch) {
                $query->where(function ($query) use ($searchValue, $selectBranch) {
                    $query->WhereHas('employee_detail', function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(employee_details.emp_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(employee_details.emp_id) LIKE ?', ['%' . $searchValue . '%']);
                    })
                        ->orWhereHas('branch', function ($query) use ($searchValue, $selectBranch) {
                            $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%']);
                        });
                });
            })
                ->orderBy('emp_promotions.id', 'asc')
                ->leftJoin('locations', 'emp_promotions.branch_id', '=', 'locations.id')
                ->leftJoin('employee_details', 'emp_promotions.emp_id', '=', 'employee_details.id')
                ->leftJoin('emp_approvals', 'emp_promotions.emp_id', 'emp_approvals.emp_id')
                ->select('employee_details.emp_id', 'locations.branch_name', 'emp_approvals.designation_id', 'employee_details.emp_name', 'emp_promotions.id', 'emp_promotions.from_date', 'emp_promotions.emp_desig', 'emp_promotions.is_approved')
                ->where('emp_promotions.branch_id', $selectBranch)
                ->get();
        }
        foreach($fetchData as $designation){
            if($designation->designation_id){
                $designation->new_designation = Designation::where('id',$designation->designation_id)->first();
                $designation->previous_desgnation = Designation::where('id',$designation->emp_desig)->first();
            }
            else{
                $designation->desgn = "N/A";
            }
        }
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function deletePromotion(Request $request, $id)
    {
        $promotion_data = EmployeePromotion::with('designation','preDesignation')->where('id', $id)->first();
        $EmpDetails = EmployeeDetail::where('id',$promotion_data->emp_id)->first();
        EmployeePromotion::where('id', $id)->delete();
        $msg = '"' .ucwords($EmpDetails->emp_name). '" Deleted Successfully';
        createLog('promotion_action', $msg);
        return redirect()->back()->with(['success' => 'Promotion Deleted Successfully']);
    }

    public function changePromotionStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == "0") {
            $statusValue = "0";
        } else if ($status == "2") {
            $statusValue = null;
        } else {
            $statusValue = "1";
        }

        $data = EmployeePromotion::where('id', $id)->first();
        $data->is_approved = $statusValue;
        $data->update();

        if ($data && $status == "1") {
            // $resignationDate = Carbon::parse($data->resignation_date);
            // $nextDay = $resignationDate->addDay();
            // if($nextDay->format('Y-m-d') == Carbon::now()->format('Y-m-d')){
            $user = user_approval::where('emp_id', $data->emp_id)->first();
            $user->is_active = "0";
            $user->update();

            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->is_active = "0";
            $user_detail->update();
            // }
        } else if ($data && $status == "2") {
            $user = user_approval::where('emp_id', $data->emp_id)->first();
            $user->is_active = "1";
            $user->update();

            $user_detail = EmployeeDetail::where('id', $data->emp_id)->first();
            $user_detail->is_active = "1";
            $user_detail->update();
        }

        return response()->json(['status' => '1']);
    }

    public function shiftManagement()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
             $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
            $shiftListing = ShiftManagement::where('is_active','1')
                ->where('is_deleted','0')
                ->paginate(12);
        } else {
            $companies = Company::where('is_deleted', '0')
            ->whereIn('id',$user_company_id)
            ->orderBy('company_name','asc')
            ->get();
            $shiftListing = ShiftManagement::where('is_active','1')
                ->where('is_deleted','0')
                ->paginate(12);
        }
        $departments = Department::orderBy('name','asc')->get();
        return view('shift_schedule_management.index', compact('shiftListing','user','companies','departments'));
    }

    public function addShift()
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        return view('shift_schedule_management.add_shift');
    }

    public function saveShift(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'shift_name' => 'required|unique:shift_management,shift_name',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_start_time' => 'required',
            'break_end_time' => 'required',
            'late_time' => 'required',
            'half_day' => 'required',
            'note' => 'required',
            'selectedDays' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }

        $create_shift = new ShiftManagement();
        $create_shift->shift_name = $request->shift_name;
        $create_shift->start_time = $request->start_time;
        $create_shift->end_time = $request->end_time;
        $create_shift->break_start_time = $request->break_start_time;
        $create_shift->break_end_time = $request->break_end_time;
        $create_shift->late_time = $request->late_time;
        $create_shift->half_day = $request->half_day;
        $create_shift->is_recurring = $request->is_recurring;
        $create_shift->note = $request->note;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $create_shift->working_days = $daysString;
        $start_time = strtotime($request->start_time);
        $end_time = strtotime($request->end_time);
        $duration = $end_time - $start_time;
        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $create_shift->working_hours = sprintf('%02d:%02d', $hours, $minutes);
        $create_shift->save();
        $msg = '"' . $request->shift_name . '" Added Successfully';
        createLog('shift_management_action', $msg);
        if($create_shift){
            return redirect('/employee/shift-management')->with('success','Shift Added Successfully');
        }else{
            return redirect()->back()->with('error','Something went wrong');
        }
    }

    public function editShift($id)
    {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $shift_detail = ShiftManagement::where('id',$id)->first();
        return view('shift_schedule_management.edit_shift', compact('shift_detail'));
    }

    public function updateShift(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'shift_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_start_time' => 'required',
            'break_end_time' => 'required',
            'late_time' => 'required',
            'half_day' => 'required',
            'note' => 'required',
            'selectedDays' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', "Some of the fields are missing");
        }

        $update_shift = ShiftManagement::where('id',$request->id)->first();
        $update_shift->shift_name = $request->shift_name;
        $update_shift->start_time = $request->start_time;
        $update_shift->end_time = $request->end_time;
        $update_shift->break_start_time = $request->break_start_time;
        $update_shift->break_end_time = $request->break_end_time;
        $update_shift->late_time = $request->late_time;
        $update_shift->half_day = $request->half_day;
        $update_shift->is_recurring = $request->is_recurring;
        $update_shift->note = $request->note;
        $selectedDays = $request->input('selectedDays');
        $daysString = implode(',', $selectedDays);
        $update_shift->working_days = $daysString;
        $update_shift->update();
        $msg = '"' . $request->shift_name . '" Updated Successfully';
        createLog('shift_management_action', $msg);
        if($update_shift){
            return redirect('/employee/shift-management')->with('success','Shift Updated Successfully');
        }else{
            return redirect()->back()->with('error','Something went wrong');
        }
    }

    public function ShiftSearch(Request $request)
    {
        $searchValue = strtolower($request->input('shift'));
        $fetchData = ShiftManagement::where(function ($query) use ($searchValue) {
            $query->whereRaw('LOWER(shift_management.shift_name) LIKE ?', ['%' . $searchValue . '%']);
        })
        ->orderBy('shift_management.id', 'asc')
        ->select('shift_management.*')
        ->get();
        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function holidaysList()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $companies = Company::where('is_deleted', '0')->orderBy('company_name','asc')->get();
            // $branches = Location::where('is_deleted', '0')->get();
            $holidays = Holiday::leftjoin('locations', 'locations.id', '=', 'holidays.branch_id')
                ->where('holidays.is_deleted', '0')
                ->select('holidays.*', 'locations.branch_name')
                ->orderBy('start_date', 'desc')
                ->get();
        } else {
            $companies = Company::where('is_deleted', '0')
            ->whereIn('id',$user_company_id)
            ->orderBy('company_name','asc')
            ->get();
            // $branches = Location::where('is_deleted', '0')
            // ->get();

            $holidays = Holiday::leftjoin('locations', 'locations.id', '=', 'holidays.branch_id')
                ->select('holidays.*', 'locations.branch_name')
                ->whereIn('holidays.company_id', $user_company_id)
                ->whereIn('holidays.branch_id', $user_branch_id)
                ->where('holidays.is_deleted', '0')
                ->orderBy('start_date', 'desc')
                ->get();
        }
        return view('holidays.index', compact('holidays', 'user','companies'));
    }

    public function saveHolidays(Request $request)
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = $request->end_date != null ? date('Y-m-d', strtotime($request->end_date)) : null;

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'event_name' => 'required',
            'start_date' => [
                'required',
                function ($attribute, $value, $fail) use ($start_date, $end_date) {
                    $count = DB::table('holidays')
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->count();

                    if ($count > 0) {
                        $fail("The selected date range overlaps with an existing holiday.");
                    }
                }
            ],
            'end_date' => 'required',
        ], [
            'start_date.required' => 'The start date is required.',
            'start_date.unique' => 'The selected date range overlaps with an existing holiday.',
            'end_date.required' => 'The end date is required.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $company_id = $request->company_id;
        $companyString = implode(',', $company_id);


        if (in_array('all', $request->company_id)) {
            $company_id = Company::where('is_active', '1')->where('is_deleted', '0')->pluck('id')->toArray();
            $companyString = implode(',', $company_id);
        } else {
            $company_id = $request->company_id;
            $companyString = implode(',', $company_id);
        }
        if (in_array('all', $request->branch_id)) {
            $branch_company_id = explode(',',$companyString);
                $branch_id = Location::where('is_deleted', '0')->whereIn('company_id',$branch_company_id)->pluck('id')->toArray();
                $branchString = implode(',', $branch_id);
            } else {
                $branch_id = $request->branch_id;
                $branchString = implode(',', $branch_id);
            }
        $user = Holiday::create([
            'branch_id' => $branchString,
            'company_id' => $companyString,
            'event_name' => $request->event_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_active' => $request->is_active,
        ]);
        $msg = '"' . $request->event_name . '" Added Successfully';
        createLog('holidays_action', $msg);
        return redirect()->back()->with('success', 'Holiday Added Successfully');
    }

    public function getHolidayDetail(Request $request)
    {
        $holiday = Holiday::where('id', $request->holiday_id)->first();
        $holiday->start_date = date('d-m-Y', strtotime($holiday->start_date));
        $holiday->end_date = date('d-m-Y', strtotime($holiday->end_date));

        return response()->json(['success' => true, 'data' => $holiday]);
    }

    public function updateHoliday(Request $request)
    {
        $id = $request->id;
        $start_date = date('Y-m-d', strtotime($request->holiday_start_date));
        $end_date = $request->holiday_end_date != null ? date('Y-m-d', strtotime($request->holiday_end_date)) : null;
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
            'event_name' =>  'required',
            'holiday_start_date' => [
                'required',
                function ($attribute, $value, $fail) use ($start_date, $end_date,$id) {
                    $count = DB::table('holidays')
                        ->where('id', '!=', $id)
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->count();

                    if ($count > 0) {
                        $fail("The selected date range overlaps with an existing holiday.");
                    }
                }
            ],
            'holiday_end_date' => 'required',
        ], [
            'holiday_start_date.required' => 'The start date is required.',
            'holiday_start_date.unique' => 'The selected date range overlaps with an existing holiday.',
            'holiday_end_date.required' => 'The end date is required.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $company_id = $request->company_id;
        $companyString = implode(',', $company_id);


        if (in_array('all', $request->company_id)) {
            $company_id = Company::where('is_active', '1')->where('is_deleted', '0')->pluck('id')->toArray();
            $companyString = implode(',', $company_id);
        } else {
            $company_id = $request->company_id;
            $companyString = implode(',', $company_id);
        }
        if (in_array('all', $request->branch_id)) {
            $branch_company_id = explode(',',$companyString);
                $branch_id = Location::where('is_deleted', '0')->whereIn('company_id',$branch_company_id)->pluck('id')->toArray();
                $branchString = implode(',', $branch_id);
            } else {
                $branch_id = $request->branch_id;
                $branchString = implode(',', $branch_id);
            }
           $user = Holiday::where('id',$request->id)->update([
            'branch_id' => $branchString,
            'company_id' => $companyString,
            'event_name' => $request->event_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_active' => $request->is_active,
        ]);
        $msg = '"' . $request->event_name . '" Updated Successfully';
        createLog('holidays_action', $msg);

        return redirect()->route('holidays.list')->with('success', 'Holiday Updated Successfully');
    }

    public function destroyHoliday(int $id)
    {
        $delHoliday = Holiday::findOrFail($id);
        $delHoliday->delete();

        $msg = '"'.$delHoliday->event_name.'" Deleted';
        createLog('holidays_action',$msg);

        return redirect()->back()->with('success', 'Holiday Deleted Successfully');
    }

    public function getsearchedHoliday(Request $request)
    {
        $searchValue = strtolower($request->holiday);
        $fetchData = Holiday::where(function ($query) use ($searchValue) {
            $query->where('holidays.is_deleted', '0')
                ->where(function ($query) use ($searchValue) {
                    $query->whereRaw('LOWER(event_name) LIKE ?', ['%' . $searchValue . '%'])
                        ->orWhereRaw('LOWER(start_date) LIKE ?', ['%' . $searchValue . '%']);
                });
        })
            ->leftjoin('locations', 'locations.id', '=', 'holidays.branch_id')
            ->orderBy('holidays.id', 'asc')
            ->select('holidays.id', 'holidays.event_name', 'holidays.start_date', 'holidays.is_active', 'holidays.end_date', 'holidays.updated_at', 'locations.branch_name')
            ->get();

        $fetchData->each(function ($item) {
            $item->start_date = date('d-m-Y', strtotime($item->start_date));
            $item->end_date = date('d-m-Y', strtotime($item->end_date));
        });

        if (count($fetchData) > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function getHolidayBranche(Request $request){
        $idString = $request->holiday_branch_id;
        $idArray = explode(',', $idString);
        $branches = Location::whereIn('id', $idArray)->get();
        if ($branches->count() > 0) {
            return response()->json(["success" => true, "data" => $branches]);
        } else {
            return response()->json(["success" => false, "message" => "No locations found for the given IDs."], 404);
        }
    }

    public function branchIndex()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $branches = Location::leftjoin('com_cities', 'locations.city_id', '=', 'com_cities.city_id')
                ->leftjoin('com_countries', 'locations.country_id', '=', 'com_countries.country_id')
                ->leftjoin('companies', 'locations.company_id', '=', 'companies.id')
                ->select('com_cities.city_name', 'locations.*', 'com_countries.country_name', 'companies.company_name')
                ->where('locations.is_deleted', '0')
                ->orderBy('locations.branch_id', 'asc')
                ->get();
        } else {
            $branches = Location::leftjoin('com_cities', 'locations.city_id', '=', 'com_cities.city_id')
                ->leftjoin('com_countries', 'locations.country_id', '=', 'com_countries.country_id')
                ->leftjoin('companies', 'locations.company_id', '=', 'companies.id')
                ->select('com_cities.city_name', 'locations.*', 'com_countries.country_name', 'companies.company_name')
                ->where('locations.is_deleted', '0')
                ->whereIn('locations.company_id', $user_company_id)
                ->whereIn('locations.id', $user_branch_id)
                ->orderBy('locations.branch_id', 'asc')
                ->get();
        }

        foreach ($branches as $branch) {
            $branch->total_employees = EmployeeDetail::where('branch_id', $branch->id)->count();
        }

        return view('branch.index', compact('branches','user'));
    }

    public function branchAdd(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        if ($user_role == 1) {
            $companies = Company::where('is_active', '1')->where('is_deleted', '0')->orderBy('company_name','asc')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->orderBy('company_name','asc')
                ->where('is_active', '1')
                ->where('is_deleted', '0')
                ->get();
        }
        $com_cities = City::where('is_deleted', 'N')->orderBy('city_name','asc')->get();
        $com_countries = Country::where('is_deleted', 'N')->orderBy('country_name','asc')->get();

        return view('branch.add_branch', compact('com_cities', 'companies', 'com_countries'));
    }

    public function getcities(Request $request)
    {
        $com_cities = City::where('is_deleted', 'N')->where('country_id', $request->country_id)->get();
        return response()->json(['success' => true, 'data' => $com_cities]);
    }

    public function branchStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|unique:locations',
            'branch_id' => 'required|unique:locations',
            'company_id' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
        ], [
            'branch_name.unique' => 'The branch name is already taken.',
            'branch_id.unique' => 'The branch ID is already taken.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $branch = new Location;
        $branch->company_id = $request->company_id;
        $branch->branch_name = $request->branch_name;
        $branch->branch_id = $request->branch_id;
        $branch->country_id = $request->country_id;
        $branch->city_id = $request->city_id;
        $branch->save();

        $msg = 'Added "'.$request->branch_name.'" Successfully';
        createLog('branch_action',$msg);

        return redirect()->route('branch.management')->with('success', 'Location Added Successfully');
    }

    public function editBranchData($id)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $branch_id = base64_decode($id);

        if ($user_role == 1) {
            $companies = Company::where('is_active', '1')->orderBy('company_name','asc')->where('is_deleted', '0')->get();
        } else {
            $companies = Company::whereIn('id', $user_company_id)
                ->orderBy('company_name','asc')
                ->where('is_active', '1')
                ->where('is_deleted', '0')
                ->get();
        }

        $com_cities = City::where('is_deleted', 'N')->orderBy('city_name','asc')->get();
        $com_countries = Country::where('is_deleted', 'N')->orderBy('country_name','asc')->get();
        $branch = Location::findOrFail($branch_id);

        return view('branch.editBranch', compact('branch', 'com_countries', 'companies', 'com_cities'));
    }

    public function updateBranchData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|unique:locations,branch_name,'.$request->id,
            'branch_id' => 'required|unique:locations,branch_id,'.$request->id,
            'company_id' => 'required',
            'country_id' => 'required',
            'city_id' => 'required',
        ], [
            'branch_name.unique' => 'The branch name is already taken.',
            'branch_id.unique' => 'The branch ID is already taken.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $branch = Location::findOrFail($request->id);
        $branch->branch_name = $request->branch_name;
        $branch->branch_id = $request->branch_id;
        $branch->company_id = $request->company_id;
        $branch->country_id = $request->country_id;
        $branch->city_id = $request->city_id;
        $branch->update();

        $msg = 'updated "'.$request->branch_name.'" successfully';
        createLog('branch_action',$msg);

        return redirect()->route('branch.management')->with('success', 'Location Updated Successfully');
    }

    public function destroyBranch($id)
    {
        $branch_id = base64_decode($id);
        $branch = Location::findOrFail($branch_id);
        $branch->is_deleted = '1';
        $branch->update();

        $msg = 'Deleted "'.$branch->branch_name.'"';
        createLog('branch_action',$msg);

        return redirect()->back()->with('success', 'Location Deleted Successfully');
    }

    public function getsearchedbranch(Request $request)
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $searchValue = strtolower($request->input('search_value'));
        if ($user_role == 1) {
            $fetchData = Location::where(function ($query) use ($searchValue) {
                $query->where('locations.is_deleted', '0')
                    ->where(function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(locations.branch_id) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereHas('country', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(com_countries.country_name) LIKE ?', ['%' . $searchValue . '%']);
                            })
                            ->orWhereHas('company', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(companies.company_name) LIKE ?', ['%' . $searchValue . '%']);
                            })
                            ->orWhereHas('city', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(com_cities.city_name) LIKE ?', ['%' . $searchValue . '%']);
                            });
                    });
            })
                ->orderBy('locations.id', 'asc')
                ->leftJoin('com_countries', 'locations.country_id', '=', 'com_countries.country_id')
                ->leftJoin('com_cities', 'locations.city_id', '=', 'com_cities.city_id')
                ->leftJoin('companies', 'locations.company_id', '=', 'companies.id')
                ->select('locations.id', 'locations.branch_name', 'locations.branch_id', 'companies.company_name', 'com_countries.country_name', 'com_cities.city_name')
                ->get();
        } else {
            $fetchData = Location::where(function ($query) use ($searchValue) {
                $query->where('locations.is_deleted', '0')
                    ->where(function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(locations.branch_name) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereRaw('LOWER(locations.branch_id) LIKE ?', ['%' . $searchValue . '%'])
                            ->orWhereHas('country', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(com_countries.country_name) LIKE ?', ['%' . $searchValue . '%']);
                            })
                            ->orWhereHas('company', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(companies.company_name) LIKE ?', ['%' . $searchValue . '%']);
                            })
                            ->orWhereHas('city', function ($query) use ($searchValue) {
                                $query->whereRaw('LOWER(com_cities.city_name) LIKE ?', ['%' . $searchValue . '%']);
                            });
                    });
            })
                ->whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->orderBy('locations.id', 'asc')
                ->leftJoin('com_countries', 'locations.country_id', '=', 'com_countries.country_id')
                ->leftJoin('com_cities', 'locations.city_id', '=', 'com_cities.city_id')
                ->leftJoin('companies', 'locations.company_id', '=', 'companies.id')
                ->select('locations.id', 'locations.branch_name', 'locations.branch_id', 'companies.company_name', 'com_countries.country_name', 'com_cities.city_name')
                ->get();
        }

        foreach ($fetchData as $branch) {
            $branch->total_employees = EmployeeDetail::where('branch_id', $branch->id)->count();
        }

        if ($fetchData->count() > 0) {
            return response()->json(["success" => true, "data" => $fetchData]);
        } else {
            return response()->json(["success" => false, "data" => 'No Record found']);
        }
    }

    public function AttenSheetdownload($branch_id, $year_month)
    {
       //user information
       $user = auth()->user();
       $user_role = $user->role_id;
       $user_company_id = explode(',',$user->company_id);
       $user_branch_id = explode(',',$user->branch_id);
       $branch_code = 'all';
       $branch_name = '';

        if (isset($branch_id) && $branch_id != 'all') {
            $branch = Location::where('is_deleted', '0')->where('id', $branch_id)->first();
            $branch_code = $branch->branch_id;
            $branch_name = $branch->branch_name;
        }

       $selected = isset($branch_id) ? $branch_id : 'all';
       $current_month_year = isset($year_month) ? date('F Y', strtotime($year_month)) : date('F Y');
       $month_number = isset($year_month) ? date('m', strtotime($year_month)) : date('m');
       $number_of_days = Carbon::now()->month($month_number)->daysInMonth;
       $start_date = isset($year_month) ? date('Y-m-01', strtotime($year_month)) : date('Y-m-01');
       $currentMonth = isset($year_month) ? date('Y-m', strtotime($year_month)) : Carbon::now()->format('Y-m');
       $carbonDate = Carbon::parse($currentMonth);
       $month = $carbonDate->month;
       $year = $carbonDate->year;

       if ($user_role == 1) {
           $branches = Location::where('is_deleted', '0')->get();
       } else {
           $branches = Location::whereIn('company_id', $user_company_id)
               ->whereIn('id', $user_branch_id)
               ->where('is_deleted', '0')
               ->get();
       }

       if ($selected != 'all') {
           if ($user_role == '1') {
               $employees = EmployeeDetail::with([
                   'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                       $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                   }
               ])
               ->with([
                   'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                       $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                       ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                   }
               ])
               ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
               ->where('employee_details.branch_id', $branch_id)
               ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
               ->where('employee_details.is_deleted', '0')
               ->orderBy('employee_details.emp_id', 'asc')
               ->get();
           } else {
               $employees = EmployeeDetail::with([
                   'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                       $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                   }
               ])
               ->with([
                   'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                       $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                       ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                   }
               ])
                   ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
                   ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
                   ->whereIn('employee_details.company_id', $user_company_id)
                   ->where('employee_details.branch_id', $branch_id)
                   ->where('employee_details.is_deleted', '0')
                   ->orderBy('employee_details.emp_id', 'asc')
                   ->get();
           }
       } else {
           if ($user_role == '1') {
               $employees = EmployeeDetail::with([
                   'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                       $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                   }
               ])
               ->with([
                   'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                       $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                       ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                   }
               ])
               ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
               ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
               ->where('employee_details.is_deleted', '0')
               ->orderBy('employee_details.emp_id', 'asc')
               ->get();
           } else {
               $employees = EmployeeDetail::with([
                   'get_user_daily_attendance' => function ($query) use ($currentMonth) {
                       $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
                   }
               ])
               ->with([
                   'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
                       $query->where('year',Carbon::parse($currentMonth)->format('Y'))
                       ->where('month_of',Carbon::parse($currentMonth)->format('m'));
                   }
               ])
               ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
               ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
               ->whereIn('employee_details.company_id', $user_company_id)
               ->whereIn('employee_details.branch_id', $user_branch_id)
               ->where('employee_details.is_deleted', '0')
               ->orderBy('employee_details.emp_id', 'asc')
               ->get();
           }
       }

       $attendanceArray = [];
       foreach ($employees as $employee) {
           $employeeAttendance = $employee['get_user_daily_attendance']; // Assuming this is a collection or array of attendance records for the employee

           for ($i = 0; $i < $number_of_days; $i++) {
               $current_date = strtotime($start_date);
               $next_date = strtotime("+" . $i . " day", $current_date);
               $date = date('Y-m-d', $next_date);

               $attendanceStatus = 'free'; // Initialize the status

               foreach ($employeeAttendance as $dailyAttendace) {
                   if ($date == $dailyAttendace['dated']) {
                       // Check attendance conditions for the given date
                       if ($dailyAttendace['weekend']) {
                           if ($dailyAttendace['present']) {
                               $attendanceStatus = 'present_on_weekend';
                           } else {
                               $attendanceStatus = 'weekend';
                           }
                       } elseif ($dailyAttendace['holiday']) {
                           if ($dailyAttendace['present']) {
                               $attendanceStatus = 'present_on_holiday';
                           } else {
                               $attendanceStatus = 'holiday';
                           }
                       } elseif ($dailyAttendace['leave']) {
                           if ($dailyAttendace['present']) {
                               $attendanceStatus = 'present_on_leave';
                           } else {
                               $attendanceStatus = 'leave';
                           }
                       } elseif ($dailyAttendace['absent']) {
                           $attendanceStatus = 'absent';
                       } elseif ($dailyAttendace['present']) {
                           if ($dailyAttendace['half_leave']) {
                               $attendanceStatus = 'half_leave';
                           } elseif ($dailyAttendace['late_coming']) {
                               $attendanceStatus = 'late_coming';
                           } else {
                               $attendanceStatus = 'present';
                           }
                       } elseif ($dailyAttendace['is_new_joining']) {
                           $attendanceStatus = 'new_joining';
                       } elseif ($dailyAttendace['is_resigned']) {
                           $attendanceStatus = 'resigned';
                       } elseif ($dailyAttendace['is_terminated']) {
                           $attendanceStatus = 'terminated';
                       }
                       // Break out of the inner loop since we found a match
                       break;
                   }
               }
               $attendanceArray[] = [
                   'employee_id' => $employee->id,
                   'date' => $date,
                   'attendance_status' => $attendanceStatus,
               ];
           }

           $employeeMonthlySummary = $employee['get_user_monthly_attendance'];
           if($employeeMonthlySummary){
               $actualHours = $employeeMonthlySummary != null ?$employeeMonthlySummary->actual_working_hours:0;
               $act_hours = floor($actualHours / 60);
               $act_minutes = $actualHours % 60;

               $workingHours = $employeeMonthlySummary != null ?$employeeMonthlySummary->working_hours:0;
               $w_hours = floor($workingHours / 60);
               $w_minutes = $workingHours % 60;

               // Format the result
               $employee['get_user_monthly_attendance']['actual_working_hours'] = sprintf("%02dh:%02dm", $act_hours, $act_minutes);
               $employee['get_user_monthly_attendance']['working_hours'] = sprintf("%02dh:%02dm", $w_hours, $w_minutes);
           }
       }

        $csvData = [];
        for ($day = 1; $day <= $number_of_days; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dayName = $date->format('D');
            $lengthOfDays[] = ['day' => $day, 'name' => $dayName[0]];
        }

        $csvData[] = array_merge(
            [sprintf('%-10s', 'Emp ID'), sprintf('%-20s', 'Name')],
            array_map(function ($dayData) {
                return str_pad($dayData['day'], 2, ' ', STR_PAD_LEFT) . ' ' . str_pad($dayData['name'], 2, ' ', STR_PAD_RIGHT);
            }, $lengthOfDays),
            [sprintf('%-10s', 'Absent'), sprintf('%-10s', 'Late'), sprintf('%-5s', 'HL'), sprintf('%-5s', 'Leave'), sprintf('%-15s', 'Working Hours'), sprintf('%-15s', 'Actual Hours')]
        );

        $csvData[0] = array_map(function ($cell) {
            return str_pad($cell, strlen($cell) + 6, ' ', STR_PAD_BOTH);
        }, $csvData[0]);

        foreach ($employees as $key => $employee){
            if( isset($employee->resignations) && isset($employee->resignations->is_approved) == '1' ? Date("Y-m",strtotime($employee->resignations->resignation_date)) >= Date("Y-m", strtotime($current_month_year)) : true){
                if(isset($employee->terminations) && isset($employee->terminations->is_approved) == '1' ? Date("Y-m",strtotime($employee->terminations->termination_date)) >= Date("Y-m", strtotime($current_month_year)) : true){
                    $employeeId = $employee->emp_id;
                    $employeeName = $employee->emp_name;
                    //print employee id and name
                    $row = [
                        $employeeId,
                        $employeeName,
                    ];
                    //print daily attendance
                    if($attendanceArray){
                        foreach ($attendanceArray as $dailyAttendance){
                            if($dailyAttendance['employee_id'] == $employee->id){
                                if ($dailyAttendance['attendance_status'] == 'weekend'){
                                    $printValue = 'W';
                                }elseif($dailyAttendance['attendance_status'] == 'present on weekend'){
                                    $printValue = 'P';
                                }elseif($dailyAttendance['attendance_status'] == 'present on holiday'){
                                    $printValue = 'P';
                                }elseif($dailyAttendance['attendance_status'] == 'holiday'){
                                    $printValue = 'H';
                                }elseif($dailyAttendance['attendance_status'] == 'present on leave'){
                                    $printValue = 'P';
                                }elseif($dailyAttendance['attendance_status'] == 'leave'){
                                    $printValue = 'L';
                                }elseif($dailyAttendance['attendance_status'] == 'absent'){
                                    $printValue = 'A';
                                }elseif($dailyAttendance['attendance_status'] == 'half_leave'){
                                    $printValue = 'HL';
                                }elseif ($dailyAttendance['attendance_status'] == 'late_coming'){
                                    $printValue = 'LC';
                                }elseif ($dailyAttendance['attendance_status'] == 'present'){
                                    $printValue = 'P';
                                }elseif($dailyAttendance['attendance_status'] == 'new_joining'){
                                    $printValue = 'X';
                                }elseif ($dailyAttendance['attendance_status'] == 'resigned'){
                                    $printValue = 'R';
                                }elseif ($dailyAttendance['attendance_status'] == 'terminated'){
                                    $printValue = 'T';
                                }elseif ($dailyAttendance['attendance_status'] == 'free'){
                                    $printValue = '-';
                                }
                                $row[] = str_pad($printValue, 10, ' ', STR_PAD_LEFT);
                            }
                        }
                    }else{
                        for ($num = 1; $num <= $number_of_days; $num++){
                            $row[] = str_pad('-', 10, ' ', STR_PAD_LEFT);
                        }
                    }

                    //print monthly summary
                    $monthly_summary = $employee['get_user_monthly_attendance'];
                    $row[] = str_pad(($monthly_summary ? $monthly_summary['absents'] :'-'), 10, ' ', STR_PAD_LEFT);
                    $row[] = str_pad(($monthly_summary ? $monthly_summary['late_comings'] :'-'), 10, ' ', STR_PAD_LEFT);
                    $row[] = str_pad(($monthly_summary ? $monthly_summary['half_leaves'] :'-'), 10, ' ', STR_PAD_LEFT);
                    $row[] = str_pad(($monthly_summary ? $monthly_summary['leaves'] :'-'), 10, ' ', STR_PAD_LEFT);
                    $row[] = str_pad(($monthly_summary ? $monthly_summary['working_hours'] :'-'), 15, ' ', STR_PAD_LEFT);
                    $row[] = str_pad(($monthly_summary ? $monthly_summary['actual_working_hours'] :'-'), 15, ' ', STR_PAD_LEFT);
                    $csvData[] = $row;
                }
            }
        }
        $fileName = 'attendance_sheet_' . Carbon::parse($current_month_year)->format('F_Y') .'_'.$branch_code. '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $msg = '"Monthly" Downloaded Successfully';
        createLog('timesheet_action',$msg);

        return Response::stream($callback, 200, $headers);
    }


    function csvToArray($filename = '', $delimiter = ',')
    {

        if (!file_exists($filename) || !is_readable($filename))
            return false;


        $header = null;
//        return json_encode(fopen($filename, 'r'));
        $data = array();
        if (($handle = fopen($filename, 'r')) != false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else if(count($header) == count($row))
                    $data[] = array_combine($header, $row);
                // else
                // return [$header, $row];
            }
            fclose($handle);
        }

        return $data;
    }
    public function cronJobDetal()
    {
        // if (Auth::user()->roles != '1') {
        //     return redirect('/home');
        // }
        $CronJobDetail = CronJobHistory::orderBy('created_at',"Desc")->paginate('20');
        return view('cron_job_detail', ["CronJobDetail" => $CronJobDetail]);
    }
}
