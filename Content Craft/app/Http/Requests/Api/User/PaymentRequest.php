<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class PaymentRequest extends FormRequest
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
            'cardName' => 'required|string|max:225',
            'cardNumber' => 'required|integer',
            'cvc' => 'required|integer',
            'exMonth' => 'required|integer',
            'exYear' => 'required|integer',
            'stripeToken' => 'required',
            'planId' => 'required',
            'amount' => 'required|integer',
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
