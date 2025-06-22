<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use App\Models\CompanySetting;
use App\Models\UserAttendence;
use App\Models\UserMonthlyRecord;
use App\Models\UserDailyRecord;
use App\Models\Setting;
use App\Models\UserDevice;
use App\Models\NotificationEmail;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\Holiday;
use Carbon\Carbon;
use DateTime;

class CronsJobController extends Controller
{
    public function updateUserMonthlyRecord(Request $request,$postDate=null)
    {
        $searched_date = isset($postDate) ? Carbon::parse($postDate)->format('Y-m') : Carbon::now()->format('Y-m');
        $carbonDate = Carbon::parse($searched_date);
        $month = $carbonDate->month;
        $year = $carbonDate->year;

        $employees = EmployeeDetail::with([
                'user_attendance' => function ($query) use ($searched_date) {
                    $query->where('created_at', 'LIKE', $searched_date . '%');
                }
            ])
            ->with('approval','leaves','holidays','resignations','terminations')
            ->select('employee_details.id','employee_details.branch_id','employee_details.company_id')
            ->where('employee_details.is_deleted', '0')
            // ->where('employee_details.status', '1')
            ->get();

        $attendanceData = [];
        $currentDate = [];

        foreach ($employees as $key => $employee) {
            $weekendCount = 0;
            $presentCount = 0;
            $absentCount = 0;
            $lateCount = 0;
            $leaveCount = 0;
            $halfCount = 0;
            $holidayCount = 0;
            $totalWorkingDays = 0;
            $actualWorkingHours = 0;
            $totalWorkingHours = 0;

            $holidayArray = [];
            $weekends = [];
            $leavesArray = [];

            //    resignation record
            $empResignation = $employee->resignations ? $employee->resignations->is_approved : null;
            $resignationDate = $empResignation == '1' ? Carbon::parse($employee->resignations->resignation_date)->addDay() : null;

            // termination record
            $empTermination = $employee->terminations ? $employee->terminations->is_approved : null;
            $terminationDate = $empTermination == '1' ? Carbon::parse($employee->terminations->termination_date)->addDay() : null;

            //get joining date
            $joining_date = $employee->approval ? Carbon::parse($employee->approval->joining_date)->subDay() : null;

            //get company settings
            $company_detail = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('company_id',$employee->company_id)
                ->where('is_deleted', '0')
                ->first();

            $workingDays = ($company_detail ? explode(',', strtolower($company_detail->days)) : []);

            $start_time = ($company_detail ? $company_detail->start_time : Carbon::parse('00:00:00')->format('H:i:s'));
            $end_time = ($company_detail ? $company_detail->end_time : Carbon::parse('00:00:00')->format('H:i:s'));
            $start = new DateTime($start_time);
            $end = new DateTime($end_time);
            $diff = $start->diff($end);
            $totalWorkingHours = $diff->h * 60 + $diff->i;
            //to get days of searched month according to the year
            $currentMonth = Carbon::create($year, $month)->endOfMonth();

            $data = UserAttendence::where('emp_id', $employee->id)
            ->whereDate('created_at', 'LIKE', Carbon::parse($currentMonth)->format('Y-m') . '%')
            ->whereNotNull('check_in')
            ->get();

            //make loop to make attendance
            for ($day = 1; $day <= $currentMonth->day; $day++) {
                //make date from start of month
                $currentDate = Carbon::create($currentMonth->year, $currentMonth->month, $day);

                if (!in_array(strtolower($currentDate->format('l')), $workingDays)) {
                    $weekends[$currentDate->toDateString()] = 'weekend';
                } else {
                    $totalWorkingDays++;
                }

                //get holidays
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
                            if ($startDate->month == $currentMonth->month && $startDate->year == $currentMonth->year) {
                                $holidayArray[$startDate->toDateString()] = 'Holiday';
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

                if ($joining_date !== null && strtotime($currentDate->toDateString()) <= strtotime($joining_date)) {
                    $newJoining[$currentDate->toDateString()] = 'newJoining';
                } elseif ($resignationDate !== null && strtotime($currentDate->toDateString()) >= strtotime($resignationDate)) {
                    $resignedDates[$currentDate->toDateString()] = 'resigned';
                } elseif ($terminationDate !== null && strtotime($currentDate->toDateString()) >= strtotime($terminationDate)) {
                    $terminatedDates[$currentDate->toDateString()] = 'terminate';
                } else {
                    $attendanceRecord = $data->where('created_at', '>=', $currentDate->startOfDay())
                        ->where('created_at', '<=', $currentDate->endOfDay())
                        ->first();

                    if (!$attendanceRecord) {
                        if ($currentDate->isWeekend() && !in_array(strtolower($currentDate->format('l')), $workingDays)) {
                            $weekendCount++;
                            continue;
                        } elseif (count($holidayArray) > 0 && in_array($currentDate->toDateString(), array_keys($holidayArray))) {
                            $holidayCount++;
                            continue;
                        } elseif (count($leavesArray) > 0 && in_array($currentDate->toDateString(), array_keys($leavesArray))) {
                            $leaveCount++;
                        } elseif ($company_detail && $currentDate <= now()->startOfDay()) {
                            $absentCount++;
                        }
                    } else {
                        if ($attendanceRecord->check_in != null && $attendanceRecord->check_out != null) {
                            // Attendance record is present, calculate actual working hours
                            $startTime = Carbon::parse($attendanceRecord->check_in);
                            $endTime = Carbon::parse($attendanceRecord->check_out);
                            $duration = $endTime->diffInMinutes($startTime);
                            $actualWorkingHours += $duration;
                            // Check if it's a half-day leave
                            $half_day_minutes = ($company_detail ? $company_detail->half_day : 0) * 60;
                            if ($duration <= $half_day_minutes) {
                                $halfCount++;
                            }

                            // Check for late coming
                            $late_time = ($company_detail ? $company_detail->late_time : Carbon::parse('00:00:00')->format('H:i:s'));
                            if (strtotime($startTime) >= strtotime($late_time)) {
                                $lateCount++;
                            }

                            $presentCount++;

                        }elseif ($attendanceRecord->check_in != null && $attendanceRecord->check_out == null) {
                            $presentCount++;
                        }
                    }
                }

            }
            // echo 'emp('.$employee->id.')',$presentCount, $absentCount, $halfCount,$leaveCount;
            // $totaldays = $presentCount + $absentCount + $leaveCount;
            $totalWorkingHours *= $presentCount;

            $attendanceData[] = [
                'employee_id' => $employee->id,
                'company_id' => $employee->company_id,
                'branch_id' => $employee->branch_id,
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'half_count' => $halfCount,
                'weekend_count' => $weekendCount,
                'leave_count' => $leaveCount,
                'holiday_count' => $holidayCount,
                'late_count' => $lateCount,
                'total_working_days' => $totalWorkingDays,
                'WorkingHours' => $totalWorkingHours,
                'ActualworkingHours' => $actualWorkingHours,
            ];
        }

        foreach($attendanceData as $arrayData){
            $user_monthly_record = UserMonthlyRecord::where('emp_id',$arrayData['employee_id'])
                                        ->where('year',Carbon::parse($searched_date)->format('Y'))
                                        ->where('month_of',Carbon::parse($searched_date)->format('m'))
                                        ->first();

            if(!$user_monthly_record){
                $create_record = new UserMonthlyRecord();
                $create_record->company_id = $arrayData['company_id'];
                $create_record->branch_id = $arrayData['branch_id'];
                $create_record->emp_id = $arrayData['employee_id'];
                $create_record->month_of = Carbon::parse($searched_date)->format('m');
                $create_record->year = Carbon::parse($searched_date)->format('Y');
                $create_record->presents = $arrayData['present_count'];
                $create_record->absents = $arrayData['absent_count'];
                $create_record->late_comings = $arrayData['late_count'];
                $create_record->leaves = $arrayData['leave_count'];
                $create_record->holidays = $arrayData['holiday_count'];
                $create_record->half_leaves = $arrayData['half_count'];
                $create_record->actual_working_hours = $arrayData['ActualworkingHours'];
                $create_record->working_hours = $arrayData['WorkingHours'];
                $create_record->working_days = $arrayData['total_working_days'];
                $create_record->save();
            }else{
                $user_monthly_record->presents = $arrayData['present_count'];
                $user_monthly_record->absents = $arrayData['absent_count'];
                $user_monthly_record->late_comings = $arrayData['late_count'];
                $user_monthly_record->leaves = $arrayData['leave_count'];
                $user_monthly_record->holidays = $arrayData['holiday_count'];
                $user_monthly_record->half_leaves = $arrayData['half_count'];
                $user_monthly_record->actual_working_hours = $arrayData['ActualworkingHours'];
                $user_monthly_record->working_hours = $arrayData['WorkingHours'];
                $user_monthly_record->working_days = $arrayData['total_working_days'];
                $user_monthly_record->update();
            }
        }

        return "Monthly Attendance updated";
    }

    public static function updateUserDailyAttendance($postDate=null)
    {
        $dated = Carbon::parse($postDate)->format('Y-m-d');
        $searched_date = isset($dated) ? Carbon::parse($dated)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $employees = EmployeeDetail::with([
            'user_attendance' => function ($query) use ($searched_date) {
                    $query->whereDate('created_at', 'LIKE', $searched_date . '%');
                }
            ])
            ->with('resignations', 'approval', 'terminations','leaves', 'holidays')
            ->select('employee_details.id','employee_details.company_id','employee_details.branch_id')
            ->where('employee_details.is_deleted', '0')
            // ->where('employee_details.status','1')
            ->get();

        foreach ($employees as $employee) {
            $absentCount = 0;
            $presentCount = 0;
            $LateCount = 0;
            $LeaveCount = 0;
            $weekendCount = 0;
            $holidaysCount = 0;
            $halfDaysCount = 0;
            $company_duration = 0;
            $duration = 0;
            $check_in = null;
            $check_out = null;
            $is_newJoining = 0;
            $is_resigned = 0;
            $is_terminated = 0;

            $holidaysArray = [];
            $leavesArray = [];

            // resignation record
            $empResignation = $employee->resignations ? $employee->resignations->is_approved : null;
            $resignationDate = $empResignation == '1' ? Carbon::parse($employee->resignations->resignation_date)->addDay() : null;

            // termination record
            $empTermination = $employee->terminations ? $employee->terminations->is_approved : null;
            $terminationDate = $empTermination == '1' ? Carbon::parse($employee->terminations->termination_date)->addDay() : null;

            $joining_date = $employee->approval ? Carbon::parse($employee->approval->joining_date)->subDay() : null;

            $company_setting = CompanySetting::where('branch_id', $employee->branch_id)
                ->where('is_deleted', '0')
                ->first();

            $workingDays = ($company_setting ? explode(',', strtolower($company_setting->days)) : []);

            if($company_setting){
                $start_time = Carbon::parse($company_setting->start_time);
                $end_time = Carbon::parse($company_setting->end_time);
                $company_duration = $end_time->diffInMinutes($start_time);
            }

            //get holidays
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
                        if ($startDate->month == Date('m', strtotime($searched_date)) && $startDate->year == Date('Y', strtotime($searched_date))) {
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

            $attendanceRecord = UserAttendence::where('emp_id', $employee->id)
                ->whereDate('created_at', 'LIKE', $searched_date . '%')->whereNotNull('check_in')
                ->first();

            //if user attendance of today is empty
            if (!$attendanceRecord) {
                if ($joining_date !== null && strtotime(Carbon::parse($searched_date)->toDateString()) <= strtotime($joining_date)) {
                    $is_newJoining = 1;
                }elseif ($resignationDate !== null && strtotime(Carbon::parse($searched_date)->toDateString()) >= strtotime($resignationDate)) {
                    $is_resigned = 1;
                }elseif ($terminationDate !== null && strtotime(Carbon::parse($searched_date)->toDateString()) >= strtotime($terminationDate)) {
                    $is_terminated = 1;
                }elseif ($company_setting && !in_array(strtolower(Carbon::parse($searched_date)->format('l')), $workingDays)){
                    $weekendCount = 1;
                }elseif (array_key_exists($searched_date, $holidaysArray)) {
                    //increment in holidays
                    $holidaysCount = 1;
                } elseif (array_key_exists($searched_date, $leavesArray)) {
                    //increment in leaves
                    $LeaveCount = 1;
                } elseif($company_setting) {
                    //increment in absents
                    $absentCount = 1;
                }
            } else {
                if ($attendanceRecord->check_out != null && $attendanceRecord->check_out != '') {
                    $check_out = Carbon::parse($attendanceRecord->check_out);
                    $check_in = Carbon::parse($attendanceRecord->check_in);
                    $duration = $check_out->diffInMinutes($check_in);
                    // convert company half day hours in minutes
                    $halfDayInMinuts = ($company_setting ? $company_setting->half_day : 0)*60;
                    if ($duration <= $halfDayInMinuts) {
                        //increment in half days
                        $halfDaysCount = 1;
                    }
                    $late_time = ($company_setting ? $company_setting->late_time : Carbon::parse('00:00:00')->format('H:i:s'));
                    if (strtotime($check_in) >= strtotime($late_time)) {
                        //increment in late comings
                        $LateCount = 1;
                    }
                    $presentCount = 1;
                }elseif($attendanceRecord->check_in != null && $attendanceRecord->check_out == ''){
                    $presentCount = 1;
                }
            }
            // $totaldays = $presentCount + $absentCount;
            $company_duration *= $presentCount;

            $user_daily_record = UserDailyRecord::where('emp_id',$employee->id)
                ->where('month_of',Carbon::parse($searched_date)->format('m'))
                ->where('year',Carbon::parse($searched_date)->format('Y'))
                ->whereDate('dated',Carbon::parse($searched_date)->format('Y-m-d'))
                ->first();

            if(!$user_daily_record){
                $create_record = new UserDailyRecord();
                $create_record->company_id = $employee->company_id;
                $create_record->branch_id = $employee->branch_id;
                $create_record->emp_id = $employee->id;
                $create_record->month_of = Carbon::parse($searched_date)->format('m');
                $create_record->year = Carbon::parse($searched_date)->format('Y');
                $create_record->check_in = $check_in;
                $create_record->check_out = $check_out;
                $create_record->present = $presentCount;
                $create_record->absent = $absentCount;
                $create_record->late_coming = $LateCount;
                $create_record->leave = $LeaveCount;
                $create_record->holiday = $holidaysCount;
                $create_record->half_leave = $halfDaysCount;
                $create_record->weekend = $weekendCount;
                $create_record->is_new_joining = $is_newJoining;
                $create_record->is_resigned = $is_resigned;
                $create_record->is_terminated = $is_terminated;
                $create_record->actual_working_hours = $duration;
                $create_record->working_hours = $company_duration;
                $create_record->dated = $searched_date;
                $create_record->save();
            }else{
                $user_daily_record->check_in = $check_in;
                $user_daily_record->check_out = $check_out;
                $user_daily_record->present = $presentCount;
                $user_daily_record->absent = $absentCount;
                $user_daily_record->late_coming = $LateCount;
                $user_daily_record->leave = $LeaveCount;
                $user_daily_record->holiday = $holidaysCount;
                $user_daily_record->half_leave = $halfDaysCount;
                $user_daily_record->is_new_joining = $is_newJoining;
                $user_daily_record->is_resigned = $is_resigned;
                $user_daily_record->is_terminated = $is_terminated;
                $user_daily_record->weekend = $weekendCount;
                $user_daily_record->actual_working_hours = $duration;
                $user_daily_record->working_hours = $company_duration;
                $user_daily_record->dated = $searched_date;
                $user_daily_record->update();
            }
        }

        return "Attendance Mark Successfully!";
    }


    public function send_comm_email()
    {
        $number_email = 500;
        $currentDate = now();
        $setting_comm_email = Setting::where('perimeter', 'smtp_from_email')->first();
        $setting_comm_email_name = Setting::where('perimeter', 'smtp_from_name')->first();
        $noti = NotificationEmail::where('email_sent_status', 'N')
            // ->where('schedule_date', '<=', $currentDate)
            ->orderBy('schedule_date', 'DESC')
            ->limit($number_email)
            ->get();

        if ($noti->count() > 0) {
            foreach ($noti as $element) {
                $to_email = $element->to_email;
                $email_subject = $element->email_subject;
                $email_body = $element->email_body;

                    Mail::html($email_body, function ($message) use (
                        $setting_comm_email,
                        $setting_comm_email_name,
                        $to_email,
                        $email_subject
                    ) {
                        $message->from($setting_comm_email->value, $setting_comm_email_name->value)
                                ->to($to_email)
                                ->subject($email_subject);
                    });

                    NotificationEmail::where('id', $element->id)->update([
                        'from_email' => $setting_comm_email->value,
                        'from_name' => $setting_comm_email_name->value,
                        'response' => 'sent successfully!',
                        'email_sent_status' => 'Y',
                        'sent_date' => $currentDate
                    ]);

            }

                return response()->json([

                    'status' => true,
                    'message' => 'Email Send Sucessfully!'

                ]);

        } else {
            return response()->json([

                'status' => false,
                'message' => 'No emails to send!'

            ]);
        }
    }




/**old currently deployed working should be stop after  geting  firebase updated credentails */
/*
    public function send_comm_app_notification()
    {
        $number_noti = 1000;
        // api_key available in:
        // Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = 'AAAAuiFsHdw:APA91bEogsxFmmnTOZy20mLMJ3YpFhn62KcGqSSM1KdTd_TzH5gAbcbksDPwELOMn0yfdZkVW12h27JKHzyge0AfQUYpHyITOra68sAUv-_Cgdtq6BKHUvic7toRkFXGkRmRst9UiF9W';
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $currentDate = date('Y-m-d H:i:s');

        $noti =  Notification::where(('sent_status') ,'=', "N")->whereBetween("schedule_date",[Date('Y-m-d H:i:s',strtotime('-1 days')),$currentDate])->where("is_notification_required", "Y")->orderBy('schedule_date', 'DESC')->limit($number_noti)->get();

        if(count($noti) > 0)
        {
            foreach ($noti as $element)
            {
                #send App notification
                if (($element->is_msg_app) == 'Y') {
                    $title = $element->title;
                    $description =  $element->description;
                    $user_id = $element->user_id;
                    if($element->device_type == 'all'){

                        $q = UserDevice::where('user_id', $user_id)->where('status','=','A')->get();
                    }
                    else {

                        $q = UserDevice::where('user_id', $user_id)->where('status','=','A')->where('platform', $element->device_type)->get();
                    }
                    if (count($q) > 0)
                    {
                        foreach ($q as $row)
                        {
                            // dd($row);
                            $key = $row->token;
                            $headers = array(
                                'Authorization:key=' . $server_key,
                                'Content-Type:application/json'
                            );
                            $fields = array(
                                'to' => $key,
                                'notification' => array('title' => $title, 'body' => $description, 'sound' => 1, 'vibrate' => 1),
                                'data' => array('type' => $element->notification_type, 'title' => $title, 'body' => $description)
                            );

                            $payload = json_encode($fields);
                            $curl_session = curl_init();
                            curl_setopt($curl_session, CURLOPT_URL, $url);
                            curl_setopt($curl_session, CURLOPT_POST, true);
                            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
                            $curlResult = curl_exec($curl_session);
                            $res = json_decode($curlResult, true);
                            // dd($res);
                            if (isset($res['failure']) && $res['failure'] )
                            {
                                $array = $res['results'];
                                $error = $array[0]['error'];
                                DB::table('notification')
                                ->where('id', '=', $element->id)
                                ->update(array('message_error' =>  $error));
                            } else {
                                DB::table('notification')
                                ->where('id', '=', $element->id)
                                ->update(array('message_error' =>  '','sent_status'=>'Y','app_sent_date'=>$currentDate));
                            }
                        }
                    }
                }
            }
            //return response(['success' => 1, 'message' => 'Sending all notifications', 'result' =>true], 200);
            // return true;
        }
    }
*/

    /**new logic of send notification   there are three function needed to be un-comment base64UrlEncode , getFirebaseAccessToken  ,send_comm_app_notification */
    //new  Helper function for Base64Url encoding
    function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    //new method function for fetching the firebase information

    function getFirebaseAccessToken()
    {
        //$serviceAccountFile = '/path to that sdk file placed on server or local atorage  /project sdk file here.json';// Absolute path // Update the path to your service account file

        $serviceAccountFile = base_path('app/Http/Controllers/attendance-51c26-firebase-adminsdk-u77me-4aa7a35f7f.json');
        $serviceAccount = json_decode(file_get_contents($serviceAccountFile), true);

        $clientEmail = $serviceAccount['client_email'];
        $privateKey = $serviceAccount['private_key'];
        // JWT Header
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        // JWT Payload
        $payload = [
            'iss' => $clientEmail,
            'sub' => $clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'iat' => time(),
            'exp' => time() + 3600, // Token expires in 1 hour
        ];

        // Encode Header and Payload to Base64Url
        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));

        // Create Signature
        $signatureInput = $encodedHeader . '.' . $encodedPayload;
        $privateKeyResource = openssl_pkey_get_private($privateKey);
        openssl_sign($signatureInput, $signature, $privateKeyResource, 'SHA256');
        $encodedSignature = $this->base64UrlEncode($signature);

        // Construct JWT
        $jwt = $signatureInput . '.' . $encodedSignature;

        // Request OAuth 2.0 Access Token
        $tokenRequestUrl = 'https://oauth2.googleapis.com/token';
        $tokenRequestBody = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenRequestUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenRequestBody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $responseBody = json_decode($response, true);
        return $responseBody['access_token'] ?? null;
    }

    public function send_comm_app_notification()
    {
            $number_noti = 10;
            $accessToken = $this->getFirebaseAccessToken(); // Get the OAuth 2.0 access token
            $url = 'https://fcm.googleapis.com/v1/projects/vertex-hr/messages:send'; // Replace with your project ID
            $currentDate = date('Y-m-d H:i:s');
           // dd($currentDate);

            $noti = Notification::where('sent_status', '=', 'N')
                                ->whereBetween('schedule_date', [date('Y-m-d H:i:s', strtotime('-1 days')), $currentDate])
                                ->where('is_notification_required', 'Y')
                                ->orderBy('schedule_date', 'DESC')
                                ->limit($number_noti)
                                ->get();
                           // dd($noti);

            if (count($noti) > 0) {
                foreach ($noti as $element) {
                    // if ($element->is_msg_app == 'Y') {
                        $title = $element->title;
                        $description = $element->description;
                        $user_id = $element->user_id;
                        $userDevices = UserDevice::where('user_id', $user_id)
                                                ->where('status', 'A')
                                                // ->when($element->device_type !== 'all', function ($query) use ($element) {
                                                //     return $query->where('platform', $element->device_type);
                                                // })
                                                ->get();
                        if (count($userDevices) > 0) {
                            foreach ($userDevices as $device) {
                                if($device->token){
                                    $key = $device->token;
                                    $headers = [
                                        'Authorization: Bearer ' . $accessToken,
                                        'Content-Type: application/json',
                                    ];
                                    $fields = [
                                        'message' => [
                                            'token' => $key,
                                            'notification' => [
                                                'title' => $title,
                                                'body' => $description,
                                                // 'sound' => 'default',
                                                // 'vibrate' => 1,
                                            ],
                                            'data' => [
                                                'type' => $element->notification_type,
                                                'title' => $title,
                                                'body' => $description,
                                            ],
                                        ],
                                    ];
    
                                    $payload = json_encode($fields);
                                    $curl_session = curl_init();
                                    curl_setopt($curl_session, CURLOPT_URL, $url);
                                    curl_setopt($curl_session, CURLOPT_POST, true);
                                    curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
                                    curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
    
                                    $curlResult = curl_exec($curl_session);
                                    
                                    $res = json_decode($curlResult, true);
    
                                    if (curl_errno($curl_session) || isset($res['error'])) {
                                        $error = isset($res['error']['message']) ? $res['error']['message'] : curl_error($curl_session);
                                        DB::table('notification')
                                            ->where('id', '=', $element->id)
                                            ->update(['message_error' => $error]);
                                    } else {
                                        DB::table('notification')
                                            ->where('id', '=', $element->id)
                                            ->update(['message_error' => '', 'sent_status' => 'Y', 'app_sent_date' => $currentDate]);
                                    }
                                    curl_close($curl_session);
                                }
                            }
                        }
                    // }
                }
            }
    }

}
