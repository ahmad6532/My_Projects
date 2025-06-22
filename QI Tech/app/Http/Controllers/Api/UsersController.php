<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class UsersController extends Controller
{

   
    public function index()
    {
        $users = User::with('position','locationregulatorybody')->paginate(25);

        $data = $users->transform(function ($user) {
            return $this->transform($user);
        });

        return $this->successResponse(
            'Users were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $users->url(1),
                    'last' => $users->url($users->lastPage()),
                    'prev' => $users->previousPageUrl(),
                    'next' => $users->nextPageUrl(),
                ],
                'meta' =>
                [
                    'current_page' => $users->currentPage(),
                    'from' => $users->firstItem(),
                    'last_page' => $users->lastPage(),
                    'path' => $users->resolveCurrentPath(),
                    'per_page' => $users->perPage(),
                    'to' => $users->lastItem(),
                    'total' => $users->total(),
                ],
            ]
        );
    }

    /**
     * Store a new user in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);
            
            $user = User::create($data);

            return $this->successResponse(
			    'User was successfully added.',
			    $this->transform($user)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('position','locationregulatorybody')->findOrFail($id);

        return $this->successResponse(
		    'User was successfully retrieved.',
		    $this->transform($user)
		);
    }

    /**
     * Update the specified user in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);
            
            $user = User::findOrFail($id);
            $user->update($data);

            return $this->successResponse(
			    'User was successfully updated.',
			    $this->transform($user)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified user from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->successResponse(
			    'User was successfully deleted.',
			    $this->transform($user)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }
    
    /**
     * Gets a new validator instance with the defined rules.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Facades\Validator
     */
    protected function getValidator(Request $request)
    {
        $rules = [
            'position_id' => 'required|numeric|min:0|max:10',
            'is_registered' => 'boolean|nullable',
            'registration_no' => 'string|min:2|max:50|nullable',
            'location_regulatory_body_id' => 'nullable',
            'country_of_practice' => 'string|nullable|max:80',
            'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'mobile_no' => 'required|string|min:1|max:20',
            'email' => 'required|max:150|email|unique:users,email',
            'password' => 'required|min:8|max:30', 
        ];

        return Validator::make($request->all(), $rules);
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
            'country_of_practice' => 'string|nullable|max:80',
            'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'mobile_no' => 'required|string|min:1|max:20',
            'email' => 'required|max:150|email|unique:users,email',
            'password' => 'required|min:8|max:30', 
        ];
        
        $data = $request->validate($rules);

        $data['is_registered'] = $request->has('is_registered');

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
            'password' => $user->password,
            'password_updated_at' => $user->password_updated_at,
            'email_verification_key' => $user->email_verification_key,
            'email_verified_at' => $user->email_verified_at,
        ];
    }


}
