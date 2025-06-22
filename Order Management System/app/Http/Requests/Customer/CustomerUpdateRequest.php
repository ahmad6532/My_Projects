<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CustomerUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorMessages = implode(' | ', $validator->errors()->all());
        throw new HttpResponseException(
            response()->json([
                'response' => [
                    'status' => false,
                    'message' => $errorMessages
                ]
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
