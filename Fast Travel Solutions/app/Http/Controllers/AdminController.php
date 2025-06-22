<?php

namespace App\Http\Controllers;

use App\Models\BusinessModel;
use App\Models\Company;
use App\Mail\CompanyRegisterPasswordEmail;
use App\Models\AboutUs;
use App\Models\AffiliateApiForm;
use App\Models\BankTransferAccount;
use App\Models\BecomeDriverPage;
use App\Models\BecomeOperatorPage;
use App\Models\BookingContent;
use App\Models\BookingDetail;
use App\Models\BookingRequestCompany;
use App\Models\BusinessPage;
use App\Models\ChangeRequest;
use App\Models\CompanyDocument;
use App\Models\ContactPage;
use App\Models\Coupon;
use App\Models\CustomerBooking;
use App\Models\Destination;
use App\Models\Driver;
use App\Models\DriverOtherDocument;
use App\Models\FAQ;
use App\Models\FaqSectionName;
use App\Models\Fleet;
use App\Models\FleetAdditionalCharge;
use App\Models\FleetType;
use App\Models\LinkTransfer;
use App\Models\Notification;
use App\Models\NotificationEmail;
use App\Models\NotificationManagement;
use App\Models\QuoteAgainstRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use App\Models\RouteFare;
use App\Models\Setting;
use App\Models\SMS;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\UserPayment;
use App\Models\WebsiteBranding;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Stripe;
use Stripe\Checkout\Session as StripeSession;



class AdminController extends Controller
{
    use FileUploadTrait;

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }


    public function add_fare(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'fleet_type_id' => 'required|exists:fleet_types,id',
                'min_distance' => 'required',
                'max_distance' => 'required',
                'ride_fare' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            $fare = new RouteFare();
            $fare->fleet_type_id = $request->fleet_type_id;
            $fare->min_distance = $request->min_distance;
            $fare->max_distance = $request->max_distance;
            $fare->ride_fare = $request->ride_fare;
            $fare->save();

            return response()->json([
                'status' => true,
                'message' => 'Fare added successfully',
                'response' => $fare
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to add fare!',
            ]);
        }
    }

    public function approved_company(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:companies,id',
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

            $company = Company::find($request->company_id);

            if ($request->status == 'approved') {
                $company->status = 1;
            } else if ($request->status == 'banned') {
                $company->status = 2;
            } else if ($request->status == 'pending') {
                $company->status = 0;
            }

            $company->save();

            if ($request->status == 'approved') {

                $password = Str::random(8);

                $user = User::where('email', $company->company_email)->first();
                $user->active_status = 1;
                $user->password = bcrypt($password);
                $user->save();

                // $documents = CompanyDocument::where('company_id', $request->company_id)->first();
                // $documents->document_status = 'accepted';
                // $documents->save();

                $checkMail = NotificationManagement::where('type', 'User Register Notification')->first();


                if ($checkMail->send_email == "Y") {

                    $queryCompany = NotificationManagement::where('type', 'User Register Notification')
                        ->where('user_type', 'LIKE', '%company%')
                        ->exists();


                    if ($queryCompany) {

                        $patterns = [
                            '/\{(user_name)}]?/',
                            '/\{(user_email)}]?/',
                            '/\{(password)}]?/'
                        ];

                        $replacements = [
                            $user->name,
                            $user->email,
                            $password
                        ];

                        $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                        $emailnotification = new NotificationEmail();
                        $emailnotification->user_id = $user->id;
                        $emailnotification->to_email = $user->email;
                        $emailnotification->email_subject = $checkMail->mail_subject;
                        $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                        $emailnotification->schedule_date = date('Y-m-d H:i:s');
                        $emailnotification->email_sent_status = 'N';
                        $emailnotification->save();
                        // }

                        $currentDate = now();
                        $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                        $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                        $noti = NotificationEmail::find($emailnotification->id);

                        $to_email = $noti->to_email;
                        $email_subject = $noti->email_subject;
                        $email_body = $noti->email_body;

                        try {

                            Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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
                                'message' => 'Company approved successfully',
                            ]);
                        } catch (\Exception $e) {
                            NotificationEmail::where('id', $noti->id)->update([
                                'response' => $e->getMessage()
                            ]);
                        }
                    } else {

                        return response()->json([
                            'status' => true,
                            'message' => 'Company approved but registration email cant send please on email ',
                        ]);
                    }
                }
            } else {

                if ($request->status == 'banned') {

                    return response()->json([
                        'status' => true,
                        'message' => 'Company banned successfully',
                    ]);
                } else {

                    return response()->json([
                        'status' => true,
                        'message' => 'Company status pending',
                    ]);
                }
            }

            // try {

            //     Mail::to($company->company_email)->send(new CompanyRegisterPasswordEmail($company->company_email, $password));
            // } catch (\Exception $e) {
            //     // Log the exception for debugging purposes
            //     Log::error('Error sending email: ' . $e->getMessage());

            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Technical Error in sending Email! Please try again later.',
            //     ]);
            // }

            // return response()->json([
            //     'status' => true,
            //     'message' => 'Company Approved Sucessfully',
            // ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to approved!',
            ]);
        }
    }

    public function approved_user(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
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

            $user = User::find($request->user_id);

            if ($request->status == 'approved') {
                $user->active_status = 1;
            } else if ($request->status == 'banned') {
                $user->active_status = 2;
            } else if ($request->status == 'pending') {
                $user->active_status = 0;
            }

            $user->save();

            if ($request->status == 'approved') {

                return response()->json([
                    'status' => true,
                    'message' => 'User approved successfully',
                ]);
            } else {

                if ($request->status == 'banned') {

                    return response()->json([
                        'status' => true,
                        'message' => 'User banned successfully',
                    ]);
                } else {

                    return response()->json([
                        'status' => true,
                        'message' => 'User status pending',
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to approved!',
            ]);
        }
    }

    public function approved_driver(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'driver_id' => 'required|exists:drivers,id',
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

            $driver = Driver::find($request->driver_id);

            if ($request->status == 'approved') {
                $driver->active_status = 'approved';
            } else if ($request->status == 'banned') {
                $driver->active_status = 'banned';
            } else if ($request->status == 'pending') {
                $driver->active_status = 'pending';
            }

            $driver->save();

            if ($request->status == 'approved') {

                return response()->json([
                    'status' => true,
                    'message' => 'Driver approved successfully',
                ]);
            } else {

                if ($request->status == 'banned') {

                    return response()->json([
                        'status' => true,
                        'message' => 'Driver banned successfully',
                    ]);
                } else {

                    return response()->json([
                        'status' => true,
                        'message' => 'Driver status pending',
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to approved!',
            ]);
        }
    }

    public function destinations(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'required|string',

            ];

            if (is_null($request->destination_id)) {

                // $rules['license_pic'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if (is_null($request->destination_id)) {

                $destination = new Destination();
            } else {

                $destination = Destination::find($request->destination_id);

                if (!$destination) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Destination does not found against ID',
                    ]);
                }
            }

            $destination->name = $request->name;
            if ($request->hasFile('image')) {

                $destination->image = $this->handleFileUpload($request->file('image'));

                // $destination->image = $request->file('image')->store('images', 'public');
            }
            $destination->description = $request->description;
            $destination->save();

            return response()->json([
                'status' => true,
                'message' => 'Destination ' . (is_null($request->destination_id) ? 'Added' : 'updated') . ' successfully',
                'response' => $destination
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to add destination!',
            ]);
        }
    }

    public function destination_delete($id)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            $destination = Destination::find($id);

            if (!$destination) {
                return response()->json([
                    'status' => false,
                    'message' => 'Destination does not found!',
                ]);
            }


            $destination->delete();

            return response()->json([
                'status' => true,
                'message' => 'Destination deleted successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to delete destination!',
            ]);
        }
    }

    public function approved_company_quote(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'quote_id' => 'required|exists:quote_against_requests,id',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->status == 'accepted') {

                $quote = QuoteAgainstRequest::find($request->quote_id);
                $quote->status = 1;
                $quote->save();

                if (is_null($quote->driver_id)) {

                    $change_request = new ChangeRequest();
                    $change_request->quote_id = $request->quote_id;
                    $change_request->booking_id = $quote->booking_id;
                    $change_request->company_id = $quote->company_id;
                    $change_request->booking_req_id = $quote->booking_req_id;
                    $change_request->price = $quote->price;
                    $change_request->vehicle_type = $quote->vehicle_type;
                    $change_request->color = $quote->color;
                    $change_request->manufacturer = $quote->manufacturer;
                    $change_request->model = $quote->model;
                    $change_request->description = $quote->description;
                    $change_request->status = 1;
                    $change_request->save();
                }

                if (is_null($quote->driver_id)) {
                    $booking_req_company = BookingRequestCompany::where('booking_id', $quote->booking_id)->where('company_id', $quote->company_id)->first();
                } else {
                    $booking_req_company = BookingRequestCompany::where('booking_id', $quote->booking_id)->where('driver_id', $quote->driver_id)->first();
                }
                $booking_req_company->status = 1;
                $booking_req_company->save();

                $booking = CustomerBooking::find($quote->booking_id);

                $booking_details_via = BookingDetail::select('via_name')
                    ->where('booking_id', $booking->id)
                    ->get();

                $booking_details = '';
                foreach ($booking_details_via as $data) {
                    $booking_details .= <<<HTML
                <span class="location">{$data->via_name}</span><br><br>
                HTML;
                }

                $return_status = $booking->booking_return_status == 1 ? 'Yes' : 'No';


                $type = FleetType::find($booking->car_type_id);
                $type_name = $type->type_name;
                $car_name = $type->car_name;


                $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
                $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

                if (is_null($quote->driver_id)) {

                    $company = Company::find($quote->company_id);

                    $company_id = $company->id;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $company->company_email;
                    $operator_price = $quote->price;
                    $driver_id = NULL;
                    $driver_name = $company->company_name;
                    $is_driver = false;
                    // $driver_email = $company->company_email;


                } else {

                    $driver = Driver::find($quote->driver_id);
                    $company_id = NULL;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $driver->driver_email;
                    $operator_price = $quote->price;
                    $driver_id = $driver->id;
                    $driver_name = $driver->name . '' . $driver->first_name;
                    $vehicle_reg_no = $driver->vehicle_reg_num;
                    $driver_licence = $driver->driver_pco_license_num;
                    $driver_pic = $driver->profile_picture;
                    $is_driver = true;
                    // $driver_email = $driver->driver_email;

                }
                $payment = UserPayment::where('booking_id', $quote->booking_id)->first();

                $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);


                // $company_id = $company->id;
                // $tracking_number = $booking->tracking_number;
                // $company_email = $company->company_email;
                // $operator_price = $quote->price;
                // $driver_id = NULL;

                if ($payment->payment_type == 'cash') {

                    $this->confirm_quote_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $operator_price, $booking_details, $return_status, $driver_id);
                } else {

                    $this->confirm_quote_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                }
            }


            $quote = QuoteAgainstRequest::find($request->quote_id);


            $booking = CustomerBooking::find($quote->booking_id);
            if ($request->status == 'accepted') {
                $booking->company_id = $quote->company_id;
                $booking->booking_status = 'accepted';
            } else if ($request->status == 'rejected') {

                $quote = QuoteAgainstRequest::find($request->quote_id);
                $quote->status = 2;
                $quote->save();

                $booking_req_company = BookingRequestCompany::where('booking_id', $quote->booking_id)->where('company_id', $quote->company_id)->first();
                $booking_req_company->status = 2;
                $booking_req_company->save();

                $booking->booking_status = 'pending';

                $change_request = new ChangeRequest();
                $change_request->quote_id = $request->quote_id;
                $change_request->booking_id = $quote->booking_id;
                $change_request->company_id = $quote->company_id;
                $change_request->booking_req_id = $quote->booking_req_id;
                $change_request->price = $quote->price;
                $change_request->vehicle_type = $quote->vehicle_type;
                $change_request->color = $quote->color;
                $change_request->manufacturer = $quote->manufacturer;
                $change_request->model = $quote->model;
                $change_request->description = $quote->description;
                $change_request->status = 2;
                $change_request->save();

                $booking_details_via = BookingDetail::select('via_name')
                    ->where('booking_id', $booking->id)
                    ->get();

                $booking_details = '';
                foreach ($booking_details_via as $data) {
                    $booking_details .= <<<HTML
            <span class="location">{$data->via_name}</span><br><br>
            HTML;
                }

                $return_status = $booking->booking_return_status == 1 ? 'Yes' : 'No';


                $type = FleetType::find($booking->car_type_id);
                $type_name = $type->type_name;
                $car_name = $type->car_name;


                $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
                $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

                if (is_null($quote->driver_id)) {

                    $company = Company::find($quote->company_id);

                    $company_id = $company->id;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $company->company_email;
                    $operator_price = $quote->price;
                    $driver_id = NULL;
                    $driver_name = $company->company_name;
                    // $driver_email = $company->company_email;


                } else {

                    $driver = Driver::find($quote->driver_id);
                    $company_id = NULL;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $driver->driver_email;
                    $operator_price = $quote->price;
                    $driver_id = $driver->id;
                    $driver_name = $driver->name . '' . $driver->last_name;
                    // $driver_email = $driver->driver_email;

                }

                $this->quote_reject($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $operator_price, $booking_details, $return_status, $driver_id);
            } else if ($request->status == 'pending') {

                $quote = QuoteAgainstRequest::find($request->quote_id);
                $quote->status = 0;
                $quote->save();

                $booking_req_company = BookingRequestCompany::where('booking_id', $quote->booking_id)->where('company_id', $quote->company_id)->first();
                $booking_req_company->status = 0;
                $booking_req_company->save();

                $booking->booking_status = 'pending';
            }
            $booking->save();


            return response()->json([
                'status' => true,
                'message' => $request->status == 'accepted' ? 'Quote against company approved successfully' : ($request->status == 'rejected' ? 'Quote against company rejected successfully' : 'Quote against company pending successfully')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to approved!',
            ]);
        }
    }

    public function approved_changed_request(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'quote_id' => 'required|exists:quote_against_requests,id',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            $quote = QuoteAgainstRequest::find($request->quote_id);


            if ($request->status == 'accepted') {

                $change_request = ChangeRequest::where('quote_id', $request->quote_id)->orderBy('created_at', 'desc')->first();
                $change_request->status = 1;
                $change_request->save();


                $quote = QuoteAgainstRequest::find($request->quote_id);
                $quote->booking_id = $change_request->booking_id;
                $quote->company_id = $change_request->company_id;
                $quote->booking_req_id = $change_request->booking_req_id;
                $quote->price = $change_request->price;
                $quote->vehicle_type = $change_request->vehicle_type;
                $quote->color = $change_request->color;
                $quote->manufacturer = $change_request->manufacturer;
                $quote->model = $change_request->model;
                $quote->description = $change_request->description;
                $quote->status = 1;
                $quote->save();
            } else if ($request->status == 'rejected') {


                $change_request = ChangeRequest::where('quote_id', $request->quote_id)->orderBy('created_at', 'desc')->first();
                $change_request->status = 2;
                $change_request->save();

                $quote = QuoteAgainstRequest::find($request->quote_id);

                $booking = CustomerBooking::find($quote->booking_id);
                $booking->company_id = $quote->company_id;
                $booking->booking_status = 'accepted';
                $booking->save();

                $booking_details_via = BookingDetail::select('via_name')
                    ->where('booking_id', $booking->id)
                    ->get();

                $booking_details = '';
                foreach ($booking_details_via as $data) {
                    $booking_details .= <<<HTML
                    <span class="location">{$data->via_name}</span><br><br>
                    HTML;
                }

                $return_status = $booking->booking_return_status == 1 ? 'Yes' : 'No';

                $type = FleetType::find($booking->car_type_id);
                $type_name = $type->type_name;
                $car_name = $type->car_name;


                $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
                $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

                if (is_null($change_request->driver_id)) {

                    $company = Company::find($change_request->company_id);

                    $company_id = $company->id;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $company->company_email;
                    $operator_price = $change_request->price;
                    $driver_id = NULL;
                    $driver_name = $company->company_name;
                    // $driver_email = $company->company_email;


                } else {

                    $driver = Driver::find($change_request->driver_id);
                    $company_id = NULL;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $driver->driver_email;
                    $operator_price = $change_request->price;
                    $driver_id = $driver->id;
                    $driver_name = $driver->name . '' . $driver->last_name;
                    // $driver_email = $driver->driver_email;

                }

                $this->quote_reject($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $operator_price, $booking_details, $return_status, $driver_id);
            } else if ($request->status == 'pending') {

                $change_request = ChangeRequest::where('quote_id', $request->quote_id)->orderBy('created_at', 'desc')->first();
                $change_request->status = 0;
                $change_request->save();
            }

            if ($request->status == 'accepted') {

                $quote = QuoteAgainstRequest::find($request->quote_id);


                $booking = CustomerBooking::find($quote->booking_id);
                $booking->company_id = $quote->company_id;
                $booking->booking_status = 'accepted';
                $booking->save();

                $booking_details_via = BookingDetail::select('via_name')
                    ->where('booking_id', $booking->id)
                    ->get();

                $booking_details = '';
                foreach ($booking_details_via as $data) {
                    $booking_details .= <<<HTML
                <span class="location">{$data->via_name}</span><br><br>
                HTML;
                }

                $return_status = $booking->booking_return_status == 1 ? 'Yes' : 'No';


                $type = FleetType::find($booking->car_type_id);
                $type_name = $type->type_name;

                $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
                $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

                $company = Company::find($quote->company_id);

                $payment = UserPayment::where('booking_id', $quote->booking_id)->first();


                $company_id = $company->id;
                $tracking_number = $booking->tracking_number;
                $company_email = $company->company_email;
                $operator_price = $quote->price;
                $driver_id = NULL;

                if ($payment->payment_type == 'cash') {

                    $this->confirm_quote_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $operator_price, $booking_details, $return_status, $driver_id);
                } else {

                    $this->confirm_quote_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                }
            }

            return response()->json([
                'status' => true,
                'message' => $request->status == 'accepted' ? 'Change Request against company approved successfully' : ($request->status == 'rejected' ? 'Change Request against company rejected successfully' : 'Change Request against company pending successfully')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission to approved!',
            ]);
        }
    }

    public function company_delete($id)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Retrieve the company and its associated document
            $company = Company::find($id);

            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company not found',
                ]);
            }

            // Use transaction to ensure atomicity
            DB::beginTransaction();

            try {
                // Delete associated company document
                CompanyDocument::where('company_id', $company->id)->delete();

                // Delete the company
                $company->delete();

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Company and associated documents deleted successfully',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Error deleting company: ' . $e->getMessage());

                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete company. Please try again later.',
                ], 500);
            }
        }
    }


    public function manual_dispatch_booking_listing(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            // $validator = Validator::make($request->all(), [

            //     'month' => 'required',
            //     'year' => 'required',

            // ]);

            // if ($validator->fails()) {
            //     $errors = $validator->errors()->first(); // Get the first error message

            //     $response = [
            //         'success' => false,
            //         'message' => $errors,
            //     ];

            //     return response()->json($response); // Return JSON response with HTTP
            // }

            $bookings = CustomerBooking::where('admin_status', 'manual')->paginate(6);

            if ($bookings->isNotEmpty()) {

                foreach ($bookings as $booking) {

                    $details = BookingDetail::where('booking_id', $booking->id)->get();

                    if ($details->isNotEmpty()) {
                        $booking->booking_details = $details;
                    } else {
                        $booking->booking_details = [];
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Manual booking list',
                    'response' => $bookings
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Manual booking list not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see celander!',
            ]);
        }
    }

    // public function manual_dispatch_booking(Request $request)
    // {

    //     $auth = Auth::user();

    //     // Check permissions
    //     if ($auth->fixed_role_id == 1) {

    //         $validator = Validator::make($request->all(), [

    //             'booking_id' => 'required|exists:customer_bookings,id',
    //             'admin_quote_price' => 'required',
    //             // 'company_id' => 'required',
    //             // 'driver_id' => 'required',

    //         ]);

    //         if ($validator->fails()) {
    //             $errors = $validator->errors()->first(); // Get the first error message

    //             $response = [
    //                 'success' => false,
    //                 'message' => $errors,
    //             ];

    //             return response()->json($response); // Return JSON response with HTTP
    //         }

    //         $booking = CustomerBooking::find($request->booking_id);

    //         $booking_details_via = BookingDetail::select('via_name')
    //             ->where('booking_id', $booking->id)
    //             ->get();

    //         $booking_details = '';
    //         foreach ($booking_details_via as $data) {
    //             $booking_details .= <<<HTML
    //         <span class="location">{$data->via_name}</span><br><br>
    //         HTML;
    //         }

    //         $return_status = $request->booking_return_status == 1 ? 'Yes' : 'No';

    //         $type = FleetType::find($booking->car_type_id);
    //         $type_name = $type->type_name;

    //         $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
    //         $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time


    //         if ($request->company_id) {

    //             // $companies = explode(',', $request->company_id);

    //             $companies = $request->company_id;


    //             foreach ($companies as $value) {

    //                 $company =  Company::find($value);
    //                 // return $company;

    //                 if (!$company) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Company not found',
    //                     ]);
    //                 }
    //                 $randomString = Str::random(16);

    //                 // return $company;

    //                 $booking_request_companies = new BookingRequestCompany();
    //                 $booking_request_companies->booking_id = $request->booking_id;
    //                 $booking_request_companies->company_id = $value;
    //                 $booking_request_companies->booking_quote_status = 'job-offer';
    //                 $booking_request_companies->admin_quote_price = $request->admin_quote_price;
    //                 $booking_request_companies->description = $request->description;
    //                 $booking_request_companies->token = $randomString;
    //                 $booking_request_companies->save();



    //                 $booking_id = $request->booking_id;
    //                 $company_id = $value;
    //                 $tracking_number = $booking->tracking_number;
    //                 $company_email = $company->company_email;
    //                 $booking_req_id = $booking_request_companies->id;
    //                 $driver_id = NULL;
    //                 $booking->operator_price = $request->admin_quote_price;




    //                 $this->quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status);
    //             }
    //         }

    //         if ($request->driver_id) {

    //             // $drivers = explode(',', $request->driver_id);

    //             $drivers = $request->driver_id;


    //             foreach ($drivers as $value) {

    //                 $driver =  Driver::find($value);
    //                 if (!$driver) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'Driver not found',
    //                     ]);
    //                 }

    //                 $randomString = Str::random(16);


    //                 $booking_request_companies = new BookingRequestCompany();
    //                 $booking_request_companies->booking_id = $request->booking_id;
    //                 $booking_request_companies->driver_id = $value;
    //                 $booking_request_companies->booking_quote_status = 'job-offer';
    //                 $booking_request_companies->admin_quote_price = $request->admin_quote_price;
    //                 $booking_request_companies->description = $request->description;
    //                 $booking_request_companies->token = $randomString;
    //                 $booking_request_companies->save();


    //                 $booking_id = $request->booking_id;
    //                 $driver_id = $value;
    //                 $tracking_number = $booking->tracking_number;
    //                 $company_email = $driver->driver_email;
    //                 $booking_req_id = $booking_request_companies->id;
    //                 $company_id = NULL;
    //                 $booking->operator_price = $request->admin_quote_price;



    //                 $this->quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status);
    //             }
    //         }

    //         $booking = CustomerBooking::find($request->booking_id);
    //         $booking->admin_status = 'auto';
    //         $booking->save();

    //         if ($request->company_id || $request->driver_id) {

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Booking dispatch successfully',
    //             ]);
    //         } else {

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Please select atleast one driver or operator ',
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to see celander!',
    //         ]);
    //     }
    // }

    public function manual_dispatch_booking(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            $validator = Validator::make($request->all(), [

                'booking_id' => 'required|exists:customer_bookings,id',
                'admin_quote_price' => 'required',
                // 'company_id' => 'required',
                // 'driver_id' => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->companies || $request->drivers) {


                $booking = CustomerBooking::find($request->booking_id);

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

                // $type = FleetType::find($booking->car_type_id);
                // $type_name = $type->type_name;


                if ($request->direct_booking_status == 'quote') {

                    // Cache companies and drivers from request
                    $companies = $request->companies ?? [];
                    $drivers = $request->drivers ?? [];


                    $booking = CustomerBooking::find($booking->id);

                    $type = FleetType::find($booking->car_type_id);
                    $type_name = $type->type_name;

                    $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
                    $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

                    // Process companies
                    if (!empty($companies)) {

                        foreach ($companies as $company) {

                            $randomString = Str::random(16);

                            $company = Company::find($company['company_id']);


                            $booking_request_companies = new BookingRequestCompany();
                            $booking_request_companies->booking_id = $booking->id;
                            $booking_request_companies->company_id = $company->id;
                            $booking_request_companies->token = $randomString;
                            $booking_request_companies->admin_quote_price = $request->admin_quote_price;
                            $booking_request_companies->save();

                            $booking = CustomerBooking::find($request->booking_id);
                            $booking->admin_status = 'auto';
                            $booking->save();

                            $booking_id = $booking->id;
                            $company_id = $company->id;
                            $tracking_number = $booking->tracking_number;
                            $company_email = $company->company_email;
                            $booking_req_id = $booking_request_companies->id;
                            $driver_id = NULL;
                            $booking->operator_price = $request->admin_quote_price;

                            $queryCompany = NotificationManagement::where('type', 'quick_quote')
                                ->where('user_type', 'LIKE', '%company%')
                                ->exists();

                            if ($queryCompany) {

                                $this->quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status);
                            }
                        }
                    }

                    // Process drivers
                    if (!empty($drivers)) {
                        foreach ($drivers as $driver) {

                            $randomString = Str::random(16);

                            $driver = Driver::find($driver['driver_id']);


                            $booking_request_companies = new BookingRequestCompany();
                            $booking_request_companies->booking_id = $booking->id;
                            $booking_request_companies->driver_id = $driver->id;
                            $booking_request_companies->token = $randomString;
                            $booking_request_companies->save();

                            $booking = CustomerBooking::find($request->booking_id);
                            $booking->admin_status = 'auto';
                            $booking->save();

                            $booking_id = $booking->id;
                            $driver_id = $driver->id;
                            $tracking_number = $booking->tracking_number;
                            $company_email = $driver->driver_email;
                            $booking_req_id = $booking_request_companies->id;
                            $company_id = NULL;
                            $booking->operator_price = $request->admin_quote_price;

                            $queryDriver = NotificationManagement::where('type', 'quick_quote')
                                ->where('user_type', 'LIKE', '%driver%')
                                ->exists();

                            if ($queryDriver) {

                                $this->quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status);
                            }
                        }
                    }
                } else if ($request->direct_booking_status == 'booking') {

                    $booking = CustomerBooking::find($booking->id);

                    $type = FleetType::find($booking->car_type_id);
                    $type_name = $type->type_name;
                    $car_name = $type->car_name;


                    $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
                    $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

                    $companies = $request->companies ?? [];
                    $drivers = $request->drivers ?? [];

                    foreach ($companies as $value) {

                        $company = Company::find($value['company_id']);

                        $customer_booking = CustomerBooking::find($booking->id);
                        $customer_booking->company_id = $company->id;
                        $customer_booking->booking_status = 'accepted';
                        $customer_booking->save();

                        $booking_request_companies = new BookingRequestCompany();
                        $booking_request_companies->booking_id = $booking->id;
                        $booking_request_companies->company_id = $company->id;
                        // $booking_request_companies->token = $randomString;
                        $booking_request_companies->admin_quote_price = $request->admin_quote_price;
                        $booking_request_companies->status = 1;
                        $booking_request_companies->booking_quote_status = 'quoted';
                        $booking_request_companies->save();

                        $add_quote = new QuoteAgainstRequest();
                        $add_quote->booking_id = $booking->id;
                        $add_quote->company_id = $company->id;
                        $add_quote->booking_req_id = $booking_request_companies->id;
                        $add_quote->price = $request->admin_quote_price;
                        $add_quote->status = 1;
                        $add_quote->save();


                        $company_id = $company->id;
                        $tracking_number = $booking->tracking_number;
                        $company_email = $company->company_email;
                        $driver_id = NULL;
                        $booking->operator_price = $request->admin_quote_price;
                        $driver_name = $company->company_name;


                        if ($request->payment_type == 'payment_link') {

                            // Check if the user_type contains 'company' or 'driver'
                            $queryCompany = NotificationManagement::where('type', 'direct_booking')
                                ->where('user_type', 'LIKE', '%company%')
                                ->exists();



                            if ($queryCompany) {
                                $is_driver = false;
                                $vehicle_reg_no = null;
                                $driver_licence = null;
                                $driver_pic = null;

                                $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);

                                $this->direct_booking_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                            }
                        } else {
                            $vehicle_reg_no = null;
                            $driver_licence = null;
                            $driver_pic = null;
                            $is_driver = false;

                            $queryCompany = NotificationManagement::where('type', 'direct_booking_cash')
                                ->where('user_type', 'LIKE', '%company%')
                                ->exists();

                            if ($queryCompany) {

                                $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);

                                $this->direct_booking_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                            }
                        }
                    }
                    if (!empty($drivers)) {
                        foreach ($drivers as $value) {

                            $driver = Driver::find($value['driver_id']);

                            $customer_booking = CustomerBooking::find($booking->id);
                            $customer_booking->driver_id = $driver->id;
                            $customer_booking->booking_status = 'accepted';
                            $customer_booking->save();

                            $booking_request_companies = new BookingRequestCompany();
                            $booking_request_companies->booking_id = $booking->id;
                            $booking_request_companies->driver_id = $driver->id;
                            // $booking_request_companies->token = $randomString;
                            $booking_request_companies->admin_quote_price = $request->admin_quote_price;
                            $booking_request_companies->status = 1;
                            $booking_request_companies->booking_quote_status = 'quoted';
                            $booking_request_companies->save();

                            $add_quote = new QuoteAgainstRequest();
                            $add_quote->booking_id = $booking->id;
                            $add_quote->driver_id = $driver->id;
                            $add_quote->booking_req_id = $booking_request_companies->id;
                            $add_quote->price = $request->admin_quote_price;
                            $add_quote->status = 1;
                            $add_quote->save();

                            $company_id = NULL;
                            $tracking_number = $booking->tracking_number;
                            $company_email = $driver->driver_email;
                            $driver_id = $driver->id;
                            $booking->operator_price = $request->admin_quote_price;
                            $driver_name = $driver->name . '' . $driver->last_name;

                            $vehicle_reg_no = $driver->vehicle_reg_num;
                            $driver_licence = $driver->driver_pco_license_num;
                            $driver_pic = $driver->profile_picture;
                            $is_driver = true;

                            if ($request->payment_type == 'payment_link') {


                                $queryDriver = NotificationManagement::where('type', 'direct_booking')
                                    ->where('user_type', 'LIKE', '%driver%')
                                    ->exists();

                                if ($queryDriver) {

                                    $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);

                                    $this->direct_booking_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                                }
                            } else {

                                $queryDriver = NotificationManagement::where('type', 'direct_booking_cash')
                                    ->where('user_type', 'LIKE', '%driver%')
                                    ->exists();

                                if ($queryDriver) {

                                    $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);

                                    $this->direct_booking_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                                }
                            }
                        }
                    }
                }

                if ($request->direct_booking_status == 'booking') {

                    $booking = CustomerBooking::find($request->booking_id);
                    $booking->admin_status = 'admin_booking';
                    if ($request->payment_type == 'cash') {
                        $booking->booking_status = 'accepted';
                    }
                    $booking->save();
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Booking dispatch successfully',
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please select atleast one driver or operator ',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see celander!',
            ]);
        }
    }

    public function admin_create_booking(Request $request) // Optimize Booking Code
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

        $rand = rand(100000, 999999); //send random booking number for tracking
        $paymentSuccess = false;

        // Handle payment based on payment type
        if ($request->payment_type == 'cash' || $request->payment_type == 'payment_link') {
            $paymentSuccess = true; // Assume payment is successful for cash and payment_link
        }


        if (!$paymentSuccess) { // if payment was not sucessfull then booking cant save
            return response()->json(['error' => 'Payment was not successful']);
        }


        $setting = Setting::where('parameter', 'booking_status')->first();
        $deduction_percentage = Setting::where('parameter', 'deduction_percentage')->first();

        if ($request->booking_return_status == 1) {

            if ($request->meet_n_greet > 0) {
                $bookingPrice = $request->amount * 2 + $request->meet_n_greet; // Original booking price
            } else {
                $bookingPrice = $request->amount * 2; // Original booking price

            }
            $deduction_percentage = $deduction_percentage->value; // Percentage to deduct

            // Calculate the discount amount
            $deductionAmount = ($bookingPrice * $deduction_percentage) / 100;

            // Deduct the discount from the original price
            $finalPrice = $bookingPrice - $deductionAmount;
        } else {
            if ($request->meet_n_greet > 0) {
                $bookingPrice = $request->amount + $request->meet_n_greet; // Original booking price
            } else {
                $bookingPrice = $request->amount; // Original booking price
            }
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
        if ($request->meet_n_greet > 0) {

            $booking->booking_price = $request->booking_return_status == 1 ? $request->amount * 2 + $request->meet_n_greet : $request->amount + $request->meet_n_greet;
        } else {
            $booking->booking_price = $request->booking_return_status == 1 ? $request->amount * 2 : $request->amount;
        }
        $booking->deduction_price = $finalPrice;
        $booking->tracking_number = 'FTS-' . $rand;
        $booking->meet_n_greet = $request->meet_n_greet;
        $booking->booking_return_status = $request->booking_return_status ?? 0;

        if ($request->payment_type == 'payment_link') {
            $booking->active_status = 0;
        }

        if ($request->direct_booking_status == 'booking') {

            $booking->admin_status = 'admin_booking';
        }

        if ($request->direct_booking_status == 'create_only') {

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
        $payment->amount = $booking->booking_price;
        $payment->payment_type = $request->payment_type;
        if ($request->payment_type == 'payment_link') {
            $payment->status = 0;
        } else {
            $payment->status = 1;
        }
        $payment->save();

        if ($request->payment_type == 'payment_link') { // If user payment type is payment link then save record in Link Transfer table
            $payment_link = new LinkTransfer();
            $payment_link->user_payment_id = $payment->id;
            $payment_link->via_email = $request->payment_link_via_email;
            $payment_link->via_sms = $request->payment_link_via_sms;
            $payment_link->save();
        }

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
            $successUrl = route('payment.success.admin') . '?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking->id . '&payment_id=' . $payment->id . '&link_transfer_id=' . $payment_link->id;

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
                            'unit_amount' => $booking->booking_price * 100, // Amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                // 'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'success_url' => $successUrl, // Use the generated success URL
                'cancel_url' => route('payment.cancel'),
            ]);

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
                    $emailnotification->to_email = $request->head_passenger_email;
                    $emailnotification->email_subject = $checkMail->mail_subject;
                    $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                    $emailnotification->schedule_date = date('Y-m-d H:i:s');
                    $emailnotification->email_sent_status = 'N';
                    $emailnotification->payment_status = 'un-paid';
                    $emailnotification->save();


                    // Get SMPT Credentails for settings table and send email
                    $currentDate = now();
                    $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                    $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                    $noti = NotificationEmail::find($emailnotification->id); // Fetch records for email sending to user
                    $to_email = $noti->to_email; // User Email
                    $email_subject = $noti->email_subject; // Email Subject
                    $email_body = $noti->email_body; // Email Body

                    try {

                        Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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
            }
        }

        if ($request->direct_booking_status == 'quote') {

            // Cache companies and drivers from request
            $companies = $request->companies ?? [];
            $drivers = $request->drivers ?? [];


            $booking = CustomerBooking::find($booking->id);

            $type = FleetType::find($booking->car_type_id);
            $type_name = $type->type_name;

            $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
            $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

            // Process companies
            if (!empty($companies)) {

                foreach ($companies as $company) {

                    $randomString = Str::random(16);

                    $company = Company::find($company['company_id']);


                    $booking_request_companies = new BookingRequestCompany();
                    $booking_request_companies->booking_id = $booking->id;
                    $booking_request_companies->company_id = $company->id;
                    $booking_request_companies->token = $randomString;
                    $booking_request_companies->admin_quote_price = $request->admin_quote_price;
                    $booking_request_companies->save();

                    $booking_id = $booking->id;
                    $company_id = $company->id;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $company->company_email;
                    $booking_req_id = $booking_request_companies->id;
                    $driver_id = NULL;
                    $booking->operator_price = $request->admin_quote_price;

                    $queryCompany = NotificationManagement::where('type', 'quick_quote')
                        ->where('user_type', 'LIKE', '%company%')
                        ->exists();

                    if ($queryCompany) {

                        $this->quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status);
                    }
                }
            }

            // Process drivers
            if (!empty($drivers)) {
                foreach ($drivers as $driver) {

                    $randomString = Str::random(16);

                    $driver = Driver::find($driver['driver_id']);


                    $booking_request_companies = new BookingRequestCompany();
                    $booking_request_companies->booking_id = $booking->id;
                    $booking_request_companies->driver_id = $driver->id;
                    $booking_request_companies->token = $randomString;
                    $booking_request_companies->save();

                    $booking_id = $booking->id;
                    $driver_id = $driver->id;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $driver->driver_email;
                    $booking_req_id = $booking_request_companies->id;
                    $company_id = NULL;
                    $booking->operator_price = $request->admin_quote_price;

                    $queryDriver = NotificationManagement::where('type', 'quick_quote')
                        ->where('user_type', 'LIKE', '%driver%')
                        ->exists();

                    if ($queryDriver) {

                        $this->quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status);
                    }
                }
            }
        } else if ($request->direct_booking_status == 'booking') {

            $booking = CustomerBooking::find($booking->id);

            $type = FleetType::find($booking->car_type_id);
            $type_name = $type->type_name;
            $car_name = $type->car_name;


            $date = date('d-m-Y', strtotime($booking->created_at)); // Extracts the date
            $time = date('H:i:s', strtotime($booking->created_at)); // Extracts the time

            $companies = $request->companies ?? [];
            $drivers = $request->drivers ?? [];

            foreach ($companies as $value) {

                $company = Company::find($value['company_id']);

                $customer_booking = CustomerBooking::find($booking->id);
                $customer_booking->company_id = $company->id;
                $customer_booking->booking_status = 'accepted';
                $customer_booking->save();

                $booking_request_companies = new BookingRequestCompany();
                $booking_request_companies->booking_id = $booking->id;
                $booking_request_companies->company_id = $company->id;
                // $booking_request_companies->token = $randomString;
                $booking_request_companies->admin_quote_price = $request->admin_quote_price;
                $booking_request_companies->status = 1;
                $booking_request_companies->booking_quote_status = 'quoted';
                $booking_request_companies->save();

                $add_quote = new QuoteAgainstRequest();
                $add_quote->booking_id = $booking->id;
                $add_quote->company_id = $company->id;
                $add_quote->booking_req_id = $booking_request_companies->id;
                $add_quote->price = $request->admin_quote_price;
                $add_quote->status = 1;
                $add_quote->save();


                $company_id = $company->id;
                $tracking_number = $booking->tracking_number;
                $company_email = $company->company_email;
                $driver_id = NULL;
                $booking->operator_price = $request->admin_quote_price;
                $driver_name = $company->company_name;


                if ($request->payment_type == 'payment_link') {

                    $queryCompany = NotificationManagement::where('type', 'direct_booking')
                        ->where('user_type', 'LIKE', '%company%')
                        ->exists();


                    if ($queryCompany) {
                        $vehicle_reg_no = null;
                        $driver_licence = null;
                        $driver_pic = null;
                        $is_driver = false;

                        $this->direct_booking_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);

                        $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);
                    }
                } else {

                    $queryCompany = NotificationManagement::where('type', 'direct_booking_cash')
                        ->where('user_type', 'LIKE', '%company%')
                        ->exists();

                    if ($queryCompany) {
                        $vehicle_reg_no = null;
                        $driver_licence = null;
                        $driver_pic = null;
                        $is_driver = false;
                        $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);

                        $this->direct_booking_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);
                    }
                }
            }
            if (!empty($drivers)) {
                foreach ($drivers as $value) {

                    $driver = Driver::find($value['driver_id']);

                    $customer_booking = CustomerBooking::find($booking->id);
                    $customer_booking->driver_id = $driver->id;
                    $customer_booking->booking_status = 'accepted';
                    $customer_booking->save();

                    $booking_request_companies = new BookingRequestCompany();
                    $booking_request_companies->booking_id = $booking->id;
                    $booking_request_companies->driver_id = $driver->id;
                    // $booking_request_companies->token = $randomString;
                    $booking_request_companies->admin_quote_price = $request->admin_quote_price;
                    $booking_request_companies->status = 1;
                    $booking_request_companies->booking_quote_status = 'quoted';
                    $booking_request_companies->save();

                    $add_quote = new QuoteAgainstRequest();
                    $add_quote->booking_id = $booking->id;
                    $add_quote->driver_id = $driver->id;
                    $add_quote->booking_req_id = $booking_request_companies->id;
                    $add_quote->price = $request->admin_quote_price;
                    $add_quote->status = 1;
                    $add_quote->save();

                    $company_id = NULL;
                    $tracking_number = $booking->tracking_number;
                    $company_email = $driver->driver_email;
                    $driver_id = $driver->id;
                    $booking->operator_price = $request->admin_quote_price;
                    $driver_name = $driver->name . '' . $driver->last_name;
                    $vehicle_reg_no = $driver->vehicle_reg_num;
                    $driver_licence = $driver->driver_pco_license_num;
                    $driver_pic = $driver->profile_picture;
                    $is_driver = true;

                    if ($request->payment_type == 'payment_link') {

                        $queryDriver = NotificationManagement::where('type', 'direct_booking')
                            ->where('user_type', 'LIKE', '%driver%')
                            ->exists();

                        if ($queryDriver) {

                            $this->direct_booking_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);

                            $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);
                        }
                    } else {

                        $queryDriver = NotificationManagement::where('type', 'direct_booking_cash')
                            ->where('user_type', 'LIKE', '%driver%')
                            ->exists();

                        if ($queryDriver) {

                            $this->direct_booking_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status);

                            $this->booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_licence, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name);
                        }
                    }
                }
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

                if ($queryUser) {

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

                    $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                    $emailnotification = new NotificationEmail();
                    $emailnotification->booking_id = $booking->id;
                    $emailnotification->user_id = $request->user_id;
                    $emailnotification->to_email = $request->confirm_via_email;
                    $emailnotification->email_subject = $checkMail->mail_subject;
                    $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                    $emailnotification->schedule_date = date('Y-m-d H:i:s');
                    $emailnotification->email_sent_status = 'N';
                    if ($request->payment_type == 'payment_link') {
                        $emailnotification->payment_status = 'un-paid';
                    }
                    $emailnotification->save();
                    // }

                    if ($request->payment_type != 'payment_link') {

                        $currentDate = now();
                        $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                        $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                        $noti = NotificationEmail::find($emailnotification->id);

                        $to_email = $noti->to_email;
                        $email_subject = $noti->email_subject;
                        $email_body = $noti->email_body;

                        try {

                            Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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

        return 'Payment successful! Payment ID: ' . $session->payment_intent . ' booking_id: ' . $request->booking_id . ' payment_id_table:' . $request->payment_id . 'link_transfer_id: ' . $request->link_transfer_id;
    }

    public function quick_quote_email($booking, $tracking_number, $booking_id, $company_id, $driver_id, $company_email, $booking_req_id, $randomString, $type_name, $date, $time, $booking_details, $return_status)
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
                    '/\{(booking_id)}]?/',
                    '/\{(company_id)}]?/',
                    '/\{(driver_id)}]?/',
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
                    $booking->head_passenger_name ? $booking->head_passenger_name : 'FTS - Customer',
                    $booking->head_passenger_email,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->operator_price,
                    $booking_id,
                    $company_id,
                    $driver_id,
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
                $emailnotification->booking_id = $booking->id;
                $emailnotification->company_id = $company_id;
                $emailnotification->driver_id = $driver_id;
                $emailnotification->to_email = $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                if ($booking->active_status != 1) {
                    $emailnotification->payment_status = 'un-paid';
                }
                $emailnotification->save();

                if ($booking->active_status == 1) {

                    //Email sending process start
                    $currentDate = now();
                    $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                    $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                    $noti = NotificationEmail::find($emailnotification->id);

                    $to_email = $noti->to_email;
                    $email_subject = $noti->email_subject;
                    $email_body = $noti->email_body;

                    try {

                        Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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
    }

    public function confirm_quote_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status)
    {

        $checkMail = NotificationManagement::where('type', 'quote_confirmation_email')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {

            // Check if the user_type contains 'company' or 'driver'
            $queryCompany = NotificationManagement::where('type', 'quote_confirmation_email')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();

            $queryDriver = NotificationManagement::where('type', 'quote_confirmation_email')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();

            if ($queryCompany || $queryDriver) {

                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(phone)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_loc_details)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',
                    '/\{(type_name)}]?/',
                    '/\{(date)}]?/',
                    '/\{(time)}]?/',
                    '/\{(total_passenger)}]?/',
                    // '/\{(customer_name)}]?/',
                    '/\{(return_status)}]?/',



                ];

                $replacements = [

                    $tracking_number,
                    $booking->head_passenger_name ? $booking->head_passenger_name : 'FTS - Customer',
                    $booking->head_passenger_email,
                    $booking->head_passenger_mobile,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking_details,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->deduction_price,
                    $type_name,
                    $date,
                    $time,
                    $booking->total_passenger,
                    $return_status,
                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->company_id = $company_id;
                $emailnotification->driver_id = $driver_id;
                $emailnotification->to_email = $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                $emailnotification->save();

                $notification = new Notification();
                $notification->company_id = $company_id;
                $notification->driver_id = $driver_id;
                $notification->title = 'FTS - Booking Confirmation Notification';
                $notification->description = 'Your booking is confirmed against! #' . $tracking_number;
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

                    Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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

    public function confirm_quote_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $operator_price, $booking_details, $return_status, $driver_id)
    {

        $checkMail = NotificationManagement::where('type', 'quote_confirmation_email_cash')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {

            // Check if the user_type contains 'company' or 'driver'
            $queryCompany = NotificationManagement::where('type', 'quote_confirmation_email_cash')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();

            $queryDriver = NotificationManagement::where('type', 'quote_confirmation_email_cash')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();

            if ($queryCompany || $queryDriver) {
                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(phone)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_loc_details)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',
                    '/\{(deduction_amount)}]?/',
                    '/\{(type_name)}]?/',
                    '/\{(date)}]?/',
                    '/\{(time)}]?/',
                    '/\{(total_passenger)}]?/',
                    // '/\{(customer_name)}]?/',
                    '/\{(return_status)}]?/',


                ];

                $replacements = [

                    $tracking_number,
                    $booking->head_passenger_name ? $booking->head_passenger_name : 'FTS - Customer',
                    $booking->head_passenger_email,
                    $booking->head_passenger_mobile,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking_details,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->booking_price,
                    $operator_price,
                    $type_name,
                    $date,
                    $time,
                    $booking->total_passenger,
                    $return_status,

                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->company_id = $company_id;
                $emailnotification->driver_id = $driver_id;
                $emailnotification->to_email = $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                $emailnotification->save();

                $notification = new Notification();
                $notification->company_id = $company_id;
                $notification->driver_id = $driver_id;
                $notification->title = 'FTS - Booking Confirmation Notification';
                $notification->description = 'Your booking is confirmed against! #' . $tracking_number;
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

                    Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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

    public function direct_booking_email($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status)
    {

        $checkMail = NotificationManagement::where('type', 'direct_booking')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {

            // Check if the user_type contains 'company' or 'driver'
            $queryCompany = NotificationManagement::where('type', 'direct_booking')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();

            $queryDriver = NotificationManagement::where('type', 'direct_booking')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();

            if ($queryCompany || $queryDriver) {

                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(phone)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',
                    '/\{(type_name)}]?/',
                    '/\{(date)}]?/',
                    '/\{(time)}]?/',
                    '/\{(booking_loc_details)}]?/',
                    '/\{(total_passenger)}]?/',
                    '/\{(return_status)}]?/',

                ];

                $replacements = [

                    $tracking_number,
                    $booking->head_passenger_name ? $booking->head_passenger_name : 'FTS - Customer',
                    $booking->head_passenger_email,
                    $booking->head_passenger_mobile,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->operator_price,
                    $type_name,
                    $date,
                    $time,
                    $booking_details,
                    $booking->total_passenger,
                    $return_status


                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->company_id = $company_id;
                $emailnotification->driver_id = $driver_id;
                $emailnotification->to_email = $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                $emailnotification->save();

                $notification = new Notification();
                $notification->company_id = $company_id;
                $notification->driver_id = $driver_id;
                $notification->title = 'FTS - You have assigned booking directly by admin';
                $notification->description = 'Your booking is confirmed against! #' . $tracking_number;
                $notification->schedule_date = date('Y-m-d H:i:s');
                $notification->sent_status = 'Y';
                $notification->save();

                if ($booking->active_status == 1) {
                    //Email sending process start
                    $currentDate = now();
                    $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                    $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                    $noti = NotificationEmail::find($emailnotification->id);

                    $to_email = $noti->to_email;
                    $email_subject = $noti->email_subject;
                    $email_body = $noti->email_body;

                    try {

                        Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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
    }

    public function direct_booking_email_cash($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status)
    {

        $checkMail = NotificationManagement::where('type', 'direct_booking_cash')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {

            // Check if the user_type contains 'company' or 'driver'
            $queryCompany = NotificationManagement::where('type', 'direct_booking_cash')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();

            $queryDriver = NotificationManagement::where('type', 'direct_booking_cash')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();

            if ($queryCompany || $queryDriver) {

                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(phone)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',
                    '/\{(deduction_price)}]?/',
                    '/\{(type_name)}]?/',
                    '/\{(date)}]?/',
                    '/\{(time)}]?/',
                    '/\{(booking_loc_details)}]?/',
                    '/\{(total_passenger)}]?/',
                    '/\{(return_status)}]?/',

                ];

                $replacements = [

                    $tracking_number,
                    $booking->head_passenger_name ? $booking->head_passenger_name : 'FTS - Customer',
                    $booking->head_passenger_email,
                    $booking->head_passenger_mobile,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->booking_price,
                    $booking->operator_price,
                    $type_name,
                    $date,
                    $time,
                    $booking_details,
                    $booking->total_passenger,
                    $return_status


                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->company_id = $company_id;
                $emailnotification->driver_id = $driver_id;
                $emailnotification->to_email = $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                $emailnotification->save();

                $notification = new Notification();
                $notification->company_id = $company_id;
                $notification->driver_id = $driver_id;
                $notification->title = 'FTS - You have assigned booking directly by admin';
                $notification->description = 'Your booking is confirmed against! #' . $tracking_number;
                $notification->schedule_date = date('Y-m-d H:i:s');
                $notification->sent_status = 'Y';
                $notification->save();

                if ($booking->active_status == 1) {
                    //Email sending process start
                    $currentDate = now();
                    $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                    $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                    $noti = NotificationEmail::find($emailnotification->id);

                    $to_email = $noti->to_email;
                    $email_subject = $noti->email_subject;
                    $email_body = $noti->email_body;

                    try {

                        Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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
    }

    public function booking_confirmation_user($is_driver, $vehicle_reg_no, $driver_license, $booking, $driver_pic, $tracking_number, $company_email, $type_name, $date, $time, $booking_details, $return_status, $driver_name, $car_name)
    {
        if ($is_driver) {
            $driver_pic = 'https://fts.viion.net/' . '/api/public/storage/' . rawurlencode($driver_pic);
            $checkMail = NotificationManagement::where('type', 'Booking Confirmation by Driver')->first();
        } else {
            $checkMail = NotificationManagement::where('type', 'Booking Confirmation')->first(); // Sending Email Process Start
        }
        if ($checkMail->send_email == "Y") {

            if ($is_driver) {
                $queryUser = NotificationManagement::where('type', 'Booking Confirmation by Driver')
                    ->where('user_type', 'LIKE', '%user%')
                    ->exists();
            } else {
                $queryUser = NotificationManagement::where('type', 'Booking Confirmation')
                    ->where('user_type', 'LIKE', '%user%')
                    ->exists();
            }
            if ($queryUser) {
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
                    '/\{(type_name)}]?/',
                    '/\{(car_name)}]?/',
                    '/\{(driver_email)}]?/',
                    '/\{(driver_name)}]?/',
                    '/\{(vehicle_reg_no)}]?/',
                    '/\{(driver_license)}]?/',
                    '/\{(driver_pic)}]?/',

                ];

                $replacements = [

                    $booking->tracking_number,
                    $booking->head_passenger_name,
                    $booking->head_passenger_email,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->booking_price,
                    $booking_details,
                    $return_status,
                    $type_name,
                    $car_name,
                    $company_email,
                    $driver_name,
                    $vehicle_reg_no,
                    $driver_license,
                    $driver_pic,

                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->booking_id = $booking->id;
                $emailnotification->user_id = $booking->user_id;
                $emailnotification->to_email = $booking->confirm_via_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                $emailnotification->save();

                $currentDate = now();
                $setting_comm_email = Setting::where('parameter', 'smtp_from_email')->first();
                $setting_comm_email_name = Setting::where('parameter', 'smtp_from_name')->first();

                $noti = NotificationEmail::find($emailnotification->id);

                $to_email = $noti->to_email;
                $email_subject = $noti->email_subject;
                $email_body = $noti->email_body;

                try {

                    Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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
            }
        }
    }

    public function quote_reject($booking, $tracking_number, $company_email, $type_name, $date, $time, $company_id, $driver_id, $booking_details, $return_status)
    {

        $checkMail = NotificationManagement::where('type', 'Quote Rejection')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {

            // Check if the user_type contains 'company' or 'driver'
            $queryCompany = NotificationManagement::where('type', 'Quote Rejection')
                ->where('user_type', 'LIKE', '%company%')
                ->exists();

            $queryDriver = NotificationManagement::where('type', 'Quote Rejection')
                ->where('user_type', 'LIKE', '%driver%')
                ->exists();

            if ($queryCompany || $queryDriver) {

                $patterns = [

                    '/\{(tracking_number)}]?/',
                    '/\{(head_passenger_name)}]?/',
                    '/\{(user_email)}]?/',
                    '/\{(phone)}]?/',
                    '/\{(booking_from_loc_name)}]?/',
                    '/\{(booking_to_loc_name)}]?/',
                    '/\{(booking_loc_details)}]?/',
                    '/\{(booking_date)}]?/',
                    '/\{(booking_time)}]?/',
                    '/\{(booking_desc)}]?/',
                    '/\{(total_distance)}]?/',
                    '/\{(totalDuration)}]?/',
                    '/\{(amount)}]?/',
                    '/\{(type_name)}]?/',
                    '/\{(date)}]?/',
                    '/\{(time)}]?/',
                    '/\{(total_passenger)}]?/',
                    // '/\{(customer_name)}]?/',
                    '/\{(return_status)}]?/',



                ];

                $replacements = [

                    $tracking_number,
                    $booking->head_passenger_name ? $booking->head_passenger_name : 'FTS - Customer',
                    $booking->head_passenger_email,
                    $booking->head_passenger_mobile,
                    $booking->booking_from_loc_name,
                    $booking->booking_to_loc_name,
                    $booking_details,
                    $booking->booking_local_date,
                    $booking->booking_local_time,
                    $booking->booking_desc,
                    $booking->total_distance,
                    $booking->totalDuration,
                    $booking->deduction_price,
                    $type_name,
                    $date,
                    $time,
                    $booking->total_passenger,
                    $return_status,
                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->company_id = $company_id;
                $emailnotification->driver_id = $driver_id;
                $emailnotification->to_email = $company_email;
                $emailnotification->email_subject = $checkMail->mail_subject;
                $emailnotification->email_body = $checkMail->header . $mail . $checkMail->footer;
                $emailnotification->schedule_date = date('Y-m-d H:i:s');
                $emailnotification->email_sent_status = 'N';
                $emailnotification->save();

                $notification = new Notification();
                $notification->company_id = $company_id;
                $notification->driver_id = $driver_id;
                $notification->title = 'FTS - Booking Confirmation Notification';
                $notification->description = 'Your booking is confirmed against! #' . $tracking_number;
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

                    Mail::html($email_body, function ($message) use ($setting_comm_email, $setting_comm_email_name, $to_email, $email_subject) {
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


    public function driver_documents_listing(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            $driver = Driver::orderBy('created_at', 'desc')->paginate(5);

            foreach ($driver as $value) {

                $documents = DriverOtherDocument::where('driver_id', $value->id)->get();

                $fleet_type = FleetType::find($value->fleet_type_id);

                $value->fleet_type = $fleet_type->type_name;

                $value->other_documents = $documents;
            }

            return response()->json([
                'status' => true,
                'message' => 'Drivers documents list!',
                'response' => $driver,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }


    public function company_documents_listing(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // $documents = DB::table('company_documents')
            //     ->join('companies', 'company_documents.company_id', '=', 'companies.id')
            //     ->select('company_documents.*', 'companies.company_name')
            //     ->orderBy('company_documents.company_id') // Optional: to ensure the results are ordered
            //     ->get()
            //     ->groupBy('company_documents.company_id')
            //     ->flatten(1); // Flattens the grouped structure into a plain array

            // Retrieve paginated data
            $paginatedDocuments = DB::table('company_documents')
                ->join('companies', 'company_documents.company_id', '=', 'companies.id')
                ->select('company_documents.*', 'companies.company_name')
                ->orderBy('company_documents.company_id')
                ->paginate(5);

            // Manually group the items by 'company_id'
            $groupedDocuments = $paginatedDocuments->items(); // Get the items as an array
            $groupedDocuments = collect($groupedDocuments)->groupBy('company_id');

            // Create a new LengthAwarePaginator instance with grouped data
            $documents = new LengthAwarePaginator(
                $groupedDocuments->flatten(1),
                $paginatedDocuments->total(),
                $paginatedDocuments->perPage(),
                $paginatedDocuments->currentPage(),
                ['path' => Paginator::resolveCurrentPath()]
            );


            return response()->json([
                'status' => true,
                'message' => 'Company documents list!',
                'response' => $documents,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function fleet_documents_listing(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            $documents = DB::table('fleets')
                ->join('companies', 'fleets.company_id', '=', 'companies.id')
                ->join('fleet_types', 'fleets.vehicle_type', '=', 'fleet_types.id')
                ->select('fleets.*', 'companies.company_name', 'fleet_types.type_name')
                ->where('fleets.is_deleted', 0)
                ->orderBy('created_at', 'desc')
                ->paginate(5);


            return response()->json([
                'status' => true,
                'message' => 'Fleet documents list!',
                'response' => $documents,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function approved_document(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'document_id' => 'required',
                'status' => 'required',
                'document_role' => 'required'

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->document_role == 'company') {

                $company_document = CompanyDocument::find($request->document_id);
                if ($request->status == 'approved') {
                    $company_document->document_status = 'approved';

                    $notification = new Notification();
                    $notification->company_id = $company_document->company_id;
                    $notification->title = 'FTS - Documents Approved Notification';
                    $notification->description = 'Your documents have been approved by the admin!';
                    $notification->schedule_date = date('Y-m-d H:i:s');
                    $notification->sent_status = 'Y';
                    $notification->save();
                } else if ($request->status == 'rejected') {

                    $company_document->document_status = 'rejected';

                    $notification = new Notification();
                    $notification->company_id = $company_document->company_id;
                    $notification->title = 'FTS - Documents Rejected Notification';
                    $notification->description = 'Your documents have been rejected by the admin!';
                    $notification->schedule_date = date('Y-m-d H:i:s');
                    $notification->sent_status = 'Y';
                    $notification->save();
                } else if ($request->status == 'pending') {
                    $company_document->document_status = 'in_review';
                }
                $company_document->save();

                return response()->json([
                    'status' => true,
                    'message' => 'You have change the status of documents successfully!',
                ]);
            } else if ($request->document_role == 'driver') {

                $driver = Driver::find($request->document_id);
                if ($request->status == 'approved') {
                    $driver->active_status = 'approved';
                } else if ($request->status == 'rejected') {
                    $driver->active_status = 'banned';
                } else if ($request->status == 'pending') {
                    $driver->active_status = 'pending';
                }
                $driver->save();

                return response()->json([
                    'status' => true,
                    'message' => 'You have appproved this driver successfully!',
                ]);
            } else if ($request->document_role == 'fleet') {

                $fleet = Fleet::find($request->document_id);
                if ($request->status == 'approved') {
                    $fleet->active_status = 1;

                    $notification = new Notification();
                    $notification->company_id = $fleet->company_id;
                    $notification->title = 'FTS - Fleet Documents Approved Notification';
                    $notification->description = 'Your fleet documents have been approved by the admin!';
                    $notification->schedule_date = date('Y-m-d H:i:s');
                    $notification->sent_status = 'Y';
                    $notification->save();
                } else if ($request->status == 'rejected') {
                    $fleet->active_status = 2;

                    $notification = new Notification();
                    $notification->company_id = $fleet->company_id;
                    $notification->title = 'FTS - Fleet Documents Rejected Notification';
                    $notification->description = 'Your fleet documents have been rejected by the admin!';
                    $notification->schedule_date = date('Y-m-d H:i:s');
                    $notification->sent_status = 'Y';
                    $notification->save();
                } else if ($request->status == 'pending') {
                    $fleet->active_status = 0;
                }
                $fleet->save();

                return response()->json([
                    'status' => true,
                    'message' => 'You have change this fleet status successfully!',
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please select valid document role!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function search_documents(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'query' => 'required',
                'document_role' => 'required'

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->document_role == 'company') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $documents = CompanyDocument::join('companies', 'company_documents.company_id', '=', 'companies.id')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('companies.company_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('company_documents.license_num', 'LIKE', '%' . $query . '%');
                        })
                        ->select('company_documents.*', 'companies.company_name')
                        // ->groupBy('company_documents.company_id')
                        // ->orderBy('company_documents.company_id')
                        ->paginate(5);

                    return response()->json([
                        'status' => true,
                        'message' => 'Company documents list!',
                        'response' => $documents,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Company documents not found!',
                    ]);
                }
            } else if ($request->document_role == 'driver') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $driver = Driver::where(function ($queryBuilder) use ($query) {
                        $queryBuilder->where('first_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('national_insurance_num', 'LIKE', '%' . $query . '%');
                    })->paginate(5);

                    foreach ($driver as $value) {

                        $documents = DriverOtherDocument::where('driver_id', $value->id)->get();
                        $value->other_documents = $documents;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Drivers documents list!',
                        'response' => $driver,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Driver documents not found!',
                    ]);
                }
            } else if ($request->document_role == 'fleet') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $documents = fleet::join('companies', 'fleets.company_id', '=', 'companies.id')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('companies.company_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('fleets.vehicle_id', 'LIKE', '%' . $query . '%');
                        })
                        ->select('fleets.*', 'companies.company_name')
                        ->paginate(5);


                    return response()->json([
                        'status' => true,
                        'message' => 'Fleet documents list!',
                        'response' => $documents,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Fleet documents not found!',
                    ]);
                }
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please select valid values!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function driver_listing(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
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

            if ($request->status == 'approved') {

                $driver = Driver::where('active_status', 'approved')->orderBy('created_at', 'desc')
                    ->paginate(5);

                foreach ($driver as $value) {

                    $other_docs = DriverOtherDocument::where('driver_id', $value->id)->get();

                    $value->other_documents = $other_docs;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Approved driver list!',
                    'response' => $driver,
                ]);
            } else if ($request->status == 'pending') {

                $driver = Driver::where('active_status', 'pending')->orderBy('created_at', 'desc')->paginate(5);

                foreach ($driver as $value) {

                    $other_docs = DriverOtherDocument::where('driver_id', $value->id)->get();

                    $value->other_documents = $other_docs;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Pending driver list!',
                    'response' => $driver,
                ]);
            } else if ($request->status == 'banned') {

                $driver = Driver::where('active_status', 'banned')->orderBy('created_at', 'desc')->paginate(5);

                foreach ($driver as $value) {

                    $other_docs = DriverOtherDocument::where('driver_id', $value->id)->get();

                    $value->other_documents = $other_docs;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Banned driver list!',
                    'response' => $driver,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function search_drivers(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'query' => 'required',
                'status' => 'required'

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->status == 'pending') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $driver = Driver::where(function ($queryBuilder) use ($query) {
                        $queryBuilder->where('first_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('national_insurance_num', 'LIKE', '%' . $query . '%');
                    })->where('active_status', 'pending')->paginate(5);

                    foreach ($driver as $value) {

                        $documents = DriverOtherDocument::where('driver_id', $value->id)->get();
                        $value->other_documents = $documents;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Pending drivers list!',
                        'response' => $driver,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Driver list not found!',
                    ]);
                }
            } else if ($request->status == 'approved') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $driver = Driver::where(function ($queryBuilder) use ($query) {
                        $queryBuilder->where('first_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('national_insurance_num', 'LIKE', '%' . $query . '%');
                    })->where('active_status', 'approved')->paginate(5);

                    foreach ($driver as $value) {

                        $documents = DriverOtherDocument::where('driver_id', $value->id)->get();
                        $value->other_documents = $documents;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Approved drivers list!',
                        'response' => $driver,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Driver list not found!',
                    ]);
                }
            } else if ($request->status == 'banned') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $driver = Driver::where(function ($queryBuilder) use ($query) {
                        $queryBuilder->where('first_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $query . '%')
                            ->orWhere('national_insurance_num', 'LIKE', '%' . $query . '%');
                    })->where('active_status', 'rejected')->paginate(5);

                    foreach ($driver as $value) {

                        $documents = DriverOtherDocument::where('driver_id', $value->id)->get();
                        $value->other_documents = $documents;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Banned drivers list!',
                        'response' => $driver,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Driver list not found!',
                    ]);
                }
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please select valid values!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function company_listing(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
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

            if ($request->status == 'approved') {

                $company = Company::where('status', 1)->orderBy('created_at', 'desc')->paginate(5);

                foreach ($company as $value) {

                    $company_docs = CompanyDocument::where('company_id', $value->id)->get();

                    $value->company_documents = $company_docs;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Approved company list!',
                    'response' => $company,
                ]);
            } else if ($request->status == 'pending') {

                $company = Company::where('status', 0)->orderBy('created_at', 'desc')->paginate(5);

                foreach ($company as $value) {

                    $company_docs = CompanyDocument::where('company_id', $value->id)->get();

                    $value->company_documents = $company_docs;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Pending company list!',
                    'response' => $company,
                ]);
            } else if ($request->status == 'banned') {

                $company = Company::where('status', 2)->orderBy('created_at', 'desc')->paginate(5);

                foreach ($company as $value) {

                    $company_docs = CompanyDocument::where('company_id', $value->id)->get();

                    $value->company_documents = $company_docs;
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Banned company list!',
                    'response' => $company,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function company_listing_by_id(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->status == 'approved') {

                $company = Company::where('id', $request->company_id)->where('status', 1)->orderBy('created_at', 'desc')->first();


                $company_docs = CompanyDocument::where('company_id', $company->id)->get();

                $company->company_documents = $company_docs;


                return response()->json([
                    'status' => true,
                    'message' => 'Approved company list!',
                    'response' => $company,
                ]);
            } else if ($request->status == 'pending') {

                $company = Company::where('id', $request->company_id)->where('status', 0)->orderBy('created_at', 'desc')->first();

                $company_docs = CompanyDocument::where('company_id', $company->id)->get();

                $company->company_documents = $company_docs;


                return response()->json([
                    'status' => true,
                    'message' => 'Pending company list!',
                    'response' => $company,
                ]);
            } else if ($request->status == 'banned') {


                $company = Company::where('id', $request->company_id)->where('status', 2)->orderBy('created_at', 'desc')->first();


                $company_docs = CompanyDocument::where('company_id', $company->id)->get();

                $company->company_documents = $company_docs;


                return response()->json([
                    'status' => true,
                    'message' => 'Banned company list!',
                    'response' => $company,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function search_company(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'query' => 'required',
                'status' => 'required'

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->status == 'pending') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $company = Company::leftjoin('company_documents', 'companies.id', '=', 'company_documents.company_id')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('companies.company_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('company_documents.license_num', 'LIKE', '%' . $query . '%')
                                ->orWhere('companies.company_reg_num', 'LIKE', '%' . $query . '%');
                        })
                        ->select('companies.*') // Select only the company fields
                        ->distinct() // Ensure unique company records
                        ->where('companies.status', 0)
                        ->paginate(5);

                    // Fetch related company documents for each company
                    foreach ($company as $value) {
                        // Fetch the documents for each company without using a relationship
                        $company_docs = CompanyDocument::where('company_id', $value->id)->get();
                        $value->company_documents = $company_docs;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Pending Company list!',
                        'response' => $company,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Company list not found!',
                    ]);
                }
            } else if ($request->status == 'approved') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $company = Company::leftjoin('company_documents', 'companies.id', '=', 'company_documents.company_id')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('companies.company_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('company_documents.license_num', 'LIKE', '%' . $query . '%')
                                ->orWhere('companies.company_reg_num', 'LIKE', '%' . $query . '%');
                        })
                        ->select('companies.*') // Select only the company fields
                        ->distinct() // Ensure unique company records
                        ->where('companies.status', 1)
                        ->paginate(5);

                    // Fetch related company documents for each company
                    foreach ($company as $value) {
                        // Fetch the documents for each company without using a relationship
                        $company_docs = CompanyDocument::where('company_id', $value->id)->get();
                        $value->company_documents = $company_docs;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Approved company list!',
                        'response' => $company,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Company list not found!',
                    ]);
                }
            } else if ($request->status == 'banned') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {
                    $company = Company::leftjoin('company_documents', 'companies.id', '=', 'company_documents.company_id')
                        ->where(function ($queryBuilder) use ($query) {
                            $queryBuilder->where('companies.company_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('company_documents.license_num', 'LIKE', '%' . $query . '%')
                                ->orWhere('companies.company_reg_num', 'LIKE', '%' . $query . '%');
                        })
                        ->select('companies.*') // Select only the company fields
                        ->distinct() // Ensure unique company records
                        ->where('companies.status', 2)
                        ->paginate(5);

                    // Fetch related company documents for each company
                    foreach ($company as $value) {
                        // Fetch the documents for each company without using a relationship
                        $company_docs = CompanyDocument::where('company_id', $value->id)->get();
                        $value->company_documents = $company_docs;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Banned company list!',
                        'response' => $company,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Company list not found!',
                    ]);
                }
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please select valid values!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function customer_listing(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
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

            if ($request->status == 'approved') {

                $customer = User::where('active_status', 1)->where('role_id', 3)->orderBy('created_at', 'desc')->paginate(5);

                return response()->json([
                    'status' => true,
                    'message' => 'Approved customer list!',
                    'response' => $customer,
                ]);
            } else if ($request->status == 'pending') {

                $customer = User::where('active_status', 0)->where('role_id', 3)->orderBy('created_at', 'desc')->paginate(5);

                return response()->json([
                    'status' => true,
                    'message' => 'Pending customer list!',
                    'response' => $customer,
                ]);
            } else if ($request->status == 'banned') {

                $customer = User::where('active_status', 2)->where('role_id', 3)->orderBy('created_at', 'desc')->paginate(5);

                return response()->json([
                    'status' => true,
                    'message' => 'Banned customer list!',
                    'response' => $customer,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function search_customer(Request $request)
    {
        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'query' => 'required',
                'status' => 'required'

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if ($request->status == 'pending') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $users = User::where('name', 'LIKE', '%' . $query . '%')
                        ->where('active_status', 0)->where('role_id', 3)->paginate(5);


                    return response()->json([
                        'status' => true,
                        'message' => 'Pending user list!',
                        'response' => $users,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'User list not found!',
                    ]);
                }
            } else if ($request->status == 'approved') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $users = User::where('name', 'LIKE', '%' . $query . '%')
                        ->where('active_status', 1)->where('role_id', 3)->paginate(5);


                    return response()->json([
                        'status' => true,
                        'message' => 'Approved user list!',
                        'response' => $users,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'User list not found!',
                    ]);
                }
            } else if ($request->status == 'banned') {

                $query = $request->input('query'); // Input field for the search

                // Check if search input is not empty
                if ($query) {

                    $users = User::where('name', 'LIKE', '%' . $query . '%')
                        ->where('active_status', 2)->where('role_id', 3)->paginate(5);


                    return response()->json([
                        'status' => true,
                        'message' => 'Banned user list!',
                        'response' => $users,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'User list not found!',
                    ]);
                }
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Please select valid values!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You have not permission!',
            ]);
        }
    }

    public function users_payment_listing(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            // Start building the query
            // $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
            //     ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
            //     ->select('customer_bookings.company_id', 'customer_bookings.tracking_number', 'customer_bookings.head_passenger_name', 'user_payments.created_at', 'customer_bookings.booking_price', 'fleet_types.type_name', 'user_payments.payment_type', 'user_payments.status');

            $user_search = $request->input('user_search');
            $company_search = $request->input('company_search');
            $driver_search = $request->input('driver_search');

            $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
                ->leftJoin('companies', 'companies.id', '=', 'customer_bookings.company_id') // No whereNotNull check
                ->leftJoin('drivers', 'drivers.id', '=', 'customer_bookings.driver_id') // No whereNotNull check
                ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
                ->select('customer_bookings.company_id', 'customer_bookings.tracking_number', 'customer_bookings.head_passenger_name', 'customer_bookings.booking_status', 'user_payments.created_at', 'customer_bookings.booking_price', 'fleet_types.type_name', 'user_payments.payment_type', 'user_payments.status', 'companies.company_name', 'drivers.first_name');


            // // Apply search by tracking number if provided
            // if ($request->has('tracking_number') && !empty($request->tracking_number)) {
            //     $query->where('customer_bookings.tracking_number', 'like', '%' . $request->tracking_number . '%');
            // }

            // // Apply search by booking type  if provided
            // if ($request->has('booking_type') && !empty($request->booking_type)) {
            //     $query->where('fleet_types.type_name', 'like', '%' . $request->booking_type . '%');
            // }

            // // Apply search by customer name if provided
            // if ($request->has('customer_name') && !empty($request->customer_name)) {
            //     $query->where('customer_bookings.head_passenger_name', 'like', '%' . $request->customer_name . '%');
            // }

            // Apply search if provided
            if ($request->has('search') && !empty($request->search)) {
                $query->where('customer_bookings.tracking_number', 'like', '%' . $request->search . '%')
                    ->orWhere('fleet_types.type_name', 'like', '%' . $request->search . '%')->orwhere('customer_bookings.head_passenger_name', 'like', '%' . $request->search . '%')
                    ->orwhere('customer_bookings.head_passenger_name', 'like', '%' . $request->search . '%')
                ;
            }

            if ($user_search) {
                // if (!is_null($query->user_id)) {

                $query->where('customer_bookings.user_id', $user_search);
                // }
            }
            if ($company_search) {

                // if (!is_null($query->company_id)) {

                $query->where('customer_bookings.company_id', $company_search);
                // }
            }
            if ($driver_search) {

                // if (!is_null($query->driver_id)) {

                $query->where('customer_bookings.driver_id', $driver_search);
                // }
            }


            // Apply date range filter if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                // Ensure dates are in a valid format
                // $query->whereBetween('user_payments.created_at', [$startDate, $endDate]);

                $query->where('user_payments.created_at', '>=', $startDate)
                    ->where('user_payments.created_at', '<=', $endDate);
            }

            // Paginate results
            $bookings = $query->orderBy('customer_bookings.created_at', 'desc')->paginate(5);

            foreach ($bookings as $value) {

                if (!is_null($value->company_id)) {
                    $company = Company::find($value->company_id);
                    $value->company_name = $company->company_name;
                } else {
                    $value->company_name = [];
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Payment list!',
                'response' => $bookings,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see payments list!',
            ]);
        }
    }


    // download pdf of payment list

    public function download_payment_listing(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            $user_search = $request->input('user_search');
            $company_search = $request->input('company_search');
            $driver_search = $request->input('driver_search');

            $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
                ->leftJoin('companies', 'companies.id', '=', 'customer_bookings.company_id') // No whereNotNull check
                ->leftJoin('drivers', 'drivers.id', '=', 'customer_bookings.driver_id') // No whereNotNull check
                ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
                ->select('customer_bookings.company_id', 'customer_bookings.tracking_number', 'customer_bookings.head_passenger_name', 'customer_bookings.booking_status', 'user_payments.created_at', 'customer_bookings.booking_price', 'fleet_types.type_name', 'user_payments.payment_type', 'user_payments.status', 'companies.company_name', 'drivers.first_name');


            // Apply search if provided
            if ($request->has('search') && !empty($request->search)) {
                $query->where('customer_bookings.tracking_number', 'like', '%' . $request->search . '%')
                    ->orWhere('fleet_types.type_name', 'like', '%' . $request->search . '%')->orwhere('customer_bookings.head_passenger_name', 'like', '%' . $request->search . '%')
                    ->orwhere('customer_bookings.head_passenger_name', 'like', '%' . $request->search . '%')
                ;
            }

            if ($user_search) {
                // if (!is_null($query->user_id)) {

                $query->where('customer_bookings.user_id', $user_search);
                // }
            }
            if ($company_search) {

                // if (!is_null($query->company_id)) {

                $query->where('customer_bookings.company_id', $company_search);
                // }
            }
            if ($driver_search) {

                // if (!is_null($query->driver_id)) {

                $query->where('customer_bookings.driver_id', $driver_search);
                // }
            }


            // Apply date range filter if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                // Ensure dates are in a valid format
                // $query->whereBetween('user_payments.created_at', [$startDate, $endDate]);

                $query->where('user_payments.created_at', '>=', $startDate)
                    ->where('user_payments.created_at', '<=', $endDate);
            }

            // Paginate results
            $bookings = $query->orderBy('customer_bookings.created_at', 'desc')->get();

            foreach ($bookings as $value) {

                if (!is_null($value->company_id)) {
                    $company = Company::find($value->company_id);
                    $value->company_name = $company->company_name;
                } else {
                    $value->company_name = '-';
                }
            }
            $pdf = Pdf::loadView('payment_list_pdf', compact('bookings'))
                ->setPaper('a4', 'landscape');

                $fileName = 'user_payment_list_' . time() . '.pdf';
                $filePath = 'pdfs/' . $fileName;
            
                // Store the PDF file in the storage/app directory
                Storage::disk('local')->put($filePath, $pdf->output());
            
                // Generate a custom URL to download the file via another route
                $fileUrl = route('downloadAndDelete', ['fileName' => $fileName]);
            
                return response()->json([
                    'status' => true,
                    'message' => 'PDF Downloaded Successfully',
                    'file_url' => $fileUrl,
                ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see payments list!',
            ]);
        }
    }
    public function downloadAndDelete(Request $request, $fileName)
    {
        $filePath = 'pdfs/' . $fileName;
        if (Storage::disk('local')->exists($filePath)) {
            $fileContent = Storage::disk('local')->get($filePath);
            Storage::disk('local')->delete($filePath);
            return response($fileContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'File not found!',
            ], 404);
        }
    }
    

    public function users_feedback_listing(Request $request)
    {
        $auth = Auth::user();

        // Check permissions

        if (!$auth->fixed_role_id == 1) {

            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see feedback list!',
            ]);
        }



        // Build the query
        $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
            ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
            ->join('feed_backs', 'customer_bookings.id', '=', 'feed_backs.booking_id')
            ->select(
                'customer_bookings.company_id',
                'feed_backs.service_level',
                'customer_bookings.tracking_number',
                'customer_bookings.head_passenger_name',
                'user_payments.created_at as payment_created_at',
                'customer_bookings.booking_price',
                'fleet_types.type_name',
                'user_payments.payment_type',
                'user_payments.status',
                'customer_bookings.id as booking_id'
            );

        // // Apply search by tracking number if provided
        // if ($request->filled('tracking_number')) {
        //     $query->where('customer_bookings.tracking_number', 'like', '%' . $request->tracking_number . '%');
        // }

        // // Apply search by booking type  if provided
        // if ($request->has('booking_type') && !empty($request->booking_type)) {
        //     $query->where('fleet_types.type_name', 'like', '%' . $request->booking_type . '%');
        // }

        // // Apply search by customer name if provided
        // if ($request->has('customer_name') && !empty($request->customer_name)) {
        //     $query->where('customer_bookings.head_passenger_name', 'like', '%' . $request->customer_name . '%');
        // }

        // Apply search if provided
        if ($request->has('search') && !empty($request->search)) {
            $query->where('customer_bookings.tracking_number', 'like', '%' . $request->search . '%')->orWhere('fleet_types.type_name', 'like', '%' . $request->search . '%')->orwhere('customer_bookings.head_passenger_name', 'like', '%' . $request->search . '%');
        }

        // Apply date range filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // $query->whereBetween('user_payments.created_at', [$request->start_date, $request->end_date]);

            $query->where('user_payments.created_at', '>=', $request->start_date)
                ->where('user_payments.created_at', '<=', $request->end_date);
        }



        // Paginate results
        $bookings = $query->orderBy('customer_bookings.created_at', 'desc')->paginate(5);

        foreach ($bookings as $value) {

            if (!is_null($value->company_id)) {
                $company = Company::find($value->company_id);
                $value->company_name = $company->company_name;
            } else {
                $value->company_name = [];
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Feedback list!',
            'response' => $bookings,
        ]);
    }

    public function affiliate_api_listing(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            // Start building the query
            $query = AffiliateApiForm::query();

            // Apply search by company name  if provided
            if ($request->has('company_name') && !empty($request->company_name)) {
                $query->where('company_name', 'like', '%' . $request->company_name . '%');
            }

            // Apply search by email  if provided
            if ($request->has('email') && !empty($request->email)) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            // Paginate results
            $data = $query->orderBy('created_at', 'desc')->paginate(5);


            return response()->json([
                'status' => true,
                'message' => 'Affliate api list!',
                'response' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see payments list!',
            ]);
        }
    }

    public function admin_celander(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            $validator = Validator::make($request->all(), [

                'month' => 'required',
                'year' => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }


            $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
                ->select('customer_bookings.id', 'customer_bookings.booking_date', 'customer_bookings.created_at')
                ->where('booking_request_companies.available_status', 'available')
                ->where('customer_bookings.booking_status', 'pending')
                ->whereMonth('customer_bookings.booking_date', $request->month)
                ->whereYear('customer_bookings.booking_date', $request->year)
                ->orderBy('customer_bookings.created_at', 'desc')
                ->distinct('customer_bookings.id')
                ->get();

            if ($bookings->isNotEmpty()) {

                foreach ($bookings as $booking) {

                    // $details = BookingDetail::where('booking_id', $booking->id)->get();
                    // $quote_details = QuoteAgainstRequest::where('booking_id', $booking->id)->get();

                    $quote_count = BookingRequestCompany::where('booking_id', $booking->id)->where('booking_quote_status', 'quoted')->where('available_status', 'available')->count();

                    $un_quote_count = BookingRequestCompany::where('booking_id', $booking->id)->where('booking_quote_status', 'un-quoted')->where('available_status', 'available')->count();


                    if ($quote_count) {
                        $booking->quote_count = $quote_count;
                    } else {
                        $booking->quote_count = 0;
                    }

                    if ($un_quote_count) {
                        $booking->un_quote_count = $un_quote_count;
                    } else {
                        $booking->un_quote_count = 0;
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Quoted and un-quoted count against booking',
                    'response' => $bookings
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'List not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see celander!',
            ]);
        }
    }

    // public function dashboard_stats(Request $request)
    // {


    //     $company = Company::select('id')->where('user_id', $request->company_id)->first();

    //     $change_request = CustomerBooking::where('booking_status', 'change_request')->where('company_id', $company->id)->count();


    //     // $un_quoted_bookings = CustomerBooking::join('quote_against_requests', 'customer_bookings.id', '=', 'quote_against_requests.booking_id')
    //     // ->where('quote_against_requests.company_id', $company->id)
    //     // ->where('quote_against_requests.status', 1)
    //     // ->count();



    //     $current_date_time = Carbon::now();

    //     $upcoming_bookings = CustomerBooking::join('quote_against_requests', 'customer_bookings.id', '=', 'quote_against_requests.booking_id')
    //         ->where('quote_against_requests.company_id', $company->id)
    //         ->where('quote_against_requests.status', 1)
    //         ->where(function ($query) use ($current_date_time) {
    //             $query->where('customer_bookings.booking_date', '>', $current_date_time->toDateString())
    //                 ->orWhere(function ($query) use ($current_date_time) {
    //                     $query->where('customer_bookings.booking_date', '=', $current_date_time->toDateString())
    //                         ->whereTime('customer_bookings.booking_time', '>', $current_date_time->toTimeString());
    //                 });
    //         })
    //         ->count();

    //     $complaints = CustomerBooking::join('feed_backs', 'customer_bookings.id', '=', 'feed_backs.booking_id')
    //     ->where('customer_bookings.company_id', $company->id)
    //     ->where('feed_backs.service_level', '<' ,3)
    //     ->count();

    //     $revenue = CustomerBooking::join('quote_against_requests', 'customer_bookings.id', '=', 'quote_against_requests.booking_id')
    //     ->where('quote_against_requests.company_id', $company->id)
    //     ->sum('quote_against_requests.price');

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Dashboard statistics',
    //         'change_request_count' => $change_request,
    //         'upcoming_bookings_count' => $upcoming_bookings,
    //         'complaints_count' => $complaints,
    //         'revenue' => $revenue

    //     ]);
    // }

    public function company_driver_listing(Request $request)
    {

        $company = Company::select('id', 'company_name')->get();
        $driver = Driver::select('id', 'first_name')->get();
        $meet_greet_charges = WebsiteBranding::select('meet_greet_charges')->first();

        return response()->json([
            'status' => true,
            'message' => 'Company/driver Listing',
            'company' => $company,
            'driver' => $driver,
            'meet_greet_charges' => $meet_greet_charges,

        ]);
    }

    public function section_name(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            $validator = Validator::make($request->all(), [

                'section_name' => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if (is_Null($request->section_name_id)) {

                $faq_section = new FaqSectionName();
                $faq_section->secion_name = $request->section_name;
                $faq_section->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Section added successfully',
                    'response' => $faq_section,

                ]);
            } else {

                $faq_section = FaqSectionName::find($request->section_name_id);
                $faq_section->secion_name = $request->section_name;
                $faq_section->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Section updated successfully',
                    'response' => $faq_section,

                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to add section!',
            ]);
        }
    }

    public function delete_section(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {
            if ($request->status == 'section') {
                $faqSection = FaqSectionName::find($request->section_name_id);

                if ($faqSection) {
                    // Delete questions associated with the section
                    FAQ::where('section_name_id', $faqSection->id)->delete();

                    // Delete the section
                    $faqSection->delete();

                    return response()->json([
                        'status' => true,
                        'message' => 'Complete section deleted successfully',
                    ]);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Section not found',
                ]);
            } elseif ($request->status == 'faq') {

                $questionsDeleted = FAQ::find($request->faq_id);

                if ($questionsDeleted) {

                    $questionsDeleted->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'FAQ data deleted successfully',
                    ]);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'No FAQ data found to delete',
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized action',
        ]);
    }


    public function manage_fleet_types(Request $request)
    {

        $auth = Auth::user();

        if ($auth->fixed_role_id == 1) {

            $rules = [

                // 'type_name' => 'required',
                // 'car_name' => 'required',
                // 'total_passengers' => 'required',
                // 'luggage_bags' => 'required',

            ];


            if (is_null($request->type_id)) {

                $rules['type_name'] = 'required';
                $rules['car_name'] = 'required';
                $rules['total_passengers'] = 'required';
                $rules['luggage_bags'] = 'required';
                $rules['car_picture'] = 'required|image|mimes:jpeg,png,jpg,gif';
                $rules['car_icon'] = 'required|mimetypes:image/svg+xml';
                // $rules['min_distance'] = 'required';
                // $rules['max_distance'] = 'required';
                // $rules['ride_fare'] = 'required';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            if (is_null($request->type_id)) {
                $fleet_type = new FleetType();
            } else {
                $fleet_type = FleetType::find($request->type_id);
                if (!$fleet_type) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Fleet type not found',
                    ]);
                }
            }

            $fleet_type->type_name = $request->type_name ? $request->type_name : $fleet_type->type_name;
            $fleet_type->car_name = $request->car_name ? $request->car_name : $fleet_type->car_name;
            $fleet_type->total_passengers = $request->total_passengers ? $request->total_passengers : $fleet_type->total_passengers;
            $fleet_type->luggage_bags = $request->luggage_bags ? $request->luggage_bags : $fleet_type->luggage_bags;

            if ($request->hasFile('car_picture')) {

                $fleet_type->car_picture = $this->handleFileUpload($request->file('car_picture'));
            }

            if ($request->hasFile('car_icon')) {

                $fleet_type->car_icon = $this->handleFileUpload($request->file('car_icon'));
            }

            $fleet_type->save();


            if (is_null($request->type_id)) {
                $additional = new FleetAdditionalCharge();
            } else {
                $additional = FleetAdditionalCharge::where('fleet_type_id', $request->type_id)->first();
                if (!$additional) {
                    $additional = new FleetAdditionalCharge();
                }
            }

            // $additional = new  FleetAdditionalCharge();
            $additional->fleet_type_id = $fleet_type->id;
            $additional->additional_charges = $request->additional_charges;
            $additional->meet_n_greet = $request->meet_n_greet;
            $additional->save();


            RouteFare::where('fleet_type_id', $fleet_type->id)->delete();

            // foreach ($request->fleet_fare as $fare) {

            //     $fare_object = new RouteFare();
            //     $fare_object->fleet_type_id = $fleet_type->id;
            //     $fare_object->min_distance = $fare['min_distance'];
            //     $fare_object->max_distance = $fare['max_distance'];
            //     $fare_object->ride_fare = $fare['ride_fare'];
            //     $fare_object->save();
            // }

            // Assuming $request->fleet_fare is a JSON string
            $fleet_fare = $request->fleet_fare;

            // Decode the JSON string into an associative array
            $fleet_fare_array = json_decode($fleet_fare, true); // true ensures it converts to an array

            if (is_array($fleet_fare_array)) {
                foreach ($fleet_fare_array as $fare) {
                    $fare_object = new RouteFare();
                    $fare_object->fleet_type_id = $fleet_type->id; // Assuming $fleet_type->id exists
                    $fare_object->min_distance = $fare['min_distance'];
                    $fare_object->max_distance = $fare['max_distance'];
                    $fare_object->ride_fare = $fare['ride_fare'];
                    $fare_object->save();
                }
            }


            $fleet_type->save();
            return response()->json([
                'status' => true,
                'message' => is_null($request->type_id) ? 'Fleet type created successfully' : 'Fleet type updated successfully',
                'fleet_type' => $fleet_type,
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to create and update the fleet type',
            ]);
        }
    }

    public function get_fleet_types(Request $request)
    {

        $fleet_types = FleetType::where('is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($fleet_types as $fleet_type) {

            $additional = FleetAdditionalCharge::where('fleet_type_id', $fleet_type->id)->first();

            $route = RouteFare::where('fleet_type_id', $fleet_type->id)->get();

            $fleet_type->additional_charges = $additional;

            $fleet_type->route_fare = $route;
        }

        return response()->json([
            'status' => true,
            'message' => 'Fleet type data fetched successfully',
            'response' => $fleet_types,
        ]);
    }

    public function delete_fleet_type(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            $validator = Validator::make($request->all(), [

                'fleet_type_id' => 'required|exists:fleet_types,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            $fleet_type = FleetType::find($request->fleet_type_id);
            $fleet_type->is_deleted = 1;
            $fleet_type->save();

            return response()->json([
                'status' => true,
                'message' => 'Fleet type deleted successfulyy ',
            ]);
        }
    }

    public function booking_search_admin(Request $request)
    {

        if ($request->role == 'user') {

            $query = $request->input('query'); // Input field for the search

            // Check if search input is not empty
            if ($query) {

                $users = User::where('name', 'LIKE', '%' . $query . '%')
                    ->where('active_status', 1)->where('role_id', 3)->get();


                return response()->json([
                    'status' => true,
                    'message' => 'Approved user list!',
                    'response' => $users,
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'User list not found!',
                ]);
            }
        } else if ($request->role == 'company') {

            $query = $request->input('query'); // Input field for the search

            $company = Company::where('company_name', 'LIKE', '%' . $query . '%')->where('status', 1)->get();

            return response()->json([
                'status' => true,
                'message' => 'Approved company list!',
                'response' => $company,
            ]);
        } else if ($request->role == 'driver') {

            $query = $request->input('query'); // Input field for the search

            $driver = Driver::where('first_name', 'LIKE', '%' . $query . '%')->where('active_status', 'approved')->get();

            return response()->json([
                'status' => true,
                'message' => 'Approved drivers list!',
                'response' => $driver,
            ]);
        }
    }

    public function smtp(Request $request)
    {

        $setting = Setting::whereIn('parameter', [
            'smtp_username',
            'smtp_password',
            'smtp_from_email',
            'smtp_from_name',
            'smtp_host',
            'smtp_port',
            'smtp_encryption'
        ])
            ->get();

        if ($setting->isNotEmpty()) {

            return response()->json([
                'status' => true,
                'message' => 'SMTP record!',
                'response' => $setting,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'SMTP record not found!',
            ]);
        }
    }

    public function smtp_update(Request $request)
    {

        // $validator = Validator::make($request->all(), [

        //     'smtp_username' => 'required|string',
        //     'smtp_password' => 'required|string',
        //     'smtp_from_email' => 'required|email',
        //     'smtp_from_name' => 'required|string',
        //     'smtp_host' => 'required|string',
        //     'smtp_port' => 'required|integer',
        //     'smtp_encryption' => 'required|string'

        // ]);

        // if ($validator->fails()) {
        //     $errors = $validator->errors()->first(); // Get the first error message

        //     $response = [
        //         'success' => false,
        //         'message' => $errors,
        //     ];

        //     return response()->json($response); // Return JSON response with HTTP
        // }

        $smtpRecords = $request->smtpRecords;

        foreach ($smtpRecords as $record) {
            DB::table('settings')
                ->where('id', $record['id'])
                ->update(['value' => $record['value']]);
        }

        return response()->json([
            'status' => true,
            'message' => 'SMTP record updated successfully!',
        ]);
    }

    public function sms(Request $request)
    {

        $setting = Setting::whereIn('parameter', [
            'sms_enable',
            'sms_gateway',
            'sms_sender_name',
            'sms_account_sid',
            'sms_auth_token',
            'sms_admin_mobile'
        ])
            ->get();

        if ($setting->isNotEmpty()) {

            return response()->json([
                'status' => true,
                'message' => 'SMS record!',
                'response' => $setting,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'SMS record not found!',
            ]);
        }
    }

    public function sms_update(Request $request)
    {

        // $validator = Validator::make($request->all(), [

        //     'sms_enable' => 'required|string',
        //     'sms_gateway' => 'required|string',
        //     'sms_sender_name' => 'required|string',
        //     'sms_account_sid' => 'required|string',
        //     'sms_auth_token' => 'required|string',
        //     'sms_admin_mobile' => 'required|string'

        // ]);

        // if ($validator->fails()) {
        //     $errors = $validator->errors()->first(); // Get the first error message

        //     $response = [
        //         'success' => false,
        //         'message' => $errors,
        //     ];

        //     return response()->json($response); // Return JSON response with HTTP
        // }

        $smsRecords = $request->smsRecords;

        foreach ($smsRecords as $record) {
            DB::table('settings')
                ->where('id', $record['id'])
                ->update(['value' => $record['value']]);
        }

        return response()->json([
            'status' => true,
            'message' => 'SMS record updated successfully!',
        ]);
    }

    public function stripe_data(Request $request)
    {

        $setting = Setting::whereIn('parameter', [
            'STRIPE_KEY',
            'STRIPE_SECRET',
            'LOCAL_STRIPE_KEY',
            'LOCAL_STRIPE_SECRET',
        ])->get();

        if ($setting->isNotEmpty()) {

            return response()->json([
                'status' => true,
                'message' => 'Stripe record!',
                'response' => $setting,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Stripe record not found!',
            ]);
        }
    }

    public function stripe_update(Request $request)
    {

        $stripeRecords = $request->stripeRecords;

        foreach ($stripeRecords as $record) {
            DB::table('settings')
                ->where('id', $record['id'])
                ->update([
                    'value' => $record['value'],
                    'default_status' => $record['default_status']
                ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Stripe record updated successfully!',
        ]);
    }


    public function website_branding(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            // 'favicon' => 'nullable|mimeytpes:image/svg+xml',
            // 'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'google_map_api_key' => 'nullable|string',
            'facebook_link' => 'nullable|url',
            'whatsapp_link' => 'nullable|string',
            'twitter_link' => 'nullable|url',
            'linkedin_link' => 'nullable|url',
            'instagram_link' => 'nullable|url',
            'email_link' => 'nullable|email',
            'trip_pilot_link' => 'nullable|url',
            'meet_greet_charges' => 'numeric',
            'trust_advisor_link' => 'nullable|url',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // Retrieve the settings record with id 1
        $settings = WebsiteBranding::find(1);

        // $settings = new WebsiteBranding();


        if (!$settings) {
            return response()->json([
                'status' => false,
                'message' => 'Website branding data not found!',
            ]);
        }

        // Update the fields if provided in the request
        $settings->google_map_api_key = $request->input('google_map_api_key');
        $settings->facebook_link = $request->input('facebook_link');
        $settings->whatsapp_link = $request->input('whatsapp_link');
        $settings->twitter_link = $request->input('twitter_link');
        $settings->linkedin_link = $request->input('linkedin_link');
        $settings->instagram_link = $request->input('instagram_link');
        $settings->email_link = $request->input('email_link');
        $settings->trip_pilot_link = $request->input('trip_pilot_link');
        $settings->trust_advisor_link = $request->input('trust_advisor_link');
        $settings->phone_number = $request->input('phone_number');
        $settings->address = $request->input('address');
        $settings->meet_greet_charges = $request->input('meet_greet_charges');

        // Handle favicon file upload
        if ($request->hasFile('favicon')) {

            $settings->favicon = $this->handleFileUpload($request->file('favicon'));
        }

        // Handle logo file upload
        if ($request->hasFile('logo')) {

            $settings->logo = $this->handleFileUpload($request->file('logo'));
        }

        // Save updated settings
        $settings->save();

        return response()->json([
            'status' => true,
            'message' => 'Website branding data updated successfully!',
        ]);
    }

    public function get_website_branding(Request $request)
    {
        // Retrieve the settings record with id 1
        $settings = WebsiteBranding::find(1);

        // $settings = new WebsiteBranding();


        if (!$settings) {
            return response()->json([
                'status' => false,
                'message' => 'Website branding data not found!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Website branding data updated successfully!',
            'response' => $settings,
        ]);
    }
    // get links for branding without auth
    // public function get_web_links(Request $request)
    // {
    //     // Retrieve the settings record with id 1
    //     $settings = WebsiteBranding::select('facebook_link', 'whatsapp_link', 'twitter_link', 'linkedin_link', 'instagram_link', 'trip_pilot_link', 'trust_advisor_link', 'phone_number', 'address')->first();

    //     // $settings = new WebsiteBranding();


    //     if (!$settings) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Website links data not found!',
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Website links data fetched successfully!',
    //         'response' => $settings,
    //     ]);
    // }
    public function payment_accounts(Request $request)
    {

        $rules = [
            'title' => 'required|string|max:255',
            'bank_account' => 'required|string|max:255',
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

        if (is_null($request->bank_transfer_account_id)) {
            $accounts = new BankTransferAccount();
        } else {
            $accounts = BankTransferAccount::find($request->bank_transfer_account_id);
            if (!$accounts) {
                return response()->json([
                    'status' => false,
                    'message' => 'Account not found',
                ]);
            }
        }

        $accounts->title = $request->title;
        $accounts->bank_account = $request->bank_account;
        $accounts->default_status = $request->default_status;
        $accounts->active_status = $request->active_status;
        $accounts->save();

        return response()->json([
            'status' => true,
            'message' => is_null($request->bank_transfer_account_id) ? 'Account created successfully' : 'Account updated successfully',
            'response' => $accounts
        ]);
    }

    public function payment_accounts_list(Request $request)
    {

        $list = BankTransferAccount::orderBy('created_at', 'desc')->paginate(5);

        return response()->json([
            'status' => true,
            'message' => 'Payments account list!',
            'response' => $list
        ]);
    }

    public function payment_account_get_by_id(Request $request)
    {

        $rules = [
            'bank_transfer_account_id' => 'required|exists:bank_transfer_accounts,id',
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

        $account = BankTransferAccount::find($request->bank_transfer_account_id);

        return response()->json([
            'status' => true,
            'message' => 'Payment account',
            'response' => $account
        ]);
    }

    public function search_accounts(Request $request)
    {

        $name = $request->input('title');


        // Build the query
        $query = BankTransferAccount::query();


        // Apply filters if provided

        if ($name) {
            $query->where('title', 'LIKE', '%' . $name . '%');
        }

        // Execute the query and get results
        $results = $query->orderBy('created_at', 'desc')->paginate(5);


        return response()->json([
            'status' => true,
            'message' => 'Accounts list!',
            'response' => $results,
        ]);
    }

    public function home_section(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            'card_1_heading' => 'required',
            'card_1_desc' => 'required',
            'card_2_heading' => 'required',
            'card_2_desc' => 'required',
            'card_3_heading' => 'required',
            'card_3_desc' => 'required',



        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // Retrieve the settings record with id 1
        $settings = BookingContent::find(1);

        // $settings = new BookingContent();


        if (!$settings) {
            return response()->json([
                'status' => false,
                'message' => 'Home page data not found!',
            ]);
        }

        // Update the fields if provided in the request
        $settings->card_1_heading = $request->input('card_1_heading');
        $settings->card_1_desc = $request->input('card_1_desc');
        $settings->card_2_heading = $request->input('card_2_heading');
        $settings->card_2_desc = $request->input('card_2_desc');
        $settings->card_3_heading = $request->input('card_3_heading');
        $settings->card_3_desc = $request->input('card_3_desc');
        $settings->save();

        return response()->json([
            'status' => true,
            'message' => 'Home page data updated successfully!',
        ]);
    }


    public function get_home_section(Request $request)
    {
        // Retrieve the settings record with id 1
        $settings = BookingContent::find(1);


        if (!$settings) {
            return response()->json([
                'status' => false,
                'message' => 'Home page data not found!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Home page data fetched successfully!',
            'response' => $settings,
        ]);
    }

    public function business_page(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            'hero_section_heading' => 'required',
            'hero_section_desc' => 'required',
            'industry_section_heading' => 'required',
            'testimonial_heading' => 'required',
            'testimonial_desc' => 'required',


        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // Retrieve the settings record with id 1
        $business_page = BusinessPage::find(1);

        // $settings = new BookingContent();


        if (!$business_page) {
            return response()->json([
                'status' => false,
                'message' => 'Home page data not found!',
            ]);
        }

        if ($request->hasFile('hero_section_img')) {

            $business_page->hero_section_img = $this->handleFileUpload($request->file('hero_section_img'));
        }

        if ($request->hasFile('industry_section_img')) {

            $business_page->industry_section_img = $this->handleFileUpload($request->file('industry_section_img'));
        }


        // Update the fields if provided in the request
        $business_page->hero_section_heading = $request->input('hero_section_heading');
        $business_page->hero_section_desc = $request->input('hero_section_desc');
        $business_page->industry_section_heading = $request->input('industry_section_heading');
        $business_page->testimonial_heading = $request->input('testimonial_heading');
        $business_page->testimonial_desc = $request->input('testimonial_desc');
        $business_page->save();

        return response()->json([
            'status' => true,
            'message' => 'Business page data updated successfully!',
        ]);
    }

    public function get_business_page(Request $request)
    {
        // Retrieve the settings record with id 1
        $business = BusinessPage::find(1);

        if (!$business) {
            return response()->json([
                'status' => false,
                'message' => 'Business page data not found!',
            ]);
        }

        // Retrieve 'about_choose_fts' data
        $whyChooseFts = AboutUs::where('key', 'about_choose_fts')->first();

        if ($whyChooseFts && $this->isJson($whyChooseFts->value)) {
            $business->about_choose_fts = json_decode($whyChooseFts->value, true); // Add as array
        } elseif ($whyChooseFts) {
            $business->about_choose_fts = $whyChooseFts->value; // Add as string
        } else {
            $business->about_choose_fts = null; // Handle if `whyChooseFts` is not found
        }

        return response()->json([
            'status' => true,
            'message' => 'Business page data fetched successfully!',
            'response' => $business,
        ]);
    }


    public function contact_page(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            'contact_heading' => 'required',
            'contact_desc' => 'required',
            'contact_address' => 'required',
            'contact_email' => 'required',
            'contact_phone' => 'required',


        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // Retrieve the settings record with id 1
        $contact_page = ContactPage::find(1);

        // $settings = new BookingContent();


        if (!$contact_page) {
            return response()->json([
                'status' => false,
                'message' => 'Contact page data not found!',
            ]);
        }


        // Update the fields if provided in the request
        $contact_page->contact_heading = $request->input('contact_heading');
        $contact_page->contact_desc = $request->input('contact_desc');
        $contact_page->contact_address = $request->input('contact_address');
        $contact_page->contact_email = $request->input('contact_email');
        $contact_page->contact_phone = $request->input('contact_phone');
        $contact_page->save();

        return response()->json([
            'status' => true,
            'message' => 'Contact page data updated successfully!',
        ]);
    }

    public function get_contact_page(Request $request)
    {
        // Retrieve the settings record with id 1
        $contact_page = ContactPage::find(1);


        if (!$contact_page) {
            return response()->json([
                'status' => false,
                'message' => 'Contact page data not found!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Contact page data fetched successfully!',
            'response' => $contact_page,
        ]);
    }


    public function about_content(Request $request)
    {
        $rules = [
            'about_hero_section' => 'sometimes|string',
            'about_our_company' => 'sometimes|string',
            'about_our_service' => 'sometimes|string',
            'about_choose_fts' => 'sometimes|string',
            'about_fleet_management' => 'sometimes|string',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $keys = [
            'about_hero_section' => $request->about_hero_section,
            'about_our_company' => $request->about_our_company,
            'about_our_service' => $request->about_our_service,
            'about_choose_fts' => $request->about_choose_fts,
            'about_fleet_management' => $request->about_fleet_management,
        ];

        // Handle image uploads
        if ($request->hasFile('about_hero_section_image')) {
            $keys['about_hero_section_image'] = $this->handleFileUpload($request->file('about_hero_section_image'));
        }

        if ($request->hasFile('about_our_company_image')) {
            $keys['about_our_company_image'] = $this->handleFileUpload($request->file('about_our_company_image'));
        }

        foreach ($keys as $key => $value) {
            if (!is_null($value)) { // Only process non-null values
                $data = AboutUs::where('key', $key)->first();

                if ($data) {
                    $data->value = $value;
                    $data->save();
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Key '$key' not found in the database.",
                    ]);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Data updated successfully',
        ]);
    }

    public function get_about_content()
    {
        // Define the keys to retrieve
        $keys = [
            'about_hero_section',
            'about_our_company',
            'about_our_service',
            'about_choose_fts',
            'about_fleet_management',
            'about_hero_section_image',
            'about_our_company_image',
        ];

        // Fetch the content for each key
        $content = AboutUs::whereIn('key', $keys)->pluck('value', 'key');

        // Decode JSON strings where applicable
        $decodedContent = $content->map(function ($value, $key) {
            // Decode JSON if it's a valid JSON string
            if ($this->isJson($value)) {
                return json_decode($value, true); // Return as array
            }
            return $value; // Otherwise, return the original value
        });

        $business = BusinessPage::select('testimonial_heading', 'testimonial_desc')->first();

        // Add testimonial data to the decoded content
        $decodedContent->put('testimonial', $business);

        // $fleet_types = FleetType::where('is_deleted', 0)->orderBy('create_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $decodedContent,
        ]);
    }

    /**
     * Check if a given string is a valid JSON.
     *
     * @param string $string
     * @return bool
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function become_driver_page(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            'driver_heading' => 'required',
            'driver_desc' => 'required',
            'driver_discount_title' => 'required',
            'driver_details' => 'required',
            'title_1' => 'required',
            'desc_1' => 'required',
            'title_2' => 'required',
            'desc_2' => 'required',
            'title_3' => 'required',
            'desc_3' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // Retrieve the settings record with id 1
        $driver_page = BecomeDriverPage::find(1);

        // $settings = new BookingContent();


        if (!$driver_page) {
            return response()->json([
                'status' => false,
                'message' => 'Driver page data not found!',
            ]);
        }

        if ($request->hasFile('driver_img')) {

            $driver_page->driver_img = $this->handleFileUpload($request->file('driver_img'));
        }

        if ($request->hasFile('driver_details_img')) {

            $driver_page->driver_details_img = $this->handleFileUpload($request->file('driver_details_img'));
        }


        // Update the fields if provided in the request
        $driver_page->driver_heading = $request->input('driver_heading');
        $driver_page->driver_desc = $request->input('driver_desc');
        $driver_page->driver_discount_title = $request->input('driver_discount_title');
        $driver_page->driver_details = $request->input('driver_details');
        $driver_page->title_1 = $request->input('title_1');
        $driver_page->desc_1 = $request->input('desc_1');
        $driver_page->title_2 = $request->input('title_2');
        $driver_page->desc_2 = $request->input('desc_2');
        $driver_page->title_3 = $request->input('title_3');
        $driver_page->desc_3 = $request->input('desc_3');
        $driver_page->save();

        return response()->json([
            'status' => true,
            'message' => 'Driver page data updated successfully!',
        ]);
    }

    public function get_become_driver_page(Request $request)
    {
        // Retrieve the settings record with id 1
        $driver = BecomeDriverPage::find(1);


        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver page data not found!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Driver page data fetched successfully!',
            'response' => $driver,
        ]);
    }

    public function become_operator_page(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [

            'operator_about_heading' => 'required',
            'operator_about_desc' => 'required',
            'operator_registration_heading' => 'required',
            'operator_discount_title' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }

        // Retrieve the settings record with id 1
        $operator_page = BecomeOperatorPage::find(1);

        // $settings = new BookingContent();


        if (!$operator_page) {
            return response()->json([
                'status' => false,
                'message' => 'Operator page data not found!',
            ]);
        }

        if ($request->hasFile('operator_about_img')) {

            $operator_page->operator_about_img = $this->handleFileUpload($request->file('operator_about_img'));
        }

        if ($request->hasFile('operator_register_img')) {

            $operator_page->operator_register_img = $this->handleFileUpload($request->file('operator_register_img'));
        }


        // Update the fields if provided in the request
        $operator_page->operator_about_heading = $request->input('operator_about_heading');
        $operator_page->operator_about_desc = $request->input('operator_about_desc');
        $operator_page->operator_registration_heading = $request->input('operator_registration_heading');
        $operator_page->operator_discount_title = $request->input('operator_discount_title');
        $operator_page->save();

        return response()->json([
            'status' => true,
            'message' => 'Operator page data updated successfully!',
        ]);
    }

    public function get_become_operator_page(Request $request)
    {
        // Retrieve the settings record with id 1
        $Operator = BecomeOperatorPage::find(1);


        if (!$Operator) {
            return response()->json([
                'status' => false,
                'message' => 'Operator page data not found!',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Operator page data fetched successfully!',
            'response' => $Operator,
        ]);
    }

    public function testimonials_content(Request $request)
    {

        $rules = [
            'name' => 'required',
            'designation' => 'required',
            'ratings' => 'required',
            'description' => 'required',
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


        if (is_null($request->testimonial_id)) {
            $testimonial = new Testimonial();
        } else {
            $testimonial = Testimonial::find($request->testimonial_id);
            if (!$testimonial) {
                return response()->json([
                    'status' => false,
                    'message' => 'Testimonial not found',
                ]);
            }
        }

        // Handle favicon file upload
        if ($request->hasFile('profile_pic')) {

            $testimonial->profile_pic = $this->handleFileUpload($request->file('profile_pic'));
        }

        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->ratings = $request->ratings;
        $testimonial->description = $request->description;
        $testimonial->save();



        return response()->json([
            'status' => true,
            'message' => is_null($request->testimonial_id) ? 'Testimonial created successfully' : 'Testimonial updated successfully',
            'response' => $testimonial,

        ]);
    }

    public function get_testimonials_content(Request $request)
    {

        $list = Testimonial::where('is_deleted', 0)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Testimonial accounts list!',
            'response' => $list
        ]);
    }

    public function testimonials_content_by_id(Request $request)
    {

        $rules = [
            'testimonial_id' => 'required|exists:testimonials,id',
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

        $testimonial = Testimonial::find($request->testimonial_id);

        return response()->json([
            'status' => true,
            'message' => 'Testimonial data',
            'response' => $testimonial
        ]);
    }

    public function search_testimonials(Request $request)
    {

        $name = $request->input('name');
        $designation = $request->input('designation');


        // Build the query
        $query = Testimonial::where('is_deleted', 0);


        // Apply filters if provided

        if ($designation) {
            $query->where('designation', 'LIKE', '%' . $designation . '%');
        }
        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        // Execute the query and get results
        $results = $query->orderBy('created_at', 'desc')->paginate(5);


        return response()->json([
            'status' => true,
            'message' => 'Testimonial list!',
            'response' => $results,
        ]);
    }

    public function delete_testimonials(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if ($auth->fixed_role_id == 1) {

            $validator = Validator::make($request->all(), [

                'testimonial_id' => 'required|exists:testimonials,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP
            }

            $fleet_type = Testimonial::find($request->testimonial_id);
            $fleet_type->is_deleted = 1;
            $fleet_type->save();

            return response()->json([
                'status' => true,
                'message' => 'Testimonial deleted successfulyy ',
            ]);
        }
    }

    // public function get_notification_content(Request $request)
    // {

    //     $notifications = NotificationManagement::all()->groupBy('group_by');

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Notification list!',
    //         'response' => $notifications,
    //     ]);
    // }

    public function get_notification_content(Request $request)
    {
        $notifications = NotificationManagement::all()->groupBy('group_by');

        $response = [];
        foreach ($notifications as $group => $items) {
            $response[] = [
                'group_name' => $group,
                'notifications' => $items
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Notification list!',
            'response' => $response,
        ]);
    }



    public function update_notification_content(Request $request)
    {

        $rules = [
            'id' => 'required|exists:notification_management,id',
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

        $notification = NotificationManagement::find($request->id);
        // Update fields with request data or keep existing values if data is null
        if ($request->roles == 'null') {
            $notification->user_type = NULL;
        } else {
            $notification->user_type = $request->roles ?? $notification->user_type;
        }
        $notification->send_email = $request->send_email ?? $notification->send_email;
        $notification->send_sms = $request->send_sms ?? $notification->send_sms;
        $notification->variable_list = $request->variable_list ?? $notification->variable_list;
        $notification->mail_subject = $request->mail_subject ?? $notification->mail_subject;
        $notification->mail = $request->mail ?? $notification->mail;
        $notification->mobile_app_description = $request->mobile_app_description ?? $notification->mobile_app_description;
        $notification->save();

        return response()->json([
            'status' => true,
            'message' => 'Notification data updated sucessfully!',
            'response' => $notification,
        ]);
    }

    public function search_notification_type(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'type_name' => 'nullable|string', // Use string since it's part of a LIKE query
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, // Consistent key name
                'message' => $validator->errors()->first(), // Return first error
            ]);
        }

        // Build the query
        $query = NotificationManagement::query();

        // Filter by type_name if provided
        if ($request->filled('type_name')) {
            $query->where('type', 'LIKE', '%' . $request->type_name . '%');
        }

        if ($request->filled('role_type')) {
            $query->where('user_type', 'LIKE', '%' . $request->role_type . '%');
        }

        // Paginate the results
        $results = $query->get()->groupBy('group_by');



        // Check if results are not empty and return appropriate response
        if ($results->isNotEmpty()) {

            $response = [];
            foreach ($results as $group => $items) {
                $response[] = [
                    'group_name' => $group,
                    'notifications' => $items
                ];
            }

            return response()->json([
                'status' => true,
                'message' => 'Notification list retrieved successfully.',
                'response' => $response, // Changed 'response' to 'data' for clarity
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Notification list not found!',
        ]);
    }


    public function get_data_admin_profile(Request $request)
    {


        $rules = [
            'id' => 'required|exists:users,id',
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

        $profile = User::find($request->id);

        return response()->json([
            'status' => true,
            'message' => 'Admin profile data fetched sucessfully!',
            'response' => $profile,
        ]);
    }

    public function update_password_admin(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'password' => 'required|string|max:255',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'success' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP
        }


        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'You have changed password successfully!',
        ]);
    }

    public function update_admin_profile(Request $request)
    {

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            return response()->json([
                'success' => false,
                'message' => $errors,
            ]);
        }

        if ($request->hasFile('profile_picture')) {

            $imagePath = $this->handleFileUpload($request->file('profile_picture'));
        }

        $admin = User::find($request->id);
        $admin->name = $request->name ?? $admin->name;
        $admin->phone = $request->phone ?? $admin->phone;
        $admin->profile_picture = $imagePath ?? $admin->profile_picture;
        $admin->role_id = 1;
        $admin->save();

        if ($request->password) {

            $user = User::find($request->id);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Admin details updated successfully!',
            'response' => $admin,
        ]);
    }

    public function check_doc_expiry()
    {

        // $fleet_expiry = Fleet::where('is_deleted', 0)->get();

        // $company_document_expiry = CompanyDocument::all();

        // $driver_expiry = Driver::all();

        // Get today's date
        $today = now();

        // Check fleet expiry
        $expiredFleets = Fleet::where('is_deleted', 0)
            ->where('mot', '<', $today)
            ->get();

        // Check company document expiry
        $expiredCompanyDocuments = CompanyDocument::where('valid_to', '<', $today)
            ->get();

        // Check driver expiry
        $expiredDrivers = Driver::where('mot', '<', $today)
            ->get();

        // Prepare the response
        $response = [
            'expired_fleets' => $expiredFleets,
            'expired_company_documents' => $expiredCompanyDocuments,
            'expired_drivers' => $expiredDrivers,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Expiry check completed',
            'data' => $response
        ]);
    }


    // list coupon

    public function list_coupon(Request $request)
    {
        try {
            $query = Coupon::query();
            $query->where('is_active', '1');
            if ($request->search_by) {
                $query->where('title', 'like', '%' . $request->search_by . '%')
                    ->orWhere('promo_code', 'like', '%' . $request->search_by . '%');
            }
            if ($request->start_date && $request->end_date) {
                $query->whereBetween(DB::raw('DATE(start_date_time)'), [$request->start_date, $request->end_date])
                    ->orWhereBetween(DB::raw('DATE(end_date_time)'), [$request->start_date, $request->end_date]);
            }
            $coupon = $query->paginate();
            if ($coupon) {
                return response()->json([
                    'status' => true,
                    'success' => true,
                    'message' => 'Data fetched Successfully',
                    'response' => $coupon
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Data Not Found',
                    'response' => []
                ]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $e->getMessage(),
                'response' => []
            ]);
        }
    }
    // save and update coupons
    public function save_update_coupon(Request $request)
    {
        try {
            $start_dateTime = $request->start_date . ' ' . $request->start_time;
            $end_dateTime = $request->end_date . ' ' . $request->end_time;
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'promo_code' => 'required',
                'start_date' => 'required|date_format:Y-m-d',
                'start_time' => 'required|date_format:H:i:s',
                'end_date' => 'required|date_format:Y-m-d',
                'end_time' => 'required|date_format:H:i:s',
                'discount_type' => 'required|string|in:price,percentage',
                'discount' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'response' => []
                ]);
            } else {
                if (!$request->id) {
                    $is_coupon = Coupon::where('is_active', '1')
                        ->where('promo_code', $request->promo_code)
                        ->where(function ($query) use ($start_dateTime, $end_dateTime) {
                            $query->whereBetween('start_date_time', [$start_dateTime, $end_dateTime])
                                ->orWhereBetween('end_date_time', [$start_dateTime, $end_dateTime])
                                ->orWhere(function ($query) use ($start_dateTime, $end_dateTime) {
                                    $query->where('start_date_time', '<=', $start_dateTime)
                                        ->where('end_date_time', '>=', $end_dateTime);
                                });
                        })
                        ->first();
                    if ($is_coupon) {
                        return response()->json([
                            'status' => false,
                            'success' => false,
                            'message' => 'Promo code already exists in this time period',
                            'response' => []
                        ]);
                    }
                }
                DB::beginTransaction();
                $coupon = Coupon::updateOrCreate([
                    'id' => $request->id,
                ], [
                    'title' => $request->title,
                    'promo_code' => $request->promo_code,
                    'start_date_time' => $start_dateTime,
                    'end_date_time' => $end_dateTime,
                    'fleet_type_id' => $request->fleet_type_id,
                    'discount_type' => $request->discount_type,
                    'discount' => $request->discount,
                ]);
                if ($coupon) {
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'success' => true,
                        'message' => 'Data Added Successfully',
                        'response' => $coupon
                    ]);
                } else {
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'success' => false,
                        'message' => 'Data Not Added Successfully',
                        'response' => []
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $e->getMessage(),
                'response' => []
            ]);
        }
    }


    // edit coupon
    public function edit_coupon(Request $request)
    {
        try {

            $coupon = Coupon::find($request->id);
            if ($coupon) {
                $start_date_time = Carbon::parse($coupon->start_date_time);
                $end_date_time = Carbon::parse($coupon->end_date_time);
                $coupon->start_date = $start_date_time->format('Y-m-d');
                $coupon->start_time = $start_date_time->format('H:i:s');
                $coupon->end_date = $end_date_time->format('Y-m-d');
                $coupon->end_time = $end_date_time->format('H:i:s');
                DB::commit();
                return response()->json([
                    'status' => true,
                    'success' => true,
                    'message' => 'Data fetched Successfully',
                    'response' => $coupon
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => 'Data Not Found',
                    'response' => []
                ]);
            }

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $e->getMessage(),
                'response' => []
            ]);
        }
    }

    // delete coupon

    public function delete_coupon(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'response' => []
                ]);
            } else {
                $coupon = Coupon::where('id', $request->id)->where('is_active', '1')->first();
                if ($coupon) {
                    DB::beginTransaction();
                    $coupon->update([
                        'is_active' => '0'
                    ]);
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'success' => true,
                        'message' => 'Data Deleted Successfully',
                        'response' => []
                    ]);
                } else {
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'success' => false,
                        'message' => 'Data Not Found',
                        'response' => []
                    ]);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'success' => false,
                'message' => $e->getMessage(),
                'response' => []
            ]);
        }
    }

}
