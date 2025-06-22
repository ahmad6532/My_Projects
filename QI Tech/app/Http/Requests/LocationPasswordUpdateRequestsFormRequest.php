<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;


class LocationPasswordUpdateRequestsFormRequest extends FormRequest
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
            'old_password' => 'required|max:25',
            'new_password' => 'required|min:8|max:25', // use other validations //
            'confirm_password' => 'same:new_password'
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
        $data = $this->only(['new_password']);

        $data['new_password'] = Hash::make($data['new_password']);

        return $data;
    }

}