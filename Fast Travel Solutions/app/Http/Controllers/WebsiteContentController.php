<?php

namespace App\Http\Controllers;

use App\Models\AffiliateApiForm;
use App\Models\BusinessModel;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\Destination;
use App\Models\NewsLetter;
use App\Models\NotificationEmail;
use App\Models\NotificationManagement;
use App\Models\Setting;
use App\Models\WebsiteBranding;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class WebsiteContentController extends Controller
{
    use FileUploadTrait;

    public function contact_us(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        $message = new ContactUs();
        $message->name = $request->name;
        $message->email = $request->email;
        $message->message = $request->message;
        $message->save();

        return response()->json([
            'status' => true,
            'message' => 'Your message has been received!',

        ]);
    }

    public function business_model(Request  $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'email' => 'required',
            'monthly_bookings' => 'required',


        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        $business = new BusinessModel();
        $business->company_name = $request->company_name;
        $business->email = $request->email;
        $business->monthly_bookings = $request->monthly_bookings;
        $business->save();

        return response()->json([
            'status' => true,
            'message' => 'Your query send to admin!',
        ]);
    }

    public function destinations_list(Request $request)
    {
        $name = $request->input('name');

        // Build the query
        $query = Destination::query();


        // Apply filters if provided
        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        // Execute the query and get results
        $results = $query->orderBy('created_at', 'desc')->paginate(5);


        return response()->json([
            'status' => true,
            'message' => 'Destinations list!',
            'response' => $results,
        ]);
    }

    public function destinations_get_by_id(Request $request)
    {

        $rules = [
            'destination_id' => 'required|exists:destinations,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        $destination = Destination::find($request->destination_id);

        return response()->json([
            'status' => true,
            'message' => 'Destination data',
            'response' => $destination
        ]);
    }

    public function affiliate_api_partner(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_email' => 'required|email|max:255|unique:affiliate_api_forms',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'description' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        $business = new AffiliateApiForm();
        $business->company_name = $request->company_name;
        $business->company_email = $request->company_email;
        $business->first_name = $request->first_name;
        $business->last_name = $request->last_name;
        $business->password = Hash::make($request->password);
        $business->description = $request->description;
        $business->save();

        $checkMail = NotificationManagement::where('type', 'Affiliate Api Partner')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {
            $patterns = [

                '/\{(company_name)}]?/',
                '/\{(company_email)}]?/',
                '/\{(first_name)}]?/',
                '/\{(last_name)}]?/',
                '/\{(description)}]?/',

            ];

            $replacements = [

                $request->company_name,
                $request->company_email,
                $request->first_name,
                $request->last_name,
                $request->description,

            ];

            $mail = preg_replace($patterns, $replacements, $checkMail->mail);

            $emailnotification = new NotificationEmail();
            $emailnotification->user_id = 1;
            $emailnotification->to_email =  'mussab.ahmad@viiontech.com';
            $emailnotification->email_subject = $checkMail->mail_subject;
            $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
            $emailnotification->schedule_date = date('Y-m-d H:i:s');
            $emailnotification->email_sent_status = 'N';
            $emailnotification->save();
        }

        $currentDate = now();
        $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
        $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

        $noti = NotificationEmail::find($emailnotification->id);

        $to_email = $noti->to_email;
        $email_subject = $noti->email_subject;
        $email_body = $noti->email_body;

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

            NotificationEmail::where('id', $noti->id)->update([
                'from_email' => $setting_comm_email->value,
                'from_name' => $setting_comm_email_name->value,
                'response' => 'sent successfully!',
                'email_sent_status' => 'Y',
                'sent_date' => $currentDate
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Your affiliate partner query send to admin!',
            ]);
        } catch (\Exception $e) {
            NotificationEmail::where('id', $noti->id)->update([
                'response' => $e->getMessage()

            ]);
        }
    }

    public function news_letter_api(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        $business = new NewsLetter();
        $business->email = $request->email;

        $business->save();

        $checkMail = NotificationManagement::where('type', 'Newsletter')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {
            $patterns = [

                '/\{(email)}]?/',

            ];

            $replacements = [

                $request->email,

            ];

            $mail = preg_replace($patterns, $replacements, $checkMail->mail);

            $emailnotification = new NotificationEmail();
            $emailnotification->user_id = 1;
            $emailnotification->to_email =  'malikshehzad423@gmail.com';
            $emailnotification->email_subject = $checkMail->mail_subject;
            $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
            $emailnotification->schedule_date = date('Y-m-d H:i:s');
            $emailnotification->email_sent_status = 'N';
            $emailnotification->save();
        }

        $currentDate = now();
        $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
        $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

        $noti = NotificationEmail::find($emailnotification->id);

        $to_email = $noti->to_email;
        $email_subject = $noti->email_subject;
        $email_body = $noti->email_body;

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

            NotificationEmail::where('id', $noti->id)->update([
                'from_email' => $setting_comm_email->value,
                'from_name' => $setting_comm_email_name->value,
                'response' => 'sent successfully!',
                'email_sent_status' => 'Y',
                'sent_date' => $currentDate
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Your newsletter query send to admin!',
            ]);
        } catch (\Exception $e) {
            NotificationEmail::where('id', $noti->id)->update([
                'response' => $e->getMessage()

            ]);
        }
    }

}


