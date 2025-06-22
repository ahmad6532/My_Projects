<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeadOfficeBrandUpdateRequestsFormRequest extends FormRequest
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
            'bg_color_code' => 'min:4|max:10|nullable',
            'logo_file' => ['nullable','file'],
            'font' => 'min:1|max:80|nullable',
            'bg_file' => ['nullable','file'],
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
        $data = $this->only(['bg_color_code', 'font']);


        return $data;
    }

}
