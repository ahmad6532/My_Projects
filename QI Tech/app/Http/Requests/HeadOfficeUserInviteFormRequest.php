<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class HeadOfficeUserInviteFormRequest extends FormRequest
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

    public function rules()
    {
        $rules = [
            'email' => 'required|email',
            'head_office_position' => 'required|string|min:2|max:50',
            'head_office_user_profile_id' => 'required|numeric|min:1',
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
        $data = $this->only(['email', 'head_office_position', 'head_office_user_profile_id']);
        return $data;
    }

}