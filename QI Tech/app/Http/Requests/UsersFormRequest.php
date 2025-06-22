<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UsersFormRequest extends FormRequest
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

    private $countries = ['0' => 'England',
    '1' => 'Scotland',
    '2' => 'Wales',
    '3' => 'Channel Islands',
    '4' => 'Northern Ireland',
    '5' => 'Republic of Ireland'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
            'email' => 'required|max:150|email|unique:users,email,' . $this->user,
            'password' => 'nullable|min:8|max:30',
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
        $data = $this->only(['position_id', 'is_registered', 'registration_no', 'location_regulatory_body_id', 'country_of_practice', 'first_name', 'surname', 'mobile_no', 'email', 'password']);
        $data['is_registered'] = $this->has('is_registered');
        if($this->has('country_of_practice'))
            $data['country_of_practice'] = $this->countries[$data['country_of_practice']];
        if(strlen(trim($data['password'])) < 1)
            unset($data['password']);

        return $data;
    }

}