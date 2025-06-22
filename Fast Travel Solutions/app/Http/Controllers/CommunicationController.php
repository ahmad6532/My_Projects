<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationManagement;
use App\Models\NotificationEmail;
use App\Models\Setting;
use App\Models\SMS;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;



class CommunicationController extends Controller
{
    // public function send_comm_email()
    // {
    //     $number_email = 500;
    //     $currentDate = date('Y-m-d H:i:s');
    //     $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
    //     $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();
    //     $noti = NotificationEmail::where('email_sent_status', 'N')->where('schedule_date', '<=', $currentDate)->orderBy('schedule_date', 'DESC')->limit($number_email)->get();

    //     if (count($noti) > 0) {
    //         foreach ($noti as $key => $element) {
    //             $to_email = $element->to_email;
    //             $email_subject = $element->email_subject;
    //             $email_body = $element->email_body;
    //             // $cc_email = $element->cc_email;
    //             // $bcc_email = $element->bcc_email;
    //             if ($element->email_type == "Custom") {
    //                 Mail::send([], [], function ($message) use (
    //                     $setting_comm_email,
    //                     $setting_comm_email_name,
    //                     $to_email,
    //                     $email_subject,
    //                     $email_body,
    //                 ) {
    //                     $message->from($setting_comm_email->value, $setting_comm_email_name->value)
    //                         ->to($to_email)
    //                         //                        ->bcc($bcc_email ? $bcc_email : 'muhammad.fahad@viiontech.com')
    //                         ->subject($email_subject ? $email_subject : '')
    //                         ->setBody($email_body, 'text/html');
    //                 });
    //             }

    //             if (count(Mail::failures()) > 0) {

    //                 $mail_err = '';

    //                 foreach (Mail::failures as $email_address) {
    //                     $mail_err .= $email_address . '=>';
    //                 }

    //                 NotificationEmail::where('id', $element->id)->update(array('response' =>  $mail_err));
    //             } else {

    //                 NotificationEmail::where('id', $element->id)->update(array('from_email' => $setting_comm_email->value, 'from_name' => $setting_comm_email_name->value, 'response' =>  'sent successfully!', 'email_sent_status' => 'Y', 'sent_date' => $currentDate));
    //             }
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'No emails to send!');
    //     }
    // }

    public function send_comm_email()
    {
        $number_email = 500;
        $currentDate = now();
        $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
        $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();
        $noti = NotificationEmail::where('email_sent_status', 'N')
            ->where('schedule_date', '<=', $currentDate)
            ->where('payment_status', 'paid')
            ->orderBy('schedule_date', 'DESC')
            ->limit($number_email)
            ->get();

            // return response()->json($noti);

        if ($noti->count() > 0) {
            foreach ($noti as $element) {
                $to_email = $element->to_email;
                $email_subject = $element->email_subject;
                $email_body = $element->email_body;

                try {

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

                    return response()->json([

                        'status' => true,
                        'message' => 'Email Send Sucessfully!'

                    ]);
                } catch (\Exception $e) {
                    NotificationEmail::where('id', $element->id)->update([
                        'response' => $e->getMessage()
                    ]);
                }
            }
        } else {
            return redirect()->back()->with('error', 'No emails to send!');
        }
    }

    // public function sendCommEmail()
    // {
    //     $numberEmail = 500;
    //     $currentDate = now();
    //     $settingCommEmail = Setting::where('parameter', 'smtp_from_email')->value('value');
    //     $settingCommEmailName = Setting::where('parameter', 'smtp_from_name')->value('value');

    //     $notifications = NotificationEmail::where('email_sent_status', 'N')
    //         ->where('schedule_date', '<=', $currentDate)
    //         ->orderBy('schedule_date', 'DESC')
    //         ->limit($numberEmail)
    //         ->get();

    //     if ($notifications->isEmpty()) {
    //         return redirect()->back()->with('error', 'No emails to send!');
    //     }

    //     foreach ($notifications as $notification) {
    //         $toEmail = $notification->to_email;
    //         $emailSubject = $notification->email_subject;
    //         $emailBody = $notification->email_body;

    //         if ($notification->email_type === "Custom") {
    //             try {
    //                 Mail::send([], [], function ($message) use (
    //                     $settingCommEmail,
    //                     $settingCommEmailName,
    //                     $toEmail,
    //                     $emailSubject,
    //                     $emailBody
    //                 ) {
    //                     $message->from($settingCommEmail, $settingCommEmailName)
    //                         ->to($toEmail)
    //                         ->subject($emailSubject ?? '')
    //                         ->setBody($emailBody, 'text/html');
    //                 });

    //                 NotificationEmail::where('id', $notification->id)
    //                     ->update([
    //                         'from_email' => $settingCommEmail,
    //                         'from_name' => $settingCommEmailName,
    //                         'response' => 'Sent successfully!',
    //                         'email_sent_status' => 'Y',
    //                         'sent_date' => $currentDate,
    //                     ]);
    //             } catch (\Exception $e) {
    //                 Log::error('Mail error: ' . $e->getMessage());
    //                 NotificationEmail::where('id', $notification->id)
    //                     ->update([
    //                         'response' => $e->getMessage(),
    //                     ]);
    //             }
    //         }
    //     }
    // }



    public function send_comm_sms()
    {
        $number_sms = 20;
        $currentDate = date('Y-m-d H:i:s');
        $setting_comm_sms_username = Setting::where('parameter', 'communication_sms_username')->first();
        $setting_comm_sms_password = Setting::where('parameter', 'communication_sms_password')->first();
        $setting_comm_sms_sender_id = Setting::where('parameter', 'communication_sms_sender_id')->first();
        $noti = SMS::where('sms_sent_status', 'N')->where('sms_schedule_date', '<=', $currentDate)->orderBy('sms_schedule_date', 'DESC')->limit($number_sms)->get();
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
                    SMS::where('id', $element->id)->update(array('response' =>  $result));
                } else {
                    SMS::where('id', $element->id)->update(array('from_phone_number' => $setting_comm_sms_sender_id->value, 'response' =>  $result, 'sms_sent_status' => 'Y', 'sent_date' => $currentDate));
                }
                return $result;
            }
        }
    }

    // function send_app_notification()
    // {
    //     $success = 1;
    //     $number_noti = 100;
    //     $currentDate = date('Y-m-d H:i:s');
    //     $server_key = Setting::where('perimeter', 'SERVER_KEY')->first()['value'];
    //     $url = 'https://fcm.googleapis.com/fcm/send';
    //     $notification = Notification::where('sent_status', 'N')->where('schedule_date', '<=', $currentDate)->orderBy('schedule_date', 'DESC')->limit($number_noti)->get();
    //     if (count($notification) > 0) {
    //         foreach ($notification as $noti) {
    //             if ($noti->device_type == 'all') {
    //                 $q = UserDevice::where('user_id', $noti->user_id)->where('status', '=', 'A')->get();
    //             } else {
    //                 $q = UserDevice::where('user_id', $noti->user_id)->where('status', '=', 'A')->where('platform', $noti->device_type)->get();
    //             }

    //             if (!empty($q) && count($q) > 0) {
    //                 foreach ($q as $row) {
    //                     if (is_null($row->token)) {
    //                         Notification::where('id', '=', $noti->id)
    //                             ->update(array('message_error' => "Device token is null"));
    //                         continue;
    //                     }
    //                     $fcm_token = $row->token;
    //                     $fields = array(
    //                         'to' => $fcm_token,
    //                         'notification' => array('title' => $noti->title, 'body' => $noti->description, 'sound' => 1, 'vibrate' => 1, 'content_available' => true, 'priority' => 'high'),
    //                         'data' => array('notification_type' => $noti->notification_type, 'title' => $noti->title, 'body' => $noti->description)
    //                     );
    //                     $headers = array(
    //                         'Authorization: key=' . $server_key,
    //                         'Content-Type: application/json'
    //                     );
    //                     $ch = curl_init();
    //                     $payload = json_encode($fields);
    //                     curl_setopt($ch, CURLOPT_URL, $url);
    //                     curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    //                     curl_setopt($ch, CURLOPT_POST, true);
    //                     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //                     curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    //                     $curlResult =  curl_exec($ch);

    //                     if ($curlResult === FALSE) {
    //                         die('FCM Send Error: ' . curl_error($ch));
    //                     }

    //                     curl_close($ch);

    //                     $res = json_decode($curlResult, true);

    //                     if ($res['failure']) {
    //                         $success = 0;
    //                         $array = $res['results'];
    //                         $error = $array[0]['error'];
    //                         $noti->message_error =  $error;
    //                     } else {
    //                         $noti->sent_status =  'Y';
    //                         $noti->app_sent_date =  $currentDate;
    //                     }

    //                     $noti->save();
    //                     return $success;
    //                 }
    //             }
    //         }
    //     }
    // }
}
