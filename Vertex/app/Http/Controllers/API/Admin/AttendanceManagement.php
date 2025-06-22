<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\Leave_setting;
use App\Models\Leave_Type;
use App\Models\UserDailyRecord;
use App\Traits\ProfileImage;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Leave;
use App\Models\EmployeeDetail;
use Illuminate\Support\Carbon;
use App\Models\CompanySetting;
use App\Models\Holiday;
use App\Models\UserAttendence;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Log;
use DB;

class AttendanceManagement extends BaseController
{
    use ProfileImage;
    // public function dailyAttendance(Request $request)
    // {
    //     //user information
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',', $user->company_id);
    //     $user_branch_id = explode(',', $user->branch_id);
    //     $user_branch_id = isset($request->branch_id) ? $request->branch_id : $user_branch_id;

    //     $searched_date = isset($request->searchDate) ? date('Y-m-d', strtotime($request->searchDate)) : Carbon::now()->format('Y-m-d');
    //     $current_date = isset($request->searchDate) ? date('d-m-Y', strtotime($request->searchDate)) : Carbon::now()->format('d-m-Y');
    //     $searchByName = $request->search_by_name;
    //     $selected = isset($request->branch_id) ? $request->branch_id : 'all';
    //     if ($selected == 'all') {
    //         if ($user_role == 1) {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //                 ->with('resignations', 'approval', 'terminations', 'leaves', 'holidays')
    //                 ->where('employee_details.is_deleted', '0')
    //                 ->where('employee_details.status', '1')
    //                 ->where(function ($query) use ($searchByName) {
    //                     $query->where('employee_details.emp_name', 'LIKE', '%' . $searchByName . '%');
    //                 })
    //                 ->orderBy('employee_details.emp_id', 'asc')
    //                 ->paginate(20);
    //         } else {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //                 ->with('resignations', 'approval', 'leaves', 'terminations', 'holidays')
    //                 ->whereIn('employee_details.company_id', $user_company_id)
    //                 ->whereIn('employee_details.branch_id', $user_branch_id)
    //                 ->where(function ($query) use ($searchByName) {
    //                     $query->where('employee_details.emp_name', 'LIKE', '%' . $searchByName . '%');
    //                 })
    //                 ->where('employee_details.status', '1')
    //                 ->where('employee_details.is_deleted', '0')
    //                 ->orderBy('employee_details.emp_id', 'asc')
    //                 ->paginate(20);
    //         }
    //     } else {
    //         if ($user_role == 1) {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //                 ->with('resignations', 'approval', 'leaves', 'terminations', 'holidays')
    //                 ->where('employee_details.branch_id', $selected)
    //                 ->where(function ($query) use ($searchByName) {
    //                     $query->where('employee_details.emp_name', 'LIKE', '%' . $searchByName . '%');
    //                 })
    //                 ->where('employee_details.is_deleted', '0')
    //                 ->where('employee_details.status', '1')
    //                 ->orderBy('employee_details.emp_id', 'asc')
    //                 ->paginate(20);
    //         } else {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //                 ->with('resignations', 'approval', 'leaves', 'terminations', 'holidays')
    //                 ->whereIn('employee_details.company_id', $user_company_id)
    //                 ->where(function ($query) use ($searchByName) {
    //                     $query->where('employee_details.emp_name', 'LIKE', '%' . $searchByName . '%');
    //                 })
    //                 ->where('employee_details.branch_id', $selected)
    //                 ->where('employee_details.is_deleted', '0')
    //                 ->where('employee_details.status', '1')
    //                 ->orderBy('employee_details.emp_id', 'asc')
    //                 ->paginate(20);
    //         }
    //     }

    //     $attendanceData = [];

    //     foreach ($employees as $key => $employee) {
    //         $company_setting = CompanySetting::where('branch_id', $employee->branch_id)
    //             ->where('is_deleted', '0')
    //             ->first();

    //         $workingHours = null;
    //         $absentCount = 0;
    //         $LateCount = 0;
    //         $halfCount = 0;

    //         $holidaysArray = [];
    //         $leavesArray = [];
    //         $check_in_address = null;
    //         $check_out_address = null;
    //         $get_check_in_ip_address = null;
    //         $get_check_out_ip_address = null;
    //         $get_check_in_time = null;
    //         $get_check_out_time = null;
    //         //get Holiday
    //         $eligibleHolidays = Holiday::where('is_deleted', '0')
    //             ->where('is_active', '1')
    //             ->get();
    //         foreach ($eligibleHolidays as $holiday) {
    //             $holidayCompanyIds = explode(',', $holiday->company_id);
    //             $holidayBranchIds = explode(',', $holiday->branch_id);

    //             if (in_array($employee->company_id, $holidayCompanyIds) && in_array($employee->branch_id, $holidayBranchIds)) {
    //                 $startDate = Carbon::parse($holiday->start_date);
    //                 $endDate = Carbon::parse($holiday->end_date);

    //                 while ($startDate->lte($endDate)) {
    //                     if (strtotime($holiday['start_date']) <= strtotime($searched_date) || strtotime($holiday['end_date']) <= strtotime($searched_date)) {
    //                         $holidaysArray[$startDate->toDateString()] = 'Holiday';
    //                     }
    //                     $startDate->addDay();
    //                 }
    //             }
    //         }
    //         //get leaves
    //         foreach ($employee->leaves as $leaves) {
    //             if (strtotime($leaves['from_date']) <= strtotime($searched_date) || strtotime($leaves['to_date']) <= strtotime($searched_date)) {
    //                 $startDate = Carbon::parse($leaves['from_date']);
    //                 $endDate = Carbon::parse($leaves['to_date']);

    //                 while ($startDate->lte($endDate)) {
    //                     if ($startDate->month == Date('m', strtotime($searched_date)) && $startDate->year == Date('Y', strtotime($searched_date))) {
    //                         $leavesArray[$startDate->toDateString()] = 'Leave';
    //                     }

    //                     // Move to the next date
    //                     $startDate->addDay();
    //                 }
    //             }
    //         }

    //         $data = UserAttendence::where('emp_id', $employee->id)
    //             ->whereDate('created_at', 'LIKE', $searched_date . '%')
    //             ->get();

    //         if ($data->isEmpty()) {
    //             if (array_key_exists($searched_date, $holidaysArray)) {
    //                 $attendance = 'Holiday';
    //             } elseif (array_key_exists($searched_date, $leavesArray)) {
    //                 $attendance = 'Leave';
    //             } else {
    //                 $attendance = 'Absent';
    //             }
    //         } else {
    //             foreach ($data as $attendanceRecord) {
    //                 if ($company_setting && $attendanceRecord->check_out != null && $attendanceRecord->check_out != '') {
    //                     $endTime = Carbon::parse($attendanceRecord->check_out);
    //                     $startTime = Carbon::parse($attendanceRecord->check_in);
    //                     $duration = $endTime->diffInMinutes($startTime);
    //                     $workingHours = floor($duration / 60) . 'h:' . ($duration - floor($duration / 60) * 60) . 'm';
    //                     $halfDayInMinuts = ($company_setting->half_day) * 60;
    //                     if ($duration <= $halfDayInMinuts) {
    //                         $attendance = 'Half Leave';
    //                         $halfCount++;
    //                     } elseif ($startTime < $company_setting->late_time) {
    //                         $attendance = 'Late';
    //                         $LateCount++;
    //                     } else {
    //                         $attendance = 'Full Day';
    //                     }
    //                     $get_check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address : '';
    //                     $get_check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address : '';
    //                     $check_out_address = $attendanceRecord->check_out_address;
    //                     $check_in_address = $attendanceRecord->check_in_address;
    //                     $get_check_in_time = $attendanceRecord->check_in;
    //                     $get_check_out_time = $attendanceRecord->check_out;
    //                 } else {
    //                     $attendance = 'Present';
    //                     $get_check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address : '';
    //                     $get_check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address : '';
    //                     $check_in_address = $attendanceRecord->check_in_address;
    //                     $check_out_address = $attendanceRecord->check_out_address;
    //                     $get_check_in_time = $attendanceRecord->check_in;
    //                     $get_check_out_time = $attendanceRecord->check_out;
    //                 }
    //             }
    //         }
    //         $attendanceData[] = [
    //             'employee_id' => $employee->id,
    //             'employee_name' => $employee->emp_name,
    //             'attendance' => $attendance,
    //             'working_hours' => isset($workingHours) ? $workingHours : null,
    //             'check_in_ip_address' => $get_check_in_ip_address,
    //             'check_out_ip_address' => $get_check_out_ip_address,
    //             'check_in_address' => $check_in_address,
    //             'check_out_address' => $check_out_address,
    //             'check_in_time' => $get_check_in_time,
    //             'check_out_time' => $get_check_out_time,
    //             'status' => $attendance
    //         ];
    //     }
    //     if ($attendanceData) {
    //         return $this->sendResponse($attendanceData, 'Employee daily attendance record!', 200);
    //     } else {
    //         return $this->sendResponse($attendanceData, 'Data not found!', 200);
    //     }
    //     // return view('attendence.EmpAttendence', compact('selected','user' ,'current_date', 'employees', 'branches', ''));
    // }

    // public function dailyAttendance(Request $request)
    // {
    //     // user information
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',', $user->company_id);
    //     $user_branch_id = explode(',', $user->branch_id);
    //     $user_branch_id = isset($request->branch_id) ? $request->branch_id : $user_branch_id;

    //     $searched_date = isset($request->searchDate) ? date('Y-m-d', strtotime($request->searchDate)) : Carbon::now()->format('Y-m-d');
    //     $current_date = isset($request->searchDate) ? date('d-m-Y', strtotime($request->searchDate)) : Carbon::now()->format('d-m-Y');
    //     $searchInput = $request->input('input'); // Single input for both search by name and emp_id

    //     $selected = isset($request->branch_id) ? $request->branch_id : 'all';
    //     $per_page = isset($request->per_page) ? (int)$request->per_page : 20; // Dynamic per_page parameter

    //     if ($selected == 'all') {
    //         if ($user_role == 1) {
    //           //  dd('oks');
    //             // $employees = EmployeeDetail::with([
    //             //     //'user_attendance' => function ($query) use ($searched_date) {
    //             //         'user_attendance' => function ($query) use ($searched_date) {
    //             //         //$query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //             //     }
    //             // ])
    //             // ->with('resignations', 'approval', 'terminations', 'leaves', 'holidays')
    //             // ->where('employee_details.is_deleted', '0')
    //             // ->where('employee_details.status', '1')
    //             // ->where(function ($query) use ($searchInput) {
    //             //     if ($searchInput) {
    //             //         if (is_numeric($searchInput)) {
    //             //             $query->where('employee_details.id', $searchInput);
    //             //         } else {
    //             //             $query->where('employee_details.emp_name', 'LIKE', '%' . $searchInput . '%');
    //             //         }
    //             //     }
    //             // })
    //             // ->orderBy('employee_details.id', 'desc')
    //             // ->paginate($per_page);

    //             $employees = EmployeeDetail::with(['user_attendance', 'resignations', 'approval', 'terminations', 'leaves', 'holidays'])
    //             ->where('employee_details.is_deleted', '0')
    //             ->where('employee_details.status', '1')
    //             ->where(function ($query) use ($searchInput) {
    //                 if ($searchInput) {
    //                     if (is_numeric($searchInput)) {
    //                         $query->where('employee_details.emp_id','LIKE', '%' . $searchInput . '%');
    //                     } else {
    //                         $query->where('employee_details.emp_name', 'LIKE', '%' . $searchInput . '%');
    //                     }
    //                 }
    //             })
    //             ->orderBy('employee_details.id', 'desc')
    //             ->paginate($per_page);

    //         } else {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //             ->with('resignations', 'approval', 'leaves', 'terminations', 'holidays')
    //             ->whereIn('employee_details.company_id', $user_company_id)
    //             ->whereIn('employee_details.branch_id', $user_branch_id)
    //             ->where(function ($query) use ($searchInput) {
    //                 if ($searchInput) {
    //                     if (is_numeric($searchInput)) {
    //                         $query->where('employee_details.emp_id','LIKE', '%' . $searchInput . '%');
    //                     } else {
    //                         $query->where('employee_details.emp_name', 'LIKE', '%' . $searchInput . '%');
    //                     }
    //                 }
    //             })
    //             ->where('employee_details.status', '1')
    //             ->where('employee_details.is_deleted', '0')
    //             ->orderBy('employee_details.id', 'desc')
    //             ->paginate($per_page);
    //         }
    //     } else {
    //         if ($user_role == 1) {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //             ->with('resignations', 'approval', 'leaves', 'terminations', 'holidays')
    //             ->where('employee_details.branch_id', $selected)
    //             ->where(function ($query) use ($searchInput) {
    //                 if ($searchInput) {
    //                     if (is_numeric($searchInput)) {
    //                         $query->where('employee_details.emp_id','LIKE', '%' . $searchInput . '%');
    //                     } else {
    //                         $query->where('employee_details.emp_name', 'LIKE', '%' . $searchInput . '%');
    //                     }
    //                 }
    //             })
    //             ->where('employee_details.is_deleted', '0')
    //             ->where('employee_details.status', '1')
    //             ->orderBy('employee_details.id', 'desc')
    //             ->paginate($per_page);

    //         } else {
    //             $employees = EmployeeDetail::with([
    //                 'user_attendance' => function ($query) use ($searched_date) {
    //                     $query->whereDate('created_at', 'LIKE', $searched_date . '%');
    //                 }
    //             ])
    //             ->with('resignations', 'approval', 'leaves', 'terminations', 'holidays')
    //             ->whereIn('employee_details.company_id', $user_company_id)
    //             ->where(function ($query) use ($searchInput) {
    //                 if ($searchInput) {
    //                     if (is_numeric($searchInput)) {
    //                         $query->where('employee_details.emp_id','LIKE', '%' . $searchInput . '%');
    //                     } else {
    //                         $query->where('employee_details.emp_name', 'LIKE', '%' . $searchInput . '%');
    //                     }
    //                 }
    //             })
    //             ->where('employee_details.branch_id', $selected)
    //             ->where('employee_details.is_deleted', '0')
    //             ->where('employee_details.status', '1')
    //             ->orderBy('employee_details.id', 'desc')
    //             ->paginate($per_page);
    //         }
    //     }

    //     $attendanceData = [];

    //     foreach ($employees as $key => $employee) {
    //         $company_setting = CompanySetting::where('branch_id', $employee->branch_id)
    //             ->where('is_deleted', '0')
    //             ->first();

    //         $workingHours = null;
    //         $absentCount = 0;
    //         $LateCount = 0;
    //         $halfCount = 0;

    //         $holidaysArray = [];
    //         $leavesArray = [];
    //         $check_in_address = null;
    //         $check_out_address = null;
    //         $get_check_in_ip_address = null;
    //         $get_check_out_ip_address = null;
    //         $get_check_in_time = null;
    //         $get_check_out_time = null;
    //         //get Holiday
    //         $eligibleHolidays = Holiday::where('is_deleted', '0')
    //             ->where('is_active', '1')
    //             ->get();
    //         foreach ($eligibleHolidays as $holiday) {
    //             $holidayCompanyIds = explode(',', $holiday->company_id);
    //             $holidayBranchIds = explode(',', $holiday->branch_id);

    //             if (in_array($employee->company_id, $holidayCompanyIds) && in_array($employee->branch_id, $holidayBranchIds)) {
    //                 $startDate = Carbon::parse($holiday->start_date);
    //                 $endDate = Carbon::parse($holiday->end_date);

    //                 while ($startDate->lte($endDate)) {
    //                     if (strtotime($holiday['start_date']) <= strtotime($searched_date) || strtotime($holiday['end_date']) <= strtotime($searched_date)) {
    //                         $holidaysArray[$startDate->toDateString()] = 'Holiday';
    //                     }
    //                     $startDate->addDay();
    //                 }
    //             }
    //         }
    //         //get leaves
    //         foreach ($employee->leaves as $leaves) {
    //             if (strtotime($leaves['from_date']) <= strtotime($searched_date) || strtotime($leaves['to_date']) <= strtotime($searched_date)) {
    //                 $startDate = Carbon::parse($leaves['from_date']);
    //                 $endDate = Carbon::parse($leaves['to_date']);

    //                 while ($startDate->lte($endDate)) {
    //                     if ($startDate->month == Date('m', strtotime($searched_date)) && $startDate->year == Date('Y', strtotime($searched_date))) {
    //                         $leavesArray[$startDate->toDateString()] = 'Leave';
    //                     }

    //                     // Move to the next date
    //                     $startDate->addDay();
    //                 }
    //             }
    //         }

    //         $data = UserAttendence::where('emp_id', $employee->id)
    //             ->whereDate('created_at', 'LIKE', $searched_date . '%')
    //             ->get();
    //       //  dd($data);

    //         if ($data->isEmpty()) {

    //             if (array_key_exists($searched_date, $holidaysArray)) {
    //                 $attendance = 'Holiday';

    //             } elseif (array_key_exists($searched_date, $leavesArray)) {
    //                 $attendance = 'Leave';
    //             } else {
    //                 $attendance = 'Absent';
    //             }
    //         } else {
    //             foreach ($data as $attendanceRecord) {
    //                 if ($company_setting && $attendanceRecord->check_out != null && $attendanceRecord->check_out != '') {
    //                     $endTime = Carbon::parse($attendanceRecord->check_out);
    //                     $startTime = Carbon::parse($attendanceRecord->check_in);
    //                     $duration = $endTime->diffInMinutes($startTime);
    //                     $workingHours = floor($duration / 60) . 'h:' . ($duration - floor($duration / 60) * 60) . 'm';
    //                     $halfDayInMinuts = ($company_setting->half_day) * 60;
    //                     if ($duration <= $halfDayInMinuts) {
    //                         $attendance = 'Half Leave';
    //                         $halfCount++;
    //                     } elseif ($startTime < $company_setting->late_time) {
    //                         $attendance = 'Late';
    //                         $LateCount++;
    //                     } else {
    //                         $attendance = 'Full Day';
    //                     }
    //                     $get_check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address : '';
    //                     $get_check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address : '';
    //                     $check_out_address = $attendanceRecord->check_out_address;
    //                     $check_in_address = $attendanceRecord->check_in_address;
    //                     $get_check_in_time = $attendanceRecord->check_in;
    //                     $get_check_out_time = $attendanceRecord->check_out;
    //                 } else {
    //                     $attendance = 'Present';
    //                     $get_check_in_ip_address = $attendanceRecord->check_in_ip_address != null ? $attendanceRecord->check_in_ip_address : '';
    //                     $get_check_out_ip_address = $attendanceRecord->check_out_ip_address != null ? $attendanceRecord->check_out_ip_address : '';
    //                     $check_in_address = $attendanceRecord->check_in_address;
    //                     $check_out_address = $attendanceRecord->check_out_address;
    //                     $get_check_in_time = $attendanceRecord->check_in;
    //                     $get_check_out_time = $attendanceRecord->check_out;
    //                 }
    //             }
    //         }
    //         $attendanceData[] = [
    //             'employee_id' => $employee->emp_id,
    //             'employee_name' => $employee->emp_name,
    //             'attendance' => $attendance,
    //             'working_hours' => isset($workingHours) ? $workingHours : null,
    //             'check_in_ip_address' => $get_check_in_ip_address,
    //             'check_out_ip_address' => $get_check_out_ip_address,
    //             'check_in_address' => $check_in_address,
    //             'check_out_address' => $check_out_address,
    //             'check_in_time' => $get_check_in_time,
    //             'check_out_time' => $get_check_out_time,
    //             'status' => $attendance
    //         ];
    //     }

    //     $paginationDetails = [
    //         'current_page' => $employees->currentPage(),
    //         'last_page' => $employees->lastPage(),
    //         'from' => $employees->firstItem(),
    //         'to' => $employees->lastItem(),
    //         'previous_url' => $employees->previousPageUrl(),
    //         'next_url' => $employees->nextPageUrl(),
    //         'per_page' => $employees->perPage(),
    //         'total' => $employees->total(),
    //     ];

    //     if ($attendanceData) {
    //         return $this->sendResponse(['data' => $attendanceData, 'pagination' => $paginationDetails], 'Employee daily attendance record!', 200);
    //     } else {
    //         return $this->sendResponse(['data' => $attendanceData, 'pagination' => $paginationDetails], 'Data not found!', 200);
    //     }
    // }

    public function dailyAttendance(Request $request)
    {
        // User information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $selected = isset($request->branch_id) ? $request->branch_id : 'all';
        $searched_date = isset($request->searchDate) ? date('Y-m-d', strtotime($request->searchDate)) : Carbon::now()->format('Y-m-d');
        $searchInput = $request->input('input');
        $per_page = isset($request->per_page) ? (int) $request->per_page : 10;

        // Fetch employees based on user role and selected branch
        $employeesQuery = EmployeeDetail::with(['empToDailyRecord', 'resignations', 'approval', 'terminations', 'leaves', 'holidays'])
            ->where('employee_details.is_deleted', '0');

        if ($selected === 'all') {
            if ($user_role != 1) {
                $employeesQuery->whereIn('employee_details.company_id', $user_company_id)
                    ->whereIn('employee_details.branch_id', $user_branch_id);
            }
        } else {
            $employeesQuery->where('employee_details.branch_id', $selected);
            if ($user_role != 1) {
                $employeesQuery->whereIn('employee_details.company_id', $user_company_id);
            }
        }

        // Apply search filter
        if ($searchInput) {
            if (is_numeric($searchInput)) {
                $employeesQuery->where('employee_details.emp_id', 'LIKE', '%' . $searchInput . '%');
            } else {
                $employeesQuery->where('employee_details.emp_name', 'LIKE', '%' . $searchInput . '%');
            }
        }

        $employees = $employeesQuery->orderBy('employee_details.id', 'desc')->get();

        $attendanceData = [];

        foreach ($employees as $employee) {
            // Skip resigned or terminated employees
            if ($employee->resignations && $employee->resignations->is_approved == '1' && $employee->resignations->resignation_date < $searched_date) {
                continue;
            }
            if ($employee->terminations && $employee->terminations->is_approved == '1' && $employee->terminations->termination_date < $searched_date) {
                continue;
            }

            // Company settings and attendance logic
            $company_setting = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();

            $employee->imagePath = $this->imgFunc($employee->emp_image, $employee->emp_gender);
            $imagePath = $employee->imagePath;

            // Fetch eligible holidays
            $holidaysArray = [];
            $eligibleHolidays = Holiday::where('is_deleted', '0')->where('is_active', '1')->get();

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

            // Get attendance record for the searched date
            $data = UserDailyRecord::where('emp_id', $employee->id)
                ->whereDate('dated', $searched_date)
                ->first();

            // Determine attendance status
            if (!$data) {
                $attendance = array_key_exists($searched_date, $holidaysArray) ? 'Holiday' : 'Absent';
            } else {
                $check_in = new DateTime($data->check_in ?? 'now');
                $late_time = new DateTime($company_setting->late_time);

                if ($data->check_in && $data->check_out) {
                    $attendance = $late_time >= $check_in ? 'Present' : 'Late';
                } elseif ($data->check_in) {
                    $attendance = $late_time >= $check_in ? 'Present' : 'Late';
                } elseif ($data->leave && $data->leave_type) {
                    $attendance = $data->leave;
                } else {
                    $attendance = 'Absent';
                }
            }

            // Prepare attendance data
            $hours_data = (float) ($data->working_hours ?? 0);
            $total_hours = floor($hours_data);
            $total_minutes = round(($hours_data - $total_hours) * 60);
            $hours_formate = sprintf("%02dh : %02dm", $total_hours, $total_minutes);

            $attendanceData[] = [
                'id' => $employee->id,
                'employee_id' => $employee->emp_id,
                'employee_name' => $employee->emp_name,
                'imagePath' => $imagePath,
                'attendance' => $attendance,
                'working_hours' => $hours_formate,
                'check_in_ip_address' => $data->check_in_ip ?? null,
                'check_out_ip_address' => $data->check_out_ip ?? null,
                'check_in_type' => $data->check_in_type ?? null,
                'check_out_type' => $data->check_out_type ?? null,
                'check_in_time' => $data->check_in ?? null,
                'check_out_time' => $data->check_out ?? null,
                'check_in_image' => $data->check_in_image ?? null,
                'check_out_image' => $data->check_out_image ?? null,
                'status' => $attendance,
                'leave_type' => $data->leave_type ?? null,
            ];
        }

        // Apply attendance filter if provided
        if ($request->attendaceFilter) {
            $attendanceData = array_filter($attendanceData, function ($data) use ($request) {
                return isset($data['status']) && $data['status'] == $request->attendaceFilter;
            });
        }

        // Paginate attendanceData
        $currentPage = $request->input('page', 1);
        $perPage = $request->input('per_page', $per_page);
        $currentPageItems = array_slice($attendanceData, ($currentPage - 1) * $perPage, $perPage);

        // Create a LengthAwarePaginator instance
        $attendanceDataPaginator = new LengthAwarePaginator(
            $currentPageItems,
            count($attendanceData),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Return response
        return $this->sendResponse($attendanceDataPaginator, 'Employee daily attendance record!', 200);
    }


    public function addManuallyAttendance(Request $request)
    {
        $user = auth()->user();

        if (isset($_FILES["attendance_list"])) {
            $request->validate([
                'attendance_list' => 'required|file|max:30550',
            ]);
            $filename = $_FILES["attendance_list"]["tmp_name"];
            // ;
            $attendance_list = [];
            if ($_FILES["attendance_list"]["size"] > 0) {
                $tempFile = $request->file('attendance_list');
                $destinationPath = public_path('csvFiles/');
                $csvfile = time() . '_' . $tempFile->getClientOriginalName();
                $tempFile->move($destinationPath, $csvfile);
                $filePath = $destinationPath . $csvfile;
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
                    return $this->sendResponse($attendance_data, 'Form not submited!', 200);
                    // return redirect()->back()->withErrors($errors);
                } else {
                    return $this->sendResponse($attendance_data, 'Monthly" CSV Uploaded Successfully!', 200);
                    // $msg = '"Monthly" CSV Uploaded Successfully';
                    // createLog('timesheet_action', $msg);

                    // $success_message = count($attendance_list) . ' Attendance List added successfully.';
                    // return redirect()->back()->with('success_message', $success_message);
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
                return $this->sendError([], $validator->errors(), 400);
            }
            $attendance = null;
            $created_date = Carbon::parse($request->created_date)->format('Y-m-d');

            $attendance_data = UserDailyRecord::where('emp_id', $request->employee_id)->whereDate('dated', $created_date)->first();
            $employee = EmployeeDetail::where('id', $request->employee_id)->first();


            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();
            $workingDays = $company_detail ? explode(',', strtolower($company_detail->days)) : [];

            $match_date = Carbon::parse($created_date);
            $day_in_eng = $match_date->format('l');
            if (!in_array(strtolower($day_in_eng), $workingDays)) {
               return response()->json([
                'status' => 0,
                'success' => false,
                'message' => 'You cannot mark attendance on weekend.'
               ]);
            }
            $holidays = Holiday::whereRaw("FIND_IN_SET(?, branch_id)", [$employee->branch_id])
            ->whereRaw("FIND_IN_SET(?, company_id)", [$employee->company_id])
                ->where('is_deleted', '0')
                ->where('is_active', '1')
                ->where('start_date', '<=', $created_date)
                ->where('end_date', '>=', $created_date)
                ->count();

            if ($holidays > 0) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'message' => 'You cannot mark attendance on holiday.'
                   ]);
            }



            if (!$attendance_data) {
                $check_in_time = new DateTime($request->check_in);
                $check_out_time = new DateTime($request->check_out);
                // $check_out_time = $out_time->modify('+12 hours');
                $difference = $check_in_time->diff($check_out_time);
                $total_hours = $difference->h + $difference->i / 60;
                $working_hours = number_format($total_hours, 1);
                $user_entery = UserDailyRecord::create([
                    'emp_id' => $employee->id,
                    'dated' => $created_date,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'present' => '1',
                    'pull_time' => null,
                    'leave' => null,
                    'leave_type' => null,
                    'working_hours' => $working_hours,
                    'device_serial_no' => null,
                    'check_in_type' => '11',
                    'check_out_type' => null,
                    'check_in_ip' => null,
                    'check_out_ip' => null,
                ]);
                return $this->sendResponse($attendance, 'Daily manual attendance save successfully!', 200);
            } else {
                // $user_entery = UserDailyRecord::where('emp_id', $request->employee_id)->whereDate('dated', $created_date)->first();
                $check_in_time = new DateTime($request->check_in);
                $check_out_time = new DateTime($request->check_out);
                // $check_out_time = $check_out_time->modify('+12 hours');
                $difference = $check_in_time->diff($check_out_time);
                $total_hours = $difference->h + $difference->i / 60;
                $working_hours = number_format($total_hours, 1);
                $attendance_data->update([
                    'dated' => $created_date,
                    'check_in' => $request->check_in,
                    'check_out' => $request->check_out,
                    'present' => '1',
                    'pull_time' => null,
                    'leave' => null,
                    'leave_type' => null,
                    'working_hours' => $working_hours,
                    'device_serial_no' => null,
                    'check_in_type' => '11',
                    'check_out_type' => '11',
                    'check_in_ip' => null,
                    'check_out_ip' => null,
                ]);
            }
            if ($attendance_data) {
                $msg = '"'. $user->fullname . '" added "'. $employee->emp_name . '" Attendance Manually.';
                createLog('attendance-action',$msg);  
                return $this->sendResponse($attendance_data, 'Daily manual attendance save successfully!', 200);
            } else {
                return $this->sendResponse($attendance_data, 'Form not submited!', 200);
            }
        }
    }


    public function getEmpAttendenceSearch(Request $request)
    {
        // $validator = Validator::make($request->all(),[
        //     '*' => 'required',
        // ]);
        // if($validator->fails()){
        //     return $this->sendError([],$validator->errors(),400);
        // }
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $current_date = isset($request->searchDate) ? date('d-m-Y', strtotime($request->searchDate)) : Carbon::now()->format('d-m-Y');
        if (isset($request->input)) {
            $searched_name = $request->input;
            $selected = $request->branch_id;
            $searched_date = $request->searchDate;
            if ($selected == 'all') {
                if ($user_role == 1) {
                    $employees = EmployeeDetail::with([
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where(function ($query) use ($searched_name) {
                            $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                                ->orWhere('emp_id', 'LIKE', '%' . $searched_name . '%');
                        })
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->orderBy('emp_id', 'asc')
                        ->paginate(10);
                } else {
                    $employees = EmployeeDetail::with([
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where(function ($query) use ($searched_name) {
                            $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                                ->orWhere('emp_id', 'LIKE', '%' . $searched_name . '%');
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
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where(function ($query) use ($searched_name) {
                            $query->where('emp_name', 'LIKE', '%' . $searched_name . '%')
                                ->orWhere('emp_id', 'LIKE', '%' . $searched_name . '%');
                        })
                        ->where('branch_id', $selected)
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->orderBy('emp_id', 'asc')
                        ->paginate(10);
                } else {
                    $employees = EmployeeDetail::with([
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
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
        } else {
            $selected = $request->branch_id;
            $searched_date = $request->searchDate;
            if ($selected == 'all') {
                if ($user_role == 1) {
                    $employees = EmployeeDetail::with([
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->orderBy('emp_id', 'asc')
                        ->paginate(20);
                } else {
                    $employees = EmployeeDetail::with([
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
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
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
                        }
                    ])
                        ->where('branch_id', $selected)
                        ->where('status', '1')
                        ->where('is_deleted', '0')
                        ->orderBy('emp_id', 'asc')
                        ->paginate(20);
                } else {
                    $employees = EmployeeDetail::with([
                        'empToDailyRecord' => function ($query) use ($searched_date) {
                            $query->whereDate('dated', 'LIKE', $searched_date . '%');
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
            $company_setting = CompanySetting::where('branch_id', $employee->branch_id)->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();
            $absentCount = 0;
            $LateCount = 0;
            $halfCount = 0;
            $holidaysArray = [];
            $check_in_address = null;
            $check_out_address = null;

            $data = UserDailyRecord::where('emp_id', $employee->id)
                ->whereDate('dated', 'LIKE', $searched_date . '%')
                ->first();
            if (!$data) {

                $attendance = 'Absent';

            } else {
                if ($data->check_out != null) {
                    $check_in = new DateTime($data->check_in);
                    $check_out = new DateTime($data->check_out);
                    $late_time = new DateTime($company_setting->late_time);

                    if ($late_time >= $check_in) {
                        $attendance = 'Present';
                    } else {
                        $attendance = 'Late';
                    }
                } elseif ($data->leave && $data->leave != null && $data->leave_type != null && $data->check_in == null) {
                    $attendance = 'Leave';
                } else {
                    $attendance = 'Present';
                }
                $attendance_detail = AttendanceDetail::where('daily_record_id', $data->id)->first();
                if ($attendance_detail) {
                    $check_in_address = $attendance_detail->check_in_address;
                    $check_out_address = $attendance_detail->check_out_address;
                }

            }
            $attendanceData[] = [
                'employee_id' => $employee->emp_id,
                'employee_name' => $employee->emp_name,
                'attendance' => $attendance,
                'working_hours' => $data->working_hours ?? '0',
                'check_in_ip_address' => $data->check_in_ip ?? null,
                'check_out_ip_address' => $data->check_out_ip ?? null,
                'check_in_type' => $data->check_in_type ?? null,
                'check_out_type' => $data->check_out_type ?? null,
                'check_in_address' => $check_in_address ?? null,
                'check_out_address' => $check_out_address ?? null,
                'check_in_time' => $data->check_in ?? null,
                'check_out_time' => $data->check_out ?? null,
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

        if (count($employees) > 0) {
            $data['selected'] = $selected;
            $data['current_date'] = $current_date;
            $data['employees'] = $employees;
            $data['branches'] = $branches;
            $data['attendanceData'] = $attendanceData;
            return $this->sendResponse($data, 'Attendance search successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    // public function monthlyAttenSheet(Request $request)
    // {
    //     //user information
    //     $user = auth()->user();
    //     $user_role = $user->role_id;
    //     $user_company_id = explode(',',$user->company_id);
    //     $user_branch_id = explode(',',$user->branch_id);

    //     $selected = isset($request->branch_id) ? $request->branch_id : 'all';
    //     $current_month_year = isset($request->year_month) ? date('F Y', strtotime($request->year_month)) : date('F Y');
    //     $month_number = isset($request->year_month) ? date('m', strtotime($request->year_month)) : date('m');
    //     $number_of_days = Carbon::now()->month($month_number)->daysInMonth;
    //     $start_date = isset($request->year_month) ? date('Y-m-01', strtotime($request->year_month)) : date('Y-m-01');
    //     $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
    //     $carbonDate = Carbon::parse($currentMonth);
    //     $month = $carbonDate->month;
    //     $year = $carbonDate->year;

    //     // if ($user_role == 1) {
    //     //     $branches = Location::where('is_deleted', '0')->get();
    //     // } else {
    //     //     $branches = Location::whereIn('company_id', $user_company_id)
    //     //         ->whereIn('id', $user_branch_id)
    //     //         ->where('is_deleted', '0')
    //     //         ->get();
    //     // }

    //     // if (isset($request->branch_id) && $request->branch_id != 'all') {
    //     //     $branch = Location::where('is_deleted', '0')->where('id', $request->branch_id)->first();
    //     //     $branch_id = $branch->branch_id;
    //     // } else {
    //     //     $branch_id = null;
    //     // }

    //     if ($selected != 'all') {
    //         if ($user_role == '1') {
    //             $employees = EmployeeDetail::with([
    //                 'get_user_daily_attendance' => function ($query) use ($currentMonth) {
    //                     $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
    //                 }
    //             ])
    //             ->with([
    //                 'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
    //                     $query->where('year',Carbon::parse($currentMonth)->format('Y'))
    //                     ->where('month_of',Carbon::parse($currentMonth)->format('m'));
    //                 }
    //             ])
    //             ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
    //             ->where('employee_details.branch_id', $request->branch_id)
    //             ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
    //             ->where('employee_details.is_deleted', '0')
    //             // ->where('employee_details.status', '1')
    //             ->orderBy('employee_details.emp_id', 'asc')
    //             ->get();
    //         } else {
    //             $employees = EmployeeDetail::with([
    //                 'get_user_daily_attendance' => function ($query) use ($currentMonth) {
    //                     $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
    //                 }
    //             ])
    //             ->with([
    //                 'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
    //                     $query->where('year',Carbon::parse($currentMonth)->format('Y'))
    //                     ->where('month_of',Carbon::parse($currentMonth)->format('m'));
    //                 }
    //             ])
    //                 ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
    //                 ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
    //                 ->whereIn('employee_details.company_id', $user_company_id)
    //                 ->where('employee_details.branch_id', $request->branch_id)
    //                 ->where('employee_details.is_deleted', '0')
    //                 ->orderBy('employee_details.emp_id', 'asc')
    //                 ->get();
    //         }
    //     } else {
    //         if ($user_role == '1') {
    //             $employees = EmployeeDetail::with([
    //                 'get_user_daily_attendance' => function ($query) use ($currentMonth) {
    //                     $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
    //                 }
    //             ])
    //             ->with([
    //                 'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
    //                     $query->where('year',Carbon::parse($currentMonth)->format('Y'))
    //                     ->where('month_of',Carbon::parse($currentMonth)->format('m'));
    //                 }
    //             ])
    //             ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
    //             ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
    //             ->where('employee_details.is_deleted', '0')
    //             // ->where('employee_details.status', '1')
    //             ->orderBy('employee_details.emp_id', 'asc')
    //             ->get();
    //         } else {
    //             $employees = EmployeeDetail::with([
    //                 'get_user_daily_attendance' => function ($query) use ($currentMonth) {
    //                     $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated','asc');
    //                 }
    //             ])
    //             ->with([
    //                 'get_user_monthly_attendance' => function ($query) use ($currentMonth) {
    //                     $query->where('year',Carbon::parse($currentMonth)->format('Y'))
    //                     ->where('month_of',Carbon::parse($currentMonth)->format('m'));
    //                 }
    //             ])
    //             ->with('resignations', 'approval', 'leaves','terminations', 'holidays')
    //             ->select('employee_details.id','employee_details.emp_id','employee_details.emp_name')
    //             ->whereIn('employee_details.company_id', $user_company_id)
    //             ->whereIn('employee_details.branch_id', $user_branch_id)
    //             ->where('employee_details.is_deleted', '0')
    //             ->orderBy('employee_details.emp_id', 'asc')
    //             ->get();
    //         }
    //     }

    //     $attendanceArray = [];
    //     foreach ($employees as $employee) {
    //         $employeeAttendance = $employee['get_user_daily_attendance']; // Assuming this is a collection or array of attendance records for the employee
    //         for ($i = 0; $i < $number_of_days; $i++) {
    //             $current_date = strtotime($start_date);
    //             $next_date = strtotime("+" . $i . " day", $current_date);
    //             $date = date('Y-m-d', $next_date);

    //             $attendanceStatus = 'free'; // Initialize the status

    //             foreach ($employeeAttendance as $dailyAttendace) {
    //                 if ($date == $dailyAttendace['dated']) {
    //                     // Check attendance conditions for the given date
    //                     if ($dailyAttendace['weekend']) {
    //                         if ($dailyAttendace['present']) {
    //                             $attendanceStatus = 'present_on_weekend';
    //                         } else {
    //                             $attendanceStatus = 'weekend';
    //                         }
    //                     } elseif ($dailyAttendace['holiday']) {
    //                         if ($dailyAttendace['present']) {
    //                             $attendanceStatus = 'present_on_holiday';
    //                         } else {
    //                             $attendanceStatus = 'holiday';
    //                         }
    //                     } elseif ($dailyAttendace['leave']) {
    //                         if ($dailyAttendace['present']) {
    //                             $attendanceStatus = 'present_on_leave';
    //                         } else {
    //                             $attendanceStatus = 'leave';
    //                         }
    //                     } elseif ($dailyAttendace['absent']) {
    //                         $attendanceStatus = 'absent';
    //                     } elseif ($dailyAttendace['present']) {
    //                         if ($dailyAttendace['half_leave']) {
    //                             $attendanceStatus = 'half_leave';
    //                         } elseif ($dailyAttendace['late_coming']) {
    //                             $attendanceStatus = 'late_coming';
    //                         } else {
    //                             $attendanceStatus = 'present';
    //                         }
    //                     } elseif ($dailyAttendace['is_new_joining']) {
    //                         $attendanceStatus = 'new_joining';
    //                     } elseif ($dailyAttendace['is_resigned']) {
    //                         $attendanceStatus = 'resigned';
    //                     } elseif ($dailyAttendace['is_terminated']) {
    //                         $attendanceStatus = 'terminated';
    //                     }
    //                     $dailyAttendace->attendance_status = $attendanceStatus;
    //                     break;
    //                 }
    //             }
    //             $employeeMonthlySummary = $employee['get_user_monthly_attendance'];
    //             if ($employeeMonthlySummary) {
    //                 $actualHours = $employeeMonthlySummary != null ? $employeeMonthlySummary->actual_working_hours : 0;
    //                 $act_hours = floor( (int) $actualHours / 60);
    //                 $act_minutes = (int) $actualHours % 60;

    //                 $workingHours = $employeeMonthlySummary != null ? $employeeMonthlySummary->working_hours : 0;
    //                 $w_hours =  floor( (int) $workingHours / 60);
    //                 $w_minutes = (int) $workingHours % 60;

    //                 // Format the result
    //                 $employee['get_user_monthly_attendance']['actual_working_hours'] = sprintf("%02dh:%02dm", $act_hours, $act_minutes);
    //                 $employee['get_user_monthly_attendance']['working_hours'] = sprintf("%02dh:%02dm", $w_hours, $w_minutes);
    //             }
    //         }
    //     }
    //     foreach ($employees as $employee) {
    //         $attendanceArray['employee'][] = [
    //             'employee_id' => $employee->id,
    //             'employee_name' => $employee->emp_name,
    //             'employee_image' => asset('assets/images/users/images/'.$employee->emp_image),
    //             'attendance' => [
    //                 'month' => $month,
    //                 'year' => $year,
    //                 'current_month_year' => $current_month_year,
    //                 'number_of_days' => $number_of_days,
    //                 'currentMonth' => $currentMonth,
    //                 'daily_attendance' => $employee['get_user_daily_attendance'], // Initialize daily attendance array
    //                 'monthly_attendance' => array($employee['get_user_monthly_attendance']), // Initialize monthly attendance array
    //             ],
    //         ];
    //     }

    //     $currentPage = Paginator::resolveCurrentPage();
    //     $perPage = 15;
    //     $employeesPaginated = new LengthAwarePaginator(
    //         $employees->forPage($currentPage, $perPage),
    //         $employees->count(),
    //         $perPage,
    //         $currentPage,
    //         ['path' => Paginator::resolveCurrentPath()]
    //     );
    //     $attendanceArray['employees'] = $employeesPaginated->toArray();
    //     if($attendanceArray){
    //         return $this->sendResponse($attendanceArray,'Monthly attendance fetched successfully!',200);
    //     }else{
    //         return $this->sendResponse($attendanceArray,'Data not found!',200);
    //     }
    // }


    public function monthlyAttenSheet(Request $request)
    {
        // User information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $current_month_year = $request->get('year_month') ? date('F Y', strtotime($request->year_month)) : date('F Y');
        $month_number = $request->get('year_month') ? date('m', strtotime($request->year_month)) : date('m');
        $number_of_days = Carbon::now()->month($month_number)->daysInMonth;
        $start_date = $request->get('year_month') ? date('Y-m-01', strtotime($request->year_month)) : date('Y-m-01');
        $currentMonth = $request->get('year_month') ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;

        $searchBy = $request->get('search_by', '');

        // Pagination parameters
        $perPage = $request->get('per_page', 20);
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        list($currentYear, $currentMonth) = explode('-', $currentMonth);

        $query = EmployeeDetail::with([
            'empToDailyRecord' => function ($query) use ($currentYear, $currentMonth) {
                $query->whereYear('dated', $currentYear)
                    ->whereMonth('dated', $currentMonth)
                    ->orderBy('dated', 'asc');
            },
            'resignations',
            'approval',
            'leaves',
            'terminations',
            'holidays'
        ])
            ->select('employee_details.id', 'employee_details.emp_id', 'employee_details.emp_name', 'employee_details.company_id', 'employee_details.branch_id', 'employee_details.emp_gender', 'employee_details.emp_image');

        if ($request->branch_id) {
            if ($user_role == '1') {
                $query->where('employee_details.branch_id', $request->branch_id);
            } else {
                $query->whereIn('employee_details.company_id', $user_company_id)
                    ->where('employee_details.branch_id', $request->branch_id);
            }
        } else {

            if ($user_role != '1') {
                $query->whereIn('employee_details.company_id', $user_company_id)
                    ->whereIn('employee_details.branch_id', $user_branch_id);
            }
        }

        if ($searchBy) {
            $query->where(function ($query) use ($searchBy) {
                $query->where('employee_details.emp_name', 'LIKE', '%' . $searchBy . '%')
                    ->orWhere('employee_details.emp_id', 'LIKE', '%' . $searchBy . '%');
            });
        }

        $total = $query->count();
        $employees = $query->where('employee_details.is_deleted', '0')
            // ->where('employee_details.status', '1')
            ->orderBy('employee_details.emp_id', 'DESC')
            ->offset($offset)
            ->limit($perPage)
            ->get();


        $total = $query->count();
        $total_working_days = 0;
        $absent_count = 0;
        $halfday_count = 0;
        $leave_count = 0;
        $late_count = 0;
        $present_count = 0;
        $total_employee_hours = 0;
        $response = [];
        foreach ($employees as $employee) {

            $empResignation = $employee->resignations && $employee->resignations->is_approved == 1 ? $employee->resignations->is_approved : null;
            $resignationDate = $empResignation == '1' ? Carbon::parse($employee->resignations->resignation_date)->addDay() : null;
            $empTermination = $employee->terminations ? $employee->terminations->is_approved : null;
            $terminationDate = $empTermination == '1' ? Carbon::parse($employee->terminations->termination_date)->addDay() : null;

            if (
                ($resignationDate && ($resignationDate->year < $year ||
                    ($resignationDate->year == $year && $resignationDate->month < $month))) ||
                ($terminationDate && ($terminationDate->year < $year ||
                    ($terminationDate->year == $year && $terminationDate->month < $month)))
            ) {
                continue;
            }
            $joining_date = $employee->approval ? Carbon::parse($employee->approval->joining_date)->subDay() : null;

            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();

            if ($company_detail) {
                $start_time = new DateTime($company_detail->start_time);
                $end_time = new DateTime($company_detail->end_time);
                $late_time = new DateTime($company_detail->late_time);
                $difference = $start_time->diff($end_time);
                $com_total_hours = $difference->h + $difference->i / 60;
                $company_total_hours = number_format($com_total_hours, 1);
                $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);
            } else {
                $company_total_hours = 0;
                $workingDays = [];
            }

            $half_leave = CompanySetting::where('branch_id', $employee->branch_id)->value('half_day');

            $employeeAttendance = $employee->get_user_daily_attendance;

            $attendanceArray = [];
            for ($i = 0; $i < $number_of_days; $i++) {

                $current_date = strtotime($start_date);
                $next_date = strtotime("+" . $i . " day", $current_date);
                $date = date('Y-m-d', $next_date);
                $match_date = new DateTime($date);
                $attendanceStatus = 'Free';
                $emp_total_hours = 0;

                if ($match_date > Carbon::today()) {
                    $attendanceArray[$date] = [
                        'employee_id' => $employee->id,
                        'attendance_status' => $attendanceStatus,
                    ];
                    continue;
                }

                if (($employee->resignations && $employee->resignations->is_approved == 1 && $employee->resignations->resignation_date < $date) || ($employee->terminations && $employee->terminations->is_approved == 1 && $employee->terminations->termination_date < $date)) {
                    $attendanceArray[$date] = [
                        'employee_id' => $employee->id,
                        'attendance_status' => $attendanceStatus,
                    ];
                    continue;
                }

                if (!in_array(strtolower($match_date->format('l')), $workingDays)) {
                    $attendanceStatus = 'Weekend';
                }

                $eligibleHolidays = Holiday::where('is_deleted', '0')
                    ->where('is_active', '1')
                    ->get();

                foreach ($eligibleHolidays as $holiday) {
                    $holidayCompanyIds = explode(',', $holiday->company_id);
                    $holidayBranchIds = explode(',', $holiday->branch_id);
                    $startDate = Carbon::parse($holiday->start_date);
                    $endDate = Carbon::parse($holiday->end_date);

                    if (in_array($employee->company_id, $holidayCompanyIds) && in_array($employee->branch_id, $holidayBranchIds)) {
                        while ($startDate->lte($endDate)) {
                            if ($startDate->format('Y-m-d') == $date) {
                                $attendanceStatus = 'Holiday';

                                break 2;
                            }
                            $startDate->addDay();
                        }
                    }
                }
                $attendanceFound = false;
                foreach ($employeeAttendance as $dailyAttendance) {
                    if ($date == $dailyAttendance->dated) {
                        $attendanceFound = true;
                        if ($dailyAttendance->leave && $dailyAttendance->leave_type != null && $dailyAttendance->check_in == null) {
                            $attendanceStatus = $dailyAttendance->leave;
                            $leave_count++;
                            break;
                        } elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out != null) {
                            $emp_check_in = new DateTime($dailyAttendance->check_in);
                            $emp_check_out = new DateTime($dailyAttendance->check_out);

                            $emp_total_hours = $dailyAttendance->working_hours;

                            if ($late_time >= $emp_check_in) {
                                $attendanceStatus = 'Present';
                                $present_count++;
                            } else {
                                $attendanceStatus = 'Late';
                                $late_count++;
                            }
                            $total_working_days++;
                            break;

                        } elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out == null) {
                            $emp_check_in = new DateTime($dailyAttendance->check_in);

                            if ($late_time >= $emp_check_in) {
                                $attendanceStatus = 'Present';
                                $present_count++;
                            } else {
                                $attendanceStatus = 'Late';
                                $late_count++;
                            }
                            // $total_working_days++;
                            break;
                        }
                    }
                }
                if ($attendanceFound) {
                    if ($dailyAttendance->check_in != null && $dailyAttendance->check_out != null && $dailyAttendance->working_hours < $half_leave) {
                        $attendanceStatus = 'Half Day';
                        $halfday_count++;
                    }
                }

                if ($joining_date != null && $date == $joining_date->format('Y-m-d')) {
                    $attendanceStatus = 'New Joining';
                } elseif ($resignationDate != null && $date == $resignationDate->format('Y-m-d')) {
                    $attendanceStatus = 'Resigned';
                } elseif ($terminationDate != null && $date == $terminationDate->format('Y-m-d')) {
                    $attendanceStatus = 'Terminated';
                } elseif ($joining_date < $match_date && !in_array($attendanceStatus, ['Present', 'full leave', 'half leave', 'short leave', 'Holiday', 'Weekend', 'Late', 'New Joining', 'Resigned', 'Terminated'])) {
                    $attendanceStatus = 'Absent';
                    $absent_count++;
                }

                $attendanceArray[$date] = [
                    'employee_id' => $employee->id,
                    'attendance_status' => $attendanceStatus,
                ];

                $total_employee_hours += $emp_total_hours;
            }

            $query = EmployeeDetail::with([
                'empToDailyRecord' => function ($query) use ($currentYear, $currentMonth) {
                    $query->whereYear('dated', $currentYear)
                        ->whereMonth('dated', $currentMonth)
                        ->orderBy('dated', 'asc');
                },
                'resignations',
                'approval',
                'leaves',
                'terminations',
                'holidays'
            ])
                ->select('employee_details.id', 'employee_details.emp_id', 'employee_details.emp_name', 'employee_details.company_id', 'employee_details.branch_id', 'employee_details.emp_gender', 'employee_details.emp_image');
            $record = $query->get();
            foreach ($record as $employees) {

                if (!empty($employees->emp_image)) {
                    $employees->imagePath = $this->imgFunc($employees->emp_image, $employees->emp_gender);
                } else {
                    $employees->imagePath = $this->imgFunc(null, $employees->emp_gender);
                }
            }
            $emp_shoul_work = $total_working_days * $company_total_hours;

            $data = [
                'id' => $employee->id,
                'employee_id' => $employee->emp_id,
                'employee_name' => $employee->emp_name,
                'company_id' => $employee->company_id,
                'branch_id' => $employee->branch_id,
                'emp_image' => !empty($employee->emp_image) ? $this->imgFunc($employee->emp_image, $employee->emp_gender) : $this->imgFunc(null, $employee->emp_gender),
                'attendance_record' => [
                    'daily_attendance' => $attendanceArray,
                    'working_days' => $total_working_days,
                    'working_hours' => number_format($total_employee_hours, 1),
                    'actualWorkingHours' => $emp_shoul_work,
                    'present' => $present_count,
                    'leave' => $leave_count,
                    'absent' => $absent_count,
                    'late' => $late_count,
                    'halfday_count' => $halfday_count,
                ],
            ];

            array_push($response, $data);

            $total_working_days = 0;
            $absent_count = 0;
            $leave_count = 0;
            $late_count = 0;
            $present_count = 0;
            $total_employee_hours = 0;

        }


        $pagination = [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => ceil($total / $perPage),
            'next_page_url' => $currentPage < ceil($total / $perPage) ? url()->current() . '?page=' . ($currentPage + 1) . '&per_page=' . $perPage : null,
            'prev_page_url' => $currentPage > 1 ? url()->current() . '?page=' . ($currentPage - 1) . '&per_page=' . $perPage : null,
        ];

        return $this->sendResponse($response, $pagination, 'Attendance fetched successfully!', 200);
    }

    public function yearlyDetail(Request $request)
    {
        $selectBranch = $request->selectBranch ?? 'all';
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $current_year = $request->year ?? date('Y');
        $monthCount = 12;
        $allMonthsData = [];

        // Pagination parameters
        $perPage = $request->get('per_page', 10);
        $currentPage = $request->get('page', 1);

        // Fetch employees based on role and branch
        if ($selectBranch == 'all') {
            $employeesQuery = ($user_role == 1)
                ? EmployeeDetail::leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->with('resignations', 'approval', 'leaves', 'holidays')
                    ->select('employee_details.*')
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                : EmployeeDetail::leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->with('resignations', 'approval', 'leaves', 'holidays')
                    ->select('employee_details.*')
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->whereIn('employee_details.branch_id', $user_branch_id)
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc');
        } else {
            $employeesQuery = ($user_role == 1)
                ? EmployeeDetail::leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->with('resignations', 'approval', 'leaves', 'holidays')
                    ->select('employee_details.*')
                    ->where('employee_details.branch_id', $selectBranch)
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc')
                : EmployeeDetail::leftJoin('locations', 'locations.id', '=', 'employee_details.branch_id')
                    ->with('resignations', 'approval', 'leaves', 'holidays')
                    ->select('employee_details.*')
                    ->where('employee_details.branch_id', $selectBranch)
                    ->whereIn('employee_details.company_id', $user_company_id)
                    ->where('employee_details.is_deleted', '0')
                    ->where('locations.is_deleted', '0')
                    ->orderBy('employee_details.emp_id', 'asc');
        }

        // Fetch paginated employees
        $employees = $employeesQuery->paginate($perPage, ['*'], 'page', $currentPage);
        foreach ($employees as $employee) {
            $employee_id = $employee->emp_id;
            $id = $employee->id;

            $monthlyAttendanceDataArray = [];
            $yearlyPresent = $yearlyAbsent = $yearlyLate = $yearlyLeave = 0;

            for ($month = 1; $month <= $monthCount; $month++) {
                $currentMonth = Carbon::create($current_year, $month);
                $totalWorkingDays = 0;
                $currentMonthEnd = $currentMonth->copy()->endOfMonth();
                $absentDates = $presentDates = $lateDates = $leaveDates = [];

                // Fetch attendance data
                $data = UserDailyRecord::where('emp_id', $employee->id)
                    ->whereDate('dated', 'LIKE', Carbon::parse($currentMonth)->format('Y-m') . '%')
                    ->get();

                // Fetch company late_time setting
                $late_time = CompanySetting::where('branch_id', $employee->branch_id)->value('late_time');

                // Loop through each day of the month
                for ($day = 1; $day <= $currentMonthEnd->day; $day++) {
                    $currentDate = Carbon::create($current_year, $month, $day);

                    // Skip weekends and future dates
                    if ($currentDate->isWeekend() || $currentDate > now()) {
                        continue;
                    }

                    if ($currentDate <= now()->startOfDay()) {
                        $totalWorkingDays++;
                    }

                    // Check attendance record
                    $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
                        ->where('created_at', '<=', $currentDate->endOfDay())
                        ->first();

                    // Check if employee is on leave
                    $leaveRecord = Leave::where('emp_id', $employee->id)
                        ->where('from_date', '<=', $currentDate)
                        ->where('to_date', '>=', $currentDate)
                        ->first();

                    if ($leaveRecord) {
                        $leaveDates[$currentDate->toDateString()] = 'on leave';
                        $yearlyLeave++;
                    } elseif (!$attendanceRecord) {
                        $absentDates[$currentDate->toDateString()] = 'absent';
                        $yearlyAbsent++;
                    } else {
                        if ($attendanceRecord->check_in > $late_time) {
                            $lateDates[$currentDate->toDateString()] = 'Late';
                            $yearlyLate++;
                        } else {
                            $presentDates[$currentDate->toDateString()] = 'Present';
                            $yearlyPresent++;
                        }
                    }
                }

                $monthlyData = [
                    'month' => $currentMonth->format('F Y'),
                    'Present' => count($presentDates),
                    'absent_count' => count($absentDates),
                    'late_count' => count($lateDates),
                    'leave_count' => count($leaveDates),
                ];

                array_push($monthlyAttendanceDataArray, $monthlyData);
            }

            // Add yearly totals for the employee
            $allMonthsData[] = [
                'id' => $id,
                'emp_id' => $employee_id,
                'emp_image' => !empty($employee->emp_image) ? $this->imgFunc($employee->emp_image, $employee->emp_gender) : $this->imgFunc(null, $employee->emp_gender),
                'employee_name' => $employee->emp_name,
                'attendanceData' => $monthlyAttendanceDataArray,
                'yearlyTotals' => [
                    'Present' => $yearlyPresent,
                    'Absent' => $yearlyAbsent,
                    'Late' => $yearlyLate,
                    'Leave' => $yearlyLeave,
                ],
            ];
        }

        $branches = $user_role == 1
            ? Location::where('is_deleted', '0')->get()
            : Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'allMonthsData' => $allMonthsData,
                'pagination' => [
                    'total' => $employees->total(),
                    'per_page' => $employees->perPage(),
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'next_page_url' => $employees->nextPageUrl(),
                    'prev_page_url' => $employees->previousPageUrl(),
                ],
                'user' => $user,
                'branches' => $branches,
                'current_year' => $current_year,
                'selectBranch' => $selectBranch,
            ],
        ]);
    }

    public function downloadAttendanceSheet(Request $request)
    {
        // Fetch data logic
        $branch_id = $request->branch_id;
        $year_month = $request->year_month;
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        $branch_code = 'all';
        $branch_name = '';

        // Fetch branch details if specific branch is selected
        if (isset($branch_id) && $branch_id != 'all') {
            $branch = Location::where('is_deleted', '0')->where('id', $branch_id)->first();
            if ($branch) {
                $branch_code = $branch->branch_id;
                $branch_name = $branch->branch_name;
            }
        }

        // Determine selected branch
        $selected = isset($branch_id) ? $branch_id : 'all';

        // Determine current month and year for date calculations
        $current_month_year = isset($year_month) ? date('F Y', strtotime($year_month)) : date('F Y');
        $month_number = isset($year_month) ? date('m', strtotime($year_month)) : date('m');
        $number_of_days = Carbon::now()->month($month_number)->daysInMonth;
        $start_date = isset($year_month) ? date('Y-m-01', strtotime($year_month)) : date('Y-m-01');
        $currentMonth = isset($year_month) ? date('Y-m', strtotime($year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;

        // Fetch branches based on user role and permissions
        if ($user_role == 1) {
            $branches = Location::where('is_deleted', '0')->get();
        } else {
            $branches = Location::whereIn('company_id', $user_company_id)
                ->whereIn('id', $user_branch_id)
                ->where('is_deleted', '0')
                ->get();
        }

        // Fetch employees based on selected branch and user role
        if ($selected != 'all') {
            $employees = EmployeeDetail::with([
                'empToDailyRecord' => function ($query) use ($currentMonth) {
                    $query->whereDate('dated', 'LIKE', $currentMonth . '%')->orderBy('dated', 'asc');
                },
                'resignations',
                'approval',
                'leaves',
                'terminations',
                'holidays'
            ])
                ->where('branch_id', $branch_id)
                ->where('is_deleted', '0')
                ->where('status', '1')
                ->select('id', 'emp_id', 'emp_name', 'company_id', 'branch_id')
                ->orderBy('emp_id', 'asc')
                ->get();
        } else {
            $employees = EmployeeDetail::with([
                'empToDailyRecord' => function ($query) use ($currentMonth) {
                    $query->whereDate('dated', $currentMonth)->orderBy('dated', 'asc');
                },
                'resignations',
                'approval',
                'leaves',
                'terminations',
                'holidays'
            ])
                ->whereIn('company_id', $user_company_id)
                ->whereIn('branch_id', $user_branch_id)
                ->where('is_deleted', '0')
                ->where('status', '1')
                ->select('id', 'emp_id', 'emp_name', 'company_id', 'branch_id')
                ->orderBy('emp_id', 'asc')
                ->get();
        }

        // Generate CSV file
        $csvFileName = 'attendance_' . time() . '.csv';
        $csvFilePath = storage_path('app/public/' . $csvFileName);

        $file = fopen($csvFilePath, 'w');
        if ($file === false) {
            return $this->sendResponse(null, 'Failed to create the CSV file.', 500);
        }

        $header = ['Employee ID', 'Employee Name'];

        // Generate header row with day numbers
        for ($i = 1; $i <= $number_of_days; $i++) {
            $header[] = 'Day ' . $i;
        }

        // Add header for Total Working Hours
        $header[] = 'Total Present';
        $header[] = 'Total Absent';
        $header[] = 'Total Leave';
        $header[] = 'Total Late';
        $header[] = 'Total Working Hours';

        fputcsv($file, $header);

        // Write employee data to CSV
        // Write employee data to CSV
        foreach ($employees as $employee) {
            $employeeAttendance = $employee->empToDailyRecord;
            $row = [$employee->emp_id, $employee->emp_name];
            $empResignation = $employee->resignations ? $employee->resignations->is_approved : null;
            $resignationDate = $empResignation == '1' ? Carbon::parse($employee->resignations->resignation_date)->addDay() : null;
            $joining_date = $employee->approval ? Carbon::parse($employee->approval->joining_date)->subDay() : null;
            $empTermination = $employee->terminations ? $employee->terminations->is_approved : null;
            $terminationDate = $empTermination == '1' ? Carbon::parse($employee->terminations->termination_date)->addDay() : null;
            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();

            $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);
            $totalPresent = 0;
            $totalAbsent = 0;
            $totalLeave = 0;
            $totalLate = 0;
            $totalWorkingHours = 0;

            // Populate attendance status for each day
            for ($i = 0; $i < $number_of_days; $i++) {
                $current_date = strtotime($start_date);
                $next_date = strtotime("+" . $i . " day", $current_date);
                $date = date('Y-m-d', $next_date);
                $match_date = new DateTime($date);
                $attendanceStatus = '-';

                // Check if the day is a weekend
                if (!in_array(strtolower($match_date->format('l')), $workingDays)) {
                    $attendanceStatus = 'W';
                }

                $eligibleHolidays = Holiday::where('is_deleted', '0')
                    ->where('is_active', '1')
                    ->get();

                foreach ($eligibleHolidays as $holiday) {
                    $holidayCompanyIds = explode(',', $holiday->company_id);
                    $holidayBranchIds = explode(',', $holiday->branch_id);
                    $startDate = Carbon::parse($holiday->start_date);
                    $endDate = Carbon::parse($holiday->end_date);

                    if (in_array($employee->company_id, $holidayCompanyIds) && in_array($employee->branch_id, $holidayBranchIds)) {
                        while ($startDate->lte($endDate)) {
                            if ($startDate->format('Y-m-d') == $date) {
                                $attendanceStatus = 'H';
                                break 2; // Exit both the holiday loop and the eligible holiday loop
                            }
                            $startDate->addDay();
                        }
                    }
                }

                foreach ($employeeAttendance as $dailyAttendance) {
                    if ($date == $dailyAttendance->dated) {
                        if ($dailyAttendance->leave && $dailyAttendance->leave_type != null && $dailyAttendance->check_in == null) {
                            $attendanceStatus = $dailyAttendance->leave;
                            $totalLeave++;
                            break;
                        } elseif ($dailyAttendance->check_in != null && $dailyAttendance->check_out != null) {
                            if ($company_detail && $company_detail->late_time >= $dailyAttendance->check_in) {
                                $attendanceStatus = 'P';
                                $totalPresent++;
                            } else {
                                $attendanceStatus = 'L';
                                $totalLate++;
                            }
                            // Add the working hours for the day to the total
                            $totalWorkingHours += $dailyAttendance->working_hours; // Assuming 'working_hours' field exists in 'dailyAttendance'
                            break;
                        }
                    }
                }

                if ($joining_date != null && $date == $joining_date->format('Y-m-d')) {
                    $attendanceStatus = 'N/J';
                } elseif ($resignationDate != null && $date == $resignationDate->format('Y-m-d')) {
                    $attendanceStatus = 'N/R';
                } elseif ($terminationDate != null && $date == $terminationDate->format('Y-m-d')) {
                    $attendanceStatus = 'N/T';
                }

                if ($attendanceStatus == '-') {
                    $totalAbsent++;
                }

                $row[] = $attendanceStatus;
            }

            $row[] = $totalPresent;
            $row[] = $totalAbsent;
            $row[] = $totalLeave;
            $row[] = $totalLate;
            $row[] = $totalWorkingHours;

            fputcsv($file, $row);
        }
        fclose($file);

        // Return download link
        $downloadLink = url('api/download-attendance-file?file_path=' . $csvFileName);
        return $this->sendResponse($downloadLink, 'Attendance sheet downloaded successfully!', 200);
    }


    public function downloadAttendanceFile(Request $request)
    {
        $filePath = $request->file_path;

        if (Storage::exists('public/' . $filePath)) {
            return response()->download(storage_path('app/public/' . $filePath));
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'File not found.',
            ], 404);
        }
    }

    public function getattendance(Request $request)
    {
        $date = $request->date_time;
        $emp_id = $request->emp_id;

        // Convert $date to the correct format if needed
        $date = date('Y-m-d', strtotime($date));

        $data = UserDailyRecord::where('emp_id', $emp_id)
            ->whereDate('dated', '=', $date)
            ->first();
        if ($data) {
            return $this->sendResponse($data, 'Emloyee attendance fetched successfully!', 200);
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }

    public function LeaveBalance(Request $request)
    {
        $employee = $request->emp_id;

        $currentYear = Carbon::now()->year;

        $query = DB::table('emp_leaves')
            ->where('emp_id', $employee)
            ->where('is_approved', '1')
            ->whereYear('created_at', $currentYear)
            ->select(
                DB::raw('SUM(approved_days) as total_days'),
                DB::raw('MONTH(from_date) as month')
            )
            ->groupBy(DB::raw('MONTH(from_date)'))
            ->orderBy('month', 'asc')
            ->get();

        $usermcount = [];
        $userArr = [];
        $month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'Auguest', 'September', 'October', 'November', 'December'];

        foreach ($query as $row) {
            $usermcount[(int) $row->month] = $row->total_days;
        }

        for ($i = 1; $i <= 12; $i++) {
            if (isset($usermcount[$i])) {
                $userArr[$i]['count'] = $usermcount[$i];
            } else {
                $userArr[$i]['count'] = 0;
            }
            $userArr[$i]['month'] = $month[$i - 1];
        }

        return response()->json([
            'status' => 1,
            'message' => 'User leave Fetch',
            'data' => array_values($userArr)
        ]);
    }

    public function getAttendanceDetails(Request $request)
    {
        $employeeId = $request->emp_id;
        $date = $request->date;
        $query = UserDailyRecord::where('emp_id', $employeeId)
            ->where('dated', $date)
            ->select('check_in', 'check_out', 'id', 'emp_id')
            ->first();

        if ($query) {
            return response()->json([
                'status' => 1,
                'message' => 'Record Fetch Successfully',
                'data' => $query
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Record not found',
                'data' => [
                    'emp_id' => $request->emp_id,
                    'check_in' => null,
                    'check_out' => null,
                ]
            ]);
        }
    }
}
