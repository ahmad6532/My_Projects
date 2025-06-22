<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Config;
use App\Mail\NewTicket;
use App\Models\Location;
use App\Models\Company;
use App\Models\NotificationManagement;
use App\Mail\UpdateTicket;
use App\Models\Setting;
use App\Models\PopupBanner;
use App\Models\Notification;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use App\Models\employee_detail;
use App\Models\NotificationEmail;
use App\Models\NotificationSMS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\emailFormat;

class NotificationController extends BaseController
{
    use emailFormat;
    public function allNotifications(Request $request)
    {
        $where_array = array();
        if (Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            $user_id = Auth::user()->id;
            $where_array['id'] =  $user_id;

            $notifications = Notification::where($where_array)->where('sent_status', "Y")->paginate(10);
        }else{
            $notifications = Notification::where('user_id', Auth::user()->id)->orderBy('schedule_date', 'Desc')->paginate(10);
        }

        if(!empty($notifications)){
            return $this->sendResponse($notifications,'All notifications!',200);
        }else{
            return $this->sendResponse($notifications,'Data not found!',200);
        }
    }

    public function getNotification(Request $request)
    {
        $id = $request->id;

        $notification = NotificationManagement::findOrFail($id);
        $roleIds = explode(",", $notification->role_id);

        // Fetch roles
        $roles = Role::whereIn('id', $roleIds)
            ->where('is_active', '1')
            ->where('is_deleted', '0')
            ->get(['id', 'role_name']);

        $notification->role_ids = $roleIds;

        $notification->roleName = $roles->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->role_name
            ];
        });

        // Convert variable_list to an array
        $notification->variable_list = explode(',', $notification->variable_list);

        return response()->json([
            'success' => true,
            'message' => "Record fetched successfully",
            'data' => $notification,
        ], 200);
    }


    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $send_sms = $request->send_sms;
        $send_email = $request->send_email;
        $send_app_noti = $request->send_app_noti;

        $notification = NotificationManagement::findOrFail($id);

        if (isset($send_sms)) {
            $notification->send_sms = ($send_sms == 'Y') ? 'Y' : 'N';
        }

        if (isset($send_email)) {
            $notification->send_email = ($send_email == 'Y') ? 'Y' : 'N';
        }
        if (isset($send_app_noti)) {
            $notification->send_app_noti = ($send_app_noti == 'Y') ? 'Y' : 'N';
        }

        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $notification,
        ], 200);
    }

    public function notificationManagement(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $query = DB::table('notification_management')
            ->where('is_hidden', '0');

        if (!empty($search)) {
            $query->where('type', 'like', '%' . $search . '%');
        }

        $notiTypes = $query->paginate($perPage, ['*'], 'page', $page);

        foreach ($notiTypes as $notification) {
            // Convert role_id from comma-separated string to an array and cast to integers
            $roleIds = array_map('intval', explode(",", $notification->role_id));
            $roles = Role::whereIn('id', $roleIds)
                ->where('is_active', '1')
                ->where('is_deleted', '0')
                ->get(['id', 'role_name']);

            // Attach roles information to notification
            $notification->roleName = $roles->map(function($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->role_name
                ];
            });

            // Add role_id as an array of integers to notification data
            $notification->roleIds = $roleIds;

            // Parse variable_list string into an array
            $notification->variable_list = array_map('trim', explode(',', $notification->variable_list));
        }

        $getRoles = Role::where('is_active', '1')
            ->where('is_deleted', '0')
            ->get();

        $data = [
            'user_roles' => $getRoles,
            'noti_types' => $notiTypes
        ];

        if (!empty($data)) {
            return $this->sendResponse($data, 'All notifications fetched successfully!');
        } else {
            return $this->sendResponse($data, 'Data not found!', 200);
        }
    }

    function getNotificationRoles(Request $request)
    {
        $getRoles = Role::where('is_active', '1')->where('is_deleted', '0')->get();
        $Noti_Types = DB::table('notification_management')->where('id', $request->notification_id)->where('is_hidden', '0')->first();
        $Noti_Types->roleName =  Role::whereIn('id', explode(",", $Noti_Types->role_id))->where('is_active', '1')->where('is_deleted', '0')->pluck('id');
        $data['notification_types'] = $Noti_Types;
        $data['role'] = $getRoles;
        if($data){
            return $this->sendResponse($data,'Notifications with roles fetched successfully!',200);
        }else{
            return $this->sendResponse($data,'Data not found!',200);
        }
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
        $notification = NotificationManagement::where('id',$request->notification_id)->first();
        if($addNotification){
            return $this->sendResponse($notification,'Notification added successfully!',200);
        }else{
            return $this->sendResponse($notification,'Data not found!',200);
        }
    }

    // function usernotificationManagement()
    // {
    //     $getAllUsers = User::where('is_deleted', 'N')->paginate(15);
    //     foreach ($getAllUsers as $user) {
    //         if (DB::table('user_notification_setting')->where('user_id', $user->id)->exists()) {
    //             $user->notification = DB::table('user_notification_setting')->where('user_id', $user->id)->get();
    //             foreach ($user->notification as $notification) {
    //                 if ($notification->send_email == 'Y') {
    //                     $user->email_on = 'Y';
    //                 } else {
    //                     $user->email_on = 'N';
    //                 }
    //                 if ($notification->send_app_noti == 'Y') {
    //                     $user->app_noti = 'Y';
    //                 } else {
    //                     $user->app_noti = 'N';
    //                 }
    //                 $notification->notificationName = NotificationManagement::where('id', $notification->notification_id)->first()['type'];
    //             }
    //         } else {
    //             $user->notification = "";
    //             $user->app_noti = 'N';
    //             $user->email_on = 'N';
    //         }
    //     }
    //     return view('admin.notification.user_notification_setting', ['getAllUsers' => $getAllUsers]);
    // }
    // public function userAllNotification(Request $request)
    // {
    //     if ($request->is_checked == "true") {
    //         if ($request->notification_id == null) {
    //             $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->update([$request->type => "Y"]);
    //         } else {
    //             $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->where('notification_id', $request->notification_id)->update([$request->type => "Y"]);
    //         }
    //     }
    //     if ($request->is_checked == "false") {
    //         if ($request->notification_id == null) {
    //             $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->update([$request->type => "N"]);
    //         } else {
    //             $updateUserNotification =  UserNotificationSettings::where('user_id', $request->user_id)->where('notification_id', $request->notification_id)->update([$request->type => "N"]);
    //         }
    //     }
    //     $msg = 'User Notification Updated Successfully';
    //     createLog('notification_action', $msg);
    //     if ($updateUserNotification) {
    //         return response()->json(['success' => 1, 'message' => "Data Updated"]);
    //     } else {
    //         return response()->json(['error' => 1, 'message' => "Error Occurred"]);
    //     }
    // }

    function notiSettingStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError([], $validator->errors(), 400);
        }

        $Noti_Manag = NotificationManagement::where('id', $request->id)->first();

        if (!$Noti_Manag) {
            return $this->sendError([], 'Notification management record not found', 404);
        }

        if ($request->notifi_by == "email") {
            $Noti_Manag->send_email = $request->is_check == "true" ? "Y" : "N";
        }

        if ($request->notifi_by == "sms") {
            $Noti_Manag->send_sms = $request->is_check == "true" ? "Y" : "N";
        }

        if ($request->notifi_by == "app") {
            $Noti_Manag->send_app_noti = $request->is_check == "true" ? "Y" : "N";
        }

        if ($request->email_body) {
            $Noti_Manag->mail = str_replace(['&gt;', '&lt;'], ['>', '<'], $request->email_body);
        }

        if ($request->mobile_app_title) {
            $Noti_Manag->mobile_app_title = $request->mobile_app_title;
        }

        if ($request->mobile_app_description) {
            $Noti_Manag->mobile_app_description = $request->mobile_app_description;
        }

        if ($request->smsDescription) {
            $Noti_Manag->sms = $request->smsDescription;
        }

        if ($request->mail_subject) {
            $Noti_Manag->mail_subject = $request->mail_subject;
        }

        if ($request->mail_description) {
            $Noti_Manag->mail = $request->mail_description;
        }

        // Save the updated notification management record
        if ($Noti_Manag->save()) {
            $msg = 'Notification Added Successfully';
            createLog('notification_action', $msg);

            return $this->sendResponse($Noti_Manag, 'Notification settings saved successfully!', 200);
        } else {
            return $this->sendError([], 'Form not submitted!', 500);
        }
    }

    // function clearNotification(Request $request)
    // {
    //     if ($request->has('notification_id')) {
    //         $getNotification = Notification::whereIn('id', $request->notification_id)->update(array('read_status' => 'Y'));
    //         // return [$getNotification, $request->notification_id];
    //         $getNotification->notification_id = $request->notification_id;
    //         return $this->sendResponse($getNotification, '', 200);
    //     } else {
    //         $getNotification = Notification::where('user_id', Auth::user()->id)->where('notification_type', 'Custom')->orWhere('notification_type', 'Ticket')->where('read_status', 'N')->update(array('read_status' => 'Y'));
    //     }
    //     $notifications = Notification::where('user_id', Auth::user()->id)->where('read_status', 'Y')->whereIn('notification_type', ['Custom', 'Ticket'])->orderBy('schedule_date', 'Desc')->take(10)->get();
    //     $msg = 'Notification Updated Successfully';
    //     createLog('notification_action', $msg);
    //     return $this->sendResponse($notifications, 'Notification clear successfully!', 200);
    // }

    public function clearNotification(Request $request)
    {
        try {
            if ($request->has('notification_id')) {
                $notificationIds = (array) $request->notification_id;

                Notification::whereIn('id', $notificationIds)->update(['read_status' => 'Y']);
                $updatedNotifications = Notification::whereIn('id', $notificationIds)->get();

                return $this->sendResponse($updatedNotifications, 'Notifications updated successfully!', 200);
            } else {
                Notification::where('user_id', Auth::user()->id)
                            ->where('read_status', 'N')
                            ->update(['read_status' => 'Y']);
            }

            $notifications = Notification::where('user_id', Auth::user()->id)
                                        ->where('read_status', 'Y')
                                        ->orderBy('schedule_date', 'desc')
                                        ->take(10)
                                        ->get();

            $msg = 'Notification Updated Successfully';
            createLog('notification_action', $msg);

            return $this->sendResponse($notifications, 'Notification clear successfully!', 200);
        } catch (\Exception $e) {
            return $this->sendResponse([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }


    // function allnotification()
    // {
    //     $notifications = Notification::where('user_id', Auth::user()->id)->whereIn('notification_type', ['Custom', 'Ticket'])->orderBy('schedule_date', 'Desc')->paginate(10);
    //     if(!empty($notifications)){
    //         return $this->sendResponse($notifications,'All Notification list fetched successfully!',200);
    //     }else{
    //         return $this->sendResponse($notifications,'Data not found!',200);
    //     }
    // }

    function generateNotification($type, $data, $branch)
    {
        $checkMail = NotificationManagement::where('type', $type)->first();
        if ($checkMail->role_id != null) {
            $getRoles = explode(",", $checkMail->role_id);
            foreach ($getRoles as $role) {
                $getUsers = User::where('branch_id', $branch)->where('role_id', $role)->where('is_deleted', '0')->get();
                if ($getUsers != "" && $getUsers != null) {
                    foreach ($getUsers as $user) {
                        if (($type == "Employee Termination" || $type == "Employee Resignation") && $role == 3) {
                            $user = User::where('id', $data['user_id'])->first();
                        }
                        $getUserCompanyLogo = Company::where('id', $user->company_id)->first()['logo'];
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
