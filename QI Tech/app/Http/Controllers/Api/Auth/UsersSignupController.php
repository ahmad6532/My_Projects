<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Models\HeadOffice;
use App\Models\HeadOfficeRequest;
use App\Models\Headoffices\Users\HeadOfficeUserInvite;
use App\Models\Headoffices\Users\UserProfileAssign;
use App\Models\HeadOfficeUser;
use App\Models\Location;
use App\Models\User;
use App\Models\UserLoginSession;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\Position;
use App\Models\LocationRegulatoryBody;
use CrestApps\CodeGenerator\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\UserRole;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class UsersSignupController extends Controller
{
    private $countries = [
        "England",
        "Scotland",
        "Wales",
        "Channel Islands",
        "Northern Ireland",
        "Republic of Ireland"
    ];
    
    public function register(Request $request)
    {

        
        try {
            $validator = $this->getValidator($request);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);
            $head_office = null;
            $head_office_request = null;
            //Further Validations //
            $lt = Position::findOrFail($data['position_id']);
            // $lpt_id = null;
            // if($lt->id == 1)
            // {
            //     $lpt = LocationPharmacyType::findOrFail($data['location_pharmacy_type_id']);
            //     $lpt_id = $lpt->id;
            // }   
            if(isset($data['location_regulatory_body_id']))
            {  
                $lrb = LocationRegulatoryBody::findOrFail($data['location_regulatory_body_id']);
            }

            /// Create an Email activation secret in $data //
            $data['email_verification_key'] = Str::random(64);
            
            DB::beginTransaction();
            if($request->share_case){
                $user = User::where('email',$data['email'])->first();
                $user->position_id = $request->position_id;
                $user->is_registered = $request->is_registered;
                $user->location_regulatory_body_id = null;
                $user->country_of_practice = $request->country_of_practice;
                $user->first_name = $request->first_name;
                $user->surname = $request->surname;
                $user->mobile_no = $request->mobile_no;
                $user->password = hash::make($request->password);

                $user->save();
            }else{
                $user = User::create($data);
            }

            // Creating Roles 
            if($data['user_roles'])
            {
                $now = Carbon::now();
                $to_insert = [];
                foreach($data['user_roles'] as $ur)
                {
                    $temp = Role::findOrFail($ur);
                    $to_insert[] = [
                        'user_id' => $user->id,
                        'role_id' => $ur,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
                UserRole::insert($to_insert);
            }

            if($request->ho_token != ''){
                $head_office_request=HeadOfficeRequest::where([['email',$user->email],['request_type',1]])->where('token',$request->ho_token)->first();
                if(!isset($head_office_request)){
                    return $this->errorResponse('Invalid Company Invite!');
                }
                if($head_office_request){
                    $head_office = HeadOffice::find($head_office_request->approved_head_office_id);
                    if(!isset($head_office)){
                        return $this->errorResponse('Invalid Company Invite!');
                    }
                    $head_office_user = new HeadOfficeUser();
                    $head_office_user->user_id = $user->id;
                    $head_office_user->head_office_id = $head_office_request->approved_head_office_id;
                    $head_office_user->save();
                    $user->selected_head_office_id = $head_office_request->approved_head_office_id;
                    $user->save();
                    
                    // $head_office->makeUserSuperUser($head_office_user);
                    $head_office->makeUserSuperUserNew($head_office_user);
                }
            }

            // only do this if invite flag is true
            if($request->invite == true && $request->ho_token == ''){
                $head_office_invite = HeadOfficeUserInvite::where([['email',$user->email],['invited_by_type','head_office']])->where('token',$request->invite_token)->first();
                if(isset($head_office_invite)){
                    if(!isset($head_office_invite->expires_at)){
                        return $this->errorResponse('Invite Cancelled!');
                    }
                    $expires = Carbon::parse($head_office_invite->expires_at);
                    if($expires < Carbon::now()){
                        return $this->errorResponse('Invite Expired!');
                    }
                    $head_office_user = new HeadOfficeUser;
                    $head_office_user->user_id = $user->id;
                    $head_office_user->head_office_id = $head_office_invite->head_office_id;
                    $head_office_user->position = $head_office_invite->head_office_position;
                    $head_office_user->save();
                    $head_office_invite->delete();
                    
                    $user->selected_head_office_id = $head_office_invite->head_office_id;
                    $user->save();
    
                    $access_right_assign  = new UserProfileAssign();
                    $access_right_assign->user_profile_id = $head_office_invite->head_office_user_profile_id;
                    $access_right_assign->head_office_user_id = $head_office_user->id;
                    $access_right_assign->save();
                    Mail::raw("Dear {$user->name},\n\nYou have been successfully added to the {$head_office_user->headOffice->company_name} as company user. Welcome aboard!\n\nBest Regards,\n{$head_office_user->headOffice->company_name}", function($message) use ($user,$head_office_user) {
                        $message->to($user->email)
                                ->subject('You have been added to the ' . $head_office_user->headOffice->company_name .' as company user');
                    });
                }else{
                    return $this->errorResponse('Invalid Invite Token');
                }
            }
            //event(new Registered($location));
            
            // On Successful Response, Send an Email //
            Mail::send('emails.emailVerification', ['type' => 2, 'token' => $data['email_verification_key'],'first_name'=>$data['first_name']], function($message) use($data){
                $message->to($data['email']);
                $message->subject(env('APP_NAME') . ' - Verify your email');
            });

            $emails = [
                ['email' => 'info@futuredevsolutions.com', 'name' => 'Usman'],
                ['email' => 'taskeel@qi-tech.co.uk', 'name' => 'Taskeel']
            ];
            
            foreach ($emails as $recipient) {
                Mail::raw('A new User account has been created by ' . $data['first_name'] . ' (' . $data['email'] . ')', function($message) use($recipient) {
                    $message->to($recipient['email']);
                    $message->subject(env('APP_NAME') . ' - New User Account Created');
                });
            }
            
            // dd($user);
            if($request->ho_token != '' && isset($head_office_request) && isset($head_office)){
                $user->email_verified_at = Carbon::now();
                $user->save();
                $temporaryToken = Str::random(40);

            
            }
            DB::commit();


            if($request->ho_token != '' && isset($head_office_request) && isset($head_office)){
                // Store the token in the cache for 5 minutes
                Cache::put('login_token_' . $temporaryToken, $user->id, now()->addMinutes(5));
                return $this->successResponse(
                    'Sign up Successful.',
                    ['success' => true,'route' => (string) $temporaryToken]
                );
            }else{
                
                return $this->successResponse(
                    'Sign up Successful.',
                    ['success' => true]
                );
            }
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->errorResponse('Unexpected error occurred while trying to process your request.' . $exception->getMessage());
        }
    }



    public function email_exists($email)
    {
        $record = User::where('email', $email)->first();
        if($record)
        {
            return $this->successResponse(
			    'Email exists.',
			    ['exists' => true]
			); 
        }
        return $this->successResponse(
            'Email does not exist.',
            ['exists' => false]
        ); 
    }








   
    protected function getValidator(Request $request)
    {
        $rules = [
            'dob' => 'required|date|before_or_equal:today',
            'position_id' => 'required|numeric|min:0|max:10',
            'is_registered' => 'boolean|nullable',
            'registration_no' => 'string|min:2|max:50|nullable',
            'location_regulatory_body_id' => 'nullable',
            'country_of_practice' => 'required|numeric|min:0|max:6',
            'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'mobile_no' => 'required|string|min:1|max:20',
            'password' => 'required|min:8|max:30',
            'user_roles' => 'required|array',
            'user_roles.*' => 'numeric|min:0|max:5',
            'invite' => 'required|boolean',
            'share_case' => 'required|boolean',
            'invite_token' => 'nullable|string',
            'ho_token' => 'nullable|string',
        ];

        $host = request()->getHost(); 
        $subdomain = explode('.', $host)[0];
        if (config('app.env') !== 'local' && !str_contains($request->getHost(), 'dev')) {
            $rules['email'] = $request->share_case
                ? 'required|email|indisposable'
                : 'required|email|indisposable|unique:users,email';
        } else {
            // In local environment or dev subdomain, allow disposable emails
            $rules['email'] = 'required|email';
        }

        $messages = [
            'position_id.required' => 'Position ID is required.',
            'position_id.numeric' => 'Position ID must be a number.',
            'position_id.min' => 'Position ID must be at least 0.',
            'position_id.max' => 'Position ID may not be greater than 10.',
            'is_registered.boolean' => 'Is Registered must be true or false.',
            'registration_no.string' => 'Registration No must be a string.',
            'registration_no.min' => 'Registration No must be at least 2 characters.',
            'registration_no.max' => 'Registration No may not be greater than 50 characters.',
            'country_of_practice.required' => 'Country of Practice is required.',
            'country_of_practice.numeric' => 'Country of Practice must be a number.',
            'country_of_practice.min' => 'Country of Practice must be at least 0.',
            'country_of_practice.max' => 'Country of Practice may not be greater than 6.',
            'first_name.required' => 'First Name is required.',
            'first_name.string' => 'First Name must be a string.',
            'first_name.min' => 'First Name must be at least 1 character.',
            'first_name.max' => 'First Name may not be greater than 50 characters.',
            'surname.required' => 'Surname is required.',
            'surname.string' => 'Surname must be a string.',
            'surname.min' => 'Surname must be at least 1 character.',
            'surname.max' => 'Surname may not be greater than 50 characters.',
            'mobile_no.required' => 'Mobile No is required.',
            'mobile_no.string' => 'Mobile No must be a string.',
            'mobile_no.min' => 'Mobile No must be at least 1 character.',
            'mobile_no.max' => 'Mobile No may not be greater than 20 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email has already been taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password may not be greater than 30 characters.',
            'dob.required' => 'Date of Birth is required.',
            'dob.date' => 'Date of Birth must be a valid date.',
            'dob.before_or_equal' => 'Date of Birth must be before today',
        ];

        return Validator::make($request->all(), $rules,$messages);
    }

    
    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request 
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'position_id' => 'required|numeric|min:0|max:10',
            'is_registered' => 'boolean|nullable',
            'registration_no' => 'string|min:2|max:50|nullable',
            'location_regulatory_body_id' => 'nullable',
            'country_of_practice' => 'required|numeric|min:0|max:6',
            'first_name' => 'required|string|min:1|max:50',
            'dob' => 'required|date|before_or_equal:today',
            'surname' => 'required|string|min:1|max:50',
            'mobile_no' => 'required|string|min:1|max:20',
            'email' => $request->share_case ? 'required|email' : 'required|email|unique:users,email',
            'password' => 'required|min:8|max:30', 
        ];
        
        $data = $request->validate($rules);

        $data['position_id']++;
        
        if($data['location_regulatory_body_id'] > -1)
            $data['location_regulatory_body_id']++;
        else
            unset($data['location_regulatory_body_id']);
        
        if($data['country_of_practice'] > -1)
            $data['country_of_practice'] = $this->countries[$data['country_of_practice']];


        $data['password'] = Hash::make($data['password']);
        $data['is_registered'] = $request->has('is_registered');

        $data['user_roles'] = $request->user_roles;

        return $data;
    }

    /**
     * Transform the giving user to public friendly array
     *
     * @param App\Models\User $user
     *
     * @return array
     */
    protected function transform(User $user)
    {
        return [
            'id' => $user->id,
            'position_id' => optional($user->position)->name,
            'is_registered' => ($user->is_registered) ? 'Yes' : 'No',
            'registration_no' => $user->registration_no,
            'location_regulatory_body_id' => optional($user->locationRegulatoryBody)->name,
            'country_of_practice' => $user->country_of_practice,
            'first_name' => $user->first_name,
            'surname' => $user->surname,
            'mobile_no' => $user->mobile_no,
            'email' => $user->email,
            'password_updated_at' => $user->password_updated_at,

        ];
    }


}
