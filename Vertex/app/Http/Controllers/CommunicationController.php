<?php

namespace App\Http\Controllers;



use DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Config;
use App\Mail\NewTicket;
use App\Models\Location;
use App\Models\company;
use App\Mail\UpdateTicket;
use App\Models\Setting;
use App\Models\PopupBanner;
use App\Models\Notification;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Models\employee_detail;
use App\Models\NotificationEmail;
use App\Models\NotificationSMS;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommunicationController extends Controller
{
    public function sendEmail()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);

        if ($user_role == 1) {
            $getCompanies = Company::where('is_deleted', '0')->where('is_active', '1')->get();
        } else {
            if ($user_company_id != null) {
                $getCompanies = Company::whereIn('id', $user_company_id)->where('is_deleted', '0')->get();
            } else {
                $getCompanies = "";
            }
        }
        return view('communication.comm_email', compact('getCompanies'));
    }

    public function sendSMS()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        if ($user_role == 1) {
            $getCompanies = Company::where('is_deleted', '0')->where('is_active', '1')->get();
        } else {
            if ($user_company_id != null) {
                $getCompanies = Company::whereIn('id', $user_company_id)->where('is_deleted', '0')->get();
            } else {
                $getCompanies = "";
            }
        }
        return view('communication.comm_sms', compact('getCompanies'));
    }
    public function sendMobileNot()
    {
        //user information
        $user = auth()->user();
        $user_role = $user->role_id;
        $user_company_id = explode(',', $user->company_id);
        $user_branch_id = explode(',', $user->branch_id);
        if ($user_role == 1) {
            $getCompanies = Company::where('is_deleted', '0')->where('is_active', '1')->get();
        } else {
            if ($user_company_id != null) {
                $getCompanies = Company::whereIn('id', $user_company_id)->where('is_deleted', '0')->get();
            } else {
                $getCompanies = "";
            }
        }
        return view('communication.comm_mobile_app', compact('getCompanies'));
    }
    public function getBranch(Request $request)
    {
        $getBranches = Location::where('is_deleted', '0')->whereIn('company_id', $request->company_id)->get();
        if ($getBranches) {
            return response()->json(['success' => '1', 'data' => $getBranches]);
        } else {
            return response()->json(['success' => '0', 'data' => ""]);
        }
    }
    public function storeMobileApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $compaign_entry_no = Notification::max('campaign_entry');
        $compaign_entry_no = $compaign_entry_no + 1;
        if ($request->has('company_id') && !($request->has('branch_id'))) {
            $emp_emails = EmployeeDetail::whereIn('company_id', $request->company_id)->get(['id', 'emp_email']);
        } elseif ($request->has('company_id') && $request->has('branch_id')) {
            $email_to =  $request->branch_id;
            $emp_emails = EmployeeDetail::whereIn('company_id', $request->company_id)->whereIn('branch_id', $email_to)->get(['id', 'emp_email']);
        } else {
            return redirect()->back()->with('error', 'Please fill in all the fields');
        }
        foreach ($emp_emails as $key => $emp_email) {
            $noti_email = new Notification();
            $noti_email->user_id = $emp_email->id;
            $noti_email->title = $request->email_subject;
            $noti_email->description = $request->email_body;

            if ($request->email_option_schedule == 'email_schedule') {

                $noti_email->schedule_date = date('Y-m-d H:i:s', (strtotime(($request->email_send_date) . ' ' . $request->email_send_time) - 18000));
            } else {
                $noti_email->schedule_date = date('Y-m-d H:i:s');
            }
            $noti_email->sent_status = 'N';
            $noti_email->campaign_entry = $compaign_entry_no;

            $noti_email->save();
        }
        $msg = 'Emailed with Subject "' . $request->email_subject . '" Successfully';
        createLog('comm_action', $msg);
        return redirect()->back()->with('success', 'Mobile App Notification Added Successfully!');
    }

    public function storeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $compaign_entry_no = NotificationEmail::max('campaign_entry');
        $compaign_entry_no = $compaign_entry_no + 1;
        if ($request->has('company_id') && !($request->has('branch_id'))) {
            $emp_emails = EmployeeDetail::whereIn('company_id', $request->company_id)->get(['id', 'emp_email']);
        } elseif ($request->has('company_id') && $request->has('branch_id')) {
            $email_to =  $request->branch_id;
            $emp_emails = EmployeeDetail::whereIn('company_id', $request->company_id)->whereIn('branch_id', $email_to)->get(['id', 'emp_email']);
        } else {
            return redirect()->back()->with('error', 'Please fill in all the fields');
        }
        if ($emp_emails) {
            $content = $request->email_body;
            $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
            $dom = new \DomDocument('1.0', 'utf-8');
            @$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $imageFile = $dom->getElementsByTagName('imageFile');
            foreach ($imageFile as $item => $image) {
                $data = $image->getAttribute('src');
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $imgeData = base64_decode($data);
                $image_name = "/upload/" . time() . $item . '.png';
                $path = public_path() . '/assets/blogs-attachments/' . $image_name;
                file_put_contents($path, $imgeData);
                $image->removeAttribute('src');
                $image->setAttribute('src', $image_name);
            }
            $content = $dom->saveHTML();

            foreach ($emp_emails as $key => $emp_email) {
                $noti_email = new NotificationEmail();
                $noti_email->user_id = $emp_email->id;
                $noti_email->to_email = $emp_email->emp_email;
                $noti_email->email_subject = $request->email_subject;
                $noti_email->email_body = $content;

                if ($request->email_option_schedule == 'email_schedule') {

                    $noti_email->schedule_date = date('Y-m-d H:i:s', (strtotime(($request->email_send_date) . ' ' . $request->email_send_time) - 18000));
                } else {
                    $noti_email->schedule_date = date('Y-m-d H:i:s');
                }
                $noti_email->email_sent_status = 'N';
                $noti_email->campaign_entry = $compaign_entry_no;

                $noti_email->save();
            }
            $msg = 'Emailed with Subject "' . $request->email_subject . '" Successfully';
            createLog('comm_action', $msg);
        }

        if ($request->additional_email != '') {

            foreach (explode(',', $request->additional_email) as $key => $email) {

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $noti_email = new NotificationEmail();
                    $noti_email->to_email = $email;
                    $noti_email->email_subject = $request->email_subject;
                    $noti_email->email_body = $content;

                    if ($request->email_option_schedule == 'email_schedule') {

                        $noti_email->schedule_date = $request->email_send_date . ' ' . $request->email_send_time . ':00';
                    } else {

                        $noti_email->schedule_date = date('Y-m-d H:i:s');
                    }

                    $noti_email->email_sent_status = 'N';
                    $noti_email->campaign_entry = $compaign_entry_no;

                    $noti_email->save();
                }
            }
        }
        // return $noti_email;
        // $this->send_comm_email();
        return redirect()->back()->with('success', 'Email Sent Successfully!');
        // return redirect()->back()->with('success', 'Email sent successfully!');
    }

    public function storeSMS(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'branch_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', "Some of the fields are missing");
        }
        $compaign_entry_no = NotificationSMS::max('campaign_entry');
        $compaign_entry_no = $compaign_entry_no + 1;

        if ($request->has('company_id') && !($request->has('branch_id'))) {
            $user_phone = EmployeeDetail::whereIn('company_id', $request->company_id)->get(['id', 'emp_phone']);
        } elseif ($request->has('company_id') && $request->has('branch_id')) {
            $email_to =  $request->branch_id;
            $user_phone = EmployeeDetail::whereIn('company_id', $request->company_id)->whereIn('branch_id', $email_to)->get(['id', 'emp_phone']);
        } else {
            return redirect()->back()->with('error', 'Please fill in all the fields');
        }
        if ($user_phone) {
            foreach ($user_phone as $key => $usr_phone) {
                if ($usr_phone->emp_phone != null) {
                    $usr_phone->emp_phone = substr($usr_phone->emp_phone, -10);
                    $noti_sms = new NotificationSMS();
                    $noti_sms->user_id = $usr_phone->id;
                    $noti_sms->phone_number = "+92" . $usr_phone->emp_phone;
                    $noti_sms->sms_body = $request->sms_body;

                    if ($request->sms_option_schedule == 'sms_schedule') {

                        $noti_sms->sms_schedule_date = date('Y-m-d H:i:s', (strtotime(($request->sms_send_date) . ' ' . $request->sms_send_time) - 18000));
                    } else {
                        $noti_sms->sms_schedule_date = date('Y-m-d H:i:s');
                    }

                    $noti_sms->sms_sent_status = 'N';
                    $noti_sms->campaign_entry = $compaign_entry_no;
                    $noti_sms->save();
                }
            }
            $countNumber = count($user_phone);

            $msg = 'Send "' . $countNumber . '" SMS Successfully';
            createLog('comm_action', $msg);
        }

        if ($request->additional_phone != '') {
            foreach (explode(',', $request->additional_phone) as $key => $phone) {
                if (preg_match("/^[0-9]{11}$/", $phone)) {
                    $phone = substr($phone, -10);
                    $noti_sms = new NotificationSMS();
                    $noti_sms->phone_number = "+92" . $phone;
                    $noti_sms->sms_body = $request->sms_body;
                    if ($request->sms_option_schedule == 'sms_schedule') {
                        $noti_sms->sms_schedule_date = date('Y-m-d H:i:s', (strtotime(($request->sms_send_date) . ' ' . $request->sms_send_time) - 18000));
                    } else {
                        $noti_sms->sms_schedule_date = date('Y-m-d H:i:s');
                    }

                    $noti_sms->sms_sent_status = 'N';
                    $noti_sms->campaign_entry = $compaign_entry_no;
                    $noti_sms->save();
                }
            }
        }
        // $this->send_comm_sms();

        return redirect()->back()->with('success', 'SMS Sent Successfully!');
    }

    public function send_comm_email()
    {
        $number_email = 500;
        $currentDate = date('Y-m-d H:i:s');
        $setting_comm_email = Setting::where('perimeter', 'smtp_from_email')->first();
        $setting_comm_email_name = Setting::where('perimeter', 'smtp_from_name')->first();
        $noti = NotificationEmail::where('email_sent_status', 'N')->where('schedule_date', '<=', $currentDate)->orderBy('schedule_date', 'DESC')->limit($number_email)->get();

        if (count($noti) > 0) {
            foreach ($noti as $key => $element) {
                $to_email = $element->to_email;
                $email_subject = $element->email_subject;
                $email_body = $element->email_body;
                // $cc_email = $element->cc_email;
                // $bcc_email = $element->bcc_email;
                if ($element->email_type == "Custom") {
                    Mail::send([], [], function ($message) use (
                        $setting_comm_email,
                        $setting_comm_email_name,
                        $to_email,
                        $email_subject,
                        $email_body
                    ) {
                        $message->from($setting_comm_email->value, $setting_comm_email_name->value)
                            ->to($to_email)
                            //                        ->bcc($bcc_email ? $bcc_email : 'muhammad.fahad@viiontech.com')
                            ->subject($email_subject ? $email_subject : '')
                            ->setBody($email_body, 'text/html');
                    });
                }

                if (count(Mail::failures()) > 0) {

                    $mail_err = '';

                    foreach (Mail::failures as $email_address) {
                        $mail_err .= $email_address . '=>';
                    }

                    NotificationEmail::where('id', $element->id)->update(array('response' =>  $mail_err));
                } else {

                    NotificationEmail::where('id', $element->id)->update(array('from_email' => $setting_comm_email->value, 'from_name' => $setting_comm_email_name->value, 'response' =>  'sent successfully!', 'email_sent_status' => 'Y', 'sent_date' => $currentDate));
                }
            }
        } else {
            return redirect()->back()->with('error', 'No emails to send!');
        }
    }

    public function send_comm_sms()
    {
        $number_sms = 20;
        $currentDate = date('Y-m-d H:i:s');
        $setting_comm_sms_username = Setting::where('perimeter', 'communication_sms_username')->first();
        $setting_comm_sms_password = Setting::where('perimeter', 'communication_sms_password')->first();
        $setting_comm_sms_sender_id = Setting::where('perimeter', 'communication_sms_sender_id')->first();
        $noti = NotificationSMS::where('sms_sent_status', 'N')->where('sms_schedule_date', '<=', $currentDate)->orderBy('sms_schedule_date', 'DESC')->limit($number_sms)->get();
        if (count($noti) > 0) {
            foreach ($noti as $key => $element) {
                $username = $setting_comm_sms_username->value;
                $password = $setting_comm_sms_password->value;
                $mobile = $element->phone_number;
                $sender = $setting_comm_sms_sender_id->value;
                $message = $element->sms_body;
                ////sending sms
                $post = "Username=" . $username . "&Password=" . $password . "&From=" . urlencode($sender) . "&To=" . urlencode($mobile) . "&Message=" . urlencode($message);
                $url = "https://connect.jazzcmt.com/sendsms_url.html?" . $post;
                $ch = curl_init();
                $timeout = 10; // set to zero for no timeout
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch);

                if ($result != "Message Sent Successfully!") {
                    NotificationSMS::where('id', $element->id)->update(array('response' =>  $result));
                } else {
                    NotificationSMS::where('id', $element->id)->update(array('from_phone_number' => $setting_comm_sms_sender_id->value, 'response' =>  $result, 'sms_sent_status' => 'Y', 'sent_date' => $currentDate));
                }
                return $result;
            }
        }
    }

    function send_app_notification()
    {
        $success = 1;
        $number_noti = 100;
        $currentDate = date('Y-m-d H:i:s');
        $server_key = Setting::where('perimeter', 'SERVER_KEY')->first()['value'];
        $url = 'https://fcm.googleapis.com/fcm/send';
        $notification = Notification::where('sent_status', 'N')->where('schedule_date', '<=', $currentDate)->orderBy('schedule_date', 'DESC')->limit($number_noti)->get();
        if (count($notification) > 0) {
            foreach ($notification as $noti) {
                if ($noti->device_type == 'all') {
                    $q = UserDevice::where('user_id', $noti->user_id)->where('status', '=', 'A')->get();
                } else {
                    $q = UserDevice::where('user_id', $noti->user_id)->where('status', '=', 'A')->where('platform', $noti->device_type)->get();
                }

                if (!empty($q) && count($q) > 0) {
                    foreach ($q as $row) {
                        if (is_null($row->token)) {
                            Notification::where('id', '=', $noti->id)
                                ->update(array('message_error' => "Device token is null"));
                            continue;
                        }
                        $fcm_token = $row->token;
                        $fields = array(
                            'to' => $fcm_token,
                            'notification' => array('title' => $noti->title, 'body' => $noti->description, 'sound' => 1, 'vibrate' => 1, 'content_available' => true, 'priority' => 'high'),
                            'data' => array('notification_type' => $noti->notification_type, 'title' => $noti->title, 'body' => $noti->description)
                        );
                        $headers = array(
                            'Authorization: key=' . $server_key,
                            'Content-Type: application/json'
                        );
                        $ch = curl_init();
                        $payload = json_encode($fields);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                        $curlResult =  curl_exec($ch);

                        if ($curlResult === FALSE) {
                            die('FCM Send Error: ' . curl_error($ch));
                        }

                        curl_close($ch);

                        $res = json_decode($curlResult, true);

                        if ($res['failure']) {
                            $success = 0;
                            $array = $res['results'];
                            $error = $array[0]['error'];
                            $noti->message_error =  $error;
                        } else {
                            $noti->sent_status =  'Y';
                            $noti->app_sent_date =  $currentDate;
                        }

                        $noti->save();
                        return $success;
                    }
                }
            }
        }
    }
}
