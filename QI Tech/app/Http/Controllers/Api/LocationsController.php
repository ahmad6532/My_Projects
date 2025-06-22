<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class LocationsController extends Controller
{

    public function index()
    {
        $locations = Location::with('locationtype','locationpharmacytype','locationregulatorybody')->paginate(25);

        $data = $locations->transform(function ($location) {
            return $this->transform($location);
        });

        return $this->successResponse(
            'Locations were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $locations->url(1),
                    'last' => $locations->url($locations->lastPage()),
                    'prev' => $locations->previousPageUrl(),
                    'next' => $locations->nextPageUrl(),
                ],
                'meta' =>
                [
                    'current_page' => $locations->currentPage(),
                    'from' => $locations->firstItem(),
                    'last_page' => $locations->lastPage(),
                    'path' => $locations->resolveCurrentPath(),
                    'per_page' => $locations->perPage(),
                    'to' => $locations->lastItem(),
                    'total' => $locations->total(),
                ],
            ]
        );
    }

    /**
     * Store a new location in the storage.
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
            
            $location = Location::create($data);

            return $this->successResponse(
			    'Location was successfully added.',
			    $this->transform($location)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified location.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Location::with('locationtype','locationpharmacytype','locationregulatorybody')->findOrFail($id);

        return $this->successResponse(
		    'Location was successfully retrieved.',
		    $this->transform($location)
		);
    }

    /**
     * Update the specified location in the storage.
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
            
            $location = Location::findOrFail($id);
            $location->update($data);

            return $this->successResponse(
			    'Location was successfully updated.',
			    $this->transform($location)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified location from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();

            return $this->successResponse(
			    'Location was successfully deleted.',
			    $this->transform($location)
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
            'location_type_id' => 'required|numeric',
            'location_pharmacy_type_id' => 'nullable|numeric',
            'location_regulatory_body_id' => 'nullable',
            'registered_company_name' => 'required|string|min:1|max:80',
            'trading_name' => 'required|string|min:1|max:80',
            'registration_no' => 'required|string|min:1|max:40',
            'address_line1' => 'required|string|min:1|max:100',
            'address_line2' => 'string|min:1|max:50|nullable',
            'address_line3' => 'string|min:1|max:50|nullable',
            'town' => 'required|string|min:1|max:50',
            'county' => 'required|string|min:1|max:50',
            'country' => 'required|string|min:1|max:80',
            'postcode' => 'required|string|min:1|max:30',
            'telephone_no' => 'required|string|min:1|max:20',
            'email' => 'required|email|max:240',
            'password' => 'required|string|min:1|max:80',
            'ods_name' => 'required|string|min:1|max:80',
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
                'location_type_id' => 'required|numeric',
            'location_pharmacy_type_id' => 'nullable|numeric',
            'location_regulatory_body_id' => 'nullable',
            'registered_company_name' => 'required|string|min:1|max:80',
            'trading_name' => 'required|string|min:1|max:80',
            'registration_no' => 'required|string|min:1|max:40',
            'address_line1' => 'required|string|min:1|max:100',
            'address_line2' => 'string|min:1|max:50|nullable',
            'address_line3' => 'string|min:1|max:50|nullable',
            'town' => 'required|string|min:1|max:50',
            'county' => 'required|string|min:1|max:50',
            'country' => 'required|string|min:1|max:80',
            'postcode' => 'required|string|min:1|max:30',
            'telephone_no' => 'required|string|min:1|max:20',
            'email' => 'required|email|max:240',
            'password' => 'required|string|min:1|max:80',
            'ods_name' => 'requeired|string|min:1|max:80',
        ];
        
        $data = $request->validate($rules);


        return $data;
    }

    /**
     * Transform the giving location to public friendly array
     *
     * @param App\Models\Location $location
     *
     * @return array
     */
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
            'ods_name' => $location->ods_name
            
        ];
    }


}
