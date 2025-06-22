<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class LocationsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'location_type_id' => 'required|numeric',
            'location_pharmacy_type_id' => 'nullable|numeric',
            'location_regulatory_body_id' => 'required|exists:location_regulatory_bodies,id',
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
            'email' => 'required|email|max:240|unique:locations,email,' . $this->location,
            'password' => 'string|min:8|max:80|nullable'
        ];

        return $rules;
    }
    
    /**
     * Get the request's data from the request.
     *
     * 
     * @return array
     */
    public function getData()
    {
        $data = $this->only(['location_type_id', 'location_pharmacy_type_id', 'location_regulatory_body_id', 'registered_company_name', 'trading_name', 'registration_no', 'address_line1', 'address_line2', 'address_line3', 'town', 'county', 'country', 'postcode', 'telephone_no', 'email', 'password']);

        return $data;
    }

}