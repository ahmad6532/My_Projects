<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\HeadOffice;
use App\Models\HeadOfficeRequest;
use App\Models\LfpseOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use CrestApps\CodeGenerator\Support\Str;
use Illuminate\Support\Facades\Mail;

class HeadOfficeSignupController extends Controller
{



    public function register(Request $request)
    {
        try {
            $validator = $this->getValidator($request);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }
            $data = $this->getData($request);
            $data['request_type']=0;

            /// Create an Email activation secret in $data //
            $data['email_verification_key'] = Str::random(64);
            
            // Instantiate the model
            $location = new HeadOfficeRequest();

            // Assign data to the model's attributes
            $location->first_name = $data['first_name'];
            $location->surname = $data['surname'];
            $location->organization = $data['organization'];
            $location->position = $data['position'];
            $location->email = $request->user_email            ;
            $location->telephone_no = $data['telephone_no'];
            $location->user_telephone_no = $request->user_telephone_no;
            $location->email_verification_key = $data['email_verification_key'];
            $location->request_type = $data['request_type'];
            $location->address = $data['address'];
            $location->org_email = $data['email'];
            $location->sites_count = $request->sites_count;;
            $location->staff_count = $request->staff_count;
            $location->weekdays = json_encode($request->weekdays);
            $location->weekends = json_encode($request->weekends);

            // Save the model to the database
            $location->save();


            //event(new Registered($location));
            
            // On Successful Response, Send an Email //
            Mail::send('emails.register_company_mail', ['user'=>$data['first_name'].' '.$data['surname'], 'first_name' => $data['first_name']], function($message) use($data) {
            $message->to($data['email']);
            $message->subject(env('APP_NAME') . ' -  We\'ve received your request');
            });
            $emails = [
                ['email' => 'info@futuredevsolutions.com', 'name' => 'Usman'],
                // ['email' => 'taskeel@qi-tech.co.uk', 'name' => 'Taskeel']
            ];
            
            foreach ($emails as $recipient) {
                Mail::raw('A new Comapny account has been created by ' . $data['first_name'] . ' (' . $data['email'] . ')', function($message) use($recipient) {
                    $message->to($recipient['email']);
                    $message->subject(env('APP_NAME') . ' - New Company Account Created');
                });
            }


            return $this->successResponse(
			    'Request submitted successfully.',
			    ['sucess' => true]
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.' . $exception->getMessage());
        }
    }

    public function email_exists($email)
    {
        $record = HeadOffice::where('email', $email)->first();
        if(!isset($record)){
            $record = HeadOfficeRequest::where('email', $email)->first();
        }
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
            'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'organization' => 'required|string|min:1|max:80|nullable',
            'position' => 'required|string|min:1|max:80|nullable',
            'telephone_no' => 'required|string|min:1|max:20|nullable', 
            'user_telephone_no' => 'required|string|min:1|max:20|nullable', 
            'address' => 'required|string|min:1|max:100',
            'sites_count' => 'required|numeric|min:1|digits_between:1,5',
            'staff_count' => 'required|string|in:1-5,6-30,31-100,100+',
            'weekdays' => 'required|array',
            'weekdays.morning' => 'boolean',
            'weekdays.afternoon' => 'boolean',
            'weekdays.evening' => 'boolean',
            'weekends' => 'required|array',
            'weekends.morning' => 'boolean',
            'weekends.afternoon' => 'boolean',
            'weekends.evening' => 'boolean',
        ];

        if (config('app.env') !== 'local' && !str_contains($request->getHost(), '.dev')) {
            // In non-local environments and non-dev subdomains, apply indisposable and unique rules
            $rules['email'] = 'required|email|indisposable|min:1|max:140';
            $rules['user_email'] = 'required|email|indisposable|min:1|max:140';
        } else {
            // In local environment or dev subdomain, allow disposable emails
            $rules['email'] = 'required|email|min:1|max:140';
            $rules['user_email'] = 'required|email|min:1|max:140';
        }
        
        $messages = [
            'sites_count' => 'only 5 digits allowed for sites.',
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a string.',
            'first_name.min' => 'First name cant be empty',
            'first_name.max' => 'First name may not be greater than 50 characters.',
            
            'surname.required' => 'Surname is required.',
            'surname.string' => 'Surname must be a string.',
            'surname.min' => 'Surname cant be empty',
            'surname.max' => 'Surname may not be greater than 50 characters.',
            
            'organization.required' => 'Organization is required.',
            'organization.string' => 'Organization must be a string.',
            'organization.min' => 'Organization cant be empty',
            'organization.max' => 'Organization may not be greater than 80 characters.',
            'organization.nullable' => 'Organization can be null.',
            
            'position.required' => 'Position is required.',
            'position.string' => 'Position must be a string.',
            'position.min' => 'Position cant be empty',
            'position.max' => 'Position may not be greater than 80 characters.',
            'position.nullable' => 'Position can be null.',
            
            'email.required' => 'Email address is required.',
            'email.email' => 'Email address must be a valid email format.',
            'email.min' => 'Email address cant be empty.',
            'email.max' => 'Email address may not be greater than 140 characters.',
            'email.unique' => 'Email address has already been taken.',
            
            'telephone_no.required' => 'Telephone number is required.',
            'telephone_no.string' => 'Telephone number must be a string.',
            'telephone_no.min' => 'Telephone number cant be empty',
            'telephone_no.max' => 'Telephone number may not be greater than 20 characters.',
            'telephone_no.nullable' => 'Telephone number can be null.',
            
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.min' => 'Address cant be empty',
            'address.max' => 'Address may not be greater than 100 characters.',
        ];

        return Validator::make($request->all(), $rules,$messages);
    }

    
    protected function getData(Request $request)
    {
        $rules = [
                'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'organization' => 'required|string|min:1|max:80|nullable',
            'position' => 'required|string|min:1|max:80|nullable',
            'email' => 'required|email|min:1|max:140',
            'telephone_no' => 'required|string|min:1|max:20|nullable', 
            'address' => 'required|string|min:1|max:100',
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

    /**
     * Transform the giving head office request to public friendly array
     *
     * @param App\Models\HeadOfficeRequest $headOfficeRequest
     *
     * @return array
     */
    protected function transform(HeadOfficeRequest $headOfficeRequest)
    {
        return [
            'id' => $headOfficeRequest->id,
            'first_name' => $headOfficeRequest->first_name,
            'surname' => $headOfficeRequest->surname,
            'organization' => $headOfficeRequest->organization,
            'position' => $headOfficeRequest->position,
            'email' => $headOfficeRequest->email,
            'telephone_no' => $headOfficeRequest->telephone_no,
            'address' => $headOfficeRequest->address,
            'email_verified_at' => $headOfficeRequest->email_verified_at,
            'email_verification_key' => $headOfficeRequest->email_verification_key,
        ];
    }


}
