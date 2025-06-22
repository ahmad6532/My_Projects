<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeSpokeFormCategory;
use App\Models\DefaultFiveWhysQuestion;
use App\Models\Forms\Form;
use App\Models\HeadOffice;
use App\Models\HeadOfficeRequest;
use App\Models\HeadOfficeUser;
use App\Models\near_miss_manager;
use App\Models\Position;
use App\Models\User;
use CrestApps\CodeGenerator\Support\Str;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Log;

class HeadOfficeRequestController extends Controller
{

    /**
     * head_office request status.
     */
    public function index(){
        $headOfficePendingRequests=HeadOfficeRequest::where('request_type',0)->get();
        $headOfficeApprovedRequests=HeadOfficeRequest::where('request_type',1)->get();
        $headOfficeRejectedRequests=HeadOfficeRequest::where('request_type',2)->get();

        return view('admin.head_offices.requests.index',compact('headOfficePendingRequests','headOfficeApprovedRequests','headOfficeRejectedRequests'));
    }
    /**
     * head_office request status.
     */
    public function request_pending($id)
    {
        $head_office_request=HeadOfficeRequest::findorFail($id);
        $head_office_request->request_type=0;
        $head_office_request->save();
        return redirect()->route('head_office.request.index')
            ->with('success_message', 'Request Updated.');
    }
    /**
     * head_office  status.
     */
    public function request_approved($id)
{
    try {
        DB::beginTransaction();

        // Find the HeadOfficeRequest or fail
        $head_office_request = HeadOfficeRequest::findOrFail($id);
        $head_office_request->request_type = 1;
        $head_office_request->save();
        
        // $check_head = HeadOffice::where('email', $head_office_request->org_email)->exists(); //taskeel changed this
        // dd($check_head);
        // if($check_head == true) {
        //     return redirect()->back()->with('error_message', 'Head Office Already Exists');
        // }
        // Create a new HeadOffice
        $head_office = new HeadOffice();
        $head_office->company_name = $head_office_request->organization;
        $head_office->telephone_no = $head_office_request->telephone_no;
        $head_office->address = $head_office_request->address;
        $head_office->email = $head_office_request->org_email;
        $head_office->sites_count = $head_office_request->sites_count;
        $head_office->staff_count = $head_office_request->staff_count;
        $head_office->weekdays =  $head_office_request->weekdays;
        $head_office->weekends =  $head_office_request->weekends;
        $head_office->save();

        // Create near miss manager
        $near_miss = new near_miss_manager();
        $near_miss->name = 'Near Miss';
        $near_miss->head_office_id = $head_office->id;
        $near_miss->save();

        // Link the approved HeadOffice ID
        $head_office_request->approved_head_office_id = $head_office->id;
        $head_office_request->save();

        // Prepare data for email
        $data = [
            'name' => $head_office_request->first_name,
            'email' => $head_office_request->email,
            'msg' => 'Thank you for your interest in ' . env("APP_NAME") . '. 
                      This is to notify you that your request for Head Office Registration has been Approved. 
                      Now you have to go to the signup page and create an account as a user for yourself, then you can access your head office.'
        ];

        // Create default five whys questions
        for ($i = 0; $i < 5; $i++) {
            $five_whys = new DefaultFiveWhysQuestion();
            $five_whys->head_office_id = $head_office->id;
            $five_whys->question = 'Why?';
            $five_whys->save();
        }

        // Check if the user exists
        $user = User::where('email', $head_office_request->email)->first();
        $lfpse_category = BeSpokeFormCategory::where('name', 'General')->where('reference_id', $head_office->id)->first();
            if(!$lfpse_category)
            {
                // $lfpse_category = new BeSpokeFormCategory();
                // // $lfpse_category->id = 1000; // setting initially
                // $lfpse_category->name = "General";
                // $lfpse_category->reference_type = 'head_office';
                // $lfpse_category->reference_id = $head_office->id;// use HO id here. you can assign to all HOs. #To do
                // $lfpse_category->color = '#000';
                // $lfpse_category->save();
            }
        $form = new Form();
        $form->name = 'NHS LFPSE';
        $form->reference_type = 'head_office';
        $form->reference_id = $head_office->id;
        $form->note = 'The NHS Learning from Patient Safety Events (LFPSE) form is designed to capture and report detailed information on patient safety incidents. It helps healthcare organizations systematically document and analyze events to improve patient care and safety. The form collects comprehensive data, including patient demographics, incident details, and the impact on patient outcomes, facilitating the identification of trends and the development of strategies to prevent future occurrences. This structured reporting enhances the ability to learn from past events and implement effective safety measures.';
        $form->schedule_by_day = json_encode([
            "Monday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
            "Tuesday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
            "Wednesday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
            "Thursday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
            "Friday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
            "Saturday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false],
            "Sunday" => ["active" => false, "times" => ["09:00"],'cutoff'=>"09:00",'do_not_allow_submissions'=>true,'mandatory'=>false]
        ]);
        $json_file_contents = file_get_contents(storage_path('/lfpse/lfpse_form.json'));
        $form->form_json = $json_file_contents;
        $form->submitable_to_nhs_lfpse = true;
        $form->be_spoke_form_category_id = isset($lfpse_category) ? $lfpse_category->id : null;
        $form->save();

        $random_pass = null;
        if (isset($user)) {
            $head_office_user = new HeadOfficeUser();
            $head_office_user->user_id = $user->id;
            $head_office_user->head_office_id = $head_office->id;
            $head_office_user->save();

            // $head_office->makeUserSuperUser($head_office_user);
            $head_office->makeUserSuperUserNew($head_office_user);

            // Handle email verification if needed
            if (!$user->email_verified_at) {
                $data['email_verification_key'] = Str::random(64);
                $user->email_verification_key = $data['email_verification_key'];
                $user->save();

                Mail::send('emails.emailVerification', ['type' => 1, 'token' => $data['email_verification_key'],'name'=>$data['name']], function ($message) use ($data) {
                    $message->to($data['email']);
                    $message->subject(env('APP_NAME') . ' - Verify your email');
                });
            }
        } else {

            $token = Str::random(12);
            $head_office_request->token = $token;
            $head_office_request->save();
            Mail::send('emails.create_user', ['head_office' => $head_office, 'ho_request' => $head_office_request, 'token' => $token], function ($message) use ($data, $head_office, $head_office_request, $token) {
                $message->to($data['email']);
                $message->subject(env('APP_NAME') . ' - Your company account is ready!');
            });            


        }

        if (isset($user)) {
       // Send approval email
            Mail::send('emails.approval_email', ['user' => $head_office_request->first_name . ' ' . $head_office_request->surname, 'company' => $head_office->company_name,'password'=>$random_pass], function ($message) use ($data) {
                $message->to($data['email']);
                $message->subject(env('APP_NAME') . ' - Registration Request Approved');
            });
        }

        DB::commit();
        return redirect()->route('head_office.request.index')->with('success_message', 'Request Updated.');

    } catch (\Exception $exception) {
        DB::rollBack();
        // Log the error for debugging
        Log::error('Error in request_approved: ' . $exception->getMessage());
        dd($exception);

        return redirect()->route('head_office.request.index')->withErrors(['error' => 'An error occurred while processing this request.']);
    }
}

    /**
     * head_office toggle status.
     */
    public function request_rejected($id)
    {
        try {


        $head_office_request=HeadOfficeRequest::findorFail($id);
        $head_office_request->request_type=2;
        $head_office_request->save();

        // On Successful Response, Send an Email //
        $data['email']=$head_office_request->email;
        Mail::send('emails.general_email', ['heading' => 'Rejection of Registration Request',
            'msg' => 'Thank you for your interest in ' . env("APP_NAME") . '. 
            This is to notify you that your request for Head Office Registration has been Rejected.'], function($message) use($data){
            $message->to($data['email']);
            $message->subject(env('APP_NAME') . ' - Registration Request Status');
        });
        return redirect()->route('head_office.request.index')
            ->with('success_message', 'Request Updated.');
    }
    catch (\Exception $exception){
        return redirect()->route('head_office.request.index')
            ->withErrors(['error'=> 'error caused upon processing this request']);

        }
    }

}
