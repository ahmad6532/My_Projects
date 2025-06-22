<?php

namespace App\Http\Controllers\API;

use App\Models\UserDailyRecord;
use App\Traits\ProfileImage;
use App\User;
use DateTime;
use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Location;
use App\Models\Holiday;
use App\Models\CompanySetting;
use App\Models\Setting;
use App\Models\Leave_Type;
use App\Models\Designation;
use App\Models\Notification;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Models\Leave_setting;
use App\Models\user_approval;
use App\Models\UserAttendence;
use App\Models\EmployeeDetail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;


class UserController extends BaseController
{
    use HasApiTokens, Notifiable, ProfileImage;

    public function userlist()
    {
        $result = User::where('is_deleted', '0')->get();

        $message = "Users list shown successfully";
        return $this->sendResponse($result, $message);
    }

    public function searchUserbyName(Request $request)
    {
        $result = User::where('is_deleted', '0')->where('fullname', 'LIKE', $request->name . '%')->select('id', 'fullname', 'role_id', 'email', 'is_active', 'is_deleted')->orderBy('id', 'DESC')->get();
        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'User data fetched successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function addnewUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'user_password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            $validator = $validator->errors()->first();

            return $this->sendError('Validation Error.', $validator);
        }
        $Adduser = User::create([
            'role_id' => $request->input('user_role_id'),
            'email' => $request->email,
            'fullname' => $request->user_name,
            'password' => Hash::make($request->user_password),
            'is_active' => $request->input('user_status'),

        ]);
        return $this->sendResponse($Adduser, 'User added successfully.');
    }

    public function viewUserdetails(Request $request)
    {
        $result = User::where('is_deleted', '0')->where('id', $request->id)->get();

        if (isset($result) && count($result) > 0) {
            return $this->sendResponse($result, 'User added successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function editUserDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|unique:users,email,' . $request->id,
            'new_password' => 'nullable|min:6',
            'password_confirmation' => 'same:new_password',
        ]);
        if ($validator->fails()) {
            $validator = $validator->errors()->first();

            return $this->sendError('Validation Error.', $validator);
        }

        $user = User::where('id', $request->id)->first();
        if ($user) {
            if (isset($request->role_id)) {
                $user->role_id = $request->input('role_id');
            }

            if (!empty($request->email)) {
                $user->email = $request->email;
            }
            if (isset($request->user_name)) {
                $user->fullname = $request->user_name;
            }
            if (!empty($request->new_password)) {
                $newpass = Hash::make($request->new_password);
                $user->password = $newpass;
            }
            if (isset($request->user_status)) {
                $user->is_active = $request->input('user_status');
            }
            $user->update();
        }
        if (isset($user)) {
            return $this->sendResponse($user, 'User Profile updated successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }
    }

    public function updateProfileDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_phone' => 'nullable|digits:11',
            'new_password' => 'nullable|min:6',
            'password_confirmation' => 'same:new_password',
        ]);
        if ($validator->fails()) {
            $validator = $validator->errors()->first();

            return $this->sendError('Validation Error.', $validator);
        }
        $input = $request->all();
        $UpdateUser = User::findOrFail($request->id);

        if ($UpdateUser) {
            if (isset($input['user_name'])) {
                $UpdateUser->fullname = $input['user_name'];
            }
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $filename = $file->getClientOriginalName();
                $file->move('assets/images/users/', $filename);
                $image = $filename;
                $UpdateUser->image = $image;
            }
            if (isset($input['user_phone'])) {
                $UpdateUser->phone = $input['user_phone'];
            }
            if (isset($input['new_password'])) {
                $newpass = Hash::make($request->new_password);
                $UpdateUser->password = $newpass;
            }
            $UpdateUser->update();
        }
        if (isset($UpdateUser)) {
            return $this->sendResponse($UpdateUser, 'User Profile updated successfully.');
        } else {
            return $this->sendResponse([], 'No Record found');
        }

    }
    public function deleteUser(Request $request)
    {

        $getid = User::where('id', $request->id)->first();
        if ($getid) {
            $getid->is_deleted = '1';
            $getid->update();
            $message = "User Record deleted successfully!";
            return $this->sendResponse([], $message);
        } else {
            return $this->sendResponse([], "User Record  does not exsit!!!");

        }
    }
    public function getUserProfile()
    {
        $user = Auth::user();
        $getUser = [];
        if ($user->emp_id != null) {
            $getEmp_detail = EmployeeDetail::where('id', $user->emp_id)->first();
            $getUser['branch_name'] = $getEmp_detail->branch->branch_name;
            $getUser['branch_id'] = $getEmp_detail->branch_id;
            $getUser['emp_name'] = $getEmp_detail->emp_name;
            $getUser['emp_email'] = $getEmp_detail->emp_email;
            $getUser['emp_phone'] = (string) $getEmp_detail->emp_phone;
            $getUser['report_to'] = '';
            $getUser['emp_id'] = (string) $getEmp_detail->emp_id;
            $getUser['image'] = $this->imgFunc($getEmp_detail->emp_image, $getEmp_detail->emp_gender);
            $getUserDetails = user_approval::where('emp_id', $user->emp_id)->first();
            $getUser['designation'] = Designation::where('id', $getUserDetails->designation_id)->first()['name'];
            $getUser['joining_date'] = $getUserDetails->joining_date ? date('d M Y', strtotime($getUserDetails->joining_date)) : '';

            return $this->sendResponse($getUser, "User data fetched successfully!");
        } else {
            return $this->sendResponse([], "User data not fetched!");

        }
    }

    public function updateUserProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first());
        }

        $user = Auth::user();

        if ($request->hasFile('image')) {
            $targetDirectory = 'images/users/';

            if ($user->image) {
                $oldImagePath = storage_path("app/{$user->image}");
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($targetDirectory, $filename, 'local');

            if ($filePath) {
                $user->update(['image' => $filePath]);

                if ($user->emp_id) {
                    $updateEmployee = EmployeeDetail::where('id', $user->emp_id)
                        ->update(['emp_image' => $filePath]);

                    if ($updateEmployee) {
                        return $this->sendResponse('', "User image has been updated successfully.");
                    }
                }
            }
        } else {
            return $this->sendError('Please upload an image properly.', '');
        }
    }

    public function userDashboard(Request $request)
    {


        $current_year = isset($request->year) ? date('Y', strtotime($request->year)) : date('Y');
        // $current_month_year = isset($request->year_month) ? date('F Y', strtotime($request->year_month)) : date('F Y');
        $month_number = isset($request->year) ? '12' : date('m');
        $currentMonth = date('Y-m', strtotime($current_year . $month_number));
        $carbonDate = Carbon::parse($currentMonth);
        $month = $carbonDate->month;
        $year = $carbonDate->year;
        $user = Auth::user();
        $empId = $user->emp_id;
        $getDashboard = [];
        $noti_count = Notification::where('user_id', $user->id)->where('read_status', 'N')->where('sent_status', 'Y')->count();
        $empData = EmployeeDetail::where('id', $empId)->where('is_active', '1')->where('is_deleted', '0')->where('status', '1')->first();
        if ($empData) {
            $companyId = $empData->company_id;
            $branchId = $empData->branch_id;
            $company_setting = CompanySetting::where('branch_id', $empData->branch_id)
                ->where('company_id', $empData->company_id)
                ->where('is_deleted', '0')
                ->first();
            $late_time = new DateTime($company_setting->late_time);
            if (Leave_setting::where('company_id', $companyId)->where('is_active', '1')->where('is_deleted', '0')->exists()) {

                $getLeaveSettings = Leave_setting::where('company_id', $companyId)->where('is_active', '1')->where('is_deleted', '0')->first();

                $balanceLeaves = $getLeaveSettings->annual_days + $getLeaveSettings->casual_days + $getLeaveSettings->sick_days;
                $is_on_leave = '-';
                if (Leave::where('company_id', $companyId)->where('emp_id', $empId)->where('is_approved', '1')->exists()) {
                    $getUserLeaves = Leave::where('company_id', $companyId)->where('emp_id', $empId)
                        ->where('is_approved', '1')->whereYear('from_date', Carbon::now()->year)
                        ->get();
                    foreach ($getUserLeaves as $leaves) {
                        $startDate = Carbon::parse($leaves->from_date);
                        $endDate = Carbon::parse($leaves->to_date);

                        while ($startDate->lte($endDate)) {
                            if ($startDate == Carbon::today()) {
                                $leaves->leave_type = ucfirst(Leave_Type::where('id', $leaves->leave_type)->first()['types']);
                                $is_on_leave = "Leave";
                            }
                            $startDate->addDay();
                        }
                        if ($leaves->leave_type != 5) {
                            $balanceLeaves -= $leaves->approved_days;
                        }
                    }
                }
                $getDashboard['remainingLeaves'] = $balanceLeaves;
                $getDashboard['Leave'] = $is_on_leave;

                //get upcoming Holidays
                $currentDate = Carbon::now();


                $getHolidays = Holiday::where(function ($query) use ($branchId) {
                    $query->whereRaw("FIND_IN_SET(?, branch_id)", [$branchId]);
                })
                    ->where(function ($query) use ($companyId) {
                        $query->whereRaw("FIND_IN_SET(?, company_id)", [$companyId]);
                    })
                    ->whereDate('start_date', '>=', $currentDate->format('Y-m-d'))
                    ->whereDate('end_date', '>=', $currentDate->format('Y-m-d'))
                    ->where('is_active', '1')
                    ->where('is_deleted', '0')
                    ->orderBy('start_date')
                    ->first();

                $holidayDetail = [];
                if ($getHolidays) {
                    $startDate = Carbon::parse($getHolidays->start_date);
                    $endDate = Carbon::parse($getHolidays->end_date);
                    while ($startDate->lte($endDate)) {
                        if ($startDate >= Carbon::today()) {
                            $holidayDetail = [
                                'event_name' => $getHolidays->event_name,
                                'date' => $startDate->format('Y-m-d')
                            ];
                        } else {
                            $holidayDetail = [
                                'event_name' => "",
                                'date' => ""
                            ];
                        }
                        $startDate->addDay();
                    }
                } else {
                    $holidayDetail = [
                        'event_name' => "",
                        'date' => ""
                    ];
                }
                $getDashboard['upcoming_holidays_detail'] = $holidayDetail;

                $getTodaysAttendance = UserDailyRecord::where('emp_id', $empId)
                    ->whereDate('dated', $currentDate->format('Y-m-d'))
                    ->first();

                $attendArray = [];
                if ($getTodaysAttendance) {
                    if ($getTodaysAttendance->check_in != null && $getTodaysAttendance->check_out == null) {


                        $checkIn = new DateTime($getTodaysAttendance->check_in);
                        $todayTime = Carbon::now();
                        $difference = $todayTime->diff($checkIn);
                        $empWorkingHours = sprintf('%02dh:%02dm', $difference->h, $difference->i);
                        $attendArray['check_in'] = $checkIn->format('h:i A');
                        $attendArray['check_out'] = '-';
                        $attendArray['workingHours'] = $empWorkingHours;

                        if ($late_time >= $checkIn) {
                            $getDashboard['Leave'] = 'Present';
                        } else {
                            $difference = (Carbon::parse($late_time))->diff(Carbon::parse($checkIn));
                            $late_min = sprintf('%02dh:%02dm', $difference->h, $difference->i);
                            $getDashboard['Leave'] = 'Late ' . $late_min;
                        }
                    } elseif ($getTodaysAttendance->check_in != null && $getTodaysAttendance->check_out != null) {

                        $checkIn = new DateTime($getTodaysAttendance->check_in);
                        $checkOut = new DateTime($getTodaysAttendance->check_out);
                        $difference = $checkIn->diff($checkOut);
                        $workingHours = sprintf("%02dh:%02dm", $difference->h, $difference->i);
                        $attendArray['check_in'] = $checkIn->format('h:i A');
                        $attendArray['check_out'] = $checkOut->format('h:i A');
                        $attendArray['workingHours'] = $workingHours;
                        if ($late_time >= $checkIn) {
                            $getDashboard['Leave'] = 'Present';
                        } else {
                            $difference = (Carbon::parse($late_time))->diff(Carbon::parse($checkIn));
                            $late_min = sprintf('%02dh:%02dm', $difference->h, $difference->i);
                            $getDashboard['Leave'] = "Late " . $late_min;

                        }
                    } else {
                        $attendArray['check_in'] = '';
                        $attendArray['check_out'] = '';
                        $attendArray['workingHours'] = '';
                    }
                } else {
                    $attendArray['check_in'] = '';
                    $attendArray['check_out'] = '';
                    $attendArray['workingHours'] = '';
                }
                $getDashboard['today_attendance'] = $attendArray;
                $getDashboard['notification_count'] = $noti_count;
                return $this->sendResponse($getDashboard, 'Dashboard Data Fetched Successfully!');
            } else {
                return $this->sendResponse([], 'Dashboard Data Not Fetched!');
            }
        } else {
            return $this->sendResponse([], 'Employee not found');
        }
    }

    public function getAnnouncements()
    {
        //user information
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->select('title', 'description', 'read_status', )
            ->where('sent_status','Y')
            ->latest()
            ->get();

        if ($notifications) {
            return $this->sendResponse($notifications, "User notifications fetched successfully!");
        } else {
            return $this->sendResponse([], "User notifications not fetched!");

        }

    }

    // change notification status when user read notification
    public function readAnnouncementStatus(){
       try{
        DB::beginTransaction();
        $user = Auth::user();
        Notification::where('user_id',$user->id)
        ->where('read_status','N')
        ->where('sent_status','Y')
        ->update([
            'read_status'=> 'Y',
        ]);
        DB::commit();
        return $this->sendResponse([], "Record Updated successfully!");
       }catch(Exception $e){
        DB::rollBack();
        return $this->sendResponse([],$e->getMessage());
       }
    }
}
