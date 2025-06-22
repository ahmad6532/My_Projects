<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use DB;
use Mail;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\NotificationEmail;
use App\Models\NotificationSMS;
use App\Models\EmployeeDetail;
use App\Models\Config;

class CommunicationController extends BaseController
{
    use HasApiTokens, HasFactory, Notifiable;

    public function storeEmailcommunication(Request $request) {


        $compaign_entry_no = NotificationEmail::max('campaign_entry');
        $compaign_entry_no = $compaign_entry_no + 1;

        $email_to =  $request->input('user_type');
        $emp_emails = EmployeeDetail::where('is_deleted','0')->get(['id','emp_email']);
        if($emp_emails) {
            foreach ($emp_emails as $key => $emp_email) {

                $noti_email = new NotificationEmail();
                $noti_email->user_id = $emp_email->id;
                $noti_email->to_email = $emp_email->emp_email;
                $noti_email->email_subject = $request->email_subject;
                $noti_email->email_body = $request->email_body;

                if($request->email_option_schedule == 'email_schedule') {
                    $noti_email->schedule_date = date('Y-m-d H:i:s', (strtotime(($request->email_send_date).' '.$request->email_send_time) - 18000));
                }
                else {
                    $noti_email->schedule_date = date('Y-m-d H:i:s');
                }
                $noti_email->email_sent_status = 'N';
                $noti_email->campaign_entry = $compaign_entry_no;

                $noti_email->save();
            }
        }
        if($request->additional_email != '') {

            foreach(explode(',', $request->additional_email) as $key => $email) {

                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $noti_email = new NotificationEmail();
                    $noti_email->to_email = $email;
                    $noti_email->email_subject = $request->email_subject;
                    $noti_email->email_body = $request->email_body;
                    if($request->email_option_schedule == 'email_schedule') {

                        $noti_email->schedule_date = $request->email_send_date.' '.$request->email_send_time.':00';
                    }
                    else {

                        $noti_email->schedule_date = date('Y-m-d H:i:s');
                    }

                    $noti_email->email_sent_status = 'N';
                    $noti_email->campaign_entry = $compaign_entry_no;

                    $noti_email->save();
                }
            }
        }
      $this->send_comm_email();
        
        return $this->sendResponse([], 'Email sent successfully!');
        
     
    }

    public function storeSMScommunication(Request $request) {

        $compaign_entry_no = NotificationSMS::max('campaign_entry');
        $compaign_entry_no = $compaign_entry_no + 1;

      
    
        $user_phone = EmployeeDetail::where('is_deleted','0')->get(['id','emp_phone']);

        if($user_phone) {

            foreach ($user_phone as $key => $usr_phone) {

                if($usr_phone->emp_phone != null) {

                    $noti_sms = new NotificationSMS();
                    $noti_sms->user_id = $usr_phone->id;
                    $noti_sms->phone_number = $usr_phone->emp_phone;
                    $noti_sms->sms_body = $request->sms_body;

                    if($request->sms_option_schedule == 'sms_schedule') {

                        $noti_sms->sms_schedule_date = $request->sms_send_date.' '.$request->sms_send_time.':00';
                    }
                    else {

                        $noti_sms->sms_schedule_date = date('Y-m-d H:i:s');
                    }

                    $noti_sms->sms_sent_status = 'N';
                    $noti_sms->campaign_entry = $compaign_entry_no;

                    $noti_sms->save();
                    
                }
            }
        }
        

        if($request->additional_phone != '') {

            foreach(explode(',', $request->additional_phone) as $key => $phone) {

                if(preg_match("/^[0-9]{11}$/", $phone)) {

                    $noti_sms = new NotificationSMS();
                    $noti_sms->phone_number = $phone;
                    $noti_sms->sms_body = $request->sms_body;

                    if($request->sms_option_schedule == 'sms_schedule') {

                        $noti_sms->sms_schedule_date = $request->sms_send_date.' '.$request->sms_send_time.':00';
                    }
                    else {

                        $noti_sms->sms_schedule_date = date('Y-m-d H:i:s');
                    }

                    $noti_sms->sms_sent_status = 'N';
                    $noti_sms->campaign_entry = $compaign_entry_no;

                    $noti_sms->save();
                }
            }
        }
        $this->send_comm_sms();
    }

    public function send_comm_email()
    {
        $number_email = 100;

        $currentDate = date('Y-m-d H:i:s');


        $setting_comm_email = Config::where('key', 'communication_email')->first();
        $setting_comm_email_name = Config::where('key', 'communication_email_name')->first();

        $noti = NotificationEmail::where('email_sent_status', 'N')->where('schedule_date', '<=',$currentDate)->orderBy('schedule_date', 'DESC')->limit($number_email)->get();
        if(isset($noti) && count($noti) > 0)
        {
            foreach ($noti as $key => $element) {
                $to_email = $element->to_email;
                $email_subject = $element->email_subject;
                $email_body = $element->email_body;
                $cc_email = $element->cc_email;
                $bcc_email = $element->bcc_email;


                if(count(Mail::failures()) > 0) {

                    $mail_err = '';

                    foreach(Mail::failures as $email_address) {
                        $mail_err .= $email_address.'=>';
                    }

                    NotificationEmail::where('id', $element->id)->update(array('response' =>  $mail_err));
                }
                else {
                    NotificationEmail::where('id', $element->id)->update(array('from_email' => $setting_comm_email->value, 'from_name' => $setting_comm_email_name->value,'response' =>  'sent successfully!', 'email_sent_status' => 'Y', 'sent_date'=>$currentDate));
                }
            }
        }
    }

    public function send_comm_sms()
    {
        $number_sms = 20;

        $currentDate = date('Y-m-d H:i:s');
        

        $setting_comm_sms_username = Config::where('key', 'communication_sms_username')->first();
        $setting_comm_sms_password = Config::where('key', 'communication_sms_password')->first();
        $setting_comm_sms_sender_id = Config::where('key', 'communication_sms_sender_id')->first();

        $noti = NotificationSMS::where('sms_sent_status', 'N')->where('sms_schedule_date','<=', $currentDate)->orderBy('sms_schedule_date', 'DESC')->limit($number_sms)->get();

        if(count($noti) > 0)
        {
            foreach ($noti as $key => $element)
            {
                $username = $setting_comm_sms_username->value;
                $password = $setting_comm_sms_password->value;
                $mobile = $element->phone_number;
                $sender = $setting_comm_sms_sender_id->value;
                $message = $element->sms_body;

                ////sending sms

                $post = "Username=".$username."&Password=".$password."&From=".urlencode($sender)."&To=".urlencode($mobile)."&Message=".urlencode($message);

                $url = "https://connect.jazzcmt.com/sendsms_url.html?".$post;

                $ch = curl_init();
                $timeout = 10; // set to zero for no timeout
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch);
                if ($result != "Message Sent Successfully!") {

                    NotificationSMS::where('id', $element->id)->update(array('response' =>  $result));

                }
                else {

                    NotificationSMS::where('id', $element->id)->update(array('from_phone_number' => $setting_comm_sms_sender_id->value, 'response' =>  $result, 'sms_sent_status' => 'Y', 'sent_date'=>$currentDate));

                }

            }


        }


    }

}
