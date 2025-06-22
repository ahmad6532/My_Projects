<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\Location;
use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use App\Models\CompanySetting;
use App\Models\EmployeeDetail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function dashboardMainGraph(){
    //         date_default_timezone_set("Asia/Karachi");
    //         $type = 'month';
    //         $currentMonth = date('m');
    //         $currentYear = date('Y');
    //         $number_of_days = now()->month($currentMonth)->daysInMonth;
    //         $user = auth()->user();
    //         $user_role = $user->role_id;
    //         $user_company_id = explode(',',$user->company_id);
    //         $user_branch_id = explode(',',$user->branch_id);
    //         $currentDate = Carbon::now()->toDateString();
    //         $datesInMonth = array();
    //         // $totalPresentEmp = EmployeeDetail::with('branch')->where('status', '1')
    //         //     ->where('is_deleted', '0')
    //         //     ->orderBy('emp_id','asc')
    //         //     ->whereHas('user_attendance', function ($q) use ($currentDate) {
    //         //         $q->whereNotNull('check_in');
    //         //             // ->whereDate('created_at', $currentDate);
    //         //     })->get();
    //         // $employees = EmployeeDetail::with('branch')->whereIn('company_id',$user_company_id)
    //         //         ->whereIn('branch_id',$user_branch_id)
    //         //         ->where('status', '1')
    //         //         ->where('is_deleted', '0')
    //         //         ->get();
    //         if($type == 'month'){
    //             $branchesWithAttendance = EmployeeDetail::whereIn('company_id', $user_company_id)
    //                 ->whereIn('branch_id', $user_branch_id)
    //                 ->where('status', '1')
    //                 ->where('is_deleted', '0')
    //                 ->with(['user_attendance' => function ($query) use ($currentMonth) {
    //                 $query->whereMonth('created_at', $currentMonth);
    //             }])->get();
    //         }
    //         else{
    //             $branchesWithAttendance = EmployeeDetail::whereIn('company_id',$user_company_id)->whereIn('branch_id',$user_branch_id)->where('status', '1')->where('is_deleted', '0')->with(['user_attendance',function ($q) use ($currentMonth,$currentYear){
    //                 $q->whereMonth('created_at',$currentMonth)
    //                 ->whereYear('created_at',$currentYear);
    //             }])->get();
    //         }
    //     foreach($branchesWithAttendance as $company){
    //         $company_settings = CompanySetting::where('company_id',$company->company_id)->where('branch_id', $company->id)
    //             ->where('is_deleted', '0')
    //             ->first();
    //         $hours = ($company_settings ? $company_settings->half_day : 0);
    //         $late_time = ($company_settings ? $company_settings->late_time : null);
    //         if($company_settings != null && $company_settings != ""){
    //             $countDays = count(explode(',',$company_settings->days));
    //         }
    //         $firstDayOfMonth = Carbon::create($currentYear, $currentMonth)->startOfMonth();
    //         $endDayOfMonth = Carbon::create($currentYear, $currentMonth)->endOfMonth();
    //         while ($firstDayOfMonth->lte($endDayOfMonth)) {
    //             if($countDays == 5){
    //                 if(!($firstDayOfMonth->isweekend())){
    //                     $datesInMonth[] = $firstDayOfMonth->toDateString();
    //                 }
    //             }
    //             elseif($countDays == 6){
    //                 if(!($firstDayOfMonth->isSunday())){
    //                     $datesInMonth[] = $firstDayOfMonth->toDateString();
    //                 }
    //             }
    //             $firstDayOfMonth->addDay();
    //         }
    //         if($company->user_attendance != null && count($company->user_attendance) > 0 ){
    //         //    foreach($datesInMonth as $date){
    //         //     if(!(in_array())
    //         //    }
    //         }



    //         foreach($company->employee_details_with_attendance as $branchEmployees){
    //             if($branchEmployees->branch_id == $company->id){
    //                 if(!$branchEmployees->user_attendance->isEmpty() && $branchEmployees->status == '1'){
    //                     return $branchEmployees;
    //                     //present count
    //                     $present ++;
    //                     //absent count working
    //                     $firstDayOfMonth = Carbon::create($currentYear, $currentMonth)->startOfMonth();
    //                     $endDayOfMonth = Carbon::create($currentYear, $currentMonth)->endOfMonth();
    //                     while ($firstDayOfMonth->lte($endDayOfMonth)) {
    //                         if($countDays == 5){
    //                             if(!($firstDayOfMonth->isweekend())){
    //                                 $datesInMonth[] = $firstDayOfMonth->toDateString();
    //                             }
    //                         }
    //                         elseif($countDays == 6){
    //                             if(!($firstDayOfMonth->isSunday())){
    //                                 $datesInMonth[] = $firstDayOfMonth->toDateString();
    //                             }
    //                         }
    //                         $firstDayOfMonth->addDay();
    //                     }
    //                     foreach($branchEmployees->user_attendance as $employeeDailyAttendence){
    //                          $startTime = $employeeDailyAttendence->check_in;
    //                          if ($late_time !== null) {
    //                             if (strtotime($startTime) > strtotime($late_time)) {
    //                                 $late++;
    //                                 break;
    //                             }
    //                         }
    //                     }
    //                     if(count($datesInMonth) != count($branchEmployees->user_attendance)){
    //                         $absent++;
    //                     }
    //                 }
    //             }
    //             $datesInMonth = [];
    //         }
    //         $company->present = $present;
    //         $company->late = $late;
    //         $company->absent = $absent;
    //         $present = 0;
    //         $late = 0;
    //         $absent = 0;
    //         // unset($company['employee_details_with_attendance']);
    //     }
    // }


    public function attendanceGraph(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);
        $logs = Log::orderBy('id','desc')->with('user')->paginate(7);
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $lateHours = 0;
        $lateEmpCount = 0;
        $month_number = date('m');
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        $number_of_days = now()->month($month_number)->daysInMonth;
        $currentDate = Carbon::now()->toDateString();

        // for the upper cards data
        if($user_role == 1){
            $employees = EmployeeDetail::with('branch')->where('status', '1')
                                ->where('is_deleted', '0')
                                ->orderBy('emp_id','asc')
                                ->get();
            $totalEmployees = $employees->count();
            if($request->type == 'month'){
            $totalPresentEmp = EmployeeDetail::with('branch')->where('status', '1')
                ->where('is_deleted', '0')
                ->orderBy('emp_id','asc')
                ->whereHas('user_attendance', function ($q) use ($currentMonth,$currentYear) {
                    $q->whereNotNull('check_in')
                        ->whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear);
                })
                ->get();
            }
            else{
                $totalPresentEmp = EmployeeDetail::with('branch')->where('status', '1')
                ->where('is_deleted', '0')
                ->orderBy('emp_id','asc')
                ->whereHas('user_attendance', function ($q) use ($currentMonth,$currentYear) {
                    $q->whereNotNull('check_in')
                        ->whereYear('created_at', $currentYear);
                })
                ->get();
            }

            // $totalAbsentEmp = EmployeeDetail::with('branch')->where('status', '1')
            //                         ->where('is_deleted', '0')
            //                         ->orderBy('emp_id','asc')
            //                         ->whereDoesntHave('user_attendance', function ($q) use ($currentMonth,$currentYear) {
            //                             $q->whereMonth('created_at', $currentMonth)
            //                             ->whereYear('created_at', $currentYear);
            //                         })
            //                         ->get();
            // $logs = Log::orderBy('id','desc')->with('user')->paginate(7);
        }else{
            $employees = EmployeeDetail::with('branch')->whereIn('company_id',$user_company_id)
                ->whereIn('branch_id',$user_branch_id)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->get();
            $totalEmployees = $employees->count();
            if($request->type == 'month'){
                $totalPresentEmp = EmployeeDetail::with('branch')->whereIn('company_id',$user_company_id)
                        ->whereIn('branch_id',$user_branch_id)
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->whereHas('user_attendance', function ($q) use ($currentDate) {
                            $q->whereNotNull('check_in')
                                ->whereMonth('created_at', $currentMonth)
                                ->whereYear('created_at', $currentYear);
                        })
                        ->get();
                }
                else{
                $totalPresentEmp = EmployeeDetail::with('branch')->whereIn('company_id',$user_company_id)
                                        ->whereIn('branch_id',$user_branch_id)
                                        ->where('status', '1')
                                        ->where('is_deleted', '0')
                                        ->whereHas('user_attendance', function ($q) use ($currentDate) {
                                            $q->whereNotNull('check_in')
                                                ->whereYear('created_at', $currentYear);
                                        })
                                        ->get();
                }
            // $totalAbsentEmp = EmployeeDetail::with('branch')->whereIn('company_id', $user_company_id)
            //         ->whereIn('branch_id', $user_branch_id)
            //         ->where('status', '1')
            //         ->where('is_deleted', '0')
            //         ->whereDoesntHave('user_attendance', function ($q) use ($currentDate) {
            //             $q->whereMonth('created_at', $currentMonth)
            //             ->whereYear('created_at', $currentYear);
            //         })
            //         ->get();

            // $logs = Log::orderBy('id','desc')->with('user')->where('user_id',$user->id)->paginate(7);
        }

        $lateEmployees = []; // Initialize an array to store late employees

        foreach ($totalPresentEmp as $emp) {
            $data = UserAttendence::where('emp_id', $emp->id)
                ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                ->get();

            $actual_hours = CompanySetting::where('branch_id', $emp->branch_id)
                ->where('is_deleted', '0')
                ->get();

            foreach ($actual_hours as $actual_hour) {
                $lateemp = $actual_hour->late_time;
            }

            $isLate = false; // Initialize a flag to check if the employee is late

            foreach ($data as $time) {
                $startemp = $time->check_in;
                if (isset($lateemp)) {
                    if (strtotime($startemp) > strtotime($lateemp)) {
                        $isLate = true; // Employee is late
                        break; // No need to check further, we found a late entry
                    }
                }
            }

            if ($isLate) {
                $lateEmpCount++;
                // Add the late employee to the list
                $lateEmployees[] = $emp;
            }
        }
        //to make array for bar graph
        $totalPresentEmpCount = $totalPresentEmp->count();
        // $totalAbsentEmp = $totalEmployees - $totalPresentEmpCount;
        // $totalAbsentEmpCount = $totalAbsentEmp->count();
        $attendanceData = [];
        $allData = [];
        $counter = 0;
        $mergedAttendanceData=[];

        foreach ($employees as $key => $employee) {
            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('is_deleted', '0')
                ->first();

            $total_hours = 0;
            $total_minutes = 0;
            $total_seconds = 0;
            $workingHours = 0;
            $absentCount = 0;
            $lateCount = 0;
            $present_count = 0;
            $holiday_count = 0;
            $weekend_count = 0;
            $halfCount = 0;
            $totalWorkingDays = 0;
            $holidayArray = [];
            $employeeData = [
                'present' => [],
                'late' => [],
                'absent' => [],
                'weekend' => [],
                'holiday' => [],
            ];
            //get holidays into DB
            foreach ($employee['holidays'] as $holiday) {
                if ($holiday['is_active'] == '1'){
                $startDate = Carbon::parse($holiday['start_date']);
                $endDate = Carbon::parse($holiday['end_date']);
                    while ($startDate->lte($endDate)) {
                        if ($startDate->format('Y-m') === $currentMonth) {
                            $employeeData['holiday'][$startDate->toDateString()] = 'Holiday';
                            $holiday_count++;
                        }
                        $startDate->addDay();
                    }
                }
            }
            //get days of month
            $currentMonth = Carbon::create($year, $month)->endOfMonth();
            //get user attendance data
            $data = UserAttendence::where('emp_id', $employee->id)
                ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                ->get();
            $absentDates = [];
            $attendance = [];
            $late_attendance = [];
            $presentDates = [];
            $absentCount = 0;
            $weekendsCount = 0;
            $weekends = [];

            for ($day = 1; $day <= $currentMonth->day; $day++) {
                $currentDate = Carbon::create($currentMonth->year, $currentMonth->month, $day);

                $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
                ->where('created_at', '<=', $currentDate->endOfDay())
                ->first();
                if (!$attendanceRecord) {
                    if ($currentDate->isWeekend()) {
                        $employeeData['weekend'][$currentDate->toDateString()] = 'weekend';
                        $weekendsCount++;
                        continue;
                    }
                    if ($currentDate <= now()->startOfDay()) {
                        $employeeData['absent'][$currentDate->toDateString()] = 'absent';
                        $absentCount++;
                    }
                } else {
                    $startTime = $attendanceRecord->check_in;
                    $endTime = Carbon::parse($attendanceRecord->check_out);
                    $hours = ($company_detail ? $company_detail->half_day : 0);
                    $late_time = ($company_detail ? $company_detail->late_time : null); // Set $late_time to null if not available
                    if ($startTime !== null) {
                        $attendance = 'Present';
                        $present_count++;
                        if ($late_time !== null) {
                            // Check if $startTime is later than $late_time
                            if (strtotime($startTime) > strtotime($late_time)) {
                                $late_attendance = 'Late';
                                $lateCount++;
                            }
                        }
                    }
                    $employeeData['present'][$currentDate->toDateString()] = $attendance;
                    $employeeData['late'][$currentDate->toDateString()] = $late_attendance;
                }
            }
            $allData[$employee->id] = $employeeData;
        }
        $allDates = [];
        // Step 1: Get all unique dates from $allData
        foreach ($allData as $employeeData) {
            foreach ($employeeData as $attendanceType => $dates) {
                foreach ($dates as $date => $status) {
                    if (!in_array($date, $allDates)) {
                        $allDates[] = $date;
                    }
                }
            }
        }
        // Step 2: Filter out dates greater than the current date
        $currentDate = date('Y-m-d');
        $filteredDates = array_filter($allDates, function ($date) use ($currentDate) {
            return $date <= $currentDate;
        });
        // Step 3: Create a new array with filtered dates
        $mergedAttendance = [];
        foreach ($filteredDates as $date) {
            foreach ($allData as $employeeId => $employeeData) {
                foreach ($employeeData as $attendanceType => $dates) {
                    if ($attendanceType === "weekend") {
                        // continue; // Skip weekends
                    }
                    if (isset($dates[$date]) && $dates[$date]) {
                        if (!isset($mergedAttendance[$date])) {
                            $mergedAttendance[$date] = [];
                        }
                        if (!isset($mergedAttendance[$date][$attendanceType])) {
                            $mergedAttendance[$date][$attendanceType] = [];
                        }
                        $mergedAttendance[$date][$attendanceType][$employeeId] = $dates[$date];
                    }
                }
            }
        }
        // Step 4: Initialize count variables
        $weekendCount = 0;
        $presentCount = 0;
        $lateCount = 0;
        $absentCount = 0;

        // Step 5: Update count variables and add keys to the $mergedAttendance array
        foreach ($mergedAttendance as $date => $attendanceData) {
            $weekendCount = isset($attendanceData['weekend']) ? 0 : 0;
            $presentCount = isset($attendanceData['present']) ? count($attendanceData['present']) : 0;
            $lateCount = isset($attendanceData['late']) ? count($attendanceData['late']) : 0;
            $absentCount = isset($attendanceData['absent']) ? count($attendanceData['absent']) : 0;

            $mergedAttendance[$date]['weekendCount'] = $weekendCount;
            $mergedAttendance[$date]['presentCount'] = $presentCount;
            $mergedAttendance[$date]['lateCount'] = $lateCount;
            $mergedAttendance[$date]['absentCount'] = $absentCount;

            // Remove the individual arrays if you don't want to display them in the final result
            unset($mergedAttendance[$date]['weekend']);
            unset($mergedAttendance[$date]['present']);
            unset($mergedAttendance[$date]['late']);
            unset($mergedAttendance[$date]['absent']);
        }
        ksort($mergedAttendance);
        //end of main graph

        //start bottom graphs
        // if($user_role == '1'){
        //     $branches = Location::with(['employee_details_with_attendance' => function($q){
        //         $q->where('status', '1');
        //     }])
        //     ->orderBy('branch_name', 'asc')
        //     ->get();

        //     //Location Wise
        //     $branchEmps = Location::join('employee_details', 'branches.id', '=', 'employee_details.branch_id')
        //         ->groupBy('employee_details.branch_id', 'branches.branch_name','employee_details.id')
        //         ->select('employee_details.branch_id', 'branches.branch_name','employee_details.id as employee_id')
        //         ->orderBy('branches.branch_name','asc')
        //         ->where('employee_details.status','1')
        //         ->get();
        // }else{
        //     $branches = Location::with(['employee_details_with_attendance' => function($q){
        //             $q->where('status', '1');
        //         }])
        //         ->whereIn('company_id',$user_company_id)
        //         ->whereIn('id',$user_branch_id)
        //         ->orderBy('branch_name','asc')
        //         ->get();

        //     //Location Wise
        //     $branchEmps = Location::join('employee_details', 'branches.id', '=', 'employee_details.branch_id')
        //         ->groupBy('employee_details.branch_id', 'branches.branch_name','employee_details.id')
        //         ->select('employee_details.branch_id', 'branches.branch_name','employee_details.id as employee_id')
        //         ->whereIn('branches.company_id',$user_company_id)
        //         ->whereIn('branches.id',$user_branch_id)
        //         ->where('employee_details.status','1')
        //         ->orderBy('branches.branch_name','asc')
        //         ->get();
        // }

        // $branchAttendanceData = [];
        // foreach ($branchEmps as $branchEmp) {
        //     $companyId = $branchEmp->company_id;
        //     $branchId = $branchEmp->branch_id;
        //     $employeeId = $branchEmp->employee_id;

        //     $company_settings = CompanySetting::where('company_id',$companyId)->where('branch_id', $branchId)
        //         ->where('is_deleted', '0')
        //         ->first();

        //     $data = UserAttendence::where('emp_id', $employeeId)->get();

        //     $absentCount = 0;
        //     $lateCount = 0;
        //     $present_count = 0;
        //     $holiday_count = 0;
        //     for ($day = 1; $day <= $currentMonth->day; $day++) {
        //         $currentDate = Carbon::create($year, $month, $day);
        //         if ($currentDate->isWeekend()) {
        //             $employeeData['weekend'][$currentDate->toDateString()] = 'weekend';
        //             $weekendsCount++;
        //             // continue;
        //         }
        //         $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
        //                 ->where('created_at', '<=', $currentDate->endOfDay())
        //                 ->first();

        //         if (!$attendanceRecord) {
        //             if ($currentDate <= now()->startOfDay()) {
        //                 $employeeData['absent'][$currentDate->toDateString()] = 'absent';
        //                 $absentCount++;
        //             }
        //         } else {
        //             $startTime = $attendanceRecord->check_in;
        //             $endTime = Carbon::parse($attendanceRecord->check_out);
        //             $hours = ($company_settings ? $company_settings->half_day : 0);
        //             $late_time = ($company_settings ? $company_settings->late_time : null); // Set $late_time to null if not available
        //             // return $late_time;
        //             if ($startTime !== null) {
        //                 $attendance = 'Present';
        //                 $present_count++;
        //                 if ($late_time !== null) {
        //                     // Check if $startTime is later than $late_time
        //                     if (strtotime($startTime) > strtotime($late_time)) {
        //                         $late_attendance = 'Late';
        //                         $lateCount++;
        //                     }
        //                 }
        //             }
        //             $employeeData['present'][$currentDate->toDateString()] = $attendance;
        //             $employeeData['late'][$currentDate->toDateString()] = $late_attendance;
        //         }
        //     }
        //     if (!isset($branchAttendanceData[$branchEmp->branch_name])) {
        //         $branchAttendanceData[$branchEmp->branch_name] = [
        //             'present_count' => 0,
        //             'absent_count' => 0,
        //             'late_count' => 0,
        //         ];
        //     }
        //     // Update branch data with the counts for each employee
        //     $branchAttendanceData[$branchEmp->branch_name]['present_count'] += $present_count;
        //     $branchAttendanceData[$branchEmp->branch_name]['absent_count'] += $absentCount;
        //     $branchAttendanceData[$branchEmp->branch_name]['late_count'] += $lateCount;
        // }
        return response()->json(['success'=>1, 'data'=>$mergedAttendance,'dates'=>$number_of_days]);
    }
     public function index(Request $request)
     {
        // user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',',$user->company_id);
        $user_branch_id = explode(',',$user->branch_id);

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $lateHours = 0;
        $lateEmpCount = 0;
        $month_number = date('m');
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        $number_of_days = now()->month($month_number)->daysInMonth;
        $currentDate = Carbon::now()->toDateString();

        if($user_role == 1){
            $employees = EmployeeDetail::with('branch')->where('status', '1')
                                ->where('is_deleted', '0')
                                ->orderBy('emp_id','asc')
                                ->get();
            $totalEmployees = $employees->count();

            $totalPresentEmp = UserAttendence::leftjoin('employee_details','user_attendence.emp_id','=','employee_details.id')
                ->leftjoin('locations','locations.id','=','employee_details.branch_id')
                ->select('locations.branch_name','employee_details.emp_id','employee_details.branch_id','employee_details.emp_name','user_attendence.check_in','user_attendence.check_out','user_attendence.updated_at')
                ->whereDate('user_attendence.created_at', $currentDate)
                ->get();

            $totalAbsentEmp = EmployeeDetail::with('branch')->where('status', '1')
                                    ->where('is_deleted', '0')
                                    ->orderBy('emp_id','asc')
                                    ->whereDoesntHave('user_attendance', function ($q) use ($currentDate) {
                                        $q->whereDate('created_at', $currentDate);
                                    })
                                    ->get();
            $logs = Log::orderBy('id','desc')->with('user')->paginate(7);
        }else{
            $employees = EmployeeDetail::with('branch')->whereIn('company_id',$user_company_id)
                ->whereIn('branch_id',$user_branch_id)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->get();
            $totalEmployees = $employees->count();
            // $totalPresentEmp = EmployeeDetail::with('branch')->whereIn('company_id',$user_company_id)
            //         ->whereIn('branch_id',$user_branch_id)
            //         ->where('status', '1')
            //         ->where('is_deleted', '0')
            //         ->whereHas('user_attendance', function ($q) use ($currentDate) {
            //             $q->whereNotNull('check_in')
            //                 ->whereDate('created_at', $currentDate);
            //         })
            //         ->get();
            $totalPresentEmp = UserAttendence::leftjoin('employee_details','user_attendence.emp_id','=','employee_details.id')
                ->leftjoin('locations','locations.id','=','employee_details.branch_id')
                ->select('locations.branch_name','employee_details.emp_id','employee_details.branch_id','employee_details.emp_name','user_attendence.check_in','user_attendence.check_out','user_attendence.updated_at')
                ->whereDate('user_attendence.created_at', $currentDate)
                ->whereIn('employee_details.branch_id',$user_branch_id)
                ->get();

            $totalAbsentEmp = EmployeeDetail::with('branch')->whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->whereDoesntHave('user_attendance', function ($q) use ($currentDate) {
                        $q->whereDate('created_at', $currentDate);
                    })
                    ->get();

            $logs = Log::orderBy('id','desc')->with('user')->where('user_id',$user->id)->paginate(7);
        }

        $lateEmployees = []; // Initialize an array to store late employees

        foreach ($totalPresentEmp as $emp) {
            $company_setting = CompanySetting::where('branch_id', $emp->branch_id)
                ->where('is_deleted', '0')
                ->first();

            $lateTime= $company_setting ? $company_setting->late_time : 0;

            if (strtotime($emp->check_in) >= strtotime($lateTime)) {

                $lateEmpCount++;
                // Add the late employee to the list
                $lateEmployees[] = $emp;
            }

        }

        //to make array for bar graph
        $totalPresentEmpCount = $totalPresentEmp->count();
        // $totalAbsentEmp = $totalEmployees - $totalPresentEmpCount;
        $totalAbsentEmpCount = $totalAbsentEmp->count();
        $attendanceData = [];
        $allData = [];
        $counter = 0;
        // $mergedAttendanceData=[];

        // foreach ($employees as $key => $employee) {
        //     $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
        //         ->where('is_deleted', '0')
        //         ->first();

        //     $total_hours = 0;
        //     $total_minutes = 0;
        //     $total_seconds = 0;
        //     $workingHours = 0;
        //     $absentCount = 0;
        //     $lateCount = 0;
        //     $present_count = 0;
        //     $holiday_count = 0;
        //     $weekend_count = 0;
        //     $halfCount = 0;
        //     $totalWorkingDays = 0;
        //     $holidayArray = [];
        //     $employeeData = [
        //         'present' => [],
        //         'late' => [],
        //         'absent' => [],
        //         'weekend' => [],
        //         'holiday' => [],
        //     ];
        //     //get holidays into DB
        //     foreach ($employee['holidays'] as $holiday) {
        //         if ($holiday['is_active'] == '1'){
        //         $startDate = Carbon::parse($holiday['start_date']);
        //         $endDate = Carbon::parse($holiday['end_date']);
        //             while ($startDate->lte($endDate)) {
        //                 if ($startDate->format('Y-m') === $currentMonth) {
        //                     $employeeData['holiday'][$startDate->toDateString()] = 'Holiday';
        //                     $holiday_count++;
        //                 }
        //                 $startDate->addDay();
        //             }
        //         }
        //     }
        //     //get days of month
        //     $currentMonth = Carbon::create($year, $month)->endOfMonth();
        //     //get user attendance data
        //     $data = UserAttendence::where('emp_id', $employee->id)
        //         ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
        //         ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
        //         ->get();
        //     $absentDates = [];
        //     $attendance = [];
        //     $late_attendance = [];
        //     $presentDates = [];
        //     $absentCount = 0;
        //     $weekendsCount = 0;
        //     $weekends = [];

        //     for ($day = 1; $day <= $currentMonth->day; $day++) {
        //         $currentDate = Carbon::create($currentMonth->year, $currentMonth->month, $day);

        //         $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
        //         ->where('created_at', '<=', $currentDate->endOfDay())
        //         ->first();
        //         if (!$attendanceRecord) {
        //             if ($currentDate->isWeekend()) {
        //                 $employeeData['weekend'][$currentDate->toDateString()] = 'weekend';
        //                 $weekendsCount++;
        //                 continue;
        //             }
        //             if ($currentDate <= now()->startOfDay()) {
        //                 $employeeData['absent'][$currentDate->toDateString()] = 'absent';
        //                 $absentCount++;
        //             }
        //         } else {
        //             $startTime = $attendanceRecord->check_in;
        //             $endTime = Carbon::parse($attendanceRecord->check_out);
        //             $hours = ($company_detail ? $company_detail->half_day : 0);
        //             $late_time = ($company_detail ? $company_detail->late_time : null); // Set $late_time to null if not available
        //             if ($startTime !== null) {
        //                 $attendance = 'Present';
        //                 $present_count++;
        //                 if ($late_time !== null) {
        //                     // Check if $startTime is later than $late_time
        //                     if (strtotime($startTime) > strtotime($late_time)) {
        //                         $late_attendance = 'Late';
        //                         $lateCount++;
        //                     }
        //                 }
        //             }
        //             $employeeData['present'][$currentDate->toDateString()] = $attendance;
        //             $employeeData['late'][$currentDate->toDateString()] = $late_attendance;
        //         }
        //     }
        //     $allData[$employee->id] = $employeeData;
        // }
        // $allDates = [];
        // // Step 1: Get all unique dates from $allData
        // foreach ($allData as $employeeData) {
        //     foreach ($employeeData as $attendanceType => $dates) {
        //         foreach ($dates as $date => $status) {
        //             if (!in_array($date, $allDates)) {
        //                 $allDates[] = $date;
        //             }
        //         }
        //     }
        // }
        // Step 2: Filter out dates greater than the current date
        // $currentDate = date('Y-m-d');
        // $filteredDates = array_filter($allDates, function ($date) use ($currentDate) {
        //     return $date <= $currentDate;
        // });
        // // Step 3: Create a new array with filtered dates
        // $mergedAttendance = [];
        // foreach ($filteredDates as $date) {
        //     foreach ($allData as $employeeId => $employeeData) {
        //         foreach ($employeeData as $attendanceType => $dates) {
        //             if ($attendanceType === "weekend") {
        //                 // continue; // Skip weekends
        //             }
        //             if (isset($dates[$date]) && $dates[$date]) {
        //                 if (!isset($mergedAttendance[$date])) {
        //                     $mergedAttendance[$date] = [];
        //                 }
        //                 if (!isset($mergedAttendance[$date][$attendanceType])) {
        //                     $mergedAttendance[$date][$attendanceType] = [];
        //                 }
        //                 $mergedAttendance[$date][$attendanceType][$employeeId] = $dates[$date];
        //             }
        //         }
        //     }
        // }
        // // Step 4: Initialize count variables
        // $weekendCount = 0;
        // $presentCount = 0;
        // $lateCount = 0;
        // $absentCount = 0;

        // // Step 5: Update count variables and add keys to the $mergedAttendance array
        // foreach ($mergedAttendance as $date => $attendanceData) {
        //     $weekendCount = isset($attendanceData['weekend']) ? 0 : 0;
        //     $presentCount = isset($attendanceData['present']) ? count($attendanceData['present']) : 0;
        //     $lateCount = isset($attendanceData['late']) ? count($attendanceData['late']) : 0;
        //     $absentCount = isset($attendanceData['absent']) ? count($attendanceData['absent']) : 0;

        //     $mergedAttendance[$date]['weekendCount'] = $weekendCount;
        //     $mergedAttendance[$date]['presentCount'] = $presentCount;
        //     $mergedAttendance[$date]['lateCount'] = $lateCount;
        //     $mergedAttendance[$date]['absentCount'] = $absentCount;

        //     // Remove the individual arrays if you don't want to display them in the final result
        //     unset($mergedAttendance[$date]['weekend']);
        //     unset($mergedAttendance[$date]['present']);
        //     unset($mergedAttendance[$date]['late']);
        //     unset($mergedAttendance[$date]['absent']);
        // }
        // ksort($mergedAttendance);
        // //end of main graph

        //start bottom graphs
        if($user_role == '1'){
            $branches = Location::with(['employee_details_with_attendance' => function($q){
                $q->where('status', '1');
            }])
            ->orderBy('branch_name', 'asc')
            ->get();

            //Location Wise
            $branchEmps = Location::join('employee_details', 'locations.id', '=', 'employee_details.branch_id')
                ->groupBy('employee_details.branch_id', 'locations.branch_name','employee_details.id')
                ->select('employee_details.branch_id', 'locations.branch_name','employee_details.id as employee_id')
                ->orderBy('locations.branch_name','asc')
                ->where('employee_details.status','1')
                ->get();
        }else{
            $branches = Location::with(['employee_details_with_attendance' => function($q){
                    $q->where('status', '1');
                }])
                ->whereIn('company_id',$user_company_id)
                ->whereIn('id',$user_branch_id)
                ->orderBy('branch_name','asc')
                ->get();

            //Location Wise
            $branchEmps = Location::join('employee_details', 'locations.id', '=', 'employee_details.branch_id')
                ->groupBy('employee_details.branch_id', 'locations.branch_name','employee_details.id')
                ->select('employee_details.branch_id', 'locations.branch_name','employee_details.id as employee_id')
                ->whereIn('locations.company_id',$user_company_id)
                ->whereIn('locations.id',$user_branch_id)
                ->where('employee_details.status','1')
                ->orderBy('locations.branch_name','asc')
                ->get();
        }

        $branchAttendanceData = [];
        foreach ($branchEmps as $branchEmp) {
            $companyId = $branchEmp->company_id;
            $branchId = $branchEmp->branch_id;
            $employeeId = $branchEmp->employee_id;

            $company_settings = CompanySetting::where('company_id',$companyId)->where('branch_id', $branchId)
                ->where('is_deleted', '0')
                ->first();

            $data = UserAttendence::where('emp_id', $employeeId)->get();

            $absentCount = 0;
            $lateCount = 0;
            $present_count = 0;
            $holiday_count = 0;
            // for ($day = 1; $day <= $currentMonth->day; $day++) {
            //     $currentDate = Carbon::create($year, $month, $day);
            //     if ($currentDate->isWeekend()) {
            //         $employeeData['weekend'][$currentDate->toDateString()] = 'weekend';
            //         $weekendsCount++;
            //         // continue;
            //     }
            //     $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
            //             ->where('created_at', '<=', $currentDate->endOfDay())
            //             ->first();

            //     if (!$attendanceRecord) {
            //         if ($currentDate <= now()->startOfDay()) {
            //             $employeeData['absent'][$currentDate->toDateString()] = 'absent';
            //             $absentCount++;
            //         }
            //     } else {
            //         $startTime = $attendanceRecord->check_in;
            //         $endTime = Carbon::parse($attendanceRecord->check_out);
            //         $hours = ($company_settings ? $company_settings->half_day : 0);
            //         $late_time = ($company_settings ? $company_settings->late_time : null); // Set $late_time to null if not available
            //         // return $late_time;
            //         if ($startTime !== null) {
            //             $attendance = 'Present';
            //             $present_count++;
            //             if ($late_time !== null) {
            //                 // Check if $startTime is later than $late_time
            //                 if (strtotime($startTime) > strtotime($late_time)) {
            //                     $late_attendance = 'Late';
            //                     $lateCount++;
            //                 }
            //             }
            //         }
            //         $employeeData['present'][$currentDate->toDateString()] = $attendance;
            //         $employeeData['late'][$currentDate->toDateString()] = $late_attendance;
            //     }
            // }
            if (!isset($branchAttendanceData[$branchEmp->branch_name])) {
                $branchAttendanceData[$branchEmp->branch_name] = [
                    'present_count' => 0,
                    'absent_count' => 0,
                    'late_count' => 0,
                ];
            }
            // Update branch data with the counts for each employee
            $branchAttendanceData[$branchEmp->branch_name]['present_count'] += $present_count;
            $branchAttendanceData[$branchEmp->branch_name]['absent_count'] += $absentCount;
            $branchAttendanceData[$branchEmp->branch_name]['late_count'] += $lateCount;
        }

        return view('home', compact('employees','currentMonth','logs','currentYear','branchAttendanceData','number_of_days', 'lateEmployees','lateEmpCount', 'totalEmployees', 'totalAbsentEmp','totalAbsentEmpCount', 'totalPresentEmpCount', 'totalPresentEmp','branches'));
     }
     public function EmpPresentData(Request $request){
        //adding it for testingggg
        date_default_timezone_set("Asia/Karachi");

        // $type = 'month';

        //ksjdhjkhdf

        $present = 0;
        $late = 0;
        $absent = 0;
        $currentMonth = date('m');
        $currentYear = date('Y');
        $datesInMonth = array();
        if($request->type == 'month'){
            $branchesWithAttendance = Location::with(['employee_details_with_attendance.user_attendance' => function ($query) use ($currentMonth,$currentYear) {
                    $query->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
                }])->get();
        }
        else{
            $branchesWithAttendance = Location::with(['employee_details_with_attendance.user_attendance' => function ($query) use ($currentYear) {
                    $query->whereYear('created_at', $currentYear);
                }])->get();
        }
        foreach($branchesWithAttendance as $company){
            $company_settings = CompanySetting::where('company_id',$company->company_id)->where('branch_id', $company->id)
                ->where('is_deleted', '0')
                ->first();
            $hours = ($company_settings ? $company_settings->half_day : 0);
            $late_time = ($company_settings ? $company_settings->late_time : null);
            if($company_settings != null && $company_settings != ""){
                $countDays = count(explode(',',$company_settings->days));
            }
            foreach($company->employee_details_with_attendance as $branchEmployees){
                if($branchEmployees->branch_id == $company->id){
                    if(!$branchEmployees->user_attendance->isEmpty() && $branchEmployees->status == '1'){
                        //present count
                        $present ++;
                        //absent count working
                        $firstDayOfMonth = Carbon::create($currentYear, $currentMonth)->startOfMonth();
                        $endDayOfMonth = Carbon::create($currentYear, $currentMonth)->endOfMonth();
                        while ($firstDayOfMonth->lte($endDayOfMonth)) {
                            if($countDays == 5){
                                if(!($firstDayOfMonth->isweekend())){
                                    $datesInMonth[] = $firstDayOfMonth->toDateString();
                                }
                            }
                            elseif($countDays == 6){
                                if(!($firstDayOfMonth->isSunday())){
                                    $datesInMonth[] = $firstDayOfMonth->toDateString();
                                }
                            }
                            $firstDayOfMonth->addDay();
                        }
                        foreach($branchEmployees->user_attendance as $employeeDailyAttendence){
                             $startTime = $employeeDailyAttendence->check_in;
                             if ($late_time !== null) {
                                if (strtotime($startTime) > strtotime($late_time)) {
                                    $late++;
                                    break;
                                }
                            }
                        }
                        if(count($datesInMonth) != count($branchEmployees->user_attendance)){
                            $absent++;
                        }
                    }
                }
                $datesInMonth = [];
            }
            $company->present = $present;
            $company->late = $late;
            $company->absent = $absent;
            $present = 0;
            $late = 0;
            $absent = 0;
            // unset($company['employee_details_with_attendance']);
        }
        // return [$firstDayOfMonth,$endDayOfMonth,$datesInMonth];
        return response()->json(['success'=>'1','data'=>$branchesWithAttendance]);;
     }
}
