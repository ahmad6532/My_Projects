<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    
    public function login(Request $request)
    {
       
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            if($data['type'] == 0) /// Location Signup..... 0=location. 1=user. 2=headoffice
            {
                $location = Location::where('email', $request->email)->first();
            
                //|| !$user->is_active) {
                if (!$location || !Hash::check($request->password, $location->password)) {
                    
                    return $this->successResponse(
                        "The provided credentials are incorrect.",
                        ['invalid' => true]
                    ); 

                }
                else if(!$location->email_verified_at)
                {
                    return $this->successResponse(
                        'Your account is inactive. Please activate your account by clicking on the link which was sent to your registered email.',
                        ['invalid' => true]
                    ); 
                }
                /// logout all other devices ///
                //$location->tokens()->delete();
                // generate new token //
                //$location->token = $location->createToken("Web Browser Device")->plainTextToken;
                Auth::login($location);
                //Auth::guard('location')->login($location);
                
                
                //event(new Registered($location));
                
                return $this->successResponse(
                    'Sign in Successful.',
                    ['token' => csrf_token(), 'session_id' => session()->getId()],
                );
            }
        
    }







    protected function getValidator(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:240',
            'password' => 'required|string|min:1|max:80',
            'type' => 'required|numeric|min:0|max:4'
        ];

        return Validator::make($request->all(), $rules);
    }

    
    protected function getData(Request $request)
    {
        $rules = [
            'email' => 'required|email|max:240',
            'password' => 'required|string|min:1|max:80',
            'type' => 'required|numeric|min:0|max:4'
        ];
        
        $data = $request->validate($rules);
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
            
        ];
    }


}
