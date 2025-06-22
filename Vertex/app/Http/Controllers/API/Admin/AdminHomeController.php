<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Designation;
use App\Traits\ProfileImage;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\UserMonthlyRecord;
use App\Models\Location;
use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use App\Models\CompanySetting;
use App\Models\EmployeeDetail;
use App\Models\UserDailyRecord;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Version_History;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class AdminHomeController extends BaseController
{
    use ProfileImage;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /***
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $branchIds = explode(',', $user->branch_id);
        $today = Carbon::today()->format('Y-m-d');
        $query = EmployeeDetail::query();
        $query->where('status', '1')
            ->where('is_deleted', '0')
            ->where('is_active', '1');
        if ($user->role_id != 1) {
        $query->whereIn('branch_id', $branchIds);
        }

        $employees = $query->get();
        $data = [];
        $totalEmp = [];
        $totalPresents = [];
        $totalAbsents = [];
        $totalLates = [];

        $countEmp = 0;
        $countPresents = 0;
        $countAbsents = 0;
        $countLates = 0;

        foreach ($employees as $employee) {
            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();

            if ($company_detail) {
                $totalEmp[] = $this->fetchEmpDetail($employee);
                $countEmp++;
                $late_time = new DateTime($company_detail->late_time);
                $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);
                $dailyRecord = UserDailyRecord::where('emp_id', $employee->id)->whereDate('dated', $today)->first();

                if ($dailyRecord) {
                    if ($dailyRecord->check_in != null && $dailyRecord->check_out != null) {
                        $emp_check_in = new DateTime($dailyRecord->check_in);
                        if ($late_time >= $emp_check_in) {
                            $attendanceStatus = 'Present';
                            $totalPresents[] = $this->fetchEmpDetail($employee, $dailyRecord->check_in);
                            $countPresents++;
                        } else {
                            // Calculate late time
                            $late_minutes = $emp_check_in->diff($late_time)->i;
                            $attendanceStatus = 'Late';
                            $totalPresents[] = $this->fetchEmpDetail($employee, $dailyRecord->check_in, $late_minutes); // Pass check_in time and late time
                            $totalLates[] = $this->fetchEmpDetail($employee, $dailyRecord->check_in, $late_minutes);   // Pass check_in time and late time
                            $countPresents++;
                            $countLates++;
                        }

                    } elseif ($dailyRecord->check_in != null && $dailyRecord->check_out == null) {
                        $emp_check_in = new DateTime($dailyRecord->check_in);
                        if ($late_time >= $emp_check_in) {
                            $attendanceStatus = 'Present';
                            $totalPresents[] = $this->fetchEmpDetail($employee, $dailyRecord->check_in);
                            $countPresents++;
                        } else {
                            // Calculate late time
                            $late_minutes = $emp_check_in->diff($late_time)->i;
                            $attendanceStatus = 'Late';
                            $totalPresents[] = $this->fetchEmpDetail($employee, $dailyRecord->check_in, $late_minutes); // Pass check_in time and late time
                            $totalLates[] = $this->fetchEmpDetail($employee, $dailyRecord->check_in, $late_minutes);   // Pass check_in time and late time
                            $countPresents++;
                            $countLates++;
                        }
                    } elseif ($dailyRecord->leave && $dailyRecord->leave_type != null && $dailyRecord->check_in == null) {
                        $attendanceStatus = $dailyRecord->leave;
                    } elseif ($dailyRecord->check_in == null && $dailyRecord->check_out == null && $dailyRecord->leave_type == null) {
                        // Do nothing for now
                    }
                } else {
                    $holidays = Holiday::where('is_deleted', '0')
                        ->where('is_active', '1')
                        ->whereDate('start_date', $today)
                        ->first();

                    if (!in_array(strtolower(Carbon::parse($today)->format('l')), $workingDays)) {
                        $attendanceStatus = 'Weekend';
                    } elseif ($holidays) {
                        $attendanceStatus = 'Holiday';
                    } else {
                        $attendanceStatus = 'Absent';
                        $totalAbsents[] = $this->fetchEmpDetail($employee);
                        $countAbsents++;
                    }
                }
            }
        }
        $logs = Log::with([
            'user' => function ($query) {
                $query->select('id', 'email');
            }
        ])
            ->orderBy('id', 'desc')->where('user_id', $user->id)
            ->limit(7)
            ->get();
        $data = [
            'countEmp' => $countEmp,
            'countPresents' => $countPresents,
            'countAbsents' => $countAbsents,
            'countLates' => $countLates,
            'totalEmp' => $totalEmp,
            'presents' => $totalPresents,
            'absents' => $totalAbsents,
            'late' => $totalLates,
            'logs' => $logs,
        ];

        if ($data) {
            return response()->json([
                'status' => 1,
                'success' => true,
                'message' => "Data fetched successfully",
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'success' => false,
                'message' => "Data not found",
                'data' => '',
            ]);
        }
    }

    public function fetchEmpDetail($employee, $check_in_time = null, $late_time = null)
    {
        $data = [
            "id" => $employee->id,
            "emp_id" => $employee->emp_id ?? null,
            "emp_image" => $this->imgFunc($employee->emp_image, $employee->emp_gender) ?? null,
            "emp_name" => $employee->emp_name ?? null,
            "location" => $employee->branch->branch_name ?? null,
            "branch_id" => $employee->branch->id ?? null,
            'designation' => $employee->approval->designation->name ?? null,
            'department' => $employee->approval->designation->department->name ?? null,
            'check_in_time' => $check_in_time,
            'late_time' => $late_time,
            'check_out' => $employee->check_out ?? null,
        ];
        return $data;
    }
    public function attendanceGraph(Request $request)
    {
        // return Auth::user();
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $lateEmpCount = 0;
        $month_number = date('m'); // current month
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        // return $carbonDate;
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        $number_of_days = now()->month($month_number)->daysInMonth;
        $currentDate = Carbon::now()->toDateString();

        // for the upper cards data
        if ($user_role == 1) {
            $employees = EmployeeDetail::with('branch')->where('status', '1')
                ->where('is_deleted', '0')
                ->orderBy('emp_id', 'asc')
                ->get();

            if ($request->type == 'month') {
                $totalPresentEmp = EmployeeDetail::with('branch')->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->whereHas('user_attendance', function ($q) use ($month, $year) {
                        $q->whereNotNull('check_in')
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year);
                    })
                    ->get();
            } else {
                $totalPresentEmp = EmployeeDetail::with('branch')->where('status', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('emp_id', 'asc')
                    ->whereHas('user_attendance', function ($q) use ($year) {
                        $q->whereNotNull('check_in')
                            ->whereYear('created_at', $year);
                    })
                ->get();
            }

            // dd($totalPresentEmp);
        }
        else {
            $employees = EmployeeDetail::with('branch')->whereIn('company_id', $user_company_id)
                ->whereIn('branch_id', $user_branch_id)
                ->where('status', '1')
                ->where('is_deleted', '0')
                ->get();
            if ($request->type == 'month') {
                $totalPresentEmp = EmployeeDetail::with('branch')
                    ->whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->whereHas('user_attendance', function ($q) use ($month, $year) {
                        $q->whereNotNull('check_in')
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year);
                    })
                    ->get();
            } else {
                $totalPresentEmp = EmployeeDetail::with('branch')
                    ->whereIn('company_id', $user_company_id)
                    ->whereIn('branch_id', $user_branch_id)
                    ->where('status', '1')
                    ->where('is_deleted', '0')
                    ->whereHas('user_attendance', function ($q) use ($year) {
                        $q->whereNotNull('check_in')
                            ->whereYear('created_at', $year);
                    })
                    ->get();
            }
        }
        $lateEmployees = [];

        foreach ($totalPresentEmp as $emp) {
            $data = UserDailyRecord::where('emp_id', $emp->id)
                ->whereDate('dated', '>=', Carbon::now()->startOfMonth())
                ->whereDate('dated', '<=', Carbon::now()->endOfMonth())
                ->get();
            $actual_hours = CompanySetting::where('branch_id', $emp->branch_id)
                ->where('is_deleted', '0')
                ->get();

            foreach ($actual_hours as $actual_hour) {
                $lateemp = $actual_hour->late_time;
            }

            $isLate = false;

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

        $attendanceData = [];
        $allData = [];

        foreach ($employees as $key => $employee) {
            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('is_deleted', '0')
                ->first();

            $absentCount = 0;
            $lateCount = 0;
            $present_count = 0;
            $holiday_count = 0;
            $employeeData = [
                'present' => [],
                'late' => [],
                'absent' => [],
                'weekend' => [],
                'holiday' => [],
            ];
            //get holidays into DB
            foreach ($employee['holidays'] as $holiday) {
                if ($holiday['is_active'] == '1') {
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
            $data = UserDailyRecord::where('emp_id', $employee->id)
                ->whereDate('dated', '>=', Carbon::now()->startOfMonth())
                ->whereDate('dated', '<=', Carbon::now()->endOfMonth())
                ->get();

            $attendance = [];
            $late_attendance = [];
            $absentCount = 0;
            $weekendsCount = 0;

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

            unset($mergedAttendance[$date]['weekend']);
            unset($mergedAttendance[$date]['present']);
            unset($mergedAttendance[$date]['late']);
            unset($mergedAttendance[$date]['absent']);
        }
        ksort($mergedAttendance);
        return response()->json(['success' => 1, 'data' => $mergedAttendance, 'dates' => $number_of_days]);
    }

    public function EmpPresentData(Request $request)
    {
        date_default_timezone_set("Asia/Karachi");

        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        $present = 0;
        $late = 0;
        $absent = 0;

        $dated = isset($request->year_month) && !empty($request->year_month) ? Carbon::parse($request->year_mont)->format('Y-m') : date('Y-m');
        $user_branch_id = isset($request->branch_id) && !empty($request->branch_id) ? $request->branch_id : $user_branch_id;
        $datesInMonth = array();
        $branchesWithAttendance = Location::with([
            'employee_details_with_attendance.user_attendance' => function ($query) use ($dated) {
                $query->whereDate('created_at', $dated);
            }
        ])
            ->whereIn('company_id', $user_company_id)
            ->whereIn('id', $user_branch_id)
            ->get();

        $present = 0;
        $late = 0;
        $absent = 0;
        foreach ($branchesWithAttendance as $company) {
            $company_settings = CompanySetting::where('company_id', $company->company_id)->where('branch_id', $company->id)
                ->where('is_deleted', '0')
                ->first();
            $hours = ($company_settings ? $company_settings->half_day : 0);
            $late_time = ($company_settings ? $company_settings->late_time : null);
            if ($company_settings != null && $company_settings != "") {
                $countDays = count(explode(',', $company_settings->days));
            }
            foreach ($company->employee_details_with_attendance as $branchEmployees) {
                if ($branchEmployees->branch_id == $company->id) {
                    if (!$branchEmployees->user_attendance->isEmpty() && $branchEmployees->status == '1') {
                        //present count
                        $present++;
                        //absent count working
                        $firstDayOfMonth = Carbon::create($dated, $dated)->startOfMonth();
                        $endDayOfMonth = Carbon::create($dated, $dated)->endOfMonth();
                        while ($firstDayOfMonth->lte($endDayOfMonth)) {
                            if ($countDays == 5) {
                                if (!($firstDayOfMonth->isweekend())) {
                                    $datesInMonth[] = $firstDayOfMonth->toDateString();
                                }
                            } elseif ($countDays == 6) {
                                if (!($firstDayOfMonth->isSunday())) {
                                    $datesInMonth[] = $firstDayOfMonth->toDateString();
                                }
                            }
                            $firstDayOfMonth->addDay();
                        }
                        foreach ($branchEmployees->user_attendance as $employeeDailyAttendence) {
                            $startTime = $employeeDailyAttendence->check_in;
                            if ($late_time !== null) {
                                if (strtotime($startTime) > strtotime($late_time)) {
                                    $late++;
                                    break;
                                }
                            }
                        }
                        if (count($datesInMonth) != count($branchEmployees->user_attendance)) {
                            $absent++;
                        }
                    }
                }
                $datesInMonth = [];
            }

            $company['present'] = $present;
            $company['late'] = $late;
            $company['absent'] = $absent;
        }

        if (!empty($branchesWithAttendance)) {
            return $this->sendResponse($branchesWithAttendance, 'Graph data fecthed successfully!');
        } else {
            return $this->sendError([], 'Data not found!');
        }
    }

    public function versionHistory()
    {
        $versions = Version_History::select('id', 'version', 'reason', 'type')->orderBy('id', 'desc')->paginate(20);
        if ($versions) {
            return $this->sendResponse($versions, 'App version history', 200);
        } else {
            return $this->sendResponse($versions, 'Data not found!', 200);
        }
    }

    public function saveVersion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'version' => 'required',
            'reason' => 'required',
            'type' => 'required'
        ]);
        if ($validate->fails()) {
            return $this->sendError([], $validate->errors(), 400);
        }
        $version = new Version_History;
        $version->version = $request->version;
        $version->reason = $request->reason;
        $version->type = $request->type;
        $version->save();
        $msg = 'Version "' . ucwords($request->type) . ' ' . ucwords($request->version) . '" Added Successfully';
        createLog('version_action', $msg);
        if ($version) {
            return $this->sendResponse($version, 'App version saved successfully!', 200);
        } else {
            return $this->sendError([], 'App version not saved succesfully!', 500);
        }
    }

    public function viewVersion(Request $request)
    {
        $appVersion = Version_History::where('id', $request->version_id)->first();
        if ($appVersion) {
            return $this->sendResponse($appVersion, 'App version fetched successfully!', 200);
        } else {
            return $this->sendResponse([], 'Data not found!', 200);
        }
    }

    public function attendanceGraphPresent(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $branch_id = explode(',', $user->branch_id);
        $dateSearch = $request->input('dateSearch', Carbon::now()->toDateString());

        $karachiCount = 0;
        $lahoreCount = 0;
        $islamabadCount = 0;
        $noBranch = 0;

        if ($user_role == 1) {
            $query = DB::table('user_daily_records')
                ->leftJoin('employee_details', 'employee_details.id', '=', 'user_daily_records.emp_id')
                ->select('dated', 'employee_details.company_id', 'employee_details.branch_id')
                ->where('dated', $dateSearch)
                ->where('present', '1')
                ->get();
        } else {
            $query = DB::table('user_daily_records')
                ->leftJoin('employee_details', 'employee_details.id', '=', 'user_daily_records.emp_id')
                ->select('dated', 'employee_details.company_id', 'employee_details.branch_id')
                ->where('dated', $dateSearch)
                ->where('present', '1')
                ->whereIn('employee_details.branch_id', $branch_id)
                ->get();
        }

        foreach ($query as $items) {
            if (in_array($items->branch_id, [6, 7])) {
                $lahoreCount++;
            } elseif (in_array($items->branch_id, [8, 11])) {
                $karachiCount++;
            } elseif ($items->branch_id == 9) {
                $islamabadCount++;
            }
        }
        $response = [];

        if ($karachiCount > 0) {
            $response['karachiCount'] = $karachiCount;
        }
        if ($lahoreCount > 0) {
            $response['lahoreCount'] = $lahoreCount;
        }
        if ($islamabadCount > 0) {
            $response['islamabadCount'] = $islamabadCount;
        }

        return response()->json($response);
    }

    public function attendanceGraphAbsent(Request $request)
    {
        $user = auth()->user();
        $user_role = $user->role_id;
        $branch_id = explode(',', $user->branch_id);
        $dateSearch = $request->input('dateSearch', Carbon::now()->toDateString());

        $branchCounts = [
            'karachi' => 0,
            'lahore' => 0,
            'islamabad' => 0,
        ];

        $query = DB::table('employee_details')
                    ->leftJoin('user_daily_records', 'employee_details.id', '=', 'user_daily_records.emp_id')
                    ->select('user_daily_records.dated', 'employee_details.company_id', 'employee_details.branch_id', 'employee_details.emp_id')
                    ->where('user_daily_records.dated', $dateSearch)
                    ->where('employee_details.status', 1)
                    ->where('employee_details.is_active', 1)
                    ->where('employee_details.is_deleted', 0);

        if ($user_role != 1) {
            $query->whereIn('employee_details.branch_id', $branch_id);
        }

        $employees = $query->get();

        foreach ($query as $item) {
            if (in_array($item->branch_id, [6, 7])) {
                $branchCounts['lahore']++;
            } elseif (in_array($item->branch_id, [8, 11])) {
                $branchCounts['karachi']++;
            } elseif ($item->branch_id == 9) {
                $branchCounts['islamabad']++;
            }
        }

        $response = array_filter($branchCounts);

        return response()->json($response);
    }

}
