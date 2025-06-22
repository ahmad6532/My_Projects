<?php

namespace App\Http\Controllers\API;

use App\Models\AttendanceDetail;
use DateTime;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\UserAttendence;
use App\Models\CompanySetting;
use App\Models\EmployeeDetail;
use App\Models\MonthlyAttendance;
use App\Models\UserMonthlyRecord;
use App\Models\UserDailyRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class AttendenceController extends BaseController
{

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function addAttendance(Request $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            $image = '';
            $remarks = null;
            $device_type = isset($input['device_type']) ? $input['device_type'] : '';
            $mark_status = isset($input['attendance_type']) ? $input['attendance_type'] : null;
            if (
                isset($input['fingerprint']) && $input['fingerprint'] != null && $input['fingerprint'] != '' ||
                isset($input['pin']) && $input['pin'] != null && $input['pin'] != '' ||
                isset($input['emp_id']) && $input['emp_id'] != null && $input['emp_id'] != ''
            ) {

                if (isset($input['fingerprint'])) {

                    $fingerprintValue = (float) $input['fingerprint'];

                    $allEmployees = EmployeeDetail::where('status', '1')->get();
                    $empDetail = [];

                    foreach ($allEmployees as $employee) {
                        $fingerprintArray = json_decode($employee->fingerprint, true);

                        if (is_array($fingerprintArray) && in_array($fingerprintValue, $fingerprintArray)) {

                            $empDetail = $employee;

                        } elseif (!is_array($fingerprintArray) && $fingerprintValue == $fingerprintArray) {

                            $empDetail = $employee;
                            break;
                        }
                    }
                    if ($device_type == 'mobile') {
                        $remarks = 9;
                    } elseif ($device_type == 'tablet') {
                        $remarks = 6;
                    } else {
                        $remarks = 6;
                    }
                }

                if (isset($input['pin'])) {
                    $empDetail = EmployeeDetail::where('attend_pin', $input['pin'])->where('status', '1')->first();
                    if (!$empDetail) {
                        return response()->json(['success' => 0, 'message' => 'Please enter valid pin']);
                    }
                    if ($device_type == '10') { // 10 for mobile
                        $remarks = 10;
                    } elseif ($device_type == '7') { // 7 for tablet
                        $remarks = 7;
                    } else {
                        $remarks = 7;
                    }
                }

                if (isset($input['emp_id'])) {
                    $empDetail = EmployeeDetail::where('emp_id', $input['emp_id'])->where('status', '1')->first();
                    if (!$empDetail) {
                        return response()->json(['success' => 0, 'message' => 'Please Enter Valid Data']);
                    }
                    if ($device_type == '10') { // 10 for mobile
                        $remarks = 10;
                    } elseif ($device_type == '7') { // 7 for tablet
                        $remarks = 7;
                    } else {
                        $remarks = 7;
                    }
                }
            } else {
                return response()->json(['success' => 0, 'message' => 'Please enter valid data']);
            }

            // if(!is_null($input['lati']) && !is_null($input['longi']) && $input['lati'] != 0 && $input['longi'] != 0) {
            // $Location = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $input['lati'] . ',' . $input['longi'] . '&key=AIzaSyAfh-Jh-Vn1Lf2TeP9g9cf5bzRbX1gnFZ4');
            // $Location = json_decode($Location);

            //     if (isset($Location) && isset($Location->results) && $Location->status != "ZERO_RESULTS") {

            //         $LocationDetails = $Location->results[0]->address_components;
            // $plantlocation = $Location->results[0]->formatted_address;

            // foreach ($LocationDetails as $key => $address) {

            // if ($address->types[0] === "administrative_area_level_2") {

            // $city = $address->long_name;

            // } else if ($address->types[0] === "administrative_area_level_3") {

            // $city = $address->long_name;

            // } else if ($address->types[0] === "locality") {

            // $city = $address->long_name;

            // }
            //             if ($address->types[0] === "administrative_area_level_1") {

            // $province = $address->long_name;
            // }
            //         }
            //     }
            // }else {
            $city = '';
            $province = '';
            $plantlocation = 'null';
            // }

            if ($mark_status == '0') {
                $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $input['time']);
            } else {
                $dateTime = Carbon::now();
            }
            $date = $dateTime->toDateString();
            $time = $dateTime->toTimeString();

            $company_detail = CompanySetting::where('branch_id', $empDetail->branch_id)
                ->where('company_id', $empDetail->company_id)
                ->where('is_deleted', '0')
                ->first();
            $workingDays = $company_detail ? explode(',', strtolower($company_detail->days)) : [];

            $match_date = Carbon::parse($date);
            $day_in_eng = $match_date->format('l');
            if (!in_array(strtolower($day_in_eng), $workingDays)) {
                $response['success'] = '0';
                $response['message'] = 'You cannot mark attendance on weekend';
                return $response;
            }
            $holidays = Holiday::whereRaw("FIND_IN_SET(?, branch_id)", [$empDetail->branch_id])
                ->whereRaw("FIND_IN_SET(?, company_id)", [$empDetail->company_id])
                ->where('is_deleted', '0')
                ->where('is_active', '1')
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->count();

            if ($holidays > 0) {
                $response['success'] = '0';
                $response['message'] = 'You cannot mark attendance on holiday';
                return $response;
            }
            if ($empDetail) {

                // check employee attendance
                $empAttendence = UserDailyRecord::where('emp_id', $empDetail->id)->whereDate('dated', $date)->first();
                if (!$empAttendence) {
                    if ($request->hasFile('user_image')) {
                        $uploadPath = 'attendance/users/';
                        $current_month = Carbon::parse($date)->format('Y-m');
                        $targetFolder = $uploadPath . $current_month;
                        $path = $targetFolder . '/';

                        if (is_dir($targetFolder)) {
                            $file = $request->file('user_image');
                            $ext = $file->getClientOriginalExtension();
                            $filename = time() . '_' . $empDetail->id . '.' . $ext;
                            $file->move($path, $filename);
                            $image = $path . $filename;
                        } else {
                            $file = $request->file('user_image');
                            $ext = $file->getClientOriginalExtension();
                            $filename = time() . '_' . $empDetail->id . '.' . $ext;
                            $file->move($path, $filename);
                            $image = $path . $filename;
                        }
                    }
                    $user_entery = UserDailyRecord::create([
                        'emp_id' => $empDetail->id,
                        'dated' => $date,
                        'check_in' => $time,
                        'check_out' => null,
                        'present' => '1',
                        'pull_time' => null,
                        'leave' => null,
                        'leave_type' => null,
                        'working_hours' => 0,
                        'device_serial_no' => $input['serial_no'] ?? null,
                        'check_in_type' => $remarks,
                        'check_out_type' => null,
                        'check_in_ip' => $input['ip_address'],
                        'check_out_ip' => null,
                        'mark_in_status' => $mark_status,
                        'mark_out_status' => null
                    ]);
                    if ($image || $input['lati']) {
                        AttendanceDetail::create([
                            'daily_record_id' => $user_entery->id,
                            'check_in_lati' => $input['lati'] ?? null,
                            'check_out_longi' => null,
                            'check_in_address' => $plantlocation,
                            'check_out_address' => null,
                            'check_in_image' => $image,
                            'check_out_image' => null,
                        ]);
                    }

                    // $company_setting = CompanySetting::where('company_id', $empDetail->company_id)
                    //     ->where('branch_id', $empDetail->branch_id)
                    //     ->where('is_deleted', '0')
                    //     ->first();
                    // $late_time = Carbon::parse($company_setting->late_time);
                    // $check_in = Carbon::parse($time);

                    // if ($late_time < $check_in) {
                    //     dd('We will send notification on monday');
                    // }

                } else if ($empAttendence && $empAttendence->check_in !== null && $empAttendence->check_in !== $time) {

                    if ($request->hasFile('user_image')) {
                        $uploadPath = 'attendance/users/';
                        $current_month = Carbon::parse($date)->format('Y-m');
                        ;
                        $targetFolder = $uploadPath . $current_month;
                        $path = $targetFolder . '/';

                        if (is_dir($targetFolder)) {
                            $file = $request->file('user_image');
                            $ext = $file->getClientOriginalExtension();
                            $filename = time() . '_' . $empDetail->id . '.' . $ext;
                            $file->move($path, $filename);
                            $image = $path . $filename;
                        } else {
                            $file = $request->file('user_image');
                            $ext = $file->getClientOriginalExtension();
                            $filename = time() . '_' . $empDetail->id . '.' . $ext;
                            $file->move($path, $filename);
                            $image = $path . $filename;
                        }
                    }
                    $user_entery = UserDailyRecord::where('emp_id', $empDetail->id)->whereDate('dated', $date)->first();
                    $check_in_time = new DateTime($user_entery->check_in);
                    $check_out_time = new DateTime($time);
                    $difference = $check_in_time->diff($check_out_time);
                    $total_hours = $difference->h + $difference->i / 60;
                    $total_hours = number_format($total_hours, 1);


                    $user_entery->update([
                        'check_out' => $time ?? null,
                        'working_hours' => $total_hours,
                        'check_out_type' => $remarks,
                        'check_out_ip' => $input['ip_address'],
                        'mark_out_status' => $mark_status
                    ]);
                    if ($image || $input['longi']) {
                        AttendanceDetail::where('daily_record_id', $user_entery->id)->whereDate('created_at', $date)
                            ->update([
                                'check_out_longi' => $input['longi'] ?? null,
                                'check_out_address' => $plantlocation,
                                'check_out_image' => $image,
                            ]);
                    }

                }
                DB::commit();
                $response['success'] = '1';
                $response['message'] = 'Attendance Added Successfully.';
                $response['emp_name'] = $empDetail->emp_name;
                return $response;
            } else {
                $response['success'] = '0';
                $response['message'] = 'Attendance Not Added.';
                $response['emp_name'] = null;
                return $response;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $response['success'] = '0';
            $response['message'] = $e->getMessage();
            $response['emp_name'] = null;
            return $response;
        }
    }

    public function getAllAttendances()
    {
        $data = UserDailyRecord::all();

        if ($data) {
            return $this->sendResponse($data, "Attendance data fetched successfully!");
        } else {
            return $this->sendResponse([], "Attendance data not fetched!");
        }

    }


    public function hoursIntoMin($hours_data)
    {
        $hours_data = (float) $hours_data;
        $total_hours = floor($hours_data);
        $total_minutes = round(($hours_data - $total_hours) * 60);
        return sprintf("%02dh : %02dm", $total_hours, $total_minutes);
    }
    public function getUserMonthlyAttendances(Request $request)
    {
        $user = Auth::user();
        $currentMonth = isset($request->year_month) ? date('Y-m', strtotime($request->year_month)) : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        $attendanceData = [];

        if ($request->filter == 'monthly') {
            $monthlyAttendance = $this->calculateMonthlyAttendance($user, $year, $month);

            $collection = [
                'working_hours' => $this->hoursIntoMin($monthlyAttendance['working_hours']),
                'presents' => $monthlyAttendance['presents'],
                'leaves' => $monthlyAttendance['leaves'],
                'absents' => $monthlyAttendance['absents'],
                'holidays' => $monthlyAttendance['holidays'],
                'halfday' => $monthlyAttendance['halfday'],
                'late' => $monthlyAttendance['late'],
            ];
            $attendance = $monthlyAttendance['attendance'];
            return response()->json([
                'status' => 1,
                'message' => 'Attendance Fetched Successfully!',
                'details' => $collection,
                'attendance' => $attendance
            ]);
        } elseif ($request->filter == 'yearly') {
            $yearlyData = [];
            $labels = [
                'working_hours' => 0,
                'presents' => 0,
                'leaves' => 0,
                'absents' => 0,
                'holidays' => 0,
                'halfday' => 0,
                'late' => 0,
            ];

            for ($i = 1; $i <= 12; $i++) {
                $monthlyAttendance = $this->calculateMonthlyAttendance($user, $year, $i);

                $yearly_Data = [
                    'month' => $monthlyAttendance['month'],
                    'month_in_count' => sprintf('%02d', $monthlyAttendance['month_in_count']),
                    'working_hours' => $monthlyAttendance['working_hours'],
                    'presents' => $monthlyAttendance['presents'],
                    'leaves' => $monthlyAttendance['leaves'],
                    'absents' => $monthlyAttendance['absents'],
                    'holidays' => $monthlyAttendance['holidays'],
                    'halfday' => $monthlyAttendance['halfday'],
                    'late' => $monthlyAttendance['late'],
                ];
                array_push($yearlyData, $yearly_Data);

                // Summing up yearly totals for labels
                $labels['working_hours'] += $monthlyAttendance['working_hours'];
                $labels['presents'] += $monthlyAttendance['presents'];
                $labels['leaves'] += $monthlyAttendance['leaves'];
                $labels['absents'] += $monthlyAttendance['absents'];
                $labels['holidays'] += $monthlyAttendance['holidays'];
                $labels['halfday'] += $monthlyAttendance['halfday'];
                $labels['late'] += $monthlyAttendance['late'];
            }

            $attendanceData = $yearlyData;
            return response()->json([
                'status' => 1,
                'message' => 'Attendance Fetched Successfully!',
                'details' => $attendanceData,
                'labels' => [
                    'working_hours' => $labels['working_hours'],
                    'presents' => $labels['presents'],
                    'leaves' => $labels['leaves'],
                    'absents' => $labels['absents'],
                    'holidays' => $labels['holidays'],
                    'halfday' => $labels['halfday'],
                    'late' => $labels['late'],
                ]
            ]);

        }
    }

    private function calculateMonthlyAttendance($user, $year, $month)
    {
        $number_of_days = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $start_date = Carbon::create($year, $month, 1)->format('Y-m-d');

        // Check for role 1 to decide filtering conditions
        $query = EmployeeDetail::with([
            'empToDailyRecord' => function ($query) use ($month, $year) {
                $query->whereMonth('dated', $month)
                    ->whereYear('dated', $year);
            },
            'resignations',
            'approval',
            'leaves',
            'terminations',
            'holidays'
        ])->select('employee_details.id', 'employee_details.emp_id', 'employee_details.emp_name', 'employee_details.company_id', 'employee_details.branch_id');

        // Apply filters only if role is not 1
        if ($user->role_id != 1) {
            $query->where('id', $user->emp_id);
            //   ->where('employee_details.company_id', $user->company_id)
            //   ->where('employee_details.branch_id', $user->branch_id);
        }

        $employee = $query->where('employee_details.is_deleted', '0')
            ->where('employee_details.status', '1')
            ->first();

        $absent_count = 0;
        $leave_count = 0;
        $holiday_count = 0;
        $present_count = 0;
        $WorkingHours = 0;
        $halfday_count = 0;
        $late_count = 0;
        $dailyRecords = [];

        // Determine working days based on company details if role is not 1
        $workingDays = [];
        if ($user->role_id != 1) {
            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id', $employee->company_id)
                ->where('is_deleted', '0')
                ->first();
            $workingDays = $company_detail ? explode(',', strtolower($company_detail->days)) : [];
        }

        $employeeAttendance = $employee->empToDailyRecord;

        for ($i = 0; $i < $number_of_days; $i++) {
            $date = Carbon::parse($start_date)->addDays($i)->format('Y-m-d');
            $match_date = Carbon::parse($date);
            $day_eng = $match_date->format('l');
            $attendanceStatus = '-';

            $leaveStatus = '';
            // Skip future dates
            if ($match_date->greaterThan(Carbon::today())) {
                $dailyRecords[] = [
                    'day' => $i + 1,
                    'working_hours' => '',
                    'check_in' => '',
                    'check_out' => '',
                    'leave' => '',
                    'date' => $match_date->format('j M Y'),
                    'day_eng' => $day_eng,
                ];
                continue;
            }

            // Check if it's a weekend (only for non-role-1 users)
            if ($user->role_id != 1 && !in_array(strtolower($day_eng), $workingDays)) {
                $attendanceStatus = 'Weekend';
                $leaveStatus = 'Weekend';
            } else {
                // Process holidays and attendance records
                $eligibleHolidays = Holiday::where('is_deleted', '0')
                    ->where('is_active', '1')
                    ->get();

                foreach ($eligibleHolidays as $holiday) {
                    if (
                        ($user->role_id == 1 ||
                            (in_array($employee->company_id, explode(',', $holiday->company_id)) &&
                                in_array($employee->branch_id, explode(',', $holiday->branch_id)))) &&
                        Carbon::parse($holiday->start_date)->lte($match_date) &&
                        Carbon::parse($holiday->end_date)->gte($match_date)
                    ) {
                        $holiday_count++;
                        $attendanceStatus = 'Holiday';
                        $leaveStatus = 'Holiday';
                        break;
                    }
                }

                // Process attendance
                foreach ($employeeAttendance as $dailyAttendance) {
                    if ($date == $dailyAttendance->dated) {
                        if ($dailyAttendance->leave && $dailyAttendance->leave_type != null) {
                            $leave_count++;
                            $leaveStatus = 'Leave';
                            $attendanceStatus = ucfirst($dailyAttendance->dailyRecordToLeaveType->types);
                        } elseif ($dailyAttendance->check_in && $dailyAttendance->check_out) {
                            $WorkingHours += $dailyAttendance->working_hours;
                            $attendanceStatus = 'Present';
                            $leaveStatus = 'Present';
                            $present_count++;

                        }
                        break;
                    }
                }
            }

            if ($attendanceStatus == '-' && !in_array(strtolower($day_eng), ['saturday', 'sunday'])) {
                $attendanceStatus = 'Absent';
                $leaveStatus = 'Absent';
                $absent_count++;
            }

            $dailyRecords[] = [
                'day' => $i + 1,
                'working_hours' => $attendanceStatus == 'Present' ? $this->hoursIntoMin($dailyAttendance->working_hours) : '',
                'check_in' => $dailyAttendance->check_in ?? '',
                'check_out' => $dailyAttendance->check_out ?? '',
                'leave' => $leaveStatus,
                'date' => $match_date->format('j M Y'),
                'day_eng' => $day_eng,
            ];
        }

        return [
            'attendance' => $dailyRecords,
            'month' => Carbon::createFromDate($year, $month, 1)->format('F'),
            'month_in_count' => $month,
            'working_hours' => number_format($WorkingHours, 1),
            'presents' => $present_count,
            'leaves' => $leave_count,
            'absents' => $absent_count,
            'holidays' => $holiday_count,
            'halfday' => $halfday_count,
            'late' => $late_count,
        ];
    }


    public function getUserDailyAttendances(Request $request)
    {
        $user = Auth::user();
        $employee_data = EmployeeDetail::where('id', $user->emp_id)->first();
        $company_data = CompanySetting::where('company_id', $user->company_id)
            ->where('branch_id', $employee_data->branch_id)
            ->where('is_deleted', '0')
            ->first();
        $company_start = new DateTime($company_data->start_time);
        $company_end = new DateTime($company_data->end_time);
        $difference = $company_start->diff($company_end);
        $actualWorkingHours = sprintf("%02dh:%02dm", $difference->h, $difference->i);

        $user_daily_record = [];
        $getDashboard = [];
        $user_daily_record = UserDailyRecord::where('emp_id', $user->emp_id)
            ->where('dated', Carbon::today())
            ->orderBy('dated', 'asc')
            ->get();
        foreach ($user_daily_record as $data) {
            $key = $data->dated;

            $check_in_time = new DateTime($data->check_in);
            $out_time = new DateTime($data->check_out);
            $check_out_time = $out_time->modify('+12 hours');
            $diff = $check_in_time->diff($check_out_time);
            $WorkingHours = sprintf("%02dh:%02dm", $diff->h, $diff->i);
            $getDashboard[$key] = [
                'check_in' => $data->check_in,
                'check_out' => $data->check_out,
                'dated' => $data->dated,
                'present' => $data->present ?? '0',
                'leave' => $data->leave ?? '0',
                'late_coming' => $data->late_coming ?? '0',
                'holiday' => $data->holiday ?? '0',
                'weekend' => $data->weekend ?? '0',
                'actual_working_hours' => $actualWorkingHours,
                'working_hours' => $WorkingHours,
            ];
        }
        $dashboardData = $getDashboard;

        if ($dashboardData) {
            return $this->sendResponse($dashboardData, 'Daily Attendance Fetched Successfully!');
        } else {
            return $this->sendResponse(null, 'Daily Attendance Not Fetched!');
        }
    }
}
