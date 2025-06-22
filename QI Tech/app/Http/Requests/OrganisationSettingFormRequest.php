<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganisationSettingFormRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'bg_color_code' => 'min:4|max:10|nullable',
            'location_section_heading_color' => 'min:4|max:10|nullable',
            'location_form_setting_color' => 'min:4|max:10|nullable',
            'location_button_color' => 'min:4|max:10|nullable',
            'location_button_text_color' => 'min:4|max:10|nullable',
            'logo_file' => ['nullable','file'],
            'font' => 'min:1|max:80|nullable',
            'name' => 'required|min:1',
            'bg_file' => ['nullable','file'],
        ];

        return $rules;
    }
    public function getData() {
        $data = $this->only(['name','bg_color_code', 'font','logo_file','bg_file', 'location_button_text_color', 'location_button_color', 'location_form_setting_color', 'location_section_heading_color']);
        if($this->has('logo_file'))
        {
            
        }
        return $data;
    }

}
