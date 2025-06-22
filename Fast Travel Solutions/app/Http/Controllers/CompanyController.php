<?php

namespace App\Http\Controllers;

use App\Mail\CompanyRegisterPasswordEmail;
use App\Models\BookingDetail;
use App\Models\BookingRequestCompany;
use App\Models\CardPayment;
use App\Models\ChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Models\CompanyContactUs;
use App\Models\User;
use App\Models\FleetType;
use App\Models\CompanyDocument;
use App\Models\CustomerBooking;
use App\Models\Driver;
use App\Models\FAQ;
use App\Models\FaqSectionName;
use App\Models\FeedBack;
use App\Models\Fleet;
use App\Models\Notification;
use App\Models\NotificationEmail;
use App\Models\NotificationManagement;
use App\Models\QuoteAgainstRequest;
use App\Models\Setting;
use App\Models\UserPayment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Symfony\Contracts\Service\Attribute\Required;

class CompanyController extends Controller
{
    use FileUploadTrait;

    // public function add_company(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [

    //         'company_name' => 'required|string|max:255',
    //         'company_email' => 'required|string|email|max:255|unique:companies',
    //         'company_type' => 'required|string|max:255',
    //         'company_address' => 'required|string|max:255',
    //         'company_reg_num' => 'required|string|max:100',
    //         'document_name' => 'required|string|max:255',
    //         'document_type' => 'required|string|max:255',
    //         'license_num' => 'required|string|max:50',
    //         'issuance_authority' => 'required|string|max:255',
    //         'valid_from' => 'required|date',
    //         'valid_to' => 'required|date|after:valid_from',
    //         'license_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'other_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'

    //     ]);

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->first(); // Get the first error message

    //         $response = [
    //             'success' => false,
    //             'message' => $errors,
    //         ];

    //         return response()->json($response); // Return JSON response with HTTP 
    //     }

    //     $password = Str::random(8);

    //     try {
    //         Mail::to($request->company_email)->send(new CompanyRegisterPasswordEmail($request->company_email, $password));
    //     } catch (\Exception $e) {
    //         // Log the exception for debugging purposes
    //         Log::error('Error sending email: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Technical Error in sending Email! Please try again later.',
    //         ]);
    //     }


    //     $company = Company::create([
    //         'company_name' => $request->company_name,
    //         'company_email' => $request->company_email,
    //         // 'password' => bcrypt($password), // Hash the password
    //         'company_type' => $request->company_type,
    //         'company_address' => $request->company_address,
    //         'company_reg_num' => $request->company_reg_num,

    //     ]);


    //     $document = new CompanyDocument();
    //     $document->company_id = $company->id;
    //     $document->document_name = $request->document_name;
    //     $document->document_type = $request->document_type;
    //     $document->license_num = $request->license_num;
    //     $document->issuance_authority = $request->issuance_authority;
    //     $document->valid_from = $request->valid_from;
    //     $document->valid_to = $request->valid_to;

    //     // Handle file uploads
    //     if ($request->hasFile('license_pic')) {
    //         $document->license_pic = $request->file('license_pic')->store('images', 'public');
    //     }

    //     if ($request->hasFile('other_file')) {
    //         $document->other_file = $request->file('other_file')->store('images', 'public');
    //     }

    //     $document->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Company created successfully',
    //     ]);
    // }

    // public function edit_company(Request $request)
    // {

    //     // Parse and decode JSON data if sent as JSON
    //     // $requestData = json_decode($request->getContent(), true);

    //     return $request;

    //     $validator = Validator::make($request, [

    //         'company_id' => 'required',
    //         'company_name' => 'required|string|max:255',
    //         // 'company_email' => 'required|string|email|max:255|unique:companies',
    //         'company_type' => 'required|string|max:255',
    //         'company_address' => 'required|string|max:255',
    //         'company_reg_num' => 'required|string|max:100',
    //         'document_name' => 'required|string|max:255',
    //         'document_type' => 'required|string|max:255',
    //         'license_num' => 'required|string|max:50',
    //         'issuance_authority' => 'required|string|max:255',
    //         'valid_from' => 'required|date',
    //         'valid_to' => 'required|date|after:valid_from',

    //     ]);

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->first(); // Get the first error message

    //         $response = [
    //             'success' => false,
    //             'message' => $errors,
    //         ];

    //         return response()->json($response); // Return JSON response with HTTP 
    //     }

    //     $company = Company::find($request->company_id);
    //     return $company;
    //     $company->company_name = $request->company_name;
    //     // $company->company_email = $request->company_email;
    //     $company->company_type = $request->company_type;
    //     $company->company_address = $request->company_address;
    //     $company->company_reg_num = $request->company_reg_num;
    //     $company->save();


    //     $document = CompanyDocument::where('company_id', $request->company_id)->first();
    //     $document->company_id = $company->id;
    //     $document->document_name = $request->document_name;
    //     $document->document_type = $request->document_type;
    //     $document->license_num = $request->license_num;
    //     $document->issuance_authority = $request->issuance_authority;
    //     $document->valid_from = $request->valid_from;
    //     $document->valid_to = $request->valid_to;

    //     // Handle file uploads
    //     if ($request->hasFile('license_pic')) {
    //         $document->license_pic = $request->file('license_pic')->store('images', 'public');
    //     }

    //     if ($request->hasFile('other_file')) {
    //         $document->other_file = $request->file('other_file')->store('images', 'public');
    //     }

    //     $document->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Company created successfully',
    //     ]);
    // }

    // public function company_previous_api(Request $request) // Create  and Edit the company
    // {

    //     $rules = [
    //         'company_name' => 'required|string|max:255',
    //         'company_type' => 'required|string|max:255',
    //         'company_address' => 'required|string|max:255',
    //         'company_reg_num' => 'required|string|max:100',
    //         'document_name' => 'required|string|max:255',
    //         'document_type' => 'required|string|max:255',
    //         'license_num' => 'required|string|max:50',
    //         'issuance_authority' => 'required|string|max:255',
    //         'valid_from' => 'required|date',
    //         'valid_to' => 'required|date|after:valid_from',
    //     ];

    //     if (is_null($request->company_id)) {
    //         $rules['company_email'] = 'required|string|email|max:255|unique:companies';
    //         $rules['license_pic'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
    //         $rules['other_file'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
    //     }

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->first(); // Get the first error message

    //         $response = [
    //             'success' => false,
    //             'message' => $errors,
    //         ];

    //         return response()->json($response); // Return JSON response with HTTP 
    //     }

    //     if (is_null($request->company_id)) {

    //         $password = Str::random(8);

    //         try {
    //             Mail::to($request->company_email)->send(new CompanyRegisterPasswordEmail($request->company_email, $password));
    //         } catch (\Exception $e) {
    //             // Log the exception for debugging purposes
    //             Log::error('Error sending email: ' . $e->getMessage());

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Technical Error in sending Email! Please try again later.',
    //             ]);
    //         }
    //     }


    //     if (is_null($request->company_id)) {
    //         $company = new Company();
    //     } else {
    //         $company = Company::find($request->company_id);
    //         if (!$company) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Company not found',
    //             ]);
    //         }
    //     }

    //     $company->company_name = $request->company_name;
    //     if (is_null($request->company_id)) {
    //         $company->company_email = $request->company_email;
    //         $company->password = bcrypt($password);
    //     }
    //     $company->company_type = $request->company_type;
    //     $company->company_address = $request->company_address;
    //     $company->company_reg_num = $request->company_reg_num;
    //     $company->save();

    //     if (is_null($request->company_id)) {

    //         $document = new CompanyDocument();
    //         $document->company_id = $company->id;
    //     } else {

    //         $document = CompanyDocument::where('company_id', $request->company_id)->first();

    //         if (!$document) {
    //             $document = new CompanyDocument();
    //             $document->company_id = $company->id;
    //         }
    //     }

    //     $document->document_name = $request->document_name;
    //     $document->document_type = $request->document_type;
    //     $document->license_num = $request->license_num;
    //     $document->issuance_authority = $request->issuance_authority;
    //     $document->valid_from = $request->valid_from;
    //     $document->valid_to = $request->valid_to;

    //     // Handle file uploads
    //     if ($request->hasFile('license_pic')) {
    //         $document->license_pic = $request->file('license_pic')->store('images', 'public');
    //     }

    //     if ($request->hasFile('other_file')) {
    //         $document->other_file = $request->file('other_file')->store('images', 'public');
    //     }

    //     $document->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Company ' . (is_null($request->company_id) ? 'created' : 'updated') . ' successfully',
    //     ]);
    // }

    public function company(Request $request)
    {

        $rules = [
            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'company_reg_num' => 'required|string|max:100',
        ];

        if (is_null($request->company_id)) {
            $rules['company_email'] = 'required|string|email|max:255|unique:companies,company_email|unique:users,email';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        // if (is_null($request->company_id)) {


        //     $password = Str::random(8);

        //     try {
        //         Mail::to($request->company_email)->send(new CompanyRegisterPasswordEmail($request->company_email, $password));
        //     } catch (\Exception $e) {
        //         // Log the exception for debugging purposes
        //         Log::error('Error sending email: ' . $e->getMessage());

        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Technical Error in sending Email! Please try again later.',
        //         ]);
        //     }
        // }

        if (is_null($request->company_id)) {
            $company = new Company();
        } else {
            $company = Company::find($request->company_id);
            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company not found',
                ]);
            }
        }

        $company->company_name = $request->company_name;
        if (is_null($request->company_id)) {
            $company->company_email = $request->company_email;
            // $company->password = bcrypt($password);
        }
        $company->company_type = $request->company_type;
        $company->company_address = $request->company_address;
        $company->company_reg_num = $request->company_reg_num;
        $company->save();

        if (is_null($request->company_id)) {

            $user = new User();
            $user->company_id = $company->id;
            $user->name = $request->company_name;
            $user->email = $request->company_email;
            $user->password = 'in-active';
            $user->role_id = 2;
            $user->fixed_role_id = 2;
            $user->company_user_role = 'Company';
            $user->fixed_user_role = 'Company';
            $user->active_status = 0;
            $user->save();

            $user->assignRole('Company');

            $company = Company::find($company->id);
            $company->user_id = $user->id;
            $company->save();
        }

        $documents = CompanyDocument::where('company_id', $request->company_id)->get();

        return response()->json([
            'status' => true,
            'message' => is_null($request->company_id) ? 'Company created successfully' : 'Company updated successfully',
            'response' => [
                'company' => $company,
                'documents' => $documents,
            ],
        ]);
    }

    public function documents(Request $request)
    {

        $rules = [
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
            'license_num' => 'required|string|max:50',
            'issuance_authority' => 'required|string|max:255',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
        ];

        if (is_null($request->document_id)) {

            // $rules['license_pic'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['other_file'] = 'required|mimes:jpeg,png,jpg,gif,pdf,doc,docx';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        if ($request->role == 'Admin') {

            $company = Company::find($request->company_id);
        } else {

            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);
        }


        // $company = Company::select('id')->where('user_id', $request->company_id)->first();


        // $company = Company::find($request->company_id);

        if ($company) {

            if (is_null($request->document_id)) {

                $document = new CompanyDocument();
            } else {

                $document = CompanyDocument::find($request->document_id);


                if (!$document) {
                    $document = new CompanyDocument();
                } else {

                    $document = CompanyDocument::find($request->document_id);
                    $document->document_status = 'in_review';
                }
            }



            $document->company_id = $company->id;
            $document->document_name = $request->document_name;
            $document->document_type = $request->document_type;
            $document->license_num = $request->license_num;
            $document->issuance_authority = $request->issuance_authority;
            $document->valid_from = $request->valid_from;
            $document->valid_to = $request->valid_to;

            // Handle file uploads
            // if ($request->hasFile('license_pic')) {
            //     $document->license_pic = $request->file('license_pic')->store('images', 'public');
            // }

            //   Handle file uploads
            if ($request->hasFile('other_file')) {

                $document->other_file = $this->handleFileUpload($request->file('other_file'));


                // $document->other_file = $request->file('other_file')->store('images', 'public');
            }

            $document->save();

            return response()->json([
                'status' => true,
                'message' => 'Document ' . (is_null($request->document_id) ? 'uploaded' : 'updated') . ' successfully',
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'Company not found',
            ]);
        }
    }

    public function documents_list($company_id)
    {
        // $auth = Auth::user();

        // // Check permissions
        // if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

        // $company = Company::select('id')->where('user_id', $company_id)->first();

        $user = User::find($company_id);
        $company = Company::find($user->company_id);

        // $company = Company::find($company->id);
        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found',
            ]);
        } else {

            // $company = Company::select('id')->where('user_id', $company_id)->first();
            $user = User::find($company_id);
            $company = Company::find($user->company_id);

            // $company = Company::find($company_id);

            $documents = CompanyDocument::where('company_id', $company->id)->get();


            return response()->json([
                'status' => true,
                'message' => 'Company documents list',
                'response' => $documents
            ]);
        }
        // } else {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'You do not have permission to search quotes!',
        //     ]); // Return HTTP 403 Forbidden
        // }
    }

    public function document_remove(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            $validator = Validator::make($request->all(), [

                'document_id' => 'required|exists:company_documents,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP 
            }

            $documents = CompanyDocument::find($request->document_id);
            $documents->delete();

            return response()->json([
                'status' => true,
                'message' => 'documents deleted successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to delete documents!',
            ]);
        }
    }

    public function company_celander(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            $validator = Validator::make($request->all(), [

                'company_id' => 'required|exists:users,id',
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

            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);

            // $company = Company::select('id')->where('user_id', $request->company_id)->first();

            // $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
            //     ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
            //     ->select('customer_bookings.*', 'booking_request_companies.id as booking_req_id', 'booking_request_companies.booking_quote_status')
            //     ->where('booking_request_companies.company_id', $company->id)
            //     ->where('booking_request_companies.available_status', 'available')
            //     ->where('customer_bookings.booking_status', 'pending')
            //     ->whereMonth('customer_bookings.booking_date', $request->month)
            //     ->whereYear('customer_bookings.booking_date', $request->year)
            //     ->orderBy('customer_bookings.created_at', 'desc')
            //     ->paginate(5);

            $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
                ->select('customer_bookings.id', 'customer_bookings.booking_date', 'customer_bookings.created_at')
                ->where('booking_request_companies.company_id', $company->id)
                ->where('booking_request_companies.available_status', 'available')
                ->where('customer_bookings.booking_status', 'pending')
                ->whereMonth('customer_bookings.booking_date', $request->month)
                ->whereYear('customer_bookings.booking_date', $request->year)
                ->orderBy('customer_bookings.created_at', 'desc')
                ->distinct('customer_bookings.id')
                ->get();

            // const groupBookingsByDate = (bookings) => {
            //     return bookings.reduce((acc, booking) => {
            //       const date = moment(booking.booking_date).format("YYYY-MM-DD");
            //       if (!acc[date]) {
            //         acc[date] = { quoted: 0, unQuoted: 0 };
            //       }
            //       acc[date].quoted += booking.quote_count;
            //       acc[date].unQuoted += booking.un_quote_count;
            //       return acc;
            //     }, {});
            //   };

            // return $bookings;

            if ($bookings->isNotEmpty()) {

                foreach ($bookings as $booking) {

                    $quote_count = BookingRequestCompany::where('booking_id', $booking->id)->where('booking_quote_status', 'quoted')->where('available_status', 'available')->where('company_id', $company->id)->count();

                    $un_quote_count = BookingRequestCompany::where('booking_id', $booking->id)->where('booking_quote_status', 'un-quoted')->where('available_status', 'available')->where('company_id', $company->id)->count();

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

                    // $details = BookingDetail::where('booking_id', $booking->id)->get();
                    // $quote_details = QuoteAgainstRequest::where('booking_id', $booking->id)->where('company_id', $company->id)->first();

                    // if ($details->isNotEmpty()) {
                    //     $booking->booking_details = $details;
                    // } else {
                    //     $booking->booking_details = [];
                    // }

                    // if ($quote_details) {
                    //     $booking->quote_details = $quote_details;
                    // } else {
                    //     $booking->quote_details = [];
                    // }


                }

                return response()->json([
                    'status' => true,
                    'message' => 'Quoted and un-quoted count against company',
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

    // public function qoutes_list(Request $request) // before driver issuse code
    // {
    //     $auth = Auth::user();

    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

    //         if ($auth->fixed_role_id == 2 || $request->company_id) {
    //             $validator = Validator::make($request->all(), [

    //                 'company_id' => 'required|exists:users,id',
    //                 'status' => 'required',

    //             ]);

    //             if ($validator->fails()) {
    //                 $errors = $validator->errors()->first(); // Get the first error message

    //                 $response = [
    //                     'success' => false,
    //                     'message' => $errors,
    //                 ];

    //                 return response()->json($response); // Return JSON response with HTTP 
    //             }


    //             // $company = Company::select('id')->where('user_id', $request->company_id)->first();

    //             $user = User::find($request->company_id);
    //             $company = Company::find($user->company_id);

    //             if (!$company) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Company not found',
    //                 ]);
    //             }
    //         }


    //         // $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
    //         //     ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
    //         //     ->select('customer_bookings.*', 'booking_request_companies.id as booking_req_id')
    //         //     ->where('booking_request_companies.booking_quote_status', $request->status)
    //         //     ->where('booking_request_companies.available_status', 'available')
    //         //     ->where('customer_bookings.booking_status', 'pending')
    //         //     ->orderBy('customer_bookings.created_at', 'desc')
    //         //     ->paginate(5);

    //         // $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
    //         //     ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
    //         //    ->select('customer_bookings.*', 'companies.company_name', 'booking_request_companies.id as booking_req_id');

    //         // $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
    //         //     ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
    //         //     ->select(
    //         //         'customer_bookings.id',
    //         //         'customer_bookings.user_id',
    //         //         'customer_bookings.company_id',
    //         //         'customer_bookings.driver_id',
    //         //         'customer_bookings.booking_from_lat',
    //         //         'customer_bookings.booking_from_long',
    //         //         'customer_bookings.booking_to_lat',
    //         //         'customer_bookings.booking_to_long',
    //         //         'customer_bookings.booking_from_loc_name',
    //         //         'customer_bookings.booking_to_loc_name',
    //         //         'customer_bookings.booking_date',
    //         //         'customer_bookings.booking_local_date',
    //         //         'customer_bookings.booking_time',
    //         //         'customer_bookings.booking_local_time',
    //         //         'customer_bookings.booking_desc',
    //         //         'customer_bookings.return_date',
    //         //         'customer_bookings.return_local_date',
    //         //         'customer_bookings.return_time',
    //         //         'customer_bookings.return_local_time',
    //         //         'customer_bookings.head_passenger_mobile',
    //         //         'customer_bookings.head_passenger_email',
    //         //         'customer_bookings.head_passenger_name',
    //         //         'customer_bookings.total_passenger',
    //         //         'customer_bookings.promo_code',
    //         //         'customer_bookings.car_type_id',
    //         //         'customer_bookings.confirm_via_email',
    //         //         'customer_bookings.confirm_via_sms',
    //         //         'customer_bookings.total_distance',
    //         //         'customer_bookings.totalDuration',
    //         //         'customer_bookings.booking_price',
    //         //         'customer_bookings.deduction_price',
    //         //         'customer_bookings.tracking_number',
    //         //         'customer_bookings.meet_n_greet',
    //         //         'customer_bookings.booking_status',
    //         //         'customer_bookings.booking_return_status',
    //         //         'customer_bookings.active_status',
    //         //         'customer_bookings.admin_status',
    //         //         'customer_bookings.is_deleted',
    //         //         'customer_bookings.created_at',
    //         //         // 'customer_bookings.updated_at',
    //         //         'booking_request_companies.updated_at',
    //         //         DB::raw('GROUP_CONCAT(companies.company_name SEPARATOR ", ") as company_names'),
    //         //         DB::raw('MIN(booking_request_companies.id) as booking_req_id')
    //         //     ); // or MAX, depending on your needs)


    //         // // Apply condition for role_id == 2
    //         // if ($auth->role_id == 2 || $request->company_id) {
    //         //     $bookings->where('booking_request_companies.company_id', $company->id);

    //         //     // if ($request->status == 'job_offer') {

    //         //     //     // $bookings->where('customer_bookings.admin_status', 'manual');

    //         //     // }
    //         // }

    //         // $bookings = $bookings->where('booking_request_companies.booking_quote_status', $request->status)
    //         //     ->where('booking_request_companies.available_status', 'available')
    //         //     ->where('customer_bookings.booking_status', 'pending')
    //         //     ->groupBy(
    //         //         'customer_bookings.id',
    //         //         'customer_bookings.user_id',
    //         //         'customer_bookings.company_id',
    //         //         'customer_bookings.driver_id',
    //         //         'customer_bookings.booking_from_lat',
    //         //         'customer_bookings.booking_from_long',
    //         //         'customer_bookings.booking_to_lat',
    //         //         'customer_bookings.booking_to_long',
    //         //         'customer_bookings.booking_from_loc_name',
    //         //         'customer_bookings.booking_to_loc_name',
    //         //         'customer_bookings.booking_date',
    //         //         'customer_bookings.booking_local_date',
    //         //         'customer_bookings.booking_time',
    //         //         'customer_bookings.booking_local_time',
    //         //         'customer_bookings.booking_desc',
    //         //         'customer_bookings.return_date',
    //         //         'customer_bookings.return_local_date',
    //         //         'customer_bookings.return_time',
    //         //         'customer_bookings.return_local_time',
    //         //         'customer_bookings.head_passenger_mobile',
    //         //         'customer_bookings.head_passenger_email',
    //         //         'customer_bookings.head_passenger_name',
    //         //         'customer_bookings.total_passenger',
    //         //         'customer_bookings.promo_code',
    //         //         'customer_bookings.car_type_id',
    //         //         'customer_bookings.confirm_via_email',
    //         //         'customer_bookings.confirm_via_sms',
    //         //         'customer_bookings.total_distance',
    //         //         'customer_bookings.totalDuration',
    //         //         'customer_bookings.booking_price',
    //         //         'customer_bookings.deduction_price',
    //         //         'customer_bookings.tracking_number',
    //         //         'customer_bookings.meet_n_greet',
    //         //         'customer_bookings.booking_status',
    //         //         'customer_bookings.booking_return_status',
    //         //         'customer_bookings.active_status',
    //         //         'customer_bookings.admin_status',
    //         //         'customer_bookings.is_deleted',
    //         //         'customer_bookings.created_at',
    //         //         // 'customer_bookings.updated_at',
    //         //         'booking_request_companies.updated_at'
    //         //     )
    //         //     ->orderBy('booking_request_companies.updated_at', 'desc')
    //         //     ->paginate(5);

    //         if ($request->status == 'dispatch_booking') {

    //             $bookings = CustomerBooking::where('admin_status', 'manual')->orderBy('created_at', 'desc')->paginate(6);

    //             if ($bookings->isNotEmpty()) {

    //                 foreach ($bookings as $booking) {

    //                     $details = BookingDetail::where('booking_id', $booking->id)->get();

    //                     if ($details->isNotEmpty()) {
    //                         $booking->booking_details = $details;
    //                     } else {
    //                         $booking->booking_details = [];
    //                     }
    //                 }

    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Manual booking list',
    //                     'response' => $bookings
    //                 ]);
    //             } else {

    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Manual booking list not found',
    //                 ]);
    //             }
    //         } else {


    //             $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
    //                 ->join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
    //                 ->select(
    //                     'customer_bookings.id',
    //                     'customer_bookings.user_id',
    //                     'customer_bookings.company_id',
    //                     'customer_bookings.driver_id',
    //                     'customer_bookings.booking_from_lat',
    //                     'customer_bookings.booking_from_long',
    //                     'customer_bookings.booking_to_lat',
    //                     'customer_bookings.booking_to_long',
    //                     'customer_bookings.booking_from_loc_name',
    //                     'customer_bookings.booking_to_loc_name',
    //                     'customer_bookings.booking_date',
    //                     'customer_bookings.booking_local_date',
    //                     'customer_bookings.booking_time',
    //                     'customer_bookings.booking_local_time',
    //                     'customer_bookings.booking_desc',
    //                     'customer_bookings.return_date',
    //                     'customer_bookings.return_local_date',
    //                     'customer_bookings.return_time',
    //                     'customer_bookings.return_local_time',
    //                     'customer_bookings.head_passenger_mobile',
    //                     'customer_bookings.head_passenger_email',
    //                     'customer_bookings.head_passenger_name',
    //                     'customer_bookings.total_passenger',
    //                     'customer_bookings.promo_code',
    //                     'customer_bookings.car_type_id',
    //                     'customer_bookings.confirm_via_email',
    //                     'customer_bookings.confirm_via_sms',
    //                     'customer_bookings.total_distance',
    //                     'customer_bookings.totalDuration',
    //                     'customer_bookings.booking_price',
    //                     'customer_bookings.deduction_price',
    //                     'customer_bookings.tracking_number',
    //                     'customer_bookings.meet_n_greet',
    //                     'customer_bookings.booking_status',
    //                     'customer_bookings.booking_return_status',
    //                     'customer_bookings.active_status',
    //                     'customer_bookings.admin_status',
    //                     'customer_bookings.is_deleted',
    //                     'customer_bookings.created_at',
    //                     DB::raw('GROUP_CONCAT(DISTINCT companies.company_name SEPARATOR ", ") as company_names'),
    //                     DB::raw('MIN(booking_request_companies.id) as booking_req_id')
    //                 );

    //             // Apply condition for role_id == 2 (operator) or company filter
    //             if ($auth->fixed_role_id == 2 || $request->company_id) {
    //                 $bookings->where('booking_request_companies.company_id', $company->id);

    //                 // $current_date = Carbon::now()->toDateString();  // Gets the current date
    //                 // $current_time = Carbon::now()->format('H:i');   // Gets the current time in 24-hour format

    //                 // // $bookings ->where('customer_bookings.booking_date', '>=', $current_date)->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') > ?", [$current_time]);

    //                 // $bookings->where(function ($query) use ($current_date, $current_time) {
    //                 //     // For bookings before the current date
    //                 //     $query->where('customer_bookings.booking_date', '>', $current_date);

    //                 //     // For bookings on the same date but before the current time
    //                 //     $query->orWhere(function ($query) use ($current_date, $current_time) {
    //                 //         $query->where('customer_bookings.booking_date', '=',  $current_date)
    //                 //             ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') < ?", [$current_time]);
    //                 //     });
    //                 // });

    //                 $current_date = Carbon::now()->toDateString();  // Gets the current date
    //                 $current_time = Carbon::now()->format('h:iA');  // 12-hour format with AM/PM

    //                 $bookings->where(function ($query) use ($current_date, $current_time) {
    //                     // For bookings after the current date
    //                     $query->where('customer_bookings.booking_date', '>', $current_date);

    //                     // For bookings on the same date but before the current time
    //                     $query->orWhere(function ($query) use ($current_date, $current_time) {
    //                         $query->where('customer_bookings.booking_date', '=', $current_date)
    //                             ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') >= STR_TO_DATE(?, '%h:%i%p')", [$current_time]);
    //                     });
    //                 });
    //             }

    //             // Admin case: Check for un-quoted status
    //             if ($auth->fixed_role_id != 2) {
    //                 if ($request->status == 'un-quoted') {
    //                     // Ensure all related booking_request_companies have booking_quote_status as 'un-quoted'
    //                     $bookings->whereNotIn('customer_bookings.id', function ($query) {
    //                         $query->select('booking_id')
    //                             ->from('booking_request_companies')
    //                             ->where('booking_quote_status', '<>', 'un-quoted');
    //                     });
    //                 } else {
    //                     // For other statuses, apply the usual filter
    //                     $bookings->where('booking_request_companies.booking_quote_status', $request->status);
    //                 }
    //             } else {
    //                 // Operator case, apply the requested status directly
    //                 $bookings->where('booking_request_companies.booking_quote_status', $request->status);
    //             }

    //             // Add other filters and grouping
    //             $bookings = $bookings->where('booking_request_companies.available_status', 'available')
    //                 ->where('customer_bookings.booking_status', 'pending')
    //                 ->groupBy(
    //                     'customer_bookings.id',
    //                     'customer_bookings.user_id',
    //                     'customer_bookings.company_id',
    //                     'customer_bookings.driver_id',
    //                     'customer_bookings.booking_from_lat',
    //                     'customer_bookings.booking_from_long',
    //                     'customer_bookings.booking_to_lat',
    //                     'customer_bookings.booking_to_long',
    //                     'customer_bookings.booking_from_loc_name',
    //                     'customer_bookings.booking_to_loc_name',
    //                     'customer_bookings.booking_date',
    //                     'customer_bookings.booking_local_date',
    //                     'customer_bookings.booking_time',
    //                     'customer_bookings.booking_local_time',
    //                     'customer_bookings.booking_desc',
    //                     'customer_bookings.return_date',
    //                     'customer_bookings.return_local_date',
    //                     'customer_bookings.return_time',
    //                     'customer_bookings.return_local_time',
    //                     'customer_bookings.head_passenger_mobile',
    //                     'customer_bookings.head_passenger_email',
    //                     'customer_bookings.head_passenger_name',
    //                     'customer_bookings.total_passenger',
    //                     'customer_bookings.promo_code',
    //                     'customer_bookings.car_type_id',
    //                     'customer_bookings.confirm_via_email',
    //                     'customer_bookings.confirm_via_sms',
    //                     'customer_bookings.total_distance',
    //                     'customer_bookings.totalDuration',
    //                     'customer_bookings.booking_price',
    //                     'customer_bookings.deduction_price',
    //                     'customer_bookings.tracking_number',
    //                     'customer_bookings.meet_n_greet',
    //                     'customer_bookings.booking_status',
    //                     'customer_bookings.booking_return_status',
    //                     'customer_bookings.active_status',
    //                     'customer_bookings.admin_status',
    //                     'customer_bookings.is_deleted',
    //                     'customer_bookings.created_at'
    //                 )
    //                 ->orderBy(DB::raw('MIN(booking_request_companies.updated_at)'), 'desc') // Use MIN for ordering
    //                 ->paginate(5);


    //             if ($bookings->isNotEmpty()) {

    //                 foreach ($bookings as $booking) {

    //                     $details = BookingDetail::where('booking_id', $booking->id)->get();
    //                     $user_payments = UserPayment::where('booking_id', $booking->id)->first();
    //                     $cartype = FleetType::find($booking->car_type_id);
    //                     if ($auth->fixed_role_id == 2) {
    //                         $quote_details = QuoteAgainstRequest::where('booking_id', $booking->id)->where('company_id', $company->id)->first();
    //                     } else {

    //                         $quote_details = QuoteAgainstRequest::join('companies', 'companies.id', '=', 'quote_against_requests.company_id')
    //                             ->select('quote_against_requests.*', 'companies.company_name')
    //                             ->where('booking_id', $booking->id)->paginate(5);


    //                         if ($quote_details->isNotEmpty()) {
    //                             $quote_count = QuoteAgainstRequest::where('booking_id', $booking->id)->count();
    //                             $booking->quote_count = $quote_count;
    //                         }

    //                         $booking_request_details = BookingRequestCompany::join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
    //                             ->select('companies.*', 'booking_request_companies.id as booking_request_id')
    //                             ->where('booking_id', $booking->id)->paginate(5);

    //                         if ($booking_request_details->isNotEmpty()) {
    //                             $request_count = BookingRequestCompany::where('booking_id', $booking->id)->count();
    //                             $booking->booking_request_count = $request_count;
    //                         }

    //                         if ($booking_request_details) {
    //                             $booking->booking_request_details = $booking_request_details;
    //                         } else {
    //                             $booking->booking_request_details = [];
    //                         }
    //                     }

    //                     if ($details->isNotEmpty()) {
    //                         $booking->booking_details = $details;
    //                     } else {
    //                         $booking->booking_details = [];
    //                     }

    //                     if ($quote_details) {
    //                         $booking->quote_details = $quote_details;
    //                     } else {
    //                         $booking->quote_details = [];
    //                     }

    //                     if ($cartype) {

    //                         $booking->fleet_details = $cartype;
    //                     } else {

    //                         $booking->fleet_details = [];
    //                     }

    //                     $booking->payment_type = $user_payments->payment_type;
    //                 }

    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => $request->status == 'un-quoted'
    //                         ? 'Un-quoted booking list'
    //                         : ($request->status == 'job-offer'
    //                             ? 'Job offer booking list'
    //                             : ($request->status == 'change_request'
    //                                 ? 'Change Request List'
    //                                 : 'Quoted booking list')),
    //                     'response' => $bookings
    //                 ]);
    //             } else {

    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => $request->status == 'un-quoted'
    //                         ? 'Un-quoted booking list not found'
    //                         : ($request->status == 'job-offer'
    //                             ? 'Job offer booking list not found'
    //                             : ($request->status == 'change_request'
    //                                 ? 'Change Request List not found'
    //                                 : 'Quoted booking list not found')),
    //                 ]);
    //             }
    //         }
    //     } else {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You have not permission to see booking quotes',
    //         ]);
    //     }
    // }

    public function qoutes_list(Request $request)
    {
        $auth = Auth::user();

        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id == 2 || $request->company_id) {
                $validator = Validator::make($request->all(), [

                    'company_id' => 'required|exists:users,id',
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


                // $company = Company::select('id')->where('user_id', $request->company_id)->first();

                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);

                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ]);
                }
            }

            if ($request->status == 'dispatch_booking') {

                $bookings = CustomerBooking::where('admin_status', 'manual')->orderBy('created_at', 'desc')->paginate(6);

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


                $bookings = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                    ->leftJoin('companies', function ($join) {
                        $join->on('companies.id', '=', 'booking_request_companies.company_id')
                            ->whereNotNull('booking_request_companies.company_id');
                    })
                    ->leftJoin('drivers', function ($join) {
                        $join->on('drivers.id', '=', 'booking_request_companies.driver_id')
                            ->whereNotNull('booking_request_companies.driver_id');
                    })
                    ->select(
                        'customer_bookings.id',
                        'customer_bookings.user_id',
                        'customer_bookings.company_id',
                        'customer_bookings.driver_id',
                        'customer_bookings.booking_from_lat',
                        'customer_bookings.booking_from_long',
                        'customer_bookings.booking_to_lat',
                        'customer_bookings.booking_to_long',
                        'customer_bookings.booking_from_loc_name',
                        'customer_bookings.booking_to_loc_name',
                        'customer_bookings.booking_date',
                        'customer_bookings.booking_local_date',
                        'customer_bookings.booking_time',
                        'customer_bookings.booking_local_time',
                        'customer_bookings.booking_desc',
                        'customer_bookings.return_date',
                        'customer_bookings.return_local_date',
                        'customer_bookings.return_time',
                        'customer_bookings.return_local_time',
                        'customer_bookings.head_passenger_mobile',
                        'customer_bookings.head_passenger_email',
                        'customer_bookings.head_passenger_name',
                        'customer_bookings.total_passenger',
                        'customer_bookings.promo_code',
                        'customer_bookings.car_type_id',
                        'customer_bookings.confirm_via_email',
                        'customer_bookings.confirm_via_sms',
                        'customer_bookings.total_distance',
                        'customer_bookings.totalDuration',
                        'customer_bookings.booking_price',
                        'customer_bookings.deduction_price',
                        'customer_bookings.tracking_number',
                        'customer_bookings.meet_n_greet',
                        'customer_bookings.booking_status',
                        'customer_bookings.booking_return_status',
                        'customer_bookings.active_status',
                        'customer_bookings.admin_status',
                        'customer_bookings.is_deleted',
                        'customer_bookings.created_at',
                        DB::raw('GROUP_CONCAT(DISTINCT companies.company_name SEPARATOR ", ") as company_names'),
                        DB::raw('GROUP_CONCAT(DISTINCT drivers.first_name SEPARATOR ", ") as driver_names'),
                        DB::raw('MIN(booking_request_companies.id) as booking_req_id')
                    );

                // Apply condition for role_id == 2 (operator) or company filter
                if ($auth->fixed_role_id == 2 || $request->company_id) {
                    $bookings->where('booking_request_companies.company_id', $company->id);


                    $current_date = Carbon::now()->toDateString();  // Gets the current date
                    $current_time = Carbon::now()->format('h:iA');  // 12-hour format with AM/PM

                    $bookings->where(function ($query) use ($current_date, $current_time) {
                        // For bookings after the current date
                        $query->where('customer_bookings.booking_date', '>', $current_date);

                        // For bookings on the same date but before the current time
                        $query->orWhere(function ($query) use ($current_date, $current_time) {
                            $query->where('customer_bookings.booking_date', '=', $current_date)
                                ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') >= STR_TO_DATE(?, '%h:%i%p')", [$current_time]);
                        });
                    });
                }

                // Admin case: Check for un-quoted status
                if ($auth->fixed_role_id != 2) {
                    if ($request->status == 'un-quoted') {
                        // Ensure all related booking_request_companies have booking_quote_status as 'un-quoted'
                        $bookings->whereNotIn('customer_bookings.id', function ($query) {
                            $query->select('booking_id')
                                ->from('booking_request_companies')
                                ->where('booking_quote_status', '<>', 'un-quoted');
                        });
                    } else {
                        // For other statuses, apply the usual filter
                        $bookings->where('booking_request_companies.booking_quote_status', $request->status);
                    }
                } else {
                    // Operator case, apply the requested status directly
                    $bookings->where('booking_request_companies.booking_quote_status', $request->status);
                }

                // Add other filters and grouping
                $bookings = $bookings->where('booking_request_companies.available_status', 'available')
                    ->where('customer_bookings.booking_status', 'pending')
                    ->groupBy(
                        'customer_bookings.id',
                        'customer_bookings.user_id',
                        'customer_bookings.company_id',
                        'customer_bookings.driver_id',
                        'customer_bookings.booking_from_lat',
                        'customer_bookings.booking_from_long',
                        'customer_bookings.booking_to_lat',
                        'customer_bookings.booking_to_long',
                        'customer_bookings.booking_from_loc_name',
                        'customer_bookings.booking_to_loc_name',
                        'customer_bookings.booking_date',
                        'customer_bookings.booking_local_date',
                        'customer_bookings.booking_time',
                        'customer_bookings.booking_local_time',
                        'customer_bookings.booking_desc',
                        'customer_bookings.return_date',
                        'customer_bookings.return_local_date',
                        'customer_bookings.return_time',
                        'customer_bookings.return_local_time',
                        'customer_bookings.head_passenger_mobile',
                        'customer_bookings.head_passenger_email',
                        'customer_bookings.head_passenger_name',
                        'customer_bookings.total_passenger',
                        'customer_bookings.promo_code',
                        'customer_bookings.car_type_id',
                        'customer_bookings.confirm_via_email',
                        'customer_bookings.confirm_via_sms',
                        'customer_bookings.total_distance',
                        'customer_bookings.totalDuration',
                        'customer_bookings.booking_price',
                        'customer_bookings.deduction_price',
                        'customer_bookings.tracking_number',
                        'customer_bookings.meet_n_greet',
                        'customer_bookings.booking_status',
                        'customer_bookings.booking_return_status',
                        'customer_bookings.active_status',
                        'customer_bookings.admin_status',
                        'customer_bookings.is_deleted',
                        'customer_bookings.created_at'
                    )
                    ->orderBy(DB::raw('MIN(booking_request_companies.updated_at)'), 'desc') // Use MIN for ordering
                    ->paginate(5);


                if ($bookings->isNotEmpty()) {

                    foreach ($bookings as $booking) {

                        $details = BookingDetail::where('booking_id', $booking->id)->get();
                        $user_payments = UserPayment::where('booking_id', $booking->id)->first();
                        $cartype = FleetType::find($booking->car_type_id);
                        if ($auth->fixed_role_id == 2) {
                            $quote_details = QuoteAgainstRequest::where('booking_id', $booking->id)->where('company_id', $company->id)->first();
                        } else {

                            $quote_details = QuoteAgainstRequest::leftJoin('companies', function ($join) {
                                $join->on('companies.id', '=', 'quote_against_requests.company_id')
                                    ->whereNotNull('quote_against_requests.company_id');
                            })
                                ->leftJoin('drivers', function ($join) {
                                    $join->on('drivers.id', '=', 'quote_against_requests.driver_id')
                                        ->whereNotNull('quote_against_requests.driver_id');
                                })
                                ->select(
                                    'quote_against_requests.*',
                                    'companies.company_name',
                                    'drivers.first_name'
                                )
                                ->where('booking_id', $booking->id)
                                ->paginate(5);


                            if ($quote_details->isNotEmpty()) {
                                $quote_count = QuoteAgainstRequest::where('booking_id', $booking->id)->count();
                                $booking->quote_count = $quote_count;
                            }

                            $booking_request_details = BookingRequestCompany::join('companies', 'companies.id', '=', 'booking_request_companies.company_id')
                                // ->join('drivers', 'drivers.id', '=', 'booking_request_companies.driver_id')
                                ->select('companies.*', 'booking_request_companies.id as booking_request_id')
                                ->where('booking_id', $booking->id)->paginate(5);

                            if ($booking_request_details->isNotEmpty()) {
                                $request_count = BookingRequestCompany::where('booking_id', $booking->id)->count();
                                $booking->booking_request_count = $request_count;
                            }

                            if ($booking_request_details) {
                                $booking->booking_request_details = $booking_request_details;
                            } else {
                                $booking->booking_request_details = [];
                            }
                        }

                        if ($details->isNotEmpty()) {
                            $booking->booking_details = $details;
                        } else {
                            $booking->booking_details = [];
                        }

                        if ($quote_details) {
                            $booking->quote_details = $quote_details;
                        } else {
                            $booking->quote_details = [];
                        }

                        if ($cartype) {

                            $booking->fleet_details = $cartype;
                        } else {

                            $booking->fleet_details = [];
                        }

                        $booking->payment_type = $user_payments->payment_type;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => $request->status == 'un-quoted'
                            ? 'Un-quoted booking list'
                            : ($request->status == 'job-offer'
                                ? 'Job offer booking list'
                                : ($request->status == 'change_request'
                                    ? 'Change Request List'
                                    : 'Quoted booking list')),
                        'response' => $bookings
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => $request->status == 'un-quoted'
                            ? 'Un-quoted booking list not found'
                            : ($request->status == 'job-offer'
                                ? 'Job offer booking list not found'
                                : ($request->status == 'change_request'
                                    ? 'Change Request List not found'
                                    : 'Quoted booking list not found')),
                    ]);
                }
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to see booking quotes',
            ]);
        }
    }

    public function quotes_against_booking(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'booking_id' => 'required|exists:customer_bookings,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $quote_details = QuoteAgainstRequest::leftjoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
            ->leftjoin('drivers', 'drivers.id', '=', 'quote_against_requests.driver_id')
            ->select('quote_against_requests.*', 'companies.company_name', 'drivers.first_name')
            ->where('quote_against_requests.booking_id', $request->booking_id)->paginate(5);


        return response()->json([
            'status' => true,
            'message' => 'Quotes against booking!',
            'quotes' => $quote_details,

        ]);
    }

    public function company_request_against_booking(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'booking_id' => 'required|exists:customer_bookings,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $quote_details = BookingRequestCompany::leftjoin('companies', 'companies.id', '=', 'booking_request_companies.company_id')
            ->select('booking_request_companies.*', 'companies.company_name',  'companies.company_email', 'companies.company_reg_num')
            ->whereNull('booking_request_companies.driver_id')
            ->where('booking_request_companies.booking_id', $request->booking_id)
            ->where('booking_request_companies.available_status', 'available')->paginate(5);


        return response()->json([
            'status' => true,
            'message' => 'Company requests against booking!',
            'quotes' => $quote_details,

        ]);
    }

    public function driver_request_against_booking(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'booking_id' => 'required|exists:customer_bookings,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $quote_details = BookingRequestCompany::leftjoin('drivers', 'drivers.id', '=', 'booking_request_companies.driver_id')
            ->select('booking_request_companies.*', 'drivers.first_name', 'drivers.driver_email', 'drivers.phone')
            ->whereNull('booking_request_companies.company_id')
            ->where('booking_request_companies.booking_id', $request->booking_id)
            ->where('booking_request_companies.available_status', 'available')->paginate(5);


        return response()->json([
            'status' => true,
            'message' => 'Driver requests against booking!',
            'quotes' => $quote_details,

        ]);
    }

    public function  company_change_request_against_booking(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'booking_id' => 'required|exists:customer_bookings,id',
            'company_id' => 'required|exists:companies,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $quote_request_booking = QuoteAgainstRequest::leftJoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
            ->select('quote_against_requests.*', 'companies.company_name')
            ->where('quote_against_requests.booking_id', $request->booking_id)
            ->where('quote_against_requests.company_id', $request->company_id)
            ->first();

        $change_request = ChangeRequest::leftJoin('companies', 'companies.id', '=', 'change_requests.company_id')
            ->select('change_requests.*', 'companies.company_name')
            ->where('change_requests.quote_id', $quote_request_booking->id)
            ->orderBy('change_requests.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Change request list against quote!',
            'quotes' => $change_request,

        ]);
    }

    public function  cancel_change_request_against_booking(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'quote_id' => 'required|exists:quote_against_requests,id',

        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $quote = QuoteAgainstRequest::find($request->quote_id);
        $booking = CustomerBooking::find($quote->booking_id);
        $booking->company_id = $quote->company_id;
        $booking->booking_status = 'accepted';
        $booking->save();


        $change_request = ChangeRequest::where('quote_id', $request->quote_id)
            ->latest('created_at') // Shortcut for orderBy('created_at', 'desc')
            ->first();

        if ($change_request) {
            $change_request->delete();
        }



        return response()->json([
            'status' => true,
            'message' => 'You have canceled change request against quote successfully!',

        ]);
    }



    public function company_bookings(Request $request)
    {

        $auth = Auth::user();


        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id != 1) {
                // Validate the incoming request
                $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
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

                // $company = Company::select('id')->where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);

                if (!$company) {

                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found against ID',
                    ]);
                }
            }


            if ($request->status == 'active') {

                $current_date = Carbon::now()->toDateString();  // Gets the current date
                $current_time = Carbon::now()->format('h:iA');  // 12-hour format with AM/PM

                $query = CustomerBooking::where('active_status', 1)
                    ->where('booking_status', 'accepted')
                    ->where('booking_date', '=', $current_date)
                    // ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') > ?", [$current_time])
                    ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') >= STR_TO_DATE(?, '%h:%i%p')", [$current_time])
                    ->orderBy('created_at', 'desc');


                // Apply role-based conditions dynamically
                if ($auth->fixed_role_id != 1) {
                    $query->where('company_id', $company->id);
                }

                // Execute the query with pagination
                $bookings = $query->paginate(5);


                // $bookings = CustomerBooking::where('company_id', $company->id)->where('active_status', 1)->where('booking_status', 'accepted')->orderBy('created_at', 'desc')->paginate(5);

                if ($bookings->isNotEmpty()) {


                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();
                        $cartype = FleetType::find($value->car_type_id);

                        if ($auth->fixed_role_id != 1) {
                            $quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        } else {

                            $quote_details = QuoteAgainstRequest::leftjoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
                                ->leftjoin('drivers', 'drivers.id', '=', 'quote_against_requests.driver_id')
                                ->select('quote_against_requests.*', 'companies.company_name', 'drivers.first_name')
                                ->where('quote_against_requests.booking_id', $value->id)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        }


                        $ratings = FeedBack::where('booking_id', $value->id)->first();




                        if ($ratings) {
                            $value->ratings = $ratings;
                        } else {
                            $value->ratings = [];
                        }

                        if ($booking_via) {

                            $value->booking_details = $booking_via;
                        } else {

                            $value->booking_details = [];
                        }

                        if ($cartype) {

                            $value->fleet_details = $cartype;
                        } else {

                            $value->fleet_details = [];
                        }

                        if ($quote_details) {
                            $value->quote_details = $quote_details;
                        } else {
                            $value->quote_details = [];
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

                // Start building the query
                $query = CustomerBooking::where('active_status', 1)
                    ->where('booking_status', 'accepted')
                    ->where('booking_date', '>', $current_date)
                    ->orderBy('created_at', 'desc');

                // Apply role-based conditions dynamically
                if ($auth->fixed_role_id != 1) {
                    $query->where('company_id', $company->id);
                }

                // Execute the query with pagination
                $bookings = $query->paginate(5);



                if ($bookings->isNotEmpty()) {

                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();
                        $cartype = FleetType::find($value->car_type_id);

                        if ($auth->fixed_role_id != 1) {
                            $quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        } else {

                            $quote_details = QuoteAgainstRequest::leftjoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
                                ->leftjoin('drivers', 'drivers.id', '=', 'quote_against_requests.driver_id')
                                ->select('quote_against_requests.*', 'companies.company_name', 'drivers.first_name')
                                ->where('quote_against_requests.booking_id', $value->id)->where('quote_against_requests.status', 1)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        }

                        $ratings = FeedBack::where('booking_id', $value->id)->first();



                        if ($ratings) {
                            $value->ratings = $ratings;
                        } else {
                            $value->ratings = [];
                        }

                        if ($booking_via) {
                            $value->booking_details = $booking_via;
                        } else {
                            $value->booking_details = [];
                        }

                        if ($cartype) {
                            $value->fleet_details = $cartype;
                        } else {
                            $value->fleet_details = [];
                        }

                        if ($quote_details) {
                            $value->quote_details = $quote_details;
                        } else {
                            $value->quote_details = [];
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
                // $current_time = Carbon::now()->format('H:i');   // Gets the current time in 24-hour 
                $current_time = Carbon::now()->format('h:iA');  // 12-hour format with AM/PM format

                $query = CustomerBooking::where('active_status', 1)
                    ->where('booking_status', 'accepted')
                    ->where(function ($query) use ($current_date, $current_time) {
                        // For bookings before the current date
                        $query->where('booking_date', '<', $current_date);

                        // For bookings on the same date but before the current time
                        // $query->orWhere(function ($query) use ($current_date, $current_time) {
                        //     $query->where('booking_date', '=',  $current_date)
                        //         ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') < ?", [$current_time]);
                        // });
                        $query->orWhere(function ($query) use ($current_date, $current_time) {
                            $query->where('booking_date', '=', $current_date)
                                ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') < STR_TO_DATE(?, '%h:%i%p')", [$current_time]);
                        });
                    })
                    ->orderBy('created_at', 'desc');

                // $query = CustomerBooking::where('active_status', 1)
                // ->where('booking_status', 'accepted')
                // ->where('booking_date', '=', $current_date)
                // ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') > ?", [$current_time])
                // ->orderBy('created_at', 'desc');


                // Apply role-based conditions dynamically
                if ($auth->fixed_role_id != 1) {
                    $query->where('company_id', $company->id);
                }

                // Execute the query with pagination
                $bookings = $query->paginate(5);

                if ($bookings->isNotEmpty()) {

                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();
                        $cartype = FleetType::find($value->car_type_id);

                        if ($auth->fixed_role_id != 1) {
                            $quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        } else {

                            $quote_details = QuoteAgainstRequest::leftjoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
                                ->leftjoin('drivers', 'drivers.id', '=', 'quote_against_requests.driver_id')
                                ->select('quote_against_requests.*', 'companies.company_name', 'drivers.first_name')
                                ->where('quote_against_requests.booking_id', $value->id)->where('quote_against_requests.status', 1)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        }

                        $ratings = FeedBack::where('booking_id', $value->id)->first();



                        if ($ratings) {
                            $value->ratings = $ratings;
                        } else {
                            $value->ratings = [];
                        }

                        if ($booking_via) {
                            $value->booking_details = $booking_via;
                        } else {
                            $value->booking_details = [];
                        }

                        if ($cartype) {
                            $value->fleet_details = $cartype;
                        } else {
                            $value->fleet_details = [];
                        }

                        if ($quote_details) {
                            $value->quote_details = $quote_details;
                        } else {
                            $value->quote_details = [];
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
            } else if ($request->status == 'change_request') {

                $query = CustomerBooking::where('active_status', 1)->where('booking_status', 'change_request')->orderBy('created_at', 'desc');

                // Apply role-based conditions dynamically
                if ($auth->fixed_role_id != 1) {
                    $query->where('company_id', $company->id);
                }

                // Execute the query with pagination
                $bookings = $query->paginate(5);


                if ($bookings->isNotEmpty()) {


                    foreach ($bookings as $value) {

                        $booking_via = BookingDetail::where('booking_id', $value->id)->get();
                        $cartype = FleetType::find($value->car_type_id);

                        if ($auth->fixed_role_id != 1) {
                            $quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        } else {

                            $quote_details = QuoteAgainstRequest::leftjoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
                                ->leftjoin('drivers', 'drivers.id', '=', 'quote_against_requests.driver_id')
                                ->select('quote_against_requests.*', 'companies.company_name', 'drivers.first_name as driver_name')
                                ->where('quote_against_requests.booking_id', $value->id)->where('quote_against_requests.status', 0)->first();

                            $payment = UserPayment::where('booking_id', $value->id)->first();

                            if ($payment) {
                                $value->payment = $payment->payment_type;
                            } else {
                                $value->payment = [];
                            }
                        }

                        $ratings = FeedBack::where('booking_id', $value->id)->first();

                        if ($ratings) {
                            $value->ratings = $ratings;
                        } else {
                            $value->ratings = [];
                        }

                        if ($booking_via) {

                            $value->booking_details = $booking_via;
                        } else {

                            $value->booking_details = [];
                        }

                        if ($cartype) {

                            $value->fleet_details = $cartype;
                        } else {

                            $value->fleet_details = [];
                        }

                        if ($quote_details) {
                            $value->quote_details = $quote_details;
                        } else {
                            $value->quote_details = [];
                        }
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Change Request booking list!',
                        'response' => $bookings,
                    ]);
                } else {

                    return response()->json([
                        'status' => false,
                        'message' => 'Change Request booking not found!',
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

    public function advance_search_booking(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id != 1) {

                // Validate the incoming request
                $validator = Validator::make($request->all(), [
                    'company_id' => 'required|exists:users,id',
                    'tracking_number' => 'nullable',
                    'booking_date' => 'nullable|date',
                    'car_type_id' => 'nullable',

                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors()->first(); // Get the first error message

                    return response()->json([
                        'success' => false,
                        'message' => $errors,
                    ]);
                }

                // $company = Company::select('id')->where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
            }

            // Get search parameters from the request
            $trackingNumber = $request->input('tracking_number');
            $bookingDate = $request->input('booking_date');
            $cartype = $request->input('car_type_id');
            $user_search = $request->input('user_search');
            $company_search = $request->input('company_search');
            $driver_search = $request->input('driver_search');


            // Build the query
            $query = CustomerBooking::query();

            if ($auth->fixed_role_id != 1) {

                $query->where('company_id', $company->id);
            }


            // Apply filters if provided

            if ($user_search) {
                $query->where('user_id', $user_search);
            }
            if ($company_search) {
                $query->where('company_id', $company_search);
            }
            if ($driver_search) {
                $query->where('driver_id', $driver_search);
            }
            if ($trackingNumber) {
                // $query->where('tracking_number', $trackingNumber);
                $query->where('tracking_number', 'like', '%' . $trackingNumber . '%');
            }
            if ($bookingDate) {
                $query->whereDate('booking_local_date', $bookingDate);
            }
            if ($cartype) {
                $query->where('car_type_id', $cartype);
            }

            if ($request->status == 'active') {

                $current_date = Carbon::now()->toDateString();  // Gets the current date
                $current_time = Carbon::now()->toTimeString();  // Gets the current time

                $query->where('active_status', 1)->where('booking_status', 'accepted')->where('booking_date', '=', $current_date)
                    ->where('booking_time', '>', $current_time);
            } else if ($request->status == 'future') {

                $current_date = Carbon::now()->toDateString();

                $query->where('active_status', 1)->where('booking_status', 'accepted')->where('booking_date', '>', $current_date);
            } else if ($request->status == 'past') {
                $current_date = Carbon::now()->toDateString();
                $current_time = Carbon::now()->format('H:iA');   // Gets the current time in 12-hour format

                $query->where('active_status', 1)->where('booking_status', 'accepted')->where(function ($query) use ($current_date, $current_time) {
                    // For bookings before the current date
                    $query->where('booking_date', '<', $current_date);

                    // For bookings on the same date but before the current time
                    // $query->orWhere(function ($query) use ($current_date, $current_time) {
                    //     $query->where('booking_date', '=',  $current_date)
                    //         ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') < ?", [$current_time]);
                    // });

                    $query->orWhere(function ($query) use ($current_date, $current_time) {
                        $query->where('booking_date', '=', $current_date)
                            ->whereRaw("STR_TO_DATE(booking_time, '%h:%i%p') < STR_TO_DATE(?, '%h:%i%p')", [$current_time]);
                    });
                });
            } else if ($request->status == 'change_request') {
                $current_date = Carbon::now()->toDateString();

                $query->where('active_status', 1)->where('booking_status', 'change_request');
            }


            // Execute the query and get results
            $results = $query->orderBy('created_at', 'desc')->paginate(5);


            if ($results->isNotEmpty()) {

                foreach ($results as $value) {

                    $booking_via = BookingDetail::where('booking_id', $value->id)->get();
                    $cartype = FleetType::find($value->car_type_id);

                    if ($auth->fixed_role_id != 1) {
                        $quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first();

                        $payment = UserPayment::where('booking_id', $value->id)->first();

                        if ($payment) {
                            $value->payment = $payment->payment_type;
                        } else {
                            $value->payment = [];
                        }
                    } else {

                        $quote_details = QuoteAgainstRequest::leftjoin('companies', 'companies.id', '=', 'quote_against_requests.company_id')
                            ->leftjoin('drivers', 'drivers.id', '=', 'quote_against_requests.driver_id')
                            ->select('quote_against_requests.*', 'companies.company_name', 'drivers.first_name')
                            ->where('quote_against_requests.booking_id', $value->id)
                            ->where('quote_against_requests.status', 1)
                            ->latest('created_at')
                            ->first();


                        $payment = UserPayment::where('booking_id', $value->id)->first();

                        if ($payment) {
                            $value->payment = $payment->payment_type;
                        } else {
                            $value->payment = [];
                        }


                        if ($payment->payment_type == 'card') {

                            $card_token = CardPayment::where('user_payment_id', $payment->id)->first();

                            if ($card_token) {
                                $value->card_token = $card_token;
                            } else {
                                $value->card_token = [];
                            }
                        }
                    }

                    $ratings = FeedBack::where('booking_id', $value->id)->first();

                    if ($ratings) {
                        $value->ratings = $ratings;
                    } else {
                        $value->ratings = [];
                    }

                    if ($booking_via) {
                        $value->booking_details = $booking_via;
                    } else {
                        $value->booking_details = [];
                    }

                    if ($cartype) {
                        $value->fleet_details = $cartype;
                    } else {
                        $value->fleet_details = [];
                    }

                    if ($quote_details) {
                        $value->quote_details = $quote_details;
                    } else {
                        $value->quote_details = [];
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Booking list!',
                    'response' => $results,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking list not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to search bookings!',
            ]); // Return HTTP 403 Forbidden
        }
    }

    public function create_quote(Request $request)
    {
        $commonRules = [
            'booking_id' => 'required|exists:customer_bookings,id',
            // 'booking_req_id' => 'required|exists:booking_request_companies,id',
            'price' => 'required',
            // 'vehicle_type' => 'required',
            // 'color' => 'required',
            // 'manufacturer' => 'required',
            // 'model' => 'required',
            // 'description' => 'required',
        ];

        if (empty($request->quote_token)) {
            $rules = array_merge($commonRules, [
                'company_id' => 'required|exists:users,id',
            ]);
        } else {
            $rules = array_merge($commonRules, [
                'company_id' => 'nullable|exists:companies,id',
                'driver_id' => 'nullable|exists:drivers,id',
            ]);
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        if (empty($request->quote_token)) {
            $auth = Auth::user();

            if (empty($auth)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Login first',
                ]);
            }

            if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {
                return $this->processQuote($request);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have permission to add a quote',
                ]);
            }
        } else {
            $booking_req_company = BookingRequestCompany::find($request->booking_req_id);

            if (!is_null($booking_req_company->company_id)) {

                $booking_req = BookingRequestCompany::select('id')->where('booking_id', $request->booking_id)->where('company_id', $request->company_id)->first();
            } else {
                $booking_req = BookingRequestCompany::select('id')->where('booking_id', $request->booking_id)->where('driver_id', $request->driver_id)->first();
            }

            if ($booking_req_company->token == NULL && $booking_req_company->booking_quote_status == 'quoted') {

                return response()->json([
                    'status' => false,
                    'message' => 'You already quote against this job!',
                    'quote_satus' => true,
                ]);
            } else {

                if ($booking_req_company && $booking_req_company->token === $request->quote_token && $booking_req_company->booking_id == $request->booking_id && $booking_req_company->company_id == $request->company_id && $booking_req->id == $request->booking_req_id) {

                    return $this->processQuote($request);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Please enter a valid URL',
                    ]);
                }
            }
        }
    }


    // private function processQuote($request)
    // {
    //     if (empty($request->quote_token)) {

    //         $user = User::find($request->company_id);
    //         $company = Company::find($user->company_id);
    //         // $company = Company::select('id')->where('user_id', $request->company_id)->first();

    //         if (is_null($company)) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Invalid company information',
    //             ]);
    //         }
    //     }

    //     $quote = is_null($request->quote_id) ? new QuoteAgainstRequest() : QuoteAgainstRequest::find($request->quote_id);

    //     if (!$quote) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Quote first against booking request!',
    //         ]);
    //     }

    //     $quote->booking_id = $request->booking_id;
    //     $quote->company_id = empty($request->quote_token) ? $company->id : $request->company_id;
    //     $quote->booking_req_id = $request->booking_req_id;
    //     $quote->price = $request->price;
    //     $quote->vehicle_type = $request->vehicle_type;
    //     $quote->color = $request->color;
    //     $quote->manufacturer = $request->manufacturer;
    //     $quote->model = $request->model;
    //     $quote->description = $request->description;
    //     if ($request->quote_id) {
    //         $customer_booking = CustomerBooking::find($request->booking_id);
    //         if ($customer_booking->booking_status == 'accepted') {

    //             $customer_booking->booking_status = 'change_request';
    //             $customer_booking->save();

    //             $quote->status = 0;

    //             if($request->booking_req_id){

    //                 $booking_req_company = BookingRequestCompany::find($request->booking_req_id);
    //                 $booking_req_company->booking_quote_status = 'change_request';
    //                 $booking_req_company->status = 0;
    //                 $booking_req_company->save();
    //             }

    //         }
    //     }
    //     $quote->save();

    //     if (is_null($request->quote_id)) {
    //         $booking_req_company = BookingRequestCompany::find($request->booking_req_id);
    //         $booking_req_company->quote_price = $request->price;
    //         $booking_req_company->booking_quote_status = 'quoted';
    //         $booking_req_company->token = NULL;
    //         $booking_req_company->save();
    //     }




    //     return response()->json([
    //         'status' => true,
    //         'message' => is_null($request->quote_id) ? 'Quote added successfully' : 'Quote updated successfully',
    //         'response' => $quote,
    //     ]);
    // }

    private function processQuote($request)
    {
        if (empty($request->quote_token)) {

            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);
            // $company = Company::select('id')->where('user_id', $request->company_id)->first();

            if (is_null($company)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid company information',
                ]);
            }
        }

        $customer_booking = CustomerBooking::find($request->booking_id);
        if ($customer_booking->booking_status == 'pending') {


            $quote = is_null($request->quote_id) ? new QuoteAgainstRequest() : QuoteAgainstRequest::find($request->quote_id);

            if (!$quote) {
                return response()->json([
                    'status' => false,
                    'message' => 'Quote first against booking request!',
                ]);
            }

            $quote->booking_id = $request->booking_id;
            $quote->company_id = empty($request->quote_token) ? $company->id : $request->company_id;
            $quote->driver_id =  $request->driver_id;
            $quote->booking_req_id = $request->booking_req_id;
            $quote->price = $request->price;
            $quote->vehicle_type = $request->vehicle_type;
            $quote->color = $request->color;
            $quote->manufacturer = $request->manufacturer;
            $quote->model = $request->model;
            $quote->description = $request->description;
            if ($request->quote_id) {
                $customer_booking = CustomerBooking::find($request->booking_id);
                if ($customer_booking->booking_status == 'accepted') {

                    $customer_booking->booking_status = 'change_request';
                    $customer_booking->save();

                    $quote->status = 0;

                    if ($request->booking_req_id) {

                        $booking_req_company = BookingRequestCompany::find($request->booking_req_id);
                        $booking_req_company->booking_quote_status = 'change_request';
                        $booking_req_company->status = 0;
                        $booking_req_company->save();
                    }
                }
            }
            $quote->save();

            if (is_null($request->quote_id)) {
                $booking_req_company = BookingRequestCompany::find($request->booking_req_id);
                $booking_req_company->quote_price = $request->price;
                $booking_req_company->booking_quote_status = 'quoted';
                $booking_req_company->token = NULL;
                $booking_req_company->save();
            }
        } else {

            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);

            $quote = ChangeRequest::where('quote_id', $request->quote_id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Check if the quote exists and its status is 0, otherwise create a new one.
            if (!$quote || $quote->status != 0) {
                $quote = new ChangeRequest();
            }


            // Set properties for the quote.
            $quote->quote_id = $request->quote_id;
            $quote->booking_id = $request->booking_id;
            $quote->company_id = $company->id;
            $quote->booking_req_id = $request->booking_req_id;
            $quote->price = $request->price;
            $quote->vehicle_type = $request->vehicle_type;
            $quote->color = $request->color;
            $quote->manufacturer = $request->manufacturer;
            $quote->model = $request->model;
            $quote->description = $request->description;
            $quote->status = 0;
            $quote->save();

            $customer_booking->booking_status = 'change_request';
            $customer_booking->save();

            if (is_null($quote->driver_id)) {

                $booking_details_via = BookingDetail::select('via_name')
                    ->where('booking_id', $customer_booking->id)
                    ->get();

                $booking_details = '';
                foreach ($booking_details_via as $data) {
                    $booking_details .= <<<HTML
                <span class="location">{$data->via_name}</span><br><br>
                HTML;
                }

                $return_status = $customer_booking->booking_return_status == 1 ? 'Yes' : 'No';


                $type = FleetType::find($customer_booking->car_type_id);
                $car_name = $type->car_name;

                $company = Company::find($quote->company_id);

                $official_quote = QuoteAgainstRequest::find($request->quote_id);
                $type_name = $official_quote->vehicle_type;
                $company_email = $company->company_email;
                $operator_price = $official_quote->price;
                $driver_name = $company->company_name;
                $color =    $official_quote->color;
                $manufacturer =  $official_quote->manufacturer;
                $model = $official_quote->model;

                $change_request = ChangeRequest::where('quote_id', $request->quote_id)->orderBy('created_at', 'desc')
                    ->first();
                $change_type_name = $change_request->vehicle_type;
                $change_operator_price = $change_request->price;
                $change_color =    $change_request->color;
                $change_manufacturer =  $change_request->manufacturer;
                $change_model = $change_request->model;

                $this->change_request_company($customer_booking, $company_email, $type_name, $booking_details, $return_status, $driver_name, $car_name, $operator_price, $color, $manufacturer, $model, $change_operator_price, $change_type_name, $change_color, $change_manufacturer, $change_model);
            }
        }


        return response()->json([
            'status' => true,
            'message' => is_null($request->quote_id) ? 'Quote added successfully' : 'Quote updated successfully',
            'response' => $quote,
        ]);
    }

    public function change_request_company($booking, $company_email, $type_name, $booking_details, $return_status, $driver_name, $car_name, $operator_price, $color, $manufacturer, $model, $change_operator_price, $change_type_name, $change_color, $change_manufacturer, $change_model)
    {

        $checkMail = NotificationManagement::where('type', 'Change Request')->first(); // Sending Email Process Start
        if ($checkMail->send_email == "Y") {

            $queryUser = NotificationManagement::where('type', 'Change Request')
                ->where('user_type', 'LIKE', '%admin%')
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
                    '/\{(type_name)}]?/',
                    '/\{(car_name)}]?/',
                    '/\{(driver_email)}]?/',
                    '/\{(driver_name)}]?/',
                    '/\{(operator_price)}]?/',
                    '/\{(color)}]?/',
                    '/\{(manufacturer)}]?/',
                    '/\{(model)}]?/',
                    '/\{(change_operator_price)}]?/',
                    '/\{(change_type_name)}]?/',
                    '/\{(change_color)}]?/',
                    '/\{(change_manufacturer)}]?/',
                    '/\{(change_model)}]?/',


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
                    $operator_price,
                    $color,
                    $manufacturer,
                    $model,
                    $change_operator_price,
                    $change_type_name,
                    $change_color,
                    $change_manufacturer,
                    $change_model,

                ];

                $mail = preg_replace($patterns, $replacements, $checkMail->mail);

                $emailnotification = new NotificationEmail();
                $emailnotification->booking_id = $booking->id;
                $emailnotification->company_id = $booking->company_id;
                $emailnotification->to_email =  'mussabahmad1@gmail.com';
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


    public function mark_unavailable(Request $request)
    {

        $auth = Auth::user();

        // return $auth;

        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {


            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:customer_bookings,id',
                'company_id' => 'required|exists:users,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP 
            }
            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);
            // $company = Company::select('id')->where('user_id', $request->company_id)->first();

            $booking_request_company = BookingRequestCompany::where('booking_id', $request->booking_id)->where('company_id', $company->id)->first();

            if ($booking_request_company) {

                $booking_request_company->available_status = 'un-available';
                $booking_request_company->save();

                return response()->json([
                    'status' => true,
                    'message' => 'You have successfully set status Un-available!',
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Request Not Found!',
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to change status',
            ]);
        }
    }

    // public function advance_search_quote(Request $request)
    // {
    //     $auth = Auth::user();

    //     // Check permissions
    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

    //         // Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             'company_id' => 'required|exists:users,id',
    //             'tracking_number' => 'nullable',
    //             'booking_date' => 'nullable|date',
    //             'total_distance' => 'nullable|numeric',
    //             'total_passenger_min' => 'nullable|integer',
    //             'total_passenger_max' => 'nullable|integer',
    //         ]);

    //         if ($validator->fails()) {
    //             $errors = $validator->errors()->first(); // Get the first error message

    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $errors,
    //             ]);
    //         }

    //         if ($request->status == 'job-offers') {

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Job offers list not found!',
    //             ]);
    //         }

    //         $company = Company::select('id')->where('user_id', $request->company_id)->first();

    //         // Build the query
    //         $query = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
    //             ->select('customer_bookings.*', 'booking_request_companies.id as booking_req_id')
    //             ->where('booking_request_companies.company_id', $company->id)
    //             ->where('booking_request_companies.available_status', 'available');

    //         // Apply filters if provided
    //         if ($request->tracking_number) {
    //             $query->where('customer_bookings.tracking_number', $request->tracking_number);
    //         }

    //         if (in_array($request->status, ['quoted', 'un-quoted'])) {
    //             $query->where('booking_request_companies.booking_quote_status', $request->status);
    //         }

    //         if ($request->booking_date) {
    //             $query->whereDate('customer_bookings.booking_date', $request->booking_date);
    //         }

    //         if ($request->total_distance) {
    //             $query->where('customer_bookings.total_distance', '<=', $request->total_distance);
    //         }

    //         if ($request->total_passenger_min && $request->total_passenger_max) {
    //             $query->whereBetween('customer_bookings.total_passenger', [$request->total_passenger_min, $request->total_passenger_max]);
    //         }

    //         if ($request->latitude && $request->longitude && $request->radius) {
    //             $query->selectRaw(
    //                 '( 6371 * acos( cos( radians(?) ) * cos( radians( customer_bookings.booking_from_lat ) ) * cos( radians( customer_bookings.booking_from_long ) - radians(?) ) + sin( radians(?) ) * sin( radians( customer_bookings.booking_from_lat ) ) ) ) AS distance',
    //                 [$request->latitude, $request->longitude, $request->latitude]
    //             )
    //                 ->having('distance', '<', $request->radius)
    //                 ->orderBy('distance', 'asc');
    //         }

    //         // Execute the query and get results
    //         $results = $query->orderBy('customer_bookings.created_at', 'desc')->paginate(5);

    //         if ($results->isNotEmpty()) {

    //             foreach ($results as $value) {
    //                 $value->booking_details = BookingDetail::where('booking_id', $value->id)->get() ?: [];
    //                 $value->fleet_details = FleetType::find($value->car_type_id) ?: [];
    //                 $value->quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first() ?: [];
    //                 $value->ratings = FeedBack::where('booking_id', $value->id)->first() ?: [];
    //             }

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Booking list!',
    //                 'response' => $results,
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Booking list not found!',
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to search quotes!',
    //         ]);
    //     }
    // }

    public function advance_search_quote(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id != 1) {

                // Validate the incoming request
                $validator = Validator::make($request->all(), [
                    'company_id' => 'required|exists:users,id',
                    'tracking_number' => 'nullable|string',
                    'booking_date' => 'nullable|date',
                    'total_distance' => 'nullable|numeric',
                    'total_passenger_min' => 'nullable|integer',
                    'total_passenger_max' => 'nullable|integer',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'radius' => 'nullable|numeric',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors()->first(); // Get the first error message

                    return response()->json([
                        'success' => false,
                        'message' => $errors,
                    ]);
                }

                if ($request->status == 'job-offers') {
                    return response()->json([
                        'status' => false,
                        'message' => 'Job offers list not found!',
                    ]);
                }
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                // $company = Company::select('id')->where('user_id', $request->company_id)->first();


                // Ensure company exists
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found!',
                    ]);
                }
            }

            // Build the query
            // $query = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
            //     ->select('customer_bookings.*', 'booking_request_companies.id as booking_req_id', 'booking_request_companies.booking_quote_status')
            //     // ->where('booking_request_companies.company_id', $company->id)
            //     ->where('booking_request_companies.available_status', 'available')
            //     ->where('customer_bookings.booking_status', 'pending');
            if ($request->booking_quote_status != 'dispatch_booking') {

                $query = CustomerBooking::join('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                    ->select(
                        'customer_bookings.*',
                        DB::raw('MAX(booking_request_companies.id) as booking_req_id'),
                        DB::raw('MAX(booking_request_companies.booking_quote_status) as booking_quote_status')
                    )
                    ->where('booking_request_companies.available_status', 'available')
                    ->where('customer_bookings.booking_status', 'pending');
            } else {

                $query = CustomerBooking::where('admin_status', 'manual');
            }


            // $results = $query->orderBy('customer_bookings.created_at', 'desc')->paginate(5);

            // return $results;

            if ($auth->fixed_role_id != 1) {

                $query->where('booking_request_companies.company_id', $company->id);
            }

            $query->groupBy('customer_bookings.id');


            // return $results;

            if ($request->booking_quote_status) {
                if ($request->booking_quote_status != 'dispatch_booking') {
                    $query->where('booking_request_companies.booking_quote_status', $request->booking_quote_status);
                }
            }

            // Apply filters if provided
            if ($request->tracking_number) {
                $query->where('customer_bookings.tracking_number', 'like', '%' . $request->tracking_number . '%');
            }

            if ($request->booking_date) {
                $query->whereDate('customer_bookings.booking_date', $request->booking_date);
            }

            if ($request->total_distance) {

                $value = (float) $request->input('total_distance');
                $query->where('customer_bookings.total_distance', '<=', $value);
            }

            if (isset($request->total_passenger_min) && isset($request->total_passenger_max)) {
                $min = (int)$request->total_passenger_min;
                $max = (int)$request->total_passenger_max;

                $query->whereBetween('customer_bookings.total_passenger', [$min, $max]);
            }

            if ($request->latitude && $request->longitude && $request->radius) {
                $query->selectRaw(
                    '( 6371 * acos( cos( radians(?) ) * cos( radians( customer_bookings.booking_from_lat ) ) * cos( radians( customer_bookings.booking_from_long ) - radians(?) ) + sin( radians(?) ) * sin( radians( customer_bookings.booking_from_lat ) ) ) ) AS distance',
                    [$request->latitude, $request->longitude, $request->latitude]
                )
                    ->having('distance', '<', $request->radius)
                    ->orderBy('distance', 'asc');
            }

            // Execute the query and get results
            $results = $query->orderBy('customer_bookings.created_at', 'desc')->paginate(5);

            if ($results->isNotEmpty()) {

                foreach ($results as $value) {
                    $value->booking_details = BookingDetail::where('booking_id', $value->id)->get() ?: [];
                    $value->fleet_details = FleetType::find($value->car_type_id) ?: [];
                    if ($auth->fixed_role_id != 1) {
                        $value->quote_details = QuoteAgainstRequest::where('booking_id', $value->id)->where('company_id', $company->id)->first() ?: [];
                        $value->ratings = FeedBack::where('booking_id', $value->id)->first() ?: [];
                    }

                    $payment = UserPayment::where('booking_id', $value->id)->first();

                    if ($payment) {
                        $value->payment = $payment->payment_type;
                    } else {
                        $value->payment = [];
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Booking list!',
                    'response' => $results->toArray(),
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking list not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to search quotes!',
            ]);
        }
    }


    public function company_profile(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                return response()->json([
                    'success' => false,
                    'message' => $errors,
                ]);
            }

            // $company = Company::where('user_id', $request->company_id)->first();
            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);

            if ($company) {

                return response()->json([
                    'status' => true,
                    'message' => 'Company details!',
                    'response' => $company,
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Company not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to to see company details!',
            ]);
        }
    }

    public function edit_company_profile(Request $request)
    {


        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                return response()->json([
                    'success' => false,
                    'message' => $errors,
                ]);
            }

            if ($auth->fixed_role_id != 1) {

                $company_object = Company::where('user_id', $request->company_id)->first();
            } else {

                $company_object = Company::find($request->company_id);
            }


            if ($company_object) {

                $company = Company::find($company_object->id);
                $company->company_name = $request->company_name ?? $company_object->company_name;
                $company->company_type = $request->company_type ?? $company_object->company_type;
                $company->company_address = $request->company_address ?? $company_object->company_address;
                $company->company_reg_num = $request->company_reg_num ?? $company_object->company_reg_num;
                $company->bank_account_name = $request->bank_account_name ?? $company->bank_account_name;
                $company->bank_account_num = $request->bank_account_num ?? $company_object->bank_account_num;
                $company->bank_sort_code = $request->bank_sort_code ?? $company_object->bank_sort_code;
                $company->center_name = $request->center_name ?? $company_object->center_name;
                $company->latitude = $request->latitude ?? $company_object->latitude;
                $company->longitude = $request->longitude ?? $company_object->longitude;
                $company->radius = $request->radius ?? $company_object->radius;
                $company->save();

                $user = User::where('company_id', $company_object->id)->first();
                $user->name = $request->company_name ?? $company_object->company_name;
                $user->save();


                return response()->json([
                    'status' => true,
                    'message' => 'Company details updated successfully!',
                    'response' => $company,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to to edit company details!',
            ]);
        }
    }

    public function company_user(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            $rules = [
                'name' => 'required|string|max:255',
                'company_id' => 'required|exists:users,id',
                'company_user_role' => 'required',
                'phone' => 'required',

            ];

            if (is_null($request->user_id)) {

                $rules['email'] = 'required|string|email|max:255|unique:users';
                $rules['password'] = 'required|string|max:255';
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


            if ($request->hasFile('profile_picture')) {

                $imagePath = $this->handleFileUpload($request->file('profile_picture'));
            } else {

                if (is_null($request->user_id)) {

                    $imagePath = NULL;
                } else {

                    $user = User::find($request->user_id);
                    if (!$user) {
                        return response()->json([
                            'status' => false,
                            'message' => 'User not found',
                        ]);
                    }
                    $imagePath = $user->profile_picture;
                }
            }

            if ($auth->fixed_role_id == 2) {

                $company_object = Company::where('user_id', $request->company_id)->first();
            } else if ($auth->fixed_role_id == 1) {

                $company_object = User::find($request->company_id);
            }


            if ($company_object) {

                if (is_null($request->user_id)) {
                    $user = new User();
                } else {
                    $user = User::find($request->user_id);
                    if (!$user) {
                        return response()->json([
                            'status' => false,
                            'message' => 'User not found',
                        ]);
                    }
                }
                if ($auth->fixed_role_id == 2) {
                    $user->company_id = $company_object->id;
                }
                $user->profile_picture = $imagePath;
                $user->name = $request->name;
                if (is_null($request->user_id)) {

                    $user->email = $request->email;
                    $user->password =  Hash::make($request->password);
                }

                if ($request->password) {
                    $user->password =  Hash::make($request->password);
                }
                $user->role_id = $request->role_id;
                $user->fixed_role_id =  $auth->fixed_role_id;
                $user->company_user_role = $request->company_user_role;
                $user->fixed_user_role = $auth->fixed_role_id == 1 ? 'Admin' : 'Company';
                $user->phone = $request->phone;
                $user->assignRole($request->company_user_role);
                $user->save();


                return response()->json([
                    'status' => true,
                    'message' => is_null($request->user_id) ? 'User created successfully' : 'User updated successfully',
                    'user' => User::find($user->id),

                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Company not found',

                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to add company users!',
            ]);
        }
    }

    public function list_company_user(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {


            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                return response()->json([
                    'success' => false,
                    'message' => $errors,
                ]);
            }

            if ($auth->fixed_role_id == 2) {

                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                // $company = Company::select('id')->where('user_id', $request->company_id)->first();

                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ]);
                }

                $company_users = User::where('fixed_role_id', 2)->where('company_user_role', '!=', 'Company')->where('company_id', $company->id)->paginate(5);
            } else if ($auth->fixed_role_id == 1) {

                $company_users = User::where('fixed_role_id', 1)->where('company_user_role', '!=', 'Admin')->paginate(5);
            }


            if ($company_users->isNotEmpty()) {

                return response()->json([
                    'status' => false,
                    'message' => ' Users list!',
                    'response' => $company_users
                ]);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Users not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see company users!',
            ]);
        }
    }

    public function delete_company_user(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',
                'user_id' => 'required|exists:users,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                return response()->json([
                    'success' => false,
                    'message' => $errors,
                ]);
            }

            $company_object = Company::where('user_id', $request->company_id)->first();

            $user = User::find($request->user_id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'You have delete this company user successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to add company users!',
            ]);
        }
    }

    // public function payment_list_against_company(Request $request){

    //     $auth = Auth::user();

    //     // Check permissions
    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

    //         $company = Company::select('id')->where('user_id', $request->company_id)->first();


    //         $bookings = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
    //         ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
    //         ->select('customer_bookings.tracking_number', 'customer_bookings.head_passenger_name', 'user_payments.created_at', 'customer_bookings.booking_price', 'fleet_types.type_name', 'user_payments.payment_type','user_payments.status')
    //         ->where('customer_bookings.company_id', $company->id)
    //         ->paginate(5);


    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Payment List Against Company!',
    //             'response' => $bookings,
    //         ]);


    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to see payments list!',
    //         ]);
    //     }
    // }


    public function payment_list_against_company(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {
            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);
            // $company = Company::select('id')->where('user_id', $request->company_id)->first();

            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company not found',
                ]);
            }

            // Start building the query
            $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
                ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
                ->select('user_payments.id', 'customer_bookings.deduction_price', 'customer_bookings.tracking_number', 'customer_bookings.booking_date', 'customer_bookings.head_passenger_name', 'user_payments.created_at', 'customer_bookings.booking_price', 'customer_bookings.booking_status',  'fleet_types.type_name', 'user_payments.payment_type', 'user_payments.status');

            // Apply search by tracking number if provided
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
                $query->where(function ($query) use ($request) {
                    $query->where('customer_bookings.tracking_number', 'like', '%' . $request->search . '%')
                        ->orWhere('fleet_types.type_name', 'like', '%' . $request->search . '%');
                })->where('customer_bookings.company_id', $company->id);
            }

            // Apply date range filter if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;


                // Ensure dates are in a valid format
                // $query->whereBetween('user_payments.created_at', [$startDate, $endDate]);

                // Using whereDate for start and end conditions
                $query->where('user_payments.created_at', '>=', $startDate . ' 00:00:00')
                    ->where('user_payments.created_at', '<=', $endDate . ' 23:59:59');
            }

            // Paginate results
            $bookings = $query->where('customer_bookings.company_id', $company->id)->orderBy('customer_bookings.created_at', 'desc')->paginate(5);

            return response()->json([
                'status' => true,
                'message' => 'Payment list against company!',
                'response' => $bookings,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see payments list!',
            ]);
        }
    }

    // public function feedback_against_company(Request $request)
    // {
    //     $auth = Auth::user();

    //     // Check permissions
    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

    //         $company = Company::select('id')->where('user_id', $request->company_id)->first();

    //         if (!$company) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Company not found',
    //             ]);
    //         }

    //         // Start building the query
    //         $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
    //             ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
    //             ->join('feed_backs', 'customer_bookings.id', '=', 'feed_backs.booking_id')
    //             ->select(
    //                 // 'feed_backs.*',
    //                 'feed_backs.on_time',
    //                 'feed_backs.driver_attitude',
    //                 'feed_backs.vehicle_standard',
    //                 'feed_backs.service_level',
    //                 'customer_bookings.tracking_number',
    //                 'customer_bookings.head_passenger_name',
    //                 'user_payments.created_at as payment_created_at',
    //                 'customer_bookings.booking_price',
    //                 'fleet_types.type_name',
    //                 'user_payments.payment_type',
    //                 'user_payments.status'
    //             )
    //             ->where('customer_bookings.company_id', $company->id);


    //         // Apply search by tracking number if provided
    //         if ($request->has('tracking_number') && !empty($request->tracking_number)) {
    //             $query->where('customer_bookings.tracking_number', 'like', '%' . $request->tracking_number . '%');
    //         }

    //         // Apply date range filter if provided
    //         if ($request->has('start_date') && $request->has('end_date')) {
    //             $startDate = $request->start_date;
    //             $endDate = $request->end_date;

    //             // Ensure dates are in a valid format
    //             $query->whereBetween('user_payments.created_at', [$startDate, $endDate]);
    //         }

    //         // Paginate results
    //         $bookings = $query->paginate(5); // Paginate on the query builder

    //         foreach ($bookings as $value) {

    //             $ratings = FeedBack::select('on_time', 'driver_attitude', 'vehicle_standard', 'service_level')->where('booking_id', $value->id);

    //             // Convert the result to an array
    //             $ratingsArray = $ratings->toArray();
    //             // Calculate the average rating for each field
    //             $averageOnTime = $ratingsArray['on_time'] ?? 0;
    //             $averageDriverAttitude = $ratingsArray['driver_attitude'] ?? 0;
    //             $averageVehicleStandard = $ratingsArray['vehicle_standard'] ?? 0;
    //             $averageServiceLevel = $ratingsArray['service_level'] ?? 0;

    //             $averageRating = ($averageOnTime + $averageDriverAttitude + $averageVehicleStandard + $averageServiceLevel) / 4;

    //             $roundedAverageRating = round($averageRating, 2);

    //             $value->average_ratings = $roundedAverageRating;

    //         }

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Feedback list against company!',
    //             'response' => $bookings,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to see feedback list!',
    //         ]);
    //     }
    // }

    // public function feedback_against_company(Request $request)
    // {
    //     $auth = Auth::user();

    //     // Check permissions
    //     if (!($auth->role_id == 2 || $auth->role_id == 1) || ($auth->fixed_role_id != 1 && $auth->id != $request->company_id)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to see feedback list!',
    //         ]);
    //     }

    //     // Validate and retrieve company
    //     $company = Company::where('user_id', $request->company_id)->first();
    //     if (!$company) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Company not found',
    //         ]);
    //     }

    //     // Build the query
    //     $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
    //         ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
    //         ->join('feed_backs', 'customer_bookings.id', '=', 'feed_backs.booking_id')
    //         ->select(
    //             'feed_backs.on_time',
    //             'feed_backs.driver_attitude',
    //             'feed_backs.vehicle_standard',
    //             'feed_backs.service_level',
    //             'customer_bookings.tracking_number',
    //             'customer_bookings.head_passenger_name',
    //             'user_payments.created_at as payment_created_at',
    //             'customer_bookings.booking_price',
    //             'fleet_types.type_name',
    //             'user_payments.payment_type',
    //             'user_payments.status'
    //         )
    //         ->where('customer_bookings.company_id', $company->id);

    //     // Apply search by tracking number if provided
    //     if ($request->filled('tracking_number')) {
    //         $query->where('customer_bookings.tracking_number', 'like', '%' . $request->tracking_number . '%');
    //     }

    //     // Apply date range filter if provided
    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         $query->whereBetween('user_payments.created_at', [$request->start_date, $request->end_date]);
    //     }

    //     // Paginate results
    //     $bookings = $query->paginate(5);

    //     // Attach average ratings to the bookings
    //     $bookings->getCollection()->transform(function ($booking) {
    //         $feedbacks = FeedBack::where('booking_id', $booking->id)
    //             ->select('on_time', 'driver_attitude', 'vehicle_standard', 'service_level')
    //             ->first();

    //         if ($feedbacks) {
    //             $averageOnTime = $feedbacks->on_time;
    //             $averageDriverAttitude = $feedbacks->driver_attitude;
    //             $averageVehicleStandard = $feedbacks->vehicle_standard;
    //             $averageServiceLevel = $feedbacks->service_level;

    //             $averageRating = ($averageOnTime + $averageDriverAttitude + $averageVehicleStandard + $averageServiceLevel) / 4;

    //             $booking->average_ratings = round($averageRating, 2);

    //         } else {
    //             $booking->average_ratings = 0;
    //         }

    //         return $booking;
    //     });

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Feedback list against company!',
    //         'response' => $bookings,
    //     ]);
    // }

    public function feedback_against_company(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (!($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) || ($auth->fixed_role_id != 1 && $auth->id != $request->company_id)) {

            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see feedback list!',
            ]);
        }

        // Validate and retrieve company
        // $company = Company::where('user_id', $request->company_id)->first();
        $user = User::find($request->company_id);
        $company = Company::find($user->company_id);

        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found',
            ]);
        }

        // Build the query
        $query = CustomerBooking::join('user_payments', 'user_payments.booking_id', '=', 'customer_bookings.id')
            ->join('fleet_types', 'customer_bookings.car_type_id', '=', 'fleet_types.id')
            ->join('feed_backs', 'customer_bookings.id', '=', 'feed_backs.booking_id')
            ->select(
                // 'feed_backs.on_time',
                // 'feed_backs.driver_attitude',
                // 'feed_backs.vehicle_standard',
                'feed_backs.service_level',
                'customer_bookings.tracking_number',
                'customer_bookings.head_passenger_name',
                'user_payments.created_at as payment_created_at',
                'customer_bookings.booking_price',
                'fleet_types.type_name',
                'user_payments.payment_type',
                'user_payments.status',
                'customer_bookings.id as booking_id',
                'customer_bookings.deduction_price',
                'customer_bookings.booking_date',
                'customer_bookings.booking_status',
            )->where('customer_bookings.company_id', $company->id);

        // Apply search by tracking number if provided
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

            $query->where(function ($query) use ($request) {
                $query->where('customer_bookings.tracking_number', 'like', '%' . $request->search . '%')
                    ->orWhere('fleet_types.type_name', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_bookings.head_passenger_name', 'like', '%' . $request->search . '%');
            })->where('customer_bookings.company_id', $company->id);
        }

        // Apply date range filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // $query->whereBetween('user_payments.created_at', [$request->start_date, $request->end_date]);

            $query->where('user_payments.created_at', '>=', $request->start_date . ' 00:00:00')
                ->where('user_payments.created_at', '<=', $request->end_date . ' 23:59:59')->where('customer_bookings.company_id', $company->id);
        }

        // Paginate results
        $bookings = $query->orderBy('customer_bookings.created_at', 'desc')->paginate(5);


        // Attach average ratings to the bookings
        // $bookings->getCollection()->transform(function ($booking) {
        //     // Retrieve feedbacks for this booking
        //     $feedbacks = FeedBack::where('booking_id', $booking->booking_id)
        //         ->select('on_time', 'driver_attitude', 'vehicle_standard', 'service_level')
        //         ->get();

        //     if ($feedbacks->isEmpty()) {
        //         $booking->average_ratings = 0; // No feedback, average rating is 0
        //     } else {
        //         // Calculate the average ratings
        //         $totalRatings = $feedbacks->reduce(function ($carry, $feedback) {
        //             $carry['on_time'] += $feedback->on_time;
        //             $carry['driver_attitude'] += $feedback->driver_attitude;
        //             $carry['vehicle_standard'] += $feedback->vehicle_standard;
        //             $carry['service_level'] += $feedback->service_level;
        //             return $carry;
        //         }, ['on_time' => 0, 'driver_attitude' => 0, 'vehicle_standard' => 0, 'service_level' => 0]);

        //         $numFeedbacks = $feedbacks->count();

        //         $averageOnTime = $totalRatings['on_time'] / $numFeedbacks;
        //         $averageDriverAttitude = $totalRatings['driver_attitude'] / $numFeedbacks;
        //         $averageVehicleStandard = $totalRatings['vehicle_standard'] / $numFeedbacks;
        //         $averageServiceLevel = $totalRatings['service_level'] / $numFeedbacks;

        //         $averageRating = ($averageOnTime + $averageDriverAttitude + $averageVehicleStandard + $averageServiceLevel) / 4;
        //         $booking->average_ratings = round($averageRating, 2);
        //     }

        //     return $booking;
        // });

        return response()->json([
            'status' => true,
            'message' => 'Feedback list against company!',
            'response' => $bookings,
        ]);
    }

    public function overall_feedback_against_company(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) || ($auth->fixed_role_id == 1 || $auth->id == $request->company_id)) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see overall feedback!',
            ]);
        }

        // Validate and retrieve company
        // $company = Company::where('user_id', $request->company_id)->first();
        $user = User::find($request->company_id);
        $company = Company::find($user->company_id);

        if (!$company) {
            return response()->json([
                'status' => false,
                'message' => 'Company not found',
            ]);
        }

        // // Retrieve bookings for the company
        // $company_bookings = CustomerBooking::where('company_id', $company->id)->get();

        // if ($company_bookings->isEmpty()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'No bookings found for this company',
        //     ]);
        // }

        // // Initialize totals and count variables
        // // $totalOnTime = 0;
        // // $totalDriverAttitude = 0;
        // // $totalVehicleStandard = 0;
        // $totalServiceLevel = 0;
        // $feedbackCount = 0;

        // foreach ($company_bookings as $booking) {
        //     // Retrieve feedback for each booking
        //     $feedbacks = FeedBack::where('booking_id', $booking->id)
        //         ->select('on_time', 'driver_attitude', 'vehicle_standard', 'service_level')
        //         ->get();

        //     foreach ($feedbacks as $feedback) {
        //         // $totalOnTime += $feedback->on_time;
        //         // $totalDriverAttitude += $feedback->driver_attitude;
        //         // $totalVehicleStandard += $feedback->vehicle_standard;
        //         $totalServiceLevel += $feedback->service_level;
        //         $feedbackCount++;
        //     }
        // }

        // if ($feedbackCount === 0) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'No feedback found for the bookings',
        //     ]);
        // }

        // // Calculate averages for each category
        // // $averageOnTime = $totalOnTime / $feedbackCount;
        // // $averageDriverAttitude = $totalDriverAttitude / $feedbackCount;
        // // $averageVehicleStandard = $totalVehicleStandard / $feedbackCount;
        // $averageServiceLevel = $totalServiceLevel / $feedbackCount;

        // // Calculate the overall average
        // // $overallAverage = ($averageOnTime + $averageDriverAttitude + $averageVehicleStandard + $averageServiceLevel) / 4;



        $company_latest_bookings = CustomerBooking::where('company_id', $company->id)->orderBy('created_at', 'desc')->limit(3)->get();

        foreach ($company_latest_bookings as $booking) {
            // Retrieve feedback for each booking
            $feedbacks = FeedBack::where('booking_id', $booking->id)->select('service_level')->get();

            $booking->lastet_customer_feedbacks = $feedbacks;
        }


        return response()->json([
            'status' => true,
            'message' => 'Overall ratings company',
            'overall_average' => (float)($company->average_ratings),
            'latest_feedback_list' => $company_latest_bookings
        ]);
    }

    public function dashboard_stats(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) || ($auth->fixed_role_id == 1 || $auth->id == $request->company_id)) {

            if ($auth->fixed_role_id != 1) {

                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ]);
                }



                $change_request = CustomerBooking::where('booking_status', 'change_request')->where('company_id', $company->id)->count();
            }

            $drivers = Driver::where('active_status', 'approved')->count();

            $operators = Company::where('status', 1)->count();

            $current_date_time = Carbon::now();


            $upcoming_bookings = CustomerBooking::join('quote_against_requests', 'customer_bookings.id', '=', 'quote_against_requests.booking_id')
                ->where('quote_against_requests.status', 1)
                ->where('customer_bookings.booking_date', '>', $current_date_time->toDateString())
                ->where(function ($query) use ($current_date_time) {
                    $query->where('customer_bookings.booking_date', '>', $current_date_time->toDateString())
                        ->orWhere(function ($query) use ($current_date_time) {
                            $query->where('customer_bookings.booking_date', '=', $current_date_time->toDateString())
                                ->whereTime('customer_bookings.booking_time', '>', $current_date_time->toTimeString());
                        });
                });

            if ($auth->fixed_role_id != 1) {
                $upcoming_bookings->where('quote_against_requests.company_id', $company->id);
            }

            $upcoming_bookings_count = $upcoming_bookings->count();

            $complaints = CustomerBooking::join('feed_backs', 'customer_bookings.id', '=', 'feed_backs.booking_id')
                ->where('feed_backs.service_level', '<', 3);

            if ($auth->fixed_role_id != 1) {
                $complaints->where('customer_bookings.company_id', $company->id);
            }
            $complaint_count = $complaints->count();


            $current_date = Carbon::now()->toDateString();
            $current_time = Carbon::now()->format('H:iA');   // Gets the current time in 24-hour format


            $revenue = CustomerBooking::join('quote_against_requests', 'customer_bookings.id', '=', 'quote_against_requests.booking_id')

                ->where('quote_against_requests.status', 1)
                ->where(function ($query) use ($current_date, $current_time) {
                    // For bookings before the current date
                    $query->where('customer_bookings.booking_date', '<', $current_date);

                    // For bookings on the same date but before the current time
                    // $query->orWhere(function ($query) use ($current_date, $current_time) {
                    //     $query->where('customer_bookings.booking_date', '=',  $current_date)
                    //         ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') < ?", [$current_time]);
                    // });
                    $query->orWhere(function ($query) use ($current_date, $current_time) {
                        $query->where('customer_bookings.booking_date', '=', $current_date)
                            ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') < STR_TO_DATE(?, '%h:%i%p')", [$current_time]);
                    });
                });


            if ($auth->fixed_role_id != 1) {
                $revenue->where('quote_against_requests.company_id', $company->id);
            }

            $total_revenue = $revenue->sum('quote_against_requests.price');

            $current_date = Carbon::now()->toDateString();
            $current_time = Carbon::now()->format('H:iA');   // Gets the current time in 12-hour format


            $future_revenue = CustomerBooking::join('quote_against_requests', 'customer_bookings.id', '=', 'quote_against_requests.booking_id')

                ->where('quote_against_requests.status', 1)
                ->where(function ($query) use ($current_date, $current_time) {
                    // For bookings before the current date
                    $query->where('customer_bookings.booking_date', '>', $current_date);

                    // For bookings on the same date but before the current time
                    // $query->orWhere(function ($query) use ($current_date, $current_time) {
                    //     $query->where('customer_bookings.booking_date', '=',  $current_date)
                    //         ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') > ?", [$current_time]);
                    // });

                    $query->orWhere(function ($query) use ($current_date, $current_time) {
                        $query->where('customer_bookings.booking_date', '=', $current_date)
                            ->whereRaw("STR_TO_DATE(customer_bookings.booking_time, '%h:%i%p') > STR_TO_DATE(?, '%h:%i%p')", [$current_time]);
                    });
                });


            if ($auth->fixed_role_id != 1) {
                $future_revenue->where('quote_against_requests.company_id', $company->id);
            }

            $future_revenue = $future_revenue->sum('quote_against_requests.price');


            if ($auth->fixed_role_id != 1) {
                return response()->json([
                    'status' => true,
                    'message' => 'Dashboard statistics',
                    'average_ratings' => $company->average_ratings,
                    'change_request_count' => $change_request,
                    'upcoming_bookings_count' => $upcoming_bookings_count,
                    'complaints_count' => $complaint_count,
                    'revenue' => $total_revenue,
                    'future_bookings_revenue' => $future_revenue


                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Dashboard statistics',
                    'drivers' => $drivers,
                    'operators' => $operators,
                    'upcoming_bookings_count' => $upcoming_bookings_count,
                    'complaints_count' => $complaint_count,
                    'revenue' => $total_revenue,
                    'future_bookings_revenue' => $future_revenue

                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see dashboard stats',
            ]);
        }
    }

    // public function fleet_dashboard(Request $request)
    // {

    //     $month = $request->month;
    //     $year = $request->year;

    //     $company = Company::where('user_id', $request->company_id)->first();


    //     if ($request->type == 'monthly') {

    //         $standard = QuoteAgainstRequest::where('vehicle_type', 'Standard')
    //             ->whereMonth('created_at', $month)
    //             ->whereYear('created_at', $year)
    //             ->where('status', 1)
    //             ->where('company_id', $company->id)
    //             ->count();

    //         $premium = QuoteAgainstRequest::where('vehicle_type', 'Premium')
    //             ->whereMonth('created_at', $month)
    //             ->whereYear('created_at', $year)
    //             ->where('status', 1)
    //             ->where('company_id', $company->id)
    //             ->count();


    //         $luxury = QuoteAgainstRequest::where('vehicle_type', 'Luxury')
    //             ->whereMonth('created_at', $month)
    //             ->whereYear('created_at', $year)
    //             ->where('status', 1)
    //             ->where('company_id', $company->id)
    //             ->count();

    //         // Get the total count
    //         $total = $standard + $premium + $luxury;


    //         // Calculate percentages
    //         $standardPercentage = $total > 0 ? ($standard / $total) * 100 : 0;
    //         $premiumPercentage = $total > 0 ? ($premium / $total) * 100 : 0;
    //         $luxuryPercentage = $total > 0 ? ($luxury / $total) * 100 : 0;

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Monthly fleet statistics',
    //             'standardPercentage' => round($standardPercentage, 2),
    //             'premiumPercentage' => round($premiumPercentage, 2),
    //             'luxuryPercentage' => round($luxuryPercentage, 2),

    //         ]);
    //     } else if ($request->type == 'yearly') {

    //         $standard = QuoteAgainstRequest::where('vehicle_type', 'Standard')
    //             ->whereYear('created_at', $year)
    //             ->where('status', 1)
    //             ->where('company_id', $company->id)
    //             ->count();

    //         $premium = QuoteAgainstRequest::where('vehicle_type', 'Premium')
    //             ->whereYear('created_at', $year)
    //             ->where('status', 1)
    //             ->where('company_id', $company->id)
    //             ->count();


    //         $luxury = QuoteAgainstRequest::where('vehicle_type', 'Luxury')
    //             ->whereYear('created_at', $year)
    //             ->where('status', 1)
    //             ->where('company_id', $company->id)
    //             ->count();

    //         // Get the total count
    //         $total = $standard + $premium + $luxury;

    //         // Calculate percentages
    //         $standardPercentage = $total > 0 ? ($standard / $total) * 100 : 0;
    //         $premiumPercentage = $total > 0 ? ($premium / $total) * 100 : 0;
    //         $luxuryPercentage = $total > 0 ? ($luxury / $total) * 100 : 0;



    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Yearly fleet statistics',
    //             'standardPercentage' => round($standardPercentage, 2),
    //             'premiumPercentage' => round($premiumPercentage, 2),
    //             'luxuryPercentage' => round($luxuryPercentage, 2),

    //         ]);
    //     }
    // }

    public function fleet_dashboard(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) || ($auth->fixed_role_id == 1 || $auth->id == $request->company_id)) {

            if ($auth->fixed_role_id != 1) {

                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ]);
                }


                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
            }

            $month = $request->month;
            $year = $request->year;

            if ($request->type == 'monthly') {

                // $fleets = Fleet::where('company_id', $company->id)->where('status', 1)->get();

                // foreach($fleets as $fleet){

                //     $get_fleet_type_name = FleetType::find($fleet->vehicle_type);

                //     $type_count = QuoteAgainstRequest::where('vehicle_type', $get_fleet_type_name->type_name)->where('company_id', $company->id)->where('status', 1)->count();

                // }

                $fleet_counts = [];
                $total_quotes = 0;

                $fleets = Fleet::query();

                if ($auth->fixed_role_id != 1) {
                    $fleets = $fleets->where('company_id', $company->id);
                }

                $fleets = $fleets->where('active_status', 1)->get();

                // First, calculate the total number of quotes and fleet type counts
                foreach ($fleets as $fleet) {

                    $get_fleet_type_name = FleetType::find($fleet->vehicle_type);

                    // Count the number of quotes for each fleet type
                    $type_count = QuoteAgainstRequest::where('vehicle_type', $get_fleet_type_name->type_name)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year);

                    if ($auth->fixed_role_id != 1) {
                        $type_count = $type_count->where('company_id', $company->id);
                    }

                    $type_count = $type_count->where('status', 1)->count();


                    // Store the count for this fleet type
                    if (isset($fleet_counts[$get_fleet_type_name->type_name])) {
                        $fleet_counts[$get_fleet_type_name->type_name] += $type_count;
                    } else {
                        $fleet_counts[$get_fleet_type_name->type_name] = $type_count;
                    }

                    // Keep track of the total number of quotes across all fleet types
                    $total_quotes += $type_count;
                }

                // Now calculate the percentage for each fleet type
                $fleet_data = [];

                foreach ($fleet_counts as $type_name => $count) {
                    $percentage = ($total_quotes > 0) ? ($count / $total_quotes) * 100 : 0;
                    $fleet_data[] = [
                        'fleetType' => $type_name,
                        'count' => $count,
                        'percentage' => round($percentage, 2)
                    ];
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Monthly fleet statistics',
                    'fleetData' => $fleet_data
                ]);
            } else if ($request->type == 'yearly') {

                $fleet_counts = [];
                $total_quotes = 0;

                $fleets = Fleet::query();

                if ($auth->fixed_role_id != 1) {
                    $fleets = $fleets->where('company_id', $company->id);
                }

                $fleets = $fleets->where('active_status', 1)->get();

                // First, calculate the total number of quotes and fleet type counts
                foreach ($fleets as $fleet) {
                    $get_fleet_type_name = FleetType::find($fleet->vehicle_type);

                    // Count the number of quotes for each fleet type
                    // $type_count = QuoteAgainstRequest::where('vehicle_type', $get_fleet_type_name->type_name)
                    //     ->whereYear('created_at', $year)
                    //     ->where('company_id', $company->id)
                    //     ->where('status', 1)
                    //     ->count();


                    $type_count = QuoteAgainstRequest::where('vehicle_type', $get_fleet_type_name->type_name)
                        ->whereYear('created_at', $year);

                    if ($auth->fixed_role_id != 1) {
                        $type_count = $type_count->where('company_id', $company->id);
                    }

                    $type_count = $type_count->where('status', 1)->count();



                    // Store the count for this fleet type
                    if (isset($fleet_counts[$get_fleet_type_name->type_name])) {
                        $fleet_counts[$get_fleet_type_name->type_name] += $type_count;
                    } else {
                        $fleet_counts[$get_fleet_type_name->type_name] = $type_count;
                    }

                    // Keep track of the total number of quotes across all fleet types
                    $total_quotes += $type_count;
                }

                // Now calculate the percentage for each fleet type
                $fleet_data = [];

                foreach ($fleet_counts as $type_name => $count) {
                    $percentage = ($total_quotes > 0) ? ($count / $total_quotes) * 100 : 0;
                    $fleet_data[] = [
                        'fleetType' => $type_name,
                        'count' => $count,
                        'percentage' => round($percentage, 2)
                    ];
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Yearly fleet statistics',
                    'fleetData' => $fleet_data
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see dashboard stats',
            ]);
        }
    }

    // public function quotes_history(Request $request)
    // {


    //     $company = Company::where('user_id', $request->company_id)->first();


    //     $quotes = QuoteAgainstRequest::where('company_id', $company->id)
    //         ->whereDate('created_at', $request->date)
    //         ->get();


    //     foreach ($quotes as $value) {

    //         $booking = CustomerBooking::find($value->booking_id);

    //         $value->tracking_number = $booking->tracking_number;
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Quotes against date',
    //         'response' => $quotes,


    //     ]);
    // }

    public function quotes_history(Request $request)
    {

        $auth = Auth::user();

        // Check permissions
        if (!($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) || ($auth->fixed_role_id == 1 || $auth->id == $request->company_id)) {

            if ($auth->fixed_role_id != 1) {

                // Validate that both 'start_date' and 'end_date' are provided in the request
                $request->validate([
                    'start_date' => 'required|date',
                    'end_date' => 'required|date',
                ]);

                // Find the company based on the provided company ID
                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);

                // Ensure the company exists before querying
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ], 404);
                }
            }

            // Retrieve quotes within the specified date range
            $quotes = QuoteAgainstRequest::join('booking_request_companies', 'quote_against_requests.booking_req_id', '=', 'booking_request_companies.id')
                ->join('customer_bookings', 'quote_against_requests.booking_id', '=', 'customer_bookings.id')
                ->where('booking_request_companies.available_status', 'available')
                ->where('quote_against_requests.created_at', '>=', $request->start_date)
                ->where('quote_against_requests.created_at', '<=', $request->end_date)
                ->where('customer_bookings.booking_status', 'pending');
            // ->whereBetween('created_at', [$request->start_date, $request->end_date])


            if ($auth->fixed_role_id != 1) {
                $quotes = $quotes->where('quote_against_requests.company_id', $company->id);
            }

            $quotes = $quotes->get();



            // Attach the tracking number from the associated booking
            foreach ($quotes as $value) {
                $booking = CustomerBooking::find($value->booking_id);
                $value->tracking_number = $booking ? $booking->tracking_number : null;
                $value->booking_date = $booking ? $booking->booking_date : null;
            }

            // Return the response with the quotes data
            return response()->json([
                'status' => true,
                'message' => 'Quotes retrieved successfully',
                'response' => $quotes,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see dashboard stats',
            ]);
        }
    }


    public function booking_history(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) || ($auth->fixed_role_id == 1 || $auth->id == $request->company_id)) {

            if ($auth->fixed_role_id != 1) {

                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found',
                    ]);
                }

                // Get the company ID from the request or authenticated user
                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
            }

            if ($request->type == 'monthly') {

                $month = $request->input('month'); // format: 'YYYY-MM'

                // Query to get bookings for the specified company within the selected month
                // $bookings = DB::table('customer_bookings')
                //     ->leftJoin('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                //     // ->where('customer_bookings.company_id', $company->id)
                //     ->where('customer_bookings.booking_date', 'LIKE', "$month%") // Filter by month
                //     ->groupBy('customer_bookings.booking_date') // Group by booking date
                //     ->select(
                //         // 'booking_request_companies.booking_id',
                //         'customer_bookings.booking_date',
                //         DB::raw('COUNT(DISTINCT customer_bookings.id) as total_bookings'),
                //         DB::raw('SUM(CASE WHEN booking_request_companies.booking_quote_status = "quoted" THEN 1 ELSE 0 END) as quoted_count'),
                //         DB::raw('SUM(CASE WHEN booking_request_companies.booking_quote_status = "un-quoted" THEN 1 ELSE 0 END) as unquoted_count')

                //     );

                // if ($auth->fixed_role_id != 1) {
                //     $bookings = $bookings->where('booking_request_companies.company_id', $company->id);
                // }

                // $bookings = $bookings->get();

                $bookings = DB::table('customer_bookings')
                    ->leftJoin('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                    ->where('customer_bookings.booking_date', 'LIKE', "$month%") // Filter by month
                    ->whereNull('booking_request_companies.driver_id')
                    ->groupBy('customer_bookings.booking_date') // Group by booking date
                    ->select(
                        'customer_bookings.booking_date',
                        DB::raw('COUNT(DISTINCT customer_bookings.id) as total_bookings'),
                        // Check if there's at least one "quoted" status for each booking_id
                        DB::raw('SUM(CASE WHEN EXISTS (
                        SELECT 1 FROM booking_request_companies AS brc 
                        WHERE brc.booking_id = customer_bookings.id AND brc.booking_quote_status = "quoted") THEN 1 ELSE 0 END) as quoted_count'),

                        // Check if all statuses are "un-quoted" for each booking_id
                        DB::raw('SUM(CASE WHEN NOT EXISTS (
                        SELECT 1 FROM booking_request_companies AS brc 
                        WHERE brc.booking_id = customer_bookings.id AND brc.booking_quote_status = "quoted") THEN 1 ELSE 0 END) as unquoted_count')
                    );

                if ($auth->fixed_role_id != 1) {
                    $bookings = $bookings->where('booking_request_companies.company_id', $company->id);
                }

                $bookings = $bookings->get();


                return response()->json([
                    'status' => true,
                    'message' => 'Monthly bookings history',
                    'response' => $bookings,
                ]);
            } else if ($request->type == 'yearly') {

                $month = $request->input('month'); // format: 'YYYY'

                // Query to get bookings for the specified company within the selected year, grouped by month
                $bookings = DB::table('customer_bookings')
                    ->leftJoin('booking_request_companies', 'customer_bookings.id', '=', 'booking_request_companies.booking_id')
                    ->whereNull('booking_request_companies.driver_id')
                    ->whereYear('customer_bookings.booking_date', $month) // Filter by year
                    ->groupBy(DB::raw('MONTH(customer_bookings.booking_date)')) // Group by month
                    ->select(
                        DB::raw('MONTH(customer_bookings.booking_date) as month'), // Select month
                        DB::raw('COUNT(DISTINCT customer_bookings.id) as total_bookings'), // Count unique bookings per month
                        // DB::raw('SUM(CASE WHEN booking_request_companies.booking_quote_status = "quoted" THEN 1 ELSE 0 END) as quoted_count'),
                        // DB::raw('SUM(CASE WHEN booking_request_companies.booking_quote_status = "un-quoted" THEN 1 ELSE 0 END) as unquoted_count')

                        // Check if there's at least one "quoted" status for each booking_id
                        DB::raw('SUM(CASE WHEN EXISTS (
                                        SELECT 1 FROM booking_request_companies AS brc 
                                        WHERE brc.booking_id = customer_bookings.id AND brc.booking_quote_status = "quoted"
                                    ) THEN 1 ELSE 0 END) as quoted_count'),

                        // Check if all statuses are "un-quoted" for each booking_id
                        DB::raw('SUM(CASE WHEN NOT EXISTS (
                                        SELECT 1 FROM booking_request_companies AS brc 
                                        WHERE brc.booking_id = customer_bookings.id AND brc.booking_quote_status = "quoted"
                                    ) THEN 1 ELSE 0 END) as unquoted_count')
                    );

                if ($auth->fixed_role_id != 1) {
                    $bookings = $bookings->where('booking_request_companies.company_id', $company->id);
                }

                $bookings = $bookings->get();


                // Return the result as a response
                return response()->json([
                    'status' => true,
                    'message' => 'Yearly bookings history',
                    'response' => $bookings,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see dashboard stats',
            ]);
        }
    }

    public function faq(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1)) {

            $validator = Validator::make($request->all(), [

                'section_name_id' => 'required|exists:faq_section_names,id',
                'question' => 'required',
                'answer' => 'required',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP 
            }

            if (is_Null($request->faq_id)) {

                $faq_section = new FAQ();
                $faq_section->section_name_id = $request->section_name_id;
                $faq_section->question = $request->question;
                $faq_section->answer = $request->answer;

                $faq_section->save();

                return response()->json([
                    'status' => true,
                    'message' => 'FAQ added successfully',
                    'response' => $faq_section,

                ]);
            } else {

                $faq_section = FAQ::find($request->faq_id);
                $faq_section->section_name_id = $request->section_name_id;
                $faq_section->question = $request->question;
                $faq_section->answer = $request->answer;
                $faq_section->save();

                return response()->json([
                    'status' => true,
                    'message' => 'FAQ updated successfully',
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

    // public function faq_listing(Request $request)
    // {
    //     $auth = Auth::user();
    //     // Check permissions
    //     if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1)) {

    //         $validator = Validator::make($request->all(), [

    //             'section_name_id' => 'required|exists:faq_section_names,id',

    //         ]);

    //         if ($validator->fails()) {
    //             $errors = $validator->errors()->first(); // Get the first error message

    //             $response = [
    //                 'success' => false,
    //                 'message' => $errors,
    //             ];

    //             return response()->json($response); // Return JSON response with HTTP 
    //         }

    //         $faq_section = FAQ::where('section_name_id',$request->section_name_id)->get();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'FAQ against section name',
    //             'response' => $faq_section,

    //         ]);     


    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You do not have permission to see listing!',
    //         ]);
    //     }
    // }

    public function sec_name_listing(Request $request)
    {

        $faq_section_name = FaqSectionName::all();

        foreach ($faq_section_name as $value) {

            $faq_details = FAQ::where('section_name_id', $value->id)->get();

            $value->faq_details = $faq_details;
        }

        return response()->json([
            'status' => true,
            'message' => 'FAQ against section name',
            'response' => $faq_section_name,

        ]);
    }

    public function company_contact_us(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'issue_type' => 'required|string|max:255',
            'issue_details' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first(); // Get the first error message

            $response = [
                'status' => false,
                'message' => $errors,
            ];

            return response()->json($response); // Return JSON response with HTTP 
        }

        $message = new CompanyContactUs();
        $message->issue_type = $request->issue_type;
        $message->issue_details = $request->issue_details;
        $message->email = $request->email;
        $message->phone = $request->phone;
        $message->save();

        return response()->json([
            'status' => true,
            'message' => 'Your query send to admin!',

        ]);
    }

    public function notice_board(Request $request)
    {

        $auth = Auth::user();
        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1)) {

            if ($auth->fixed_role_id != 1) {

                $validator = Validator::make($request->all(), [
                    'company_id' => 'required',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors()->first(); // Get the first error message

                    $response = [
                        'success' => false,
                        'message' => $errors,
                    ];

                    return response()->json($response); // Return JSON response with HTTP 
                }
                // $company = Company::where('user_id', $request->company_id)->first();
                $user = User::find($request->company_id);
                $company = Company::find($user->company_id);
            }
            $notice_board = Notification::query();

            if ($auth->fixed_role_id != 1) {
                $notice_board = $notice_board->where('company_id', $company->id);
            }

            $notice_board = $notice_board->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => true,
                'message' => 'Notification List',
                'response' => $notice_board,

            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see notice board!',
            ]);
        }
    }

    public function quote_get_against_id(Request $request)
    {

        $auth = Auth::user();
        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1)) {

            $validator = Validator::make($request->all(), [
                'quote_id' => 'required',
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

            return response()->json([
                'status' => true,
                'message' => 'Quote against ID ',
                'response' => $quote,

            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to see quote!',
            ]);
        }
    }

    public function change_payment_status(Request $request)
    {

        $auth = Auth::user();
        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1)) {

            $validator = Validator::make($request->all(), [
                'payment_id' => 'required',
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

            $user_payments = UserPayment::find($request->payment_id);

            if ($user_payments) {

                $user_payments->status = $request->status == 'paid' ? 1 : 0;
                $user_payments->save();

                return response()->json([
                    'status' => true,
                    'message' => 'You have changed the status successfully!',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment cant exist against ID',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to change status!',
            ]);
        }
    }

    public function mark_unavailable_booking(Request $request)
    {

        $auth = Auth::user();

        // return $auth;

        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {


            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required|exists:customer_bookings,id',
                'company_id' => 'required|exists:users,id',

            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first(); // Get the first error message

                $response = [
                    'success' => false,
                    'message' => $errors,
                ];

                return response()->json($response); // Return JSON response with HTTP 
            }
            $user = User::find($request->company_id);
            $company = Company::find($user->company_id);
            // $company = Company::select('id')->where('user_id', $request->company_id)->first();

            $setting = Setting::where('parameter', 'set_hour_for_cancel_booking')->first();

            $hour = $setting->value;
            $currentDateTime = Carbon::now(); // Get the current date and time

            // $cancelableBookings = CustomerBooking::where(function ($query) use ($hour, $currentDateTime) {
            //     $query->whereRaw("CONCAT(booking_date, ' ', booking_time) > ?", [$currentDateTime->addHours($hour)->toDateTimeString()]);
            // })->where('id', $request->booking_id)->where('company_id', $company->id)->first();

            $nextDayTimeLimit = $currentDateTime->addHours($hour)->toDateTimeString();

            $cancelableBookings = CustomerBooking::where(function ($query) use ($nextDayTimeLimit) {
                // Combine booking_date and booking_time as DateTime and compare with $nextDayTimeLimit
                $query->whereRaw("STR_TO_DATE(CONCAT(booking_date, ' ', booking_time), '%Y-%m-%d %h:%i%p') > ?", [$nextDayTimeLimit]);
            })->where('id', $request->booking_id)
                ->where('company_id', $company->id)
                ->first();

            if ($cancelableBookings) {

                if ($cancelableBookings->admin_status == "admin_booking") {

                    $cancelableBookings->admin_status = 'manual';
                    $cancelableBookings->booking_status = 'pending';
                    $cancelableBookings->save();


                    return response()->json([
                        'status' => true,
                        'message' => 'You have successfully set status Un-available!',
                    ]);
                } else {

                    $booking_request_company = BookingRequestCompany::where('booking_id', $request->booking_id)->where('company_id', $company->id)->where('status', 1)->first();

                    if ($booking_request_company) {

                        $booking_request_company->available_status = 'un-available';
                        $booking_request_company->save();

                        $quote = QuoteAgainstRequest::where('booking_id', $request->booking_id)->where('company_id', $company->id)->where('status', 1)->first();
                        $quote->operator_status = 'un-available';
                        $quote->save();

                        $cancelableBookings->admin_status = 'manual';
                        $cancelableBookings->booking_status = 'pending';
                        $cancelableBookings->save();


                        return response()->json([
                            'status' => true,
                            'message' => 'You have successfully set status Un-available!',
                        ]);
                    } else {

                        return response()->json([
                            'status' => false,
                            'message' => 'Request Not Found!',
                        ]);
                    }
                }
            } else {

                return response()->json([
                    'status' => false,
                    'message' => 'Unfortunately, you cannot cancel this booking as the booking time is too close.',
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => 'You have not permission to change status',
            ]);
        }
    }

    public function update_company_password(Request $request)
    {
        $auth = Auth::user();

        // Check permissions
        if (($auth->fixed_role_id == 2 || $auth->fixed_role_id == 1) && ($auth->id == $request->company_id || $auth->fixed_role_id == 1)) {

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',
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


            $user = User::find($request->company_id);
            $user->password =  Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'You have changed password successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to update company password!',
            ]);
        }
    }
}
