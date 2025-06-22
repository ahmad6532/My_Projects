<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\databases\DispensingDoctor;
use App\Models\databases\GPhCLocation;
use App\Models\databases\GPhCPharmacist;
use App\Models\databases\GPhCTechnician;
use App\Models\databases\NorthernIrelandList;
use App\Models\databases\PSIPharmacy;
use App\Models\HeadOffice;
use App\Models\HeadOfficeLocation;
use App\Models\Location;
use App\Models\UserName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\LocationType;
use App\Models\LocationPharmacyType;
use App\Models\LocationRegulatoryBody;
use Illuminate\Support\Facades\Hash;
use CrestApps\CodeGenerator\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LocationSignupController extends Controller
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
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:locations,username',
            'email' => 'required|email|unique:locations,email',
            // other validation rules here
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all());
        }

        $data = $this->getData($request);

        //Further Validations //
        $lt = LocationType::findOrFail($data['location_type_id']);
        $lpt_id = null;
        if($lt->id == 1)
        {
            $lpt = LocationPharmacyType::findOrFail($data['location_pharmacy_type_id']);
            $lpt_id = $lpt->id;
        }
        $lrb = LocationRegulatoryBody::findOrFail($data['location_regulatory_body_id']);

        $plainPassword = $request->input('password');
        $data['password'] = Hash::make($request->input('password'));
        $company_name_parts = explode('.', $data['username']);
        $company_name = end($company_name_parts);
        $rescue_head_office = HeadOffice::where('company_name', $company_name)->first();

        try{
            DB::beginTransaction();
            $location = Location::create($data);
            $location->markEmailAsVerified();
            $user = Auth::guard('web')->user();
            $head_office = isset($user) ? $user->selected_head_office: $rescue_head_office;
            $head_office_location = new HeadOfficeLocation();
            $head_office_location->head_office_id = $head_office->id;
            $head_office_location->location_id = $location->id;
            $head_office_location->save();

            $emails = [
                ['email' => 'info@futuredevsolutions.com', 'name' => 'Usman'],
                ['email' => 'taskeel@qi-tech.co.uk', 'name' => 'Taskeel']
            ];

            foreach ($emails as $recipient) {
                Mail::raw('A new Location account has been created by ' . $data['username'] . ' (' . $data['email'] . ')', function($message) use($recipient) {
                    $message->to($recipient['email']);
                    $message->subject(env('APP_NAME') . ' - New Location Account Created');
                });
            }
            // Send email to the user with their account information
            Mail::send('emails.locationAccountCreated', [
                'username' => $data['username'],
                'password' => $plainPassword,
            ], function ($message) use ($data) {
                $message->to($data['email']);
                $message->subject(env('APP_NAME') . ' - Location Account Created');
            });

            DB::commit();



        }catch(Exception $e){
            DB::rollBack();
            return $this->errorResponse('Unexpected error occurred while trying to process your request.' . $e->getMessage());
        }
        return $this->successResponse(
            'Sign up Successful.',
            ['success' => true]
        );
    } catch (Exception $exception) {
        return $this->errorResponse('Unexpected error occurred while trying to process your request.' . $exception->getMessage());
    }
}

    public function email_exists($email)
    {
        $record = Location::where('email', $email)->first();
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

    public function username_exists($username)
{
    // Preprocess the username: remove spaces and convert to lowercase
    $normalizedUsername = strtolower(str_replace(' ', '', $username));

    $record = Location::where('username', $normalizedUsername)->first();

    if ($record) {
        return $this->successResponse(
            'Username exists.',
            ['exists' => true]
        );
    }

    return $this->successResponse(
        'Username does not exist.',
        ['exists' => false]
    );
}


    public function verifyAccount($token)
    {
        $verifyUser = Location::where('email_verification_key', $token)->first();
  
        $message = 'Sorry your account cannot be identified.';
  
        if(!is_null($verifyUser) ){
            $user = $verifyUser;
            if(!$user->email_verified_at) { // also check for expiry //
                $user->email_verified_at = Carbon::now();
                $verifyUser->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }
  
      return redirect()->route('login')->with('message', $message);
    }

    public function get_location_details($type,$value){
        $location=[];
        switch ($type)
        {
            case '0':
                $data=GPhCLocation::where('postcode',$value)->first();
                if($data){
                    $location=[
                        "registration_no" => $data->gphc_registration_number,
                        "owner_name"=>$data->owner_name,
                        "trading_name"=> $data->trading_name,
                        "address_line1"=> $data->address_line_1,
                        "address_line2" => $data->address_line_2,
                        "address_line3" => $data->address_line_3,
                        "town" => $data->town,
                        "county" => $data->county,
                        "postcode" => $data->postcode,
                    ];
                }
                break;
            case '1':
                $data=NorthernIrelandList::where('postcode',$value)->first();
                if($data){
                    $location=[
                        "registration_no" => $data->pharmacy_registration_number,
                        "owner_name"=>$data->owner_name,
                        "trading_name"=> $data->trading_name,
                        "address_line1"=> $data->address_line_1,
                        "address_line2" => $data->address_line_2,
                        "town" => $data->town,
                        "postcode" => $data->postcode,
                    ];
                }
                break;
            case '2':
                $data=PSIPharmacy::where('trading_name',$value)->first();
                if($data){
                    $location=[
                        "registration_no" => $data->psi_registration_number,
                        "owner_name"=>$data->rpb_owner,
                        "trading_name"=> $data->trading_name,
                        "address_line1"=> $data->street_1,
                        "address_line2" => $data->street_2,
                        "address_line3" => $data->street_3,
                        "town" => $data->town,
                        "county" => $data->county,
                        "postcode" => $data->postcode,
                    ];
                }
                break;
            case '-1':
                $data=DispensingDoctor::where('postcode',$value)->first();
                if($data){
                    $location=[
                        "registration_no" => $data->practice_code,
                        "trading_name"=> $data->practice_name,
                        "address_line1"=> $data->address_1,
                        "address_line2" => $data->address_2,
                        "address_line3" => $data->address_3,
                        "town" => $data->address_4,
                        "postcode" => $data->postcode,
                    ];
                }
                break;
        }

        return $this->successResponse(
            'response',
            [
                'location' => $location,
                'found'=>($location) == true ]
        );

    }

    public function get_user_details($type,$value){
        $user=[];
        switch ($type)
        {
            case '5':
                $data=GPhCPharmacist::where('gphc_registration_number',$value)->first();
                if($data){
                    $user=[
                        "registration_no" => $data->gphc_registration_number,
                        "surname"=>$data->surname,
                        "firstname"=> $data->forenames,
                    ];
                }
                break;
            case '7':
                $data=GPhCTechnician::where('gphc_registration_number',$value)->first();
                if($data){
                    $user=[
                        "registration_no" => $data->gphc_registration_number,
                        "surname"=>$data->surname,
                        "firstname"=> $data->forenames,
                    ];
                }
                break;
        }

        return $this->successResponse(
            'response',
            [
                'user' => $user,
                'found'=>($user) == true ]
        );

    }

    protected function getValidator(Request $request)
    {
        $rules = [
            'location_type_id' => 'required|numeric',// The data on clientside starts from 0 // where as our ids starts from 1
            'location_pharmacy_type_id' => 'nullable|numeric',
            'location_regulatory_body_id' => 'required|numeric',
            'registered_company_name' => 'required|string|min:1|max:80',
            'trading_name' => 'required|string|min:1|max:80',
            'username'=> 'required|string|min:1|max:80',
            'registration_no' => 'required|string|min:1|max:40',
            'address_line1' => 'required|string|min:1|max:100',
            'address_line2' => 'string|min:1|max:50|nullable',
            'address_line3' => 'string|min:1|max:50|nullable',
            'town' => 'required|string|min:1|max:50',
            'county' => 'required|string|min:1|max:50',
            'country' => 'required|numeric|min:0|max:6',
            'postcode' => 'required|string|min:1|max:30',
            'telephone_no' => 'required|string|min:1|max:20',
            'email' => 'indisposable|required|email|max:240|unique:locations,email',
            'password' => 'required|string|min:1|max:80',
            'ods_name' => 'required|string|min:1|max:80',
        ];

        $host = request()->getHost(); 
        $subdomain = explode('.', $host)[0];
        if (isset($subdomain) && $subdomain !== 'dev' && $subdomain == 'qi-tech') {
            $rules['email'] = $request->share_case
                ? 'required|email|indisposable'
                : 'required|email|indisposable|unique:locations,email';
        } else {
            // In local environment or dev subdomain, allow disposable emails
            $rules['email'] = 'required|email';
        }

        return Validator::make($request->all(), $rules);
    }

    protected function getData(Request $request)
    {
        $rules = [
            'location_type_id' => 'required|numeric',
            'location_pharmacy_type_id' => 'nullable|numeric',
            'location_regulatory_body_id' => 'required|numeric',

            'registered_company_name' => 'required|string|min:1|max:80',
            'trading_name' => 'required|string|min:1|max:80',
            'location_code' => 'required|string|min:2|max:100',
            'username'=> 'required|string|min:1|max:80',
            'registration_no' => 'required|string|min:1|max:40',
            'address_line1' => 'required|string|min:1|max:100',
            'address_line2' => 'string|min:1|max:50|nullable',
            'address_line3' => 'string|min:1|max:50|nullable',
            'town' => 'required|string|min:1|max:50',
            'county' => 'required|string|min:1|max:50',
            'country' => 'required|numeric|min:0|max:6',
            'postcode' => 'required|string|min:1|max:30',
            'telephone_no' => 'required|string|min:1|max:20',

            'email' => 'required|email|max:240',
            'password' => 'required|string|min:1|max:80',
            'ods_name' => 'required|string|min:1|max:80',
        ];
        
        $data = $request->validate($rules);
        $data['location_type_id']++;
        if($data['location_type_id'] == 1)
            $data['location_pharmacy_type_id']++;
        else
            unset($data['location_pharmacy_type_id']);
        $data['location_regulatory_body_id']++;
        if($data['location_regulatory_body_id'] == 0)
            unset($data['location_regulatory_body_id']); // removing it because dispensing doctor's practice don't require it.
        
        $data['password'] = Hash::make($data['password']);

        // Normalize the username (convert to lowercase and remove spaces)
            $normalizedUsername = strtolower(str_replace(' ', '', $data['username']));

            // Check if the username already exists in the database
            $existingUser = Location::where('username', $normalizedUsername)->first();
            if ($existingUser) {
                // If the username exists, throw an error
                throw new \Illuminate\Validation\ValidationException(
                    \Validator::make($request->all(), [
                        'username' => 'unique:locations,username'
                    ], [
                        'username.unique' => 'The username has already been taken.'
                    ])
                );
            }

            $data['username'] = $normalizedUsername;


        $data['country'] = $this->countries[$data['country']];

        return $data;
    }

    protected function transform(Location $location)
    {
        return [
            'id' => $location->id,
            'location_type_id' => optional($location->locationType)->id,
            'location_pharmacy_type' => optional($location->locationPharmacyType)->id,
            'location_regulatory_body_id' => optional($location->locationRegulatoryBody)->name,
            'registered_company_name' => $location->registered_company_name,
            'trading_name' => $location->trading_name,
            'registration_no' => $location->registration_no,
            'address_line1' => $location->address_line1,
            'address_line2' => $location->address_line2,
            'address_line3' => $location->address_line3,
            'town' => $location->town,
            'county' => $location->county,
            'country' => $location->country,
            'postcode' => $location->postcode,
            'telephone_no' => $location->telephone_no,
            'email' => $location->email,
            'password' => $location->password,
            
        ];
    }


}
