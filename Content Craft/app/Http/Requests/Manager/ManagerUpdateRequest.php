<?php

namespace App\Http\Requests\Manager;


use Illuminate\Foundation\Http\FormRequest;


class ManagerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstName' =>  'required|string|max:225',
            'lastName' =>  'required|string|max:225',
            'gender' =>  'required|string',
            'address' =>  'required|string|max:225',
            'phone' =>  'required|string',
            'country' =>  'required|string|max:225',
            'postalCode' =>  'required|string',


        ];
    }
   
}
