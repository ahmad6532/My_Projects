<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfficeDetailUpdateRequestsFormRequest extends FormRequest
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
            'company_name' => 'required|string|min:1|max:80',
            'address' => 'required|string|min:1|max:80',
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
        $data = $this->only(['company_name', 'address', 'telephone_no']);

        return $data;
    }
}
