<?php

namespace App\Http\Requests;

use App\Rules\AlertClassRule;
use Illuminate\Foundation\Http\FormRequest;


class NationalAlertsFormRequest extends FormRequest
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
            'title' => 'string|min:1|max:255|required',
            'type' => 'string|min:1|required',
            'originator' => "required|array|min:1", 
            'class' => 'string|min:1|required',
            'action_within' => 'string|min:1|required',
            'summary' => 'string|min:1|required',
            'send_to_countries' => "required|array|min:1",
            'send_to_designation' => "required|array|min:1",
            'send_to_head_offices_or_location' => 'string|min:1|required',

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
        $data = $this->only(['title', 'alert_type', 'summary','alert_documents','patient_level_recall','class']);
        $data['patient_level_recall']=$this->has('patient_level_recall');
        return $data;
    }

}