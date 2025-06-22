<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\config;
use App\Mail\NewTicket;
use App\Models\Location;
use App\Models\Company;
use App\Models\NotificationManagement;
use App\Mail\UpdateTicket;
use App\Models\Setting;
use App\Models\PopupBanner;
use App\Models\Notification;
use App\Models\role;
use App\User;
use Illuminate\Http\Request;
use App\Models\employee_detail;
use App\Models\NotificationEmail;
use App\Models\NotificationSMS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\emailFormat;

class NotificationController extends Controller
{
    use emailFormat;
    public function allNotifications(Request $request)
    {

        $where_array = array();
        if (Auth::user()->roles != 1 && Auth::user()->roles != 2) {
            $user_id = Auth::user()->id;
            $where_array['user_id'] =  $user_id;
        }

        $notifications = Notification::where($where_array)->where('sent_status', "Y")->get();
        // Session::put(['notifications'=> $notifications]);
    }

    function notificationManagement()
    {
        $Noti_Types = DB::table('notification_management')->where('is_hidden', '0')->paginate();
        foreach ($Noti_Types as $notification) {
            $notification->roleName =  role::whereIn('id', explode(",", $notification->role_id))->where('is_active', '1')->where('is_deleted', '0')->pluck('role_name');
        }
        $getRoles = role::where('is_active', '1')->where('is_deleted', '0')->get();
        return view('notification.notificationSetting', ['noti_types' => $Noti_Types, 'user_roles' => $getRoles]);
    }

    function getNotificationRoles(Request $request)
    {
        $getRoles = role::where('is_active', '1')->where('is_deleted', '0')->get();
        $Noti_Types = DB::table('notification_management')->where('id', $request->id)->where('is_hidden', '0')->first();
        $Noti_Types->roleName =  role::whereIn('id', explode(",", $Noti_Types->role_id))->where('is_active', '1')->where('is_deleted', '0')->pluck('id');
        return response()->json(['success' => 1, 'data' => $Noti_Types, 'role' => $getRoles]);
    }

    public function saveNotificationRoles(Request $request)
    {
        if ($request->role_id == null) {
            $addNotification = NotificationManagement::where('id', $request->notification_id)->update(['role_id' => null]);
        } else {
            $role_id = implode(",", $request->role_id);
            $addNotification = NotificationManagement::where('id', $request->notification_id)->update(['role_id' => $role_id]);
        }
        $msg = 'Notification Added Successfully';
        createLog('notification_action', $msg);
        return redirect()->back()->with('message', 'Notifications added successfully');
    }

    function usernotificationManagement()
    {
        $getAllUsers = User::where('is_deleted', 'N')->paginate(15);
        foreach ($getAllUsers as $user) {
            if (DB::table('user_notification_setting')->where('user_id', $user->id)->exists()) {
                $user->notification = DB::table('user_notification_setting')->where('user_id', $user->id)->get();
                foreach ($user->notification as $notification) {
                    if ($notification->send_email == 'Y') {
                        $user->email_on = 'Y';
                    } else {
                        $user->email_on = 'N';
                    }
                    if ($notification->send_app_noti == 'Y') {
                        $user->app_noti = 'Y';
                    } else {
                        $user->app_noti = 'N';
                    }
                    $notification->notificationName = NotificationManagement::where('id', $notification->notification_id)->first()['type'];
                }
            } else {
                $user->notification = "";
                $user->app_noti = 'N';
                $user->email_on = 'N';
            }
        }
        return view('admin.notification.user_notification_setting', ['getAllUsers' => $getAllUsers]);
    }
    public function userAllNotification(Request $request)
    {
        if ($request->is_checked == "true") {
            if ($request->notification_id == null) {
                $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->update([$request->type => "Y"]);
            } else {
                $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->where('notification_id', $request->notification_id)->update([$request->type => "Y"]);
            }
        }
        if ($request->is_checked == "false") {
            if ($request->notification_id == null) {
                $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->update([$request->type => "N"]);
            } else {
                $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->where('notification_id', $request->notification_id)->update([$request->type => "N"]);
            }
        }
        $msg = 'User Notification Updated Successfully';
        createLog('notification_action', $msg);
        if ($updateUserNotification) {
            return response()->json(['success' => 1, 'message' => "Data Updated"]);
        } else {
            return response()->json(['error' => 1, 'message' => "Error Occurred"]);
        }
    }

    function notiSettingStore(Request $request)
    {
        $Noti_Manag =  NotificationManagement::where('id', $request->id)->first();

        if ($request->Notify_by == "email") {
            if ($request->is_check == "true") {
                $Noti_Manag->send_email = "Y";
            } else {
                $Noti_Manag->send_email = "N";
            }
        }
        if ($request->Notify_by == "sms") {
            if ($request->is_check == "true") {
                $Noti_Manag->send_sms = "Y";
            } else {
                $Noti_Manag->send_sms = "N";
            }
        }
        if ($request->Notify_by == "app") {
            if ($request->is_check == "true") {
                $Noti_Manag->send_app_noti = "Y";
            } else {
                $Noti_Manag->send_app_noti = "N";
            }
        }
        //        dd($request->email_body);

        if ($request->email_body) {
            $pattern = ['&gt;', '&lt;'];
            $replacement = ['>', '<'];
            $mail = str_replace($pattern, $replacement, $request->email_body);

            //            dd($mail);
            //            $Noti_Manag->mail = $request->email_body;
            $Noti_Manag->mail = $mail;
        }
        if ($request->app_title) {
            $Noti_Manag->mobile_app_title = $request->app_title;
        }
        if ($request->app_noti_Descr) {
            $Noti_Manag->mobile_app_description = $request->app_noti_Descr;
        }
        if ($request->smsDescription) {
            $Noti_Manag->sms = $request->smsDescription;
        }
        if ($request->subject) {
            $Noti_Manag->mail_subject = $request->subject;
        }
        if ($request->to_email) {
            $Noti_Manag->to_email = $request->to_email;
        }
        $Noti_Manag->save();
        $msg = 'Notification Added Successfully';
        createLog('notification_action', $msg);

        if ($Noti_Manag->save()) {
            return redirect()->route('admin.notifi.management');
        }
    }

    function clearNotification(Request $request)
    {

        if ($request->has('notification_id')) {
            $getNotification = Notification::whereIn('id', $request->notification_id)->update(array('read_status' => 'Y'));
            return [$getNotification, $request->notification_id];
        } else {
            $getNotification = Notification::where('user_id', Auth::user()->id)->where('notification_type', 'Custom')->orWhere('notification_type', 'Ticket')->where('read_status', 'N')->update(array('read_status' => 'Y'));
        }
        $notifications = Notification::where('user_id', Auth::user()->id)->where('read_status', 'Y')->whereIn('notification_type', ['Custom', 'Ticket'])->orderBy('schedule_date', 'Desc')->take(10)->get();
        $msg = 'Notification Updated Successfully';
        createLog('notification_action', $msg);
        return $notifications;
    }

    function allnotification()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->whereIn('notification_type', ['Custom', 'Ticket'])->orderBy('schedule_date', 'Desc')->paginate(10);

        return view('admin.notification.all-notification', ['notifications' => $notifications]);
    }

    function generateNotification($type, $data, $branch)
    {
        $checkMail = NotificationManagement::where('type', $type)->first();
        if ($checkMail->role_id != null) {
            $getRoles = explode(",", $checkMail->role_id);
            foreach ($getRoles as $role) {
                $getUsers = User::whereRaw('FIND_IN_SET(?, branch_id)', [$branch])
                    ->where('role_id', $role)
                    ->where('is_deleted', '0')
                    ->get();
                if ($getUsers != "" && $getUsers != null) {
                    foreach ($getUsers as $user) {
                        if (($type == "Employee Termination" || $type == "Employee Resignation") && $role == 3) {
                            $user = User::where('id', $data['user_id'])->first();
                        }
                        $firstCompanyId = explode(',', $user->company_id)[0];
                        $getUserCompanyLogo = Company::where('id', $firstCompanyId)->value('logo');
                        $emailresponse = $this->emailStructure($checkMail->header, $checkMail->footer, $getUserCompanyLogo);
                        $patterns = [
                            '/\{(user_name)}]?/',
                            '/\{(employee_name)}]?/',
                            '/\{(employee_position)}]?/',
                            '/\{(last_date)}]?/',
                            '/\{(termination_type)}]?/',
                            '/\{(employee_email)}]?/',
                            '/\{(joining_date)}]?/',
                            '/\{(employee_phone)}]?/',
                            '/\{(start_date)}]?/',
                            '/\{(end_date)}]?/',
                            '/\{(from_date)}]?/',
                            '/\{(to_date)}]?/',
                            '/\{(leave_type)}]?/',
                            '/\{(event_name)}]?/',
                        ];
                        $replacements = [
                            $user->fullname,
                            $data['emp_name'] ?? '',
                            $data['emp_position'] ?? '',
                            $data['last_date'] ?? '',
                            $data['termination_type'] ?? '',
                            $data['employee_email'] ?? '',
                            $data['joining_date'] ?? '',
                            $data['employee_phone'] ?? '',
                            $data['start_date'] ?? '',
                            $data['end_date'] ?? '',
                            $data['from_date'] ?? '',
                            $data['to_date'] ?? '',
                            $data['leave_type'] ?? '',
                            $data['event_name'] ?? '',

                        ];
                        if ($checkMail->send_email == "Y") {
                            $mail = preg_replace($patterns, $replacements, $checkMail->mail);
                            $emailnotification = new NotificationEmail();
                            if (($type == "Employee Termination" || $type == "Employee Resignation") && $role == 3) {
                                $emailnotification->user_id = $data['user_id'];
                                $emailnotification->to_email = ($role == 3 && isset($data['employee_personal_email'])) ? $data['employee_personal_email'] : $user->email;
                                $emailnotification->email_subject = $checkMail->mail_subject;
                                // $emailnotification->email_body = $mail;
                                $emailnotification->email_body = $emailresponse[0] . $mail . $emailresponse[1];
                                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                                $emailnotification->email_sent_status = 'N';
                                $emailnotification->save();
                                break;
                            } else {
                                $emailnotification->user_id = $user->id;
                                $emailnotification->to_email = ($role == 3 && isset($data['employee_personal_email'])) ? $data['employee_personal_email'] : $user->email;
                                $emailnotification->email_subject = $checkMail->mail_subject;
                                // $emailnotification->email_body = $mail;
                                $emailnotification->email_body = $emailresponse[0] . $mail . $emailresponse[1];
                                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                                $emailnotification->email_sent_status = 'N';
                                $emailnotification->save();
                            }
                        }
                        if ($checkMail->send_sms == "Y") {
                            $sms = preg_replace($patterns, $replacements, $checkMail->sms);
                            $phone = substr($user->phone, -10);
                            $emailnotification = new NotificationSMS();
                            $emailnotification->user_id = $user->id;
                            $emailnotification->phone_number = "+92" . $phone;
                            $emailnotification->sms_body = $sms;
                            // $emailnotification->email_body = $emailresponse[0].$mail.$emailresponse[1];
                            $emailnotification->sms_schedule_date = date('Y-m-d H:i:s');
                            $emailnotification->sms_sent_status = 'N';
                            $emailnotification->save();
                        }
                        if ($checkMail->send_app_noti == "Y") {
                            $mobileAppDescription = preg_replace($patterns, $replacements, $checkMail->mobile_app_description);
                            $emailnotification = new Notification();
                            $emailnotification->user_id = $user->id;
                            $emailnotification->title = $checkMail->mobile_app_title;
                            $emailnotification->description = $mobileAppDescription;
                            // $emailnotification->email_body = $emailresponse[0].$mail.$emailresponse[1];
                            $emailnotification->schedule_date = date('Y-m-d H:i:s');
                            $emailnotification->sent_status = 'N';
                            $emailnotification->save();
                        }
                        $msg = 'Notification Added Successfully';
                        createLog('notification_action', $msg);
                        continue;
                    }
                }
            }
        }
    }
}
