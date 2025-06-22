<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;


class ServiceMessagesFormRequest extends FormRequest
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
            'title' => 'string|min:1|max:255',
            'message' => 'required|string|min:1|max:1000',
            'send_to' => 'required',
            'countries' => 'required',
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
        $data = $this->only(['title', 'message', 'send_to', 'countries']);

        $data['send_to']=json_encode($data['send_to']);
        $data['countries']=json_encode($data['countries']);


        return $data;
    }

}