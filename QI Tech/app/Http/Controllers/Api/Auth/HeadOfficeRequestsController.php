<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\HeadOfficeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class HeadOfficeRequestsController extends Controller
{

    /**
     * Display a listing of the assets.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $headOfficeRequests = HeadOfficeRequest::paginate(25);

        $data = $headOfficeRequests->transform(function ($headOfficeRequest) {
            return $this->transform($headOfficeRequest);
        });

        return $this->successResponse(
            'Head Office Requests were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $headOfficeRequests->url(1),
                    'last' => $headOfficeRequests->url($headOfficeRequests->lastPage()),
                    'prev' => $headOfficeRequests->previousPageUrl(),
                    'next' => $headOfficeRequests->nextPageUrl(),
                ],
                'meta' =>
                [
                    'current_page' => $headOfficeRequests->currentPage(),
                    'from' => $headOfficeRequests->firstItem(),
                    'last_page' => $headOfficeRequests->lastPage(),
                    'path' => $headOfficeRequests->resolveCurrentPath(),
                    'per_page' => $headOfficeRequests->perPage(),
                    'to' => $headOfficeRequests->lastItem(),
                    'total' => $headOfficeRequests->total(),
                ],
            ]
        );
    }

    /**
     * Store a new head office request in the storage.
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
            
            $headOfficeRequest = HeadOfficeRequest::create($data);

            return $this->successResponse(
			    'Head Office Request was successfully added.',
			    $this->transform($headOfficeRequest)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified head office request.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $headOfficeRequest = HeadOfficeRequest::findOrFail($id);

        return $this->successResponse(
		    'Head Office Request was successfully retrieved.',
		    $this->transform($headOfficeRequest)
		);
    }

    /**
     * Update the specified head office request in the storage.
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
            
            $headOfficeRequest = HeadOfficeRequest::findOrFail($id);
            $headOfficeRequest->update($data);

            return $this->successResponse(
			    'Head Office Request was successfully updated.',
			    $this->transform($headOfficeRequest)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified head office request from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $headOfficeRequest = HeadOfficeRequest::findOrFail($id);
            $headOfficeRequest->delete();

            return $this->successResponse(
			    'Head Office Request was successfully deleted.',
			    $this->transform($headOfficeRequest)
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
            'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'organization' => 'required|string|min:1|max:80|nullable',
            'position' => 'required|string|min:1|max:80|nullable',
            'email' => 'required|email|min:1|max:140|unique:head_office_requests,email',
            'telephone_no' => 'required|string|min:1|max:20|nullable', 
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
                'first_name' => 'required|string|min:1|max:50',
            'surname' => 'required|string|min:1|max:50',
            'organization' => 'required|string|min:1|max:80|nullable',
            'position' => 'required|string|min:1|max:80|nullable',
            'email' => 'required|email|min:1|max:140|unique:head_office_requests,email',
            'telephone_no' => 'required|string|min:1|max:20|nullable', 
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
            'email_verified_at' => $headOfficeRequest->email_verified_at,
            'email_verification_key' => $headOfficeRequest->email_verification_key,
        ];
    }


}
