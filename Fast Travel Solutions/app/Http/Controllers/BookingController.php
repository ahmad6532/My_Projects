<?php

namespace App\Http\Controllers;

use App\Mail\PaymentLinkMail;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\CustomerBooking;
use App\Models\BookingDetail;
use App\Models\BookingRequestCompany;
use App\Models\CardPayment;
use App\Models\Company;
use App\Models\FeedBack;
use App\Models\FleetType;
use App\Models\LinkTransfer;
use App\Models\Notification;
use App\Models\NotificationEmail;
use App\Models\NotificationManagement;
use App\Models\RouteFare;
use App\Models\Setting;
use App\Models\SMS;
use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Group;
use Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Traits\FileUploadTrait;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;
use Illuminate\Support\Str;
use Stripe\Checkout\Session as StripeSession;






class BookingController extends Controller
{
    use FileUploadTrait;

    public function calculate_fare(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            // 'car_type_id' => 'required|exists:fleet_types,id',
            'total_distance' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // $id = $request->car_type_id;

        $distance = intval($request->total_distance);

        $fleetFares = RouteFare::where('min_distance', '<=', $distance)
            ->where('max_distance', '>=', $distance)
            ->get();

        $fares = [];

        foreach ($fleetFares as $fare) {
            // Perform your fare calculation logic here

            $fleet_type = FleetType::find($fare->fleet_type_id);

            $fares[] = [
                'fleet_type_id' => $fare->fleet_type_id,
                'type_name' => $fleet_type->type_name,
                'fare' => round($fare->ride_fare * $distance, 2), // Assuming 'fare' is the column storing the fare amount
            ];
        }

        if ($fares) {

            return response()->json([
                'status' => true,
                'message' => 'Fares list According fleet types!',
                'response' => $fares,

            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Fleet Types does not exist! ',
            ]);
        }


        // $carTypeFare = RouteFare::where('fleet_type_id', $id) // for single fleet
        //     ->where('min_distance', '<=', $distance)
        //     ->where('max_distance', '>=', $distance)
        //     ->first();

        // if ($carTypeFare) {

        //     $fare = $carTypeFare->ride_fare * $request->total_distance;

        //     return response()->json([
        //         'status' => true,
        //         'message' => 'Booking Fare!',
        //         'response' => $fare,

        //     ]);
        // } else {

        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Booking Fare does not exist in range!',

        //     ]);
        // }
    }


    public function create_booking(Request $request) // Optimize Booking Code
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'company_id' => 'nullable|exists:companies,id',
            'booking_from_lat' => 'required',
            'booking_from_long' => 'required',
            'booking_to_lat' => 'required',
            'booking_to_long' => 'required',
            'booking_from_loc_name' => 'required|string',
            'booking_to_loc_name' => 'required|string',
            'booking_date' => 'required|date',
            'booking_local_date' => 'required|date',
            'booking_time' => 'required|string',
            'booking_local_time' => 'required|string',
            'booking_desc' => 'nullable|string',
            'return_date' => 'nullable|date',
            'return_local_date' => 'nullable|date',
            'return_time' => 'nullable|string',
            'return_local_time' => 'nullable|string',
            'head_passenger_mobile' => 'nullable|string',
            'head_passenger_email' => 'required',
            'head_passenger_name' => 'nullable|string',
            'promo_code' => 'nullable|string',
            'car_type_id' => 'required|exists:fleet_types,id',
            'confirm_via_email' => 'nullable|string',
            'confirm_via_sms' => 'nullable|string',
            'total_distance' => 'required',
            'totalDuration' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'stripe_token' => 'nullable|string', // Add this for card payments
        ]);



        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message
            return response()->json(['success' => false, 'message' => $errors]);
        }

        $booking_amount = $request->amount;
        if($request->promo_code_check){
            $coupon = Coupon::where('promo_code',$request->promo_code)
            ->where('start_date_time','<=',$request->current_date_time)
            ->where('end_date_time','>=',$request->current_date_time)
            ->where('is_active','1')
            ->first();
           if(!$coupon){
            return response()->json(['status' => false, 'message' => 'Invalid Promo Code: ']);
           }
           if($coupon->discount_type == 'price'){
            $booking_amount = $booking_amount - $coupon->discount;
           }elseif($coupon->discount_type == 'percentage'){
            $booking_amount = $booking_amount - ($booking_amount * $coupon->discount / 100);
           }
        }

        $rand = rand(100000, 999999); //send random booking number for tracking
        $paymentSuccess = false;

        // Handle payment based on payment type
        if ($request->payment_type == 'cash' || $request->payment_type == 'bank_transfer' || $request->payment_type == 'payment_link') {
            $paymentSuccess = true; // Assume payment is successful for cash and bank transfer
        } else if ($request->payment_type == 'card') {


            // $setting_stripe_live_key = Setting::where('parameter', 'STRIPE_KEY')->where('default_status', 'yes')->first();
            $setting_stripe_live_Secret = Setting::where('parameter', 'STRIPE_SECRET')->where('default_status', 'yes')->first();

            // $setting_stripe_local_key = Setting::where('parameter', 'LOCAL_STRIPE_KEY')->where('default_status', 'yes')->first();
            $setting_stripe_local_Secret = Setting::where('parameter', 'LOCAL_STRIPE_SECRET')->where('default_status', 'yes')->first();

            if ($setting_stripe_live_Secret) {

                Stripe\Stripe::setApiKey($setting_stripe_live_Secret->value);
            } else {

                Stripe\Stripe::setApiKey($setting_stripe_local_Secret->value);
            }

            // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            if (empty($request->stripe_token) || empty($booking_amount)) {
                return response()->json(['error' => 'Please Enter Stripe Token']);
            }

            try {
                // Create a PaymentIntent with automatic_payment_methods enabled
                $paymentIntent = PaymentIntent::create([
                    'amount' => $booking_amount * 100, // Amount in cents
                    'currency' => 'usd',
                    'payment_method' => $request->stripe_token, // Payment method ID received from client-side
                    'confirm' => true, // Attempt to confirm the payment immediately
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never', // Prevent any redirects
                    ],
                ]);

                $paymentSuccess = $paymentIntent->status == 'succeeded';
            } catch (ApiErrorException $e) {
                return response()->json(['status' => false, 'message' => 'Payment failed: ' . $e->getMessage()]);
            } catch (\Exception $e) {
                return response()->json(['status' => false,  'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
            }
        }



        if (!$paymentSuccess) { // if payment was not sucessfull then booking cant save
            return response()->json(['error' => 'Payment was not successful']);
        }

        $setting = Setting::where('parameter', 'booking_status')->first();
        $deduction_percentage = Setting::where('parameter', 'deduction_percentage')->first();

        if ($request->booking_return_status == 1) {

            $bookingPrice = $booking_amount * 2; // Original booking price
            $deduction_percentage = $deduction_percentage->value; // Percentage to deduct

            // Calculate the discount amount
            $deductionAmount = ($bookingPrice * $deduction_percentage) / 100;

            // Deduct the discount from the original price
            $finalPrice = $bookingPrice - $deductionAmount;
        } else {

            $bookingPrice = $booking_amount; // Original booking price
            $deduction_percentage = $deduction_percentage->value; // Percentage to deduct

            // Calculate the discount amount
            $deductionAmount = ($bookingPrice * $deduction_percentage) / 100;

            // Deduct the discount from the original price
            $finalPrice = $bookingPrice - $deductionAmount;
        }

        // Create a new booking
        $booking = new CustomerBooking();
        $booking->user_id = $request->user_id;
        $booking->company_id = $request->company_id;
        $booking->booking_from_lat = $request->booking_from_lat;
        $booking->booking_from_long = $request->booking_from_long;
        $booking->booking_to_lat = $request->booking_to_lat;
        $booking->booking_to_long = $request->booking_to_long;
        $booking->booking_from_loc_name = $request->booking_from_loc_name;
        $booking->booking_to_loc_name = $request->booking_to_loc_name;
        $booking->booking_date = $request->booking_date;
        $booking->booking_local_date = $request->booking_local_date;
        $booking->booking_time = $request->booking_time;
        $booking->booking_local_time = $request->booking_local_time;
        $booking->booking_desc = $request->booking_desc;
        $booking->return_date = $request->return_date;
        $booking->return_local_date = $request->return_local_date;
        $booking->return_time = $request->return_time;
        $booking->return_local_time = $request->return_local_time;
        $booking->head_passenger_mobile = $request->head_passenger_mobile;
        $booking->head_passenger_email = $request->head_passenger_email;
        $booking->head_passenger_name = $request->head_passenger_name;
        $booking->total_passenger = $request->total_passenger;
        $booking->promo_code = $request->promo_code;
        $booking->car_type_id = $request->car_type_id;
        $booking->confirm_via_email = $request->confirm_via_email;
        $booking->confirm_via_sms = $request->confirm_via_sms;
        $booking->total_distance = $request->total_distance;
        $booking->totalDuration = $request->totalDuration;
        $booking->booking_price = $request->booking_return_status == 1 ? $booking_amount * 2 : $booking_amount;
        $booking->deduction_price =  $finalPrice;
        $booking->tracking_number = 'FTS-' . $rand;
        $booking->booking_return_status = $request->booking_return_status ?? 0;

        if ($request->payment_type == 'payment_link') {
            $booking->active_status = 0;
        }
        if ($setting->value == 'manual' || $request->payment_type == 'payment_link' ||  $request->payment_type == 'bank_transfer') {

            // $booking->admin_status = $setting->value;
            $booking->admin_status = 'manual';
        }

        $booking->save();

        foreach ($request->booking_details as $value) { // Add booking Via if exist
            $booking_details = new BookingDetail();
            $booking_details->booking_id = $booking->id;
            $booking_details->via_name = $value['via_name'];
            $booking_details->latitude = $value['latitude'];
            $booking_details->longitude = $value['longitude'];
            $booking_details->save();
        }

        $payment = new UserPayment(); // Add user payment record if pay
        $payment->user_id = $request->user_id;
        $payment->booking_id = $booking->id;
        $payment->email = $request->head_passenger_email;
        $payment->amount = $booking_amount;
        $payment->payment_type = $request->payment_type;
        if ($request->payment_type == 'payment_link' || $request->payment_type == 'cash' || $request->payment_type == 'bank_transfer') {
            $payment->status = 0;
        } else {
            $payment->status = 1;
        }
        $payment->save();

        $booking_details_via = BookingDetail::select('via_name')
            ->where('booking_id', $booking->id)
            ->get();

        $booking_details = '';
        foreach ($booking_details_via as $data) {
            $booking_details .= <<<HTML
            <span class="location">{$data->via_name}</span><br><br>
            HTML;
        }

        $return_status = $request->booking_return_status == 1 ? 'Yes' : 'No';


        if ($request->payment_type == 'payment_link') { // If user payment type is payment link then save record in Link Transfer table
            $payment_link = new LinkTransfer();
            $payment_link->user_payment_id = $payment->id;
            $payment_link->via_email = $request->payment_link_via_email;
            $payment_link->via_sms = $request->payment_link_via_sms;
            $payment_link->save();
        }

        if ($request->payment_type == 'payment_link') { // if user select payment type is payment link then stripe session create for payment


            // $setting_stripe_live_key = Setting::where('parameter', 'STRIPE_KEY')->where('default_status', 'yes')->first();
            $setting_stripe_live_Secret = Setting::where('parameter', 'STRIPE_SECRET')->where('default_status', 'yes')->first();

            // $setting_stripe_local_key = Setting::where('parameter', 'LOCAL_STRIPE_KEY')->where('default_status', 'yes')->first();
            $setting_stripe_local_Secret = Setting::where('parameter', 'LOCAL_STRIPE_SECRET')->where('default_status', 'yes')->first();

            if ($setting_stripe_live_Secret) {

                Stripe\Stripe::setApiKey($setting_stripe_live_Secret->value);
            } else {

                Stripe\Stripe::setApiKey($setting_stripe_local_Secret->value);
            }

            // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $successUrl = route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking->id . '&payment_id=' . $payment->id . '&link_transfer_id=' . $payment_link->id;

            // Create a new checkout session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Online Ride Booking',
                            ],
                            'unit_amount' => $booking_amount * 100, // Amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                // 'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'success_url' => $successUrl, // Use the generated success URL
                'cancel_url' => route('payment.cancel'),
            ]);

            if ($request->input('payment_link_via_email')) { // if user get payment link via email

                // Mail::to($userEmail)->send(new PaymentLinkMail($session->url));

                $checkMail = NotificationManagement::where('type', 'Payment Link')->first(); // Sending Email Process Start

                if ($checkMail->send_email == "Y") {

                    $queryUser = NotificationManagement::where('type', 'Payment Link')
                        ->where('user_type', 'LIKE', '%user%')
                        ->exists();


                    if ($queryUser) {

                        $patterns = [
                            '/\{(url)}]?/',
                        ];
                        $replacements = [
                            $session->url,
                        ];

                        $mail = preg_replace($patterns, $replacements, $checkMail->mail); // url varibale replace in mail body

                        $emailnotification = new NotificationEmail(); //Email save for record or check status
                        $emailnotification->booking_id = $booking->id;
                        $emailnotification->user_id = $request->user_id;
                        $emailnotification->to_email = $request->payment_link_via_email;
                        $emailnotification->email_subject = $checkMail->mail_subject;
                        $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                        $emailnotification->schedule_date = date('Y-m-d H:i:s');
                        $emailnotification->email_sent_status = 'N';
                        $emailnotification->payment_status = 'un-paid';
                        $emailnotification->save();
                    }


                    // Get SMPT Credentails for settings table and send email
                    $currentDate = now();
                    $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                    $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                    $noti = NotificationEmail::find($emailnotification->id); // Fetch records for email sending to user
                    $to_email = $noti->to_email; // User Email
                    $email_subject = $noti->email_subject; // Email Subject
                    $email_body = $noti->email_body; // Email Body

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

                        NotificationEmail::where('id', $noti->id)->update([ // when email successfully send record updated to set status email send successfully
                            'from_email' => $setting_comm_email->value,
                            'from_name' => $setting_comm_email_name->value,
                            'response' => 'sent successfully!',
                            'email_sent_status' => 'Y',
                            'sent_date' => $currentDate
                        ]);
                    } catch (\Exception $e) {
                        NotificationEmail::where('id', $noti->id)->update([
                            'response' => $e->getMessage() // if email not send save error in db
                        ]);
                    }
                }
            } else if ($request->input('payment_link_via_sms')) { // if user want to get payment link on sms

                $checkMail = NotificationManagement::where('type', 'Payment Link')->first(); // Sending SMS Process Start
                if ($checkMail->send_sms == "Y") {
                    $patterns = [
                        '/\{(tracking_number)}]?/',
                    ];

                    $replacements = [
                        $session->url,
                    ];

                    $sms_body = preg_replace($patterns, $replacements, $checkMail->mobile_app_description);
                    $currentDate = now();
                    $setting_comm_sms_sender_id = Setting::where('parameter', 'communication_sms_sender_id')->first();

                    $sms = new SMS();
                    $sms->user_id = $request->user_id;
                    $sms->phone_number = $request->payment_link_via_sms;
                    $sms->sms_body = $sms_body;
                    $sms->from_phone_number =  $setting_comm_sms_sender_id->value;
                    $sms->sms_schedule_date = date('Y-m-d H:i:s');
                    $sms->sms_sent_status = 'N';
                    $sms->save();


                    // $update_sms_status = SMS::find($sms->id);

                    // SMS::where('id', $update_sms_status->id)->update([
                    //     'from_phone_number' => $setting_comm_sms_sender_id->value,
                    //     'response' => 'sent successfully!',
                    //     'sms_sent_status' => 'Y',
                    //     'sent_date' => $currentDate
                    // ]);

                }
            }
        }

        if ($request->payment_type == 'card') { // if user pay from card then save details in Card payment table
            $bank_details = new CardPayment();
            $bank_details->user_payment_id = $payment->id;
            $bank_details->stripe_token = $request->stripe_token;
            $bank_details->save();
        }

        $type = FleetType::find($booking->car_type_id);
        $type_name = $type->type_name;

        $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
        $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time


        if (($setting->value == 'auto') && ($request->payment_type == 'card' || $request->payment_type == 'cash')) {

            // send booking request to all companies when booking create
            $query = Company::query();
            $query->where('status', 1)->whereHas('companyToFleet',function($q){
                $q->where('active_status',1)->where('is_deleted',0);
            });
            $companies = $query->get();
            foreach ($companies as $value) {

                $randomString = Str::random(16);

                $booking_request_companies = new BookingRequestCompany();
                $booking_request_companies->booking_id = $booking->id;
                $booking_request_companies->company_id = $value->id;
                $booking_request_companies->token = $randomString;
                $booking_request_companies->save();


                $booking_id = $booking->id;
                $company_id = $value->id;
                $tracking_number = $booking->tracking_number;
                $company_email = $value->company_email;
                $booking_req_id = $booking_request_companies->id;
                $deduction_amount = $booking->deduction_price;



                $this->quick_quote_email($request, $tracking_number, $booking_id, $company_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $deduction_amount, $booking_details, $return_status);
            }
        }

        // Get booking details/Via sending in response to diplay booking data on frontend
        $details = BookingDetail::where('booking_id', $booking->id)->get();
        $booking->booking_details = $details;


        //if user want to get booking confirmation message on email
        if ($request->input('confirm_via_email')) {

            $checkMail = NotificationManagement::where('type', 'Booking Email')->first(); // Sending Email Process Start
            if ($checkMail->send_email == "Y") {

                $queryUser = NotificationManagement::where('type', 'Booking Email')
                    ->where('user_type', 'LIKE', '%user%')
                    ->exists();

                $queryUserFeedback = NotificationManagement::where('type', 'feed_back_email')
                    ->where('user_type', 'LIKE', '%user%')
                    ->exists();


                if ($queryUser || $queryUserFeedback) {

                    $patterns = [

                        '/\{(tracking_number)}]?/',
                        '/\{(head_passenger_name)}]?/',
                        '/\{(user_email)}]?/',
                        '/\{(booking_from_loc_name)}]?/',
                        '/\{(booking_to_loc_name)}]?/',
                        '/\{(booking_date)}]?/',
                        '/\{(booking_time)}]?/',
                        '/\{(booking_desc)}]?/',
                        '/\{(total_distance)}]?/',
                        '/\{(totalDuration)}]?/',
                        '/\{(amount)}]?/',
                        '/\{(booking_loc_details)}]?/',
                        '/\{(return_status)}]?/',

                    ];

                    $replacements = [

                        $booking->tracking_number,
                        $request->head_passenger_name ? $request->head_passenger_name : 'FTS - Customer',
                        $request->head_passenger_email,
                        $request->booking_from_loc_name,
                        $request->booking_to_loc_name,
                        $request->booking_local_date,
                        $request->booking_local_time,
                        $request->booking_desc,
                        $request->total_distance,
                        $request->totalDuration,
                        $booking->booking_price,
                        $booking_details,
                        $return_status

                    ];

                    // $emailresponse = $this->emailStructure($NotifiDetail->header, $NotifiDetail->footer);


                    $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                    $emailnotification = new NotificationEmail();
                    $emailnotification->booking_id = $booking->id;
                    $emailnotification->user_id = $request->user_id;
                    $emailnotification->to_email =  $request->confirm_via_email;
                    $emailnotification->email_subject = $checkMail->mail_subject;
                    $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                    // $emailnotification->email_body = $mail;
                    $emailnotification->schedule_date = date('Y-m-d H:i:s');
                    $emailnotification->email_sent_status = 'N';
                    if ($request->payment_type == 'payment_link') {
                        $emailnotification->payment_status = 'un-paid';
                    }
                    $emailnotification->save();


                    //============================feedback email sending================
                    $checkMail_feedback = NotificationManagement::where('type', 'feed_back_email')->first();


                    $feed_back_patterns = [

                        '/\{(customer_name)}]?/',
                        '/\{(booking_id)}]?/',

                    ];

                    $feed_back_replacements = [

                        $request->head_passenger_name ? $request->head_passenger_name : 'FTS - Customer',
                        $booking->id,

                    ];


                    $feedback_mail = preg_replace($feed_back_patterns, $feed_back_replacements, $checkMail_feedback->mail);


                    // Assuming you have these variables
                    $bookingDate = $request->booking_date; // The booking date (Y-m-d format)
                    $bookingTime = $request->booking_time;   // The booking time (H:i:s format)
                    $totalDuration = $request->totalDuration;   // Total duration as string (for example '1h 23m')

                    // Combine booking date and time with AM/PM
                    $bookingDateTime = Carbon::createFromFormat('Y-m-d h:i A', $bookingDate . ' ' . $bookingTime);

                    // Initialize hours and minutes
                    $hours = 0;
                    $minutes = 0;

                    // Manually extract hours and minutes from total duration
                    if (preg_match('/(\d+)h/', $totalDuration, $match)) {
                        $hours = (int) $match[1];  // Extract hours if present
                    }
                    if (preg_match('/(\d+)m/', $totalDuration, $match)) {
                        $minutes = (int) $match[1];  // Extract minutes if present
                    }

                    // Add hours and minutes to the booking datetime
                    $scheduleDateTime = $bookingDateTime->addHours($hours)->addMinutes($minutes);

                    // Now $scheduleDateTime contains the new scheduled date and time
                    // echo $scheduleDateTime;  // e.g. 2024-10-14 03:53 PM

                    $emailnotification_feed_back = new NotificationEmail();
                    $emailnotification_feed_back->booking_id = $booking->id;
                    $emailnotification_feed_back->user_id = $request->user_id;
                    $emailnotification_feed_back->to_email =  $request->confirm_via_email;
                    $emailnotification_feed_back->email_subject = $checkMail_feedback->mail_subject;
                    $emailnotification_feed_back->email_body = $checkMail_feedback->header . $feedback_mail . $checkMail_feedback->footer;
                    $emailnotification_feed_back->schedule_date = $scheduleDateTime;
                    $emailnotification_feed_back->email_sent_status = 'N';
                    if ($request->payment_type == 'payment_link') {
                        $emailnotification_feed_back->payment_status = 'un-paid';
                    }
                    $emailnotification_feed_back->save();
                    //     }
                    // }


                    //Sending Email Process of user booking email
                    if ($request->payment_type != 'payment_link') { //Sending Email Process

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
                                'message' => 'Booking created successfully!',
                                'response' => $booking,

                            ]);
                        } catch (\Exception $e) {
                            NotificationEmail::where('id', $noti->id)->update([
                                'response' => $e->getMessage()
                            ]);
                        }
                    } else {

                        return response()->json([
                            'status' => true,
                            'message' => 'Booking created successfully!',
                            'response' => $booking,

                        ]);
                    }
                } else {

                    return response()->json([
                        'status' => true,
                        'message' => 'Booking created successfully!',
                        'response' => $booking,

                    ]);
                }
            } else {

                return response()->json([
                    'status' => true,
                    'message' => 'Booking created successfully!',
                    'response' => $booking,

                ]);
            }
        } else if ($request->input('confirm_via_sms')) {

            $checkMail = NotificationManagement::where('type', 'Booking Email')->first(); // Sending Email Process Start
            if ($checkMail->send_sms == "Y") {
                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',

                ];

                $replacements = [

                    $booking->tracking_number,
                    $request->head_passenger_name ? $request->head_passenger_name : 'FTS - Customer',
                    $request->head_passenger_email,
                    $request->booking_from_loc_name,
                    $request->booking_to_loc_name,
                    $request->booking_local_date,
                    $request->booking_local_time,
                    $request->booking_desc,
                    $request->total_distance,
                    $request->totalDuration,
                    $booking_amount,

                ];

                $sms_body = preg_replace($patterns, $replacements, $checkMail->mobile_app_description);

                $setting_comm_sms_sender_id = Setting::where('parameter', 'communication_sms_sender_id')->first();

                $sms = new SMS();
                $sms->user_id = $request->user_id;
                $sms->phone_number = $request->confirm_via_sms;
                $sms->sms_body = $sms_body;
                $sms->from_phone_number =  $setting_comm_sms_sender_id->value;
                $sms->sms_schedule_date = date('Y-m-d H:i:s');
                $sms->sms_sent_status = 'N';
                $sms->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully!',
                'response' => $booking,

            ]);
        }
    }

    public function payment_success(Request $request)
    {

        // $setting_stripe_live_key = Setting::where('parameter', 'STRIPE_KEY')->where('default_status', 'yes')->first();
        $setting_stripe_live_Secret = Setting::where('parameter', 'STRIPE_SECRET')->where('default_status', 'yes')->first();

        // $setting_stripe_local_key = Setting::where('parameter', 'LOCAL_STRIPE_KEY')->where('default_status', 'yes')->first();
        $setting_stripe_local_Secret = Setting::where('parameter', 'LOCAL_STRIPE_SECRET')->where('default_status', 'yes')->first();

        if ($setting_stripe_live_Secret) {

            Stripe\Stripe::setApiKey($setting_stripe_live_Secret->value);
        } else {

            Stripe\Stripe::setApiKey($setting_stripe_local_Secret->value);
        }

        // Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');
        Log::info('Session ID received: ', ['session_id' => $sessionId]);

        // Fetch the session details or perform any post-payment actions
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error: ', ['error' => $e->getMessage()]);
            return 'Payment failed. Please try again.';
        }

        $notificationEmails = NotificationEmail::where('booking_id', $request->booking_id)->get();

        foreach ($notificationEmails as $notificationEmail) {
            $notificationEmail->payment_status = 'paid';
            $notificationEmail->save();
        }

        $user_booking = CustomerBooking::find($request->booking_id);
        $user_booking->active_status = 1;
        $user_booking->save();

        $user_payments = UserPayment::find($request->payment_id);
        $user_payments->status = 1;
        $user_payments->save();

        $link_transfer = LinkTransfer::find($request->link_transfer_id);
        $link_transfer->stripe_payment_intent_id = $session->payment_intent;
        $link_transfer->save();

        return view('payment_success');

        // return 'Payment successful! Payment ID: ' . $session->payment_intent;

        return 'Payment successful! Payment ID: ' . $session->payment_intent . ' booking_id: ' . $request->booking_id  . ' payment_id_table:' . $request->payment_id . 'link_transfer_id: ' . $request->link_transfer_id;
    }

    public function quick_quote_email($request, $tracking_number, $booking_id, $company_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $deduction_amount, $booking_details, $return_status)
    {

        $checkMail = NotificationManagement::where('type', 'quick_quote')->first(); // Sending Email Process Start

        if ($checkMail->send_email == "Y") {

            // Check if the user_type contains 'company' or 'driver'
            $queryCompany = NotificationManagement::where('type', 'quick_quote')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();

            $queryDriver = NotificationManagement::where('type', 'quick_quote')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();

            if ($queryCompany || $queryDriver) {

                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',
                    // '/\{(deduction_amount)}]?/',
                    '/\{(booking_id)}]?/',
                    '/\{(company_id)}]?/',
                    '/\{(booking_req_id)}]?/',
                    '/\{(randomString)}]?/',
                    '/\{(type_name)}]?/',
                    '/\{(date)}]?/',
                    '/\{(time)}]?/',
                    '/\{(booking_loc_details)}]?/',
                    '/\{(return_status)}]?/',


                ];

                $replacements = [

                    $tracking_number,
                    $request->head_passenger_name ? $request->head_passenger_name : 'FTS - Customer',
                    $request->head_passenger_email,
                    $request->booking_from_loc_name,
                    $request->booking_to_loc_name,
                    $request->booking_local_date,
                    $request->booking_local_time,
                    $request->booking_desc,
                    $request->total_distance,
                    $request->totalDuration,
                    // $request->amount,
                    $deduction_amount,
                    $booking_id,
                    $company_id,
                    $booking_req_id,
                    $randomString,
                    $type_name,
                    $date,
                    $time,
                    $booking_details,
                    $return_status


                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->booking_id = $booking_id;
                $emailnotification->company_id = $company_id;
                $emailnotification->to_email =  $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                if ($request->payment_type == 'payment_link') {
                    $emailnotification->payment_status = 'un-paid';
                }
                $emailnotification->save();

                $notification = new Notification();
                $notification->company_id = $company_id;
                $notification->title = 'FTS - Quick Quote Notification';
                $notification->description = 'You have received a new booking request for a quotation. Please provide a quote! #' . $tracking_number;
                $notification->schedule_date = date('Y-m-d H:i:s');
                $notification->sent_status = 'Y';
                $notification->save();

                //Email sending process start
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
                } catch (\Exception $e) {
                    NotificationEmail::where('id', $noti->id)->update([
                        'response' => $e->getMessage()
                    ]);
                }
            }
        }
    }


    public function delete_booking($id)
    {
        $booking = CustomerBooking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found!',
            ]);
        }
        $bookingDetails = BookingDetail::where('booking_id', $id)->exists();

        if ($bookingDetails) {
            BookingDetail::where('booking_id', $id)->delete();
        }

        $booking->delete();

        return response()->json([
            'status' => true,
            'message' => 'Booking and associated details deleted successfully!',
        ]);
    }

    function booking(CustomerBooking $id)
    { // Route and Model Binding Example Booking data directly get in function parameter

        return $id;
    }

    public function booking_history(Request $request)
    {

        $auth = Auth::user();

        if (($auth->role_id == 3 || $auth->role_id == 1) || ($auth->id == $request->user_id || $auth->role_id == 1)) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'status' => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->status == 'active') {

                $bookings = CustomerBooking::where(function ($query) use ($auth, $request) {
                    $query->where('user_id', $request->user_id)
                        ->orWhere('head_passenger_email', $auth->email);
                })->where('active_status', 1)
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                if ($bookings->isNotEmpty()) {


                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();

                        if ($booking_via) {

                            $value->booking_details = $booking_via;
                        } else {

                            $value->booking_details = [];
                        }
                    }

                    foreach ($bookings as $value) {

                        $cartype = FleetType::find($value->car_type_id);

                        if ($cartype) {

                            $value->fleet_details = $cartype;
                        } else {

                            $value->fleet_details = [];
                        }
                    }


                    return response()->json([
                        'status' => true,
                        'message' => 'Active booking list!',
                        'response' => $bookings,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Active booking not found!',
                    ]);
                }
            } else if ($request->status == 'future') {

                $current_date = Carbon::now()->toDateString();

                $bookings = CustomerBooking::where(function ($query) use ($auth, $request) {
                    $query->where('user_id', $request->user_id)
                        ->orWhere('head_passenger_email', $auth->email);
                })->where('active_status', 1)->where('booking_date', '>', $current_date)
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                if ($bookings->isNotEmpty()) {

                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();

                        if ($booking_via) {

                            $value->booking_details = $booking_via;
                        } else {

                            $value->booking_details = [];
                        }
                    }

                    foreach ($bookings as $value) {

                        $cartype = FleetType::find($value->car_type_id);

                        if ($cartype) {

                            $value->fleet_details = $cartype;
                        } else {

                            $value->fleet_details = [];
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Future booking list!',
                        'response' => $bookings,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Future booking not found!',
                    ]);
                }
            } else if ($request->status == 'past') {

                $current_date = Carbon::now()->toDateString();
                $bookings = CustomerBooking::where(function ($query) use ($auth, $request) {
                    $query->where('user_id', $request->user_id)
                        ->orWhere('head_passenger_email', $auth->email);
                })->where('active_status', 1)->where('booking_date', '<', $current_date)
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                if ($bookings->isNotEmpty()) {

                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();

                        if ($booking_via) {

                            $value->booking_details = $booking_via;
                        } else {

                            $value->booking_details = [];
                        }
                    }

                    foreach ($bookings as $value) {

                        $cartype = FleetType::find($value->car_type_id);

                        if ($cartype) {

                            $value->fleet_details = $cartype;
                        } else {

                            $value->fleet_details = [];
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Past booking list!',
                        'response' => $bookings,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Past booking not found!',
                    ]);
                }
            } else if ($request->status == 'report') {

                $bookings = CustomerBooking::where(function ($query) use ($auth, $request) {
                    $query->where('user_id', $request->user_id)
                        ->orWhere('head_passenger_email', $auth->email);
                })->where('active_status', 1)->whereMonth('booking_date', $request->month)->whereYear('booking_date', $request->year)
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                if ($bookings->isNotEmpty()) {

                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();

                        if ($booking_via) {

                            $value->booking_details = $booking_via;
                        } else {

                            $value->booking_details = [];
                        }
                    }

                    foreach ($bookings as $value) {

                        $cartype = FleetType::find($value->car_type_id);

                        if ($cartype) {

                            $value->fleet_details = $cartype;
                        } else {

                            $value->fleet_details = [];
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Report list!',
                        'response' => $bookings,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Report not found against date!',
                    ]);
                }
            }
        } else {

            return response()->json([
                'status' => true,
                'message' => 'You have no permission to see bookings!',
            ]);
        }
    }

    public function track_booking(Request $request)
    {


        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'tracking_number' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        $booking = CustomerBooking::where('tracking_number', $request->tracking_number)->where('active_status', 1)->first();

        if ($booking) {

            $booking_via = BookingDetail::where('booking_id', $booking->id)->get();

            if ($booking_via) {

                $booking->booking_details = $booking_via;
            } else {

                $booking->booking_details = [];
            }

            $cartype = FleetType::find($booking->car_type_id);

            if ($cartype) {

                $booking->fleet_details = $cartype;
            } else {

                $booking->fleet_details = [];
            }


            return response()->json([
                'status' => true,
                'message' => 'User booking!',
                'response' => $booking,
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Booking not found against tracking number!',
            ]);
        }
    }

    public function booking_feedback(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:customer_bookings,id',
            'on_time' => 'required',
            'driver_attitude' => 'required',
            'vehicle_standard' => 'required',
            'service_level' => 'required',
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

        $feedback = new FeedBack();
        $feedback->booking_id = $request->booking_id;
        $feedback->on_time = $request->on_time;
        $feedback->driver_attitude = $request->driver_attitude;
        $feedback->vehicle_standard = $request->vehicle_standard;
        $feedback->service_level = $request->service_level;
        $feedback->email = $request->email;
        $feedback->comments = $request->comments;
        $feedback->save();


        //Average Ratings Add  against company
        $get_company_id = CustomerBooking::find($request->booking_id);

        $company_bookings = CustomerBooking::where('company_id', $get_company_id->company_id)->get();


        // Initialize totals and count variables
        // $totalOnTime = 0;
        // $totalDriverAttitude = 0;
        // $totalVehicleStandard = 0;
        $totalServiceLevel = 0;
        $feedbackCount = 0;

        foreach ($company_bookings as $booking) {
            // Retrieve feedback for each booking
            $feedbacks = FeedBack::where('booking_id', $booking->id)
                ->select('on_time', 'driver_attitude', 'vehicle_standard', 'service_level')
                ->get();

            foreach ($feedbacks as $feedback) {
                // $totalOnTime += $feedback->on_time;
                // $totalDriverAttitude += $feedback->driver_attitude;
                // $totalVehicleStandard += $feedback->vehicle_standard;
                $totalServiceLevel += $feedback->service_level;
                $feedbackCount++;
            }
        }

        if ($feedbackCount === 0) {
            // return response()->json([
            //     'status' => false,
            //     'message' => 'No feedback found for the bookings',
            // ]);

        } else {

            // Calculate averages for each category
            // $averageOnTime = $totalOnTime / $feedbackCount;
            // $averageDriverAttitude = $totalDriverAttitude / $feedbackCount;
            // $averageVehicleStandard = $totalVehicleStandard / $feedbackCount;
            $averageServiceLevel = $totalServiceLevel / $feedbackCount;

            // Calculate the overall average
            // $overallAverage = ($averageOnTime + $averageDriverAttitude + $averageVehicleStandard + $averageServiceLevel) / 4;

            $companies = Company::find($get_company_id->company_id);
            $companies->average_ratings =  round($averageServiceLevel, 2);
            $companies->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'You have rate this booking successfully',
        ]);
    }
}
