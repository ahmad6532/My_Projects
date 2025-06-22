<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserSignUpRequest extends FormRequest
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
            'managerId' =>  'required',
            'avatar' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',


        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorMessages = implode(' | ', $validator->errors()->all());
        throw new HttpResponseException(
            response()->json([
                'response' => [
                    'success' => false,
                    'message' => $errorMessages
                ]
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
