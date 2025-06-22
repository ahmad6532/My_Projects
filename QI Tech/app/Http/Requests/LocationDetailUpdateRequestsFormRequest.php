<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class LocationDetailUpdateRequestsFormRequest extends FormRequest
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
            'trading_name' => 'required|string|min:1|max:80',
            'address_line1' => 'required|string|min:1|max:80',
            'address_line2' => 'string|min:1|max:50|nullable',
            'address_line3' => 'string|min:1|max:50|nullable',
            'registration_no' => 'string|min:1|max:50|nullable',
            'telephone_no' => 'min:1|max:20|nullable|string',
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
        $data = $this->only(['trading_name', 'address_line1', 'address_line2', 'address_line3', 'registration_no', 'telephone_no']);



        return $data;
    }

}