<?php

namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;


class UserCreateRequest extends FormRequest
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
            'email' =>  'required|email|unique:users,email|max:225',
            'gender' =>  'required|string',
            'password' =>  'required|string|min:8|confirmed|max:225',
            'address' =>  'required|string|max:225',
            'phone' =>  'required|string|max:225',
            'country' =>  'required|string|max:225',
            'postalCode' =>  'required|string',
            'avatar' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048', 


        ];
    }
   
}
